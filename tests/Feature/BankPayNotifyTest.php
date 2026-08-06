<?php

namespace Tests\Feature;

use App\Models\Deposit;
use App\Models\User;
use App\Services\BankPay\BankPayDepositService;
use App\Services\DepositChannels;
use Tests\TestCase;

/**
 * Notifikasi server gateway adalah satu-satunya hal yang mengubah uang masuk
 * jadi saldo pada saluran BankPay. Yang diuji di sini adalah sifat-sifat yang
 * kalau rusak berarti uang hilang atau saldo tercipta dari udara:
 *
 *   - hanya notifikasi bertanda tangan sah yang dipercaya;
 *   - saldo bertambah sebesar nominal RIIL dari gateway;
 *   - kiriman ulang tidak pernah menambah saldo dua kali;
 *   - notifikasi gateway tidak bisa melunasi deposit QRIS statis.
 */
class BankPayNotifyTest extends TestCase
{
    private const KEY = 'kunci-tes-rahasia';
    private const MEMBER = '10002';

    /** Migration yang dibutuhkan tes ini, dijalankan berurutan. */
    private const MIGRASI = [
        '0001_01_01_000000_create_users_table.php',
        '2025_12_16_084528_create_deposits_table.php',
        '2026_08_06_100001_sync_deposits_columns.php',
        '2026_08_06_100002_add_unique_pending_amount_to_deposits.php',
        '2026_08_06_100003_create_mutations_table.php',
        '2026_08_06_100004_align_pending_unique_amount_scope.php',
        '2026_08_07_100000_add_payment_channel_to_deposits.php',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.bankpay.key' => self::KEY,
            'services.bankpay.member_id' => self::MEMBER,
        ]);

        foreach (self::MIGRASI as $berkas) {
            (require database_path('migrations/' . $berkas))->up();
        }
    }

    private function user(float $saldo = 0): User
    {
        static $n = 0;
        $n++;

        return User::forceCreate([
            'name' => 'Tester ' . $n,
            'phone' => '0811000' . str_pad((string) $n, 4, '0', STR_PAD_LEFT),
            'password' => bcrypt('rahasia'),
            'saldo' => $saldo,
            'role' => 'user',
        ]);
    }

    private function deposit(User $user, int $amount = 50000, string $channel = DepositChannels::BANKPAY): Deposit
    {
        return Deposit::create([
            'user_id' => $user->id,
            'order_id' => 'DEP' . now()->format('YmdHis') . str_pad((string) random_int(0, 999999), 6, '0'),
            'amount' => $amount,
            'method' => 'QRIS',
            'selected_channel' => 'QRIS',
            'payment_channel' => $channel,
            'status' => 'UNPAID',
            'real_amount' => $amount,
            'pay_fee' => 0,
        ]);
    }

    /** Bentuk notifikasi persis seperti yang dikirim gateway, lengkap tanda tangannya. */
    private function notifikasi(Deposit $deposit, float $payAmount, string $code = '1'): array
    {
        $params = [
            'memId' => self::MEMBER,
            'orderNo' => $deposit->order_id,
            'transId' => 'TRX' . $deposit->id,
            'payAmount' => number_format($payAmount, 2, '.', ''),
            'datetime' => now()->format('YmdHis'),
            'code' => $code,
        ];

        $params['sign'] = app(BankPayDepositService::class)->sign($params);

        return $params;
    }

    private function kirim(array $params)
    {
        // Gateway mengirim FORM POST, bukan JSON.
        return $this->post('/api/bankpay/deposit-notify', $params);
    }

    public function test_notifikasi_sah_melunasi_deposit_dan_menambah_saldo(): void
    {
        $user = $this->user(1000);
        $deposit = $this->deposit($user, 50000);

        $this->kirim($this->notifikasi($deposit, 50000))
            ->assertOk()
            ->assertSee('OK');

        $this->assertSame('PAID', $deposit->fresh()->status);
        $this->assertEquals(51000, $user->fresh()->saldo);
    }

    public function test_saldo_mengikuti_nominal_riil_dari_gateway(): void
    {
        $user = $this->user(0);
        // User minta 50.000 tapi yang benar-benar masuk 49.500.
        $deposit = $this->deposit($user, 50000);

        $this->kirim($this->notifikasi($deposit, 49500))->assertOk();

        $this->assertEquals(49500, $user->fresh()->saldo);
        $this->assertEquals(49500, (float) $deposit->fresh()->real_amount);
    }

    public function test_kiriman_ulang_tidak_menambah_saldo_dua_kali(): void
    {
        $user = $this->user(0);
        $deposit = $this->deposit($user, 50000);

        $params = $this->notifikasi($deposit, 50000);

        $this->kirim($params)->assertOk();
        $this->kirim($params)->assertOk();
        $this->kirim($params)->assertOk();

        $this->assertEquals(50000, $user->fresh()->saldo);
    }

    public function test_tanda_tangan_palsu_ditolak_dan_saldo_tidak_berubah(): void
    {
        $user = $this->user(0);
        $deposit = $this->deposit($user, 50000);

        $params = $this->notifikasi($deposit, 50000);
        // Nominal dinaikkan setelah ditandatangani - skenario penyerang.
        $params['payAmount'] = '5000000.00';

        $this->kirim($params)->assertStatus(400);

        $this->assertSame('UNPAID', $deposit->fresh()->status);
        $this->assertEquals(0, $user->fresh()->saldo);
    }

    public function test_kode_selain_satu_bukan_pembayaran_sukses(): void
    {
        $user = $this->user(0);
        $deposit = $this->deposit($user, 50000);

        // Dijawab OK supaya gateway berhenti mengulang, tapi tidak dilunasi.
        $this->kirim($this->notifikasi($deposit, 50000, '0'))->assertOk();

        $this->assertSame('UNPAID', $deposit->fresh()->status);
        $this->assertEquals(0, $user->fresh()->saldo);
    }

    public function test_notifikasi_gateway_tidak_bisa_melunasi_deposit_qris_statis(): void
    {
        $user = $this->user(0);
        $deposit = $this->deposit($user, 50000, DepositChannels::QRIS_STATIS);

        $this->kirim($this->notifikasi($deposit, 50000))->assertOk();

        $this->assertSame('UNPAID', $deposit->fresh()->status);
        $this->assertEquals(0, $user->fresh()->saldo);
    }

    public function test_order_tak_dikenal_dijawab_ok_tanpa_efek(): void
    {
        $params = [
            'memId' => self::MEMBER,
            'orderNo' => 'DEP-TIDAK-ADA-123456',
            'transId' => 'TRX0',
            'payAmount' => '50000.00',
            'datetime' => now()->format('YmdHis'),
            'code' => '1',
        ];

        $params['sign'] = app(BankPayDepositService::class)->sign($params);

        $this->kirim($params)->assertOk()->assertSee('OK');
    }
}
