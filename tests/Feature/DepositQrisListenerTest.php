<?php

namespace Tests\Feature;

use App\Models\Deposit;
use App\Models\Mutation;
use App\Models\User;
use App\Services\DepositQrisService;
use App\Services\MutationMatcher;
use Tests\TestCase;

class DepositQrisListenerTest extends TestCase
{
    private const STATIS = '00020101021126570011ID.DANA.WWW011893600915303371673602090337167360303UMI51440014ID.CO.QRIS.WWW0215ID10265623758930303UMI5204737253033605802ID5906Conweb6014Kota Palembang61053016163045C12';

    /**
     * Migration yang dibutuhkan tes ini, dijalankan berurutan.
     *
     * Sengaja TIDAK memakai RefreshDatabase: urutan migration di repo ini rusak
     * (file xxxx_xx_xx_* diurutkan setelah 2026_*, padahal justru yang membuat
     * tabelnya), sehingga `migrate` gagal total di database baru. Itu masalah
     * lama yang terpisah dari fitur ini - lihat catatan di ringkasan.
     */
    private const MIGRASI = [
        '0001_01_01_000000_create_users_table.php',
        '2025_12_16_084528_create_deposits_table.php',
        '2026_08_06_100001_sync_deposits_columns.php',
        '2026_08_06_100002_add_unique_pending_amount_to_deposits.php',
        '2026_08_06_100003_create_mutations_table.php',
        '2026_08_06_100004_align_pending_unique_amount_scope.php',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'deposit.driver' => 'qris_statis',
            'deposit.qris.statis' => self::STATIS,
            'deposit.listener.token' => 'token-tes-rahasia',
        ]);

        foreach (self::MIGRASI as $berkas) {
            (require database_path('migrations/' . $berkas))->up();
        }
    }

    /**
     * UserFactory bawaan repo merujuk kolom `email` yang tidak ada di tabel
     * users (adanya `phone`), jadi user dibuat manual di sini.
     */
    private function user(float $saldo = 0, string $role = 'user'): User
    {
        static $n = 0;
        $n++;

        return User::forceCreate([
            'name' => 'Tester ' . $n,
            'phone' => '0800000' . str_pad((string) $n, 4, '0', STR_PAD_LEFT),
            'password' => bcrypt('rahasia'),
            'saldo' => $saldo,
            'role' => $role,
        ]);
    }

    private function buatDeposit(User $user, int $amount = 50000): Deposit
    {
        return app(DepositQrisService::class)->createInvoice(
            $user,
            $amount,
            'QRIS',
            'DEP' . now()->format('YmdHis') . str_pad((string) random_int(0, 999999), 6, '0')
        );
    }

    private function kirimMutasi(array $body)
    {
        return $this->withHeader('X-Auth-Token', 'token-tes-rahasia')
            ->postJson('/api/listener/mutasi', $body);
    }

    // ---------------------------------------------------------------- nominal

    public function test_nominal_unik_dijamin_beda_untuk_nominal_dasar_sama(): void
    {
        $user = $this->user();

        $a = $this->buatDeposit($user);
        $b = $this->buatDeposit($user);
        $c = $this->buatDeposit($user);

        $nominal = [(int) $a->real_amount, (int) $b->real_amount, (int) $c->real_amount];

        $this->assertCount(3, array_unique($nominal), 'nominal bayar tertukar: ' . implode(',', $nominal));
        $this->assertSame([50001, 50002, 50003], $nominal);
        $this->assertSame(50000, (int) $a->amount, 'nominal diminta tidak boleh berubah');
    }

    public function test_qr_berisi_nominal_bayar_bukan_nominal_diminta(): void
    {
        $deposit = $this->buatDeposit($this->user(), 50000);

        $this->assertStringContainsString('540550001', $deposit->pay_data);
        $this->assertStringStartsWith('000201010212', $deposit->pay_data);
    }

    /**
     * Skenario produksi: database masih punya invoice BayarPro aktif dengan
     * nominal kembar (banyak orang deposit 50.000 bersamaan). Itu wajar dan
     * tidak boleh menghalangi deposit driver baru.
     */
    public function test_deposit_warisan_bernominal_kembar_tidak_menghalangi(): void
    {
        $user = $this->user();

        // Tiru deposit lama BayarPro: unique_code NULL, real_amount kembar.
        foreach (range(1, 3) as $i) {
            Deposit::forceCreate([
                'user_id' => $user->id,
                'order_id' => 'LAMA' . $i,
                'amount' => 50000,
                'method' => 'QRIS',
                'status' => 'UNPAID',
                'real_amount' => 50000,
                'unique_code' => null,
                'expired_at' => now()->addDay(),
            ]);
        }

        $this->assertSame(3, Deposit::whereNull('unique_code')->count());

        // Deposit driver baru tetap bisa dibuat.
        $baru = $this->buatDeposit($user, 50000);

        $this->assertSame(50001, (int) $baru->real_amount);
        $this->assertNotNull($baru->unique_code);
    }

    public function test_nominal_unik_dibebaskan_setelah_kedaluwarsa(): void
    {
        $user = $this->user();
        $a = $this->buatDeposit($user);

        $a->update(['expired_at' => now()->subMinute()]);
        $this->artisan('deposits:expire')->assertSuccessful();

        $b = $this->buatDeposit($user);

        $this->assertSame('EXPIRED', $a->fresh()->status);
        $this->assertSame(50001, (int) $b->real_amount, 'nominal lama seharusnya bisa dipakai lagi');
    }

    // ----------------------------------------------------------------- auth

    public function test_endpoint_listener_menolak_tanpa_token(): void
    {
        $this->postJson('/api/listener/mutasi', ['ext_id' => 'x', 'amount' => 1])
            ->assertStatus(401);
    }

    public function test_endpoint_listener_menolak_token_salah(): void
    {
        $this->withHeader('X-Auth-Token', 'token-palsu')
            ->postJson('/api/listener/mutasi', ['ext_id' => 'x', 'amount' => 1])
            ->assertStatus(401);
    }

    // -------------------------------------------------------------- matching

    public function test_notifikasi_melunasi_deposit_dan_menambah_saldo(): void
    {
        $user = $this->user(0);
        $deposit = $this->buatDeposit($user, 50000);

        $this->kirimMutasi([
            'ext_id' => 'notif-1',
            'raw' => 'DANA Bisnis: Anda menerima pembayaran QRIS Rp50.001',
            'device' => 'hp-kasir',
        ])->assertOk()->assertJson([
            'status' => 'matched',
            'deposit_id' => $deposit->id,
        ]);

        $this->assertSame('PAID', $deposit->fresh()->status);
        // credit_paid_amount default true -> saldo bertambah sebesar yang dibayar
        $this->assertSame(50001.0, (float) $user->fresh()->saldo);
    }

    public function test_kiriman_ulang_tidak_menambah_saldo_dua_kali(): void
    {
        $user = $this->user(0);
        $this->buatDeposit($user, 50000);

        $body = [
            'ext_id' => 'notif-retry',
            'raw' => 'DANA Bisnis: Anda menerima pembayaran QRIS Rp50.001',
        ];

        $this->kirimMutasi($body)->assertOk()->assertJson(['duplicate' => false]);
        $this->kirimMutasi($body)->assertOk()->assertJson(['duplicate' => true]);
        $this->kirimMutasi($body)->assertOk()->assertJson(['duplicate' => true]);

        $this->assertSame(50001.0, (float) $user->fresh()->saldo);
        $this->assertSame(1, Mutation::count());
    }

    public function test_notifikasi_uang_keluar_tidak_dianggap_pembayaran(): void
    {
        $user = $this->user(0);
        $deposit = $this->buatDeposit($user, 50000);

        $this->kirimMutasi([
            'ext_id' => 'notif-debit',
            'raw' => 'Transfer ke 1234567 sebesar Rp50.001 berhasil',
        ])->assertOk()->assertJson(['deposit_id' => null]);

        $this->assertSame('UNPAID', $deposit->fresh()->status);
        $this->assertSame(0.0, (float) $user->fresh()->saldo);
    }

    public function test_notifikasi_tidak_terbaca_disimpan_untuk_review(): void
    {
        $this->buatDeposit($this->user(), 50000);

        $this->kirimMutasi([
            'ext_id' => 'notif-ngaco',
            'raw' => 'Promo gede-gedean diskon 50 persen!',
        ])->assertOk()->assertJson(['status' => 'needs_review']);

        $this->kirimMutasi([
            'ext_id' => 'notif-ambigu',
            'raw' => 'Saldo Rp1.000.000 setelah menerima Rp50.001',
        ])->assertOk()->assertJson(['status' => 'needs_review']);

        $this->assertSame(2, Mutation::whereIn('status', ['needs_review', 'unmatched'])->count());
    }

    public function test_nominal_asing_tersimpan_sebagai_unmatched(): void
    {
        $this->kirimMutasi([
            'ext_id' => 'notif-nyasar',
            'raw' => 'Anda menerima pembayaran QRIS Rp777.777',
        ])->assertOk()->assertJson(['status' => 'unmatched', 'amount' => 777777]);

        $this->assertSame(1, Mutation::where('status', 'unmatched')->count());
    }

    // ------------------------------------------------- banyak orang barengan

    public function test_empat_orang_bayar_barengan_dengan_ext_id_kembar(): void
    {
        $users = collect(range(1, 4))->map(fn () => $this->user(0));
        $deposits = $users->map(fn ($u) => $this->buatDeposit($u, 50000));

        $nominal = $deposits->map(fn ($d) => (int) $d->real_amount)->all();
        $this->assertSame([50001, 50002, 50003, 50004], $nominal);

        // ext_id SENGAJA sama semua: meniru DANA yang memakai ulang
        // notification_id ketika beberapa notif datang di detik yang sama.
        foreach ($deposits as $d) {
            $this->kirimMutasi([
                'ext_id' => 'dana-notif-999-1754159760',
                'raw' => 'DANA Bisnis: Anda menerima pembayaran QRIS Rp' . number_format((int) $d->real_amount, 0, ',', '.'),
            ])->assertOk()->assertJson([
                'duplicate' => false,
                'status' => 'matched',
                'deposit_id' => $d->id,
            ]);
        }

        foreach ($deposits as $d) {
            $this->assertSame('PAID', $d->fresh()->status, "deposit #{$d->id} tidak lunas");
        }

        foreach ($users as $i => $u) {
            $this->assertSame(
                50001.0 + $i,
                (float) $u->fresh()->saldo,
                "saldo user ke-{$i} salah"
            );
        }

        $this->assertSame(4, Mutation::count(), 'harus tersimpan 4 mutasi, bukan 1');
    }

    // ----------------------------------------------------------- notif telat

    public function test_notifikasi_telat_masuk_antrian_review_bukan_hilang(): void
    {
        $user = $this->user(0);
        $deposit = $this->buatDeposit($user, 50000);

        $deposit->update(['expired_at' => now()->subMinute()]);
        $this->artisan('deposits:expire');

        $this->kirimMutasi([
            'ext_id' => 'notif-telat',
            'raw' => 'Anda menerima pembayaran QRIS Rp50.001',
        ])->assertOk()->assertJson(['status' => 'needs_review']);

        // Tidak otomatis menambah saldo - admin yang memutuskan.
        $this->assertSame(0.0, (float) $user->fresh()->saldo);
        $this->assertSame($deposit->id, Mutation::first()->candidate_deposit_id);
    }

    // ---------------------------------------------------------------- admin

    public function test_admin_bisa_mencocokkan_manual(): void
    {
        $user = $this->user(0);
        $deposit = $this->buatDeposit($user, 50000);

        $this->kirimMutasi([
            'ext_id' => 'notif-manual',
            'raw' => 'Anda menerima pembayaran QRIS Rp777.777',
        ])->assertOk();

        $mutation = Mutation::first();

        $admin = $this->user(0, 'admin');

        $this->actingAs($admin)
            ->postJson('/admin/mutations/resolve', [
                'mutation_id' => $mutation->id,
                'deposit_id' => $deposit->id,
            ])->assertOk();

        $this->assertSame('PAID', $deposit->fresh()->status);
        $this->assertSame('matched', $mutation->fresh()->status);
        $this->assertSame(50001.0, (float) $user->fresh()->saldo);
    }

    public function test_deposit_lunas_tidak_bisa_dibayar_dua_kali(): void
    {
        $user = $this->user(0);
        $deposit = $this->buatDeposit($user, 50000);

        $this->kirimMutasi([
            'ext_id' => 'notif-a',
            'raw' => 'Anda menerima pembayaran QRIS Rp50.001',
        ])->assertOk()->assertJson(['status' => 'matched']);

        // Notif kedua dengan nominal sama, tapi deposit sudah lunas.
        $this->kirimMutasi([
            'ext_id' => 'notif-b',
            'raw' => 'Anda menerima lagi pembayaran QRIS Rp50.001',
        ])->assertOk()->assertJson(['deposit_id' => null]);

        $this->assertSame(50001.0, (float) $user->fresh()->saldo);
    }

    // --------------------------------------------------------------- parser

    public function test_parser_nominal(): void
    {
        $m = app(MutationMatcher::class);

        $this->assertSame(2000, $m->toRupiah('2.000'));
        $this->assertSame(2000, $m->toRupiah('2.000,00'));
        $this->assertSame(50123, $m->toRupiah('50.123'));
        $this->assertSame(2012, $m->toRupiah('2.012'));
        $this->assertSame(1500000, $m->toRupiah('1.500.000'));
    }
}
