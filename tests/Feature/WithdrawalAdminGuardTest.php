<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Penjagaan aksi admin atas penarikan yang SUDAH dikirim ke gateway.
 *
 * Latar belakangnya kejadian nyata pada 7 Agustus 2026: dua penarikan
 * berstatus PROCESSING direject admin, saldo user dikembalikan, padahal
 * gateway tetap mencairkan dananya. User menerima dua kali dan uangnya hilang.
 *
 * Aturannya sekarang: begitu permintaan lepas ke gateway, hanya gateway yang
 * boleh memutuskan gagal - bukan tebakan admin.
 */
class WithdrawalAdminGuardTest extends TestCase
{
    private const MIGRASI = [
        '0001_01_01_000000_create_users_table.php',
        'xxxx_xx_xx_add_saldo_hold_to_users_table.php',
        '2026_08_07_100001_add_saldo_penarikan_to_users_table.php',
        'xxxx_xx_xx_create_user_payout_accounts_table.php',
        'xxxx_xx_xx_create_withdrawals_table.php',
        '2026_06_21_001014_add_is_test_to_withdrawals_table.php',
        '2026_08_07_100002_sync_withdrawals_columns.php',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.bankpay.key' => 'rahasia',
            'services.bankpay.member_id' => '13315',
        ]);

        foreach (self::MIGRASI as $berkas) {
            (require database_path('migrations/' . $berkas))->up();
        }
    }

    private function buatUser(string $role = 'user', float $saldoPenarikan = 0, float $hold = 0): User
    {
        static $n = 0;
        $n++;

        return User::forceCreate([
            'name' => 'Orang ' . $n,
            'phone' => '0812000' . str_pad((string) $n, 4, '0', STR_PAD_LEFT),
            'password' => bcrypt('rahasia'),
            'saldo' => 0,
            'saldo_penarikan' => $saldoPenarikan,
            'saldo_hold' => $hold,
            'role' => $role,
        ]);
    }

    private function buatWithdrawal(User $user, string $status = 'PROCESSING'): Withdrawal
    {
        $rekening = \App\Models\UserPayoutAccount::forceCreate([
            'user_id' => $user->id,
            'type' => 'EWALLET',
            'provider' => 'DANA',
            'account_name' => 'Siti nur',
            'account_number' => '089630162241',
            'is_default' => true,
        ]);

        return Withdrawal::create([
            'user_id' => $user->id,
            'user_payout_account_id' => $rekening->id,
            'order_id' => 'WD' . now()->format('YmdHis') . 'ABC123',
            'bank_code' => 'DANA',
            'account_no' => '089630162241',
            'account_name' => 'Siti nur',
            'amount' => 70000,
            'fee' => 3500,
            'net_amount' => 66500,
            'status' => $status,
            'plat_order_num' => 'TRX-1',
            'requested_at' => now(),
        ]);
    }

    /** Balasan dfquery yang sah, lengkap dengan tanda tangannya. */
    private function jawabanGateway(string $state): array
    {
        return [
            'memberid' => '13315',
            'orderid' => 'WD-X',
            'amount' => '66500.00',
            'fee' => '0.00',
            'returncode' => '00',
            'trade_state' => $state,
        ];
    }

    // ------------------------------------------------------------------ reject

    public function test_reject_ditolak_untuk_penarikan_yang_sudah_dikirim_ke_gateway(): void
    {
        $admin = $this->buatUser('admin');
        $user = $this->buatUser('user', saldoPenarikan: 0, hold: 70000);
        $wd = $this->buatWithdrawal($user, 'PROCESSING');

        $this->actingAs($admin)
            ->postJson('/admin/withdrawals/' . $wd->id . '/reject', ['reason' => 'Rekening salah'])
            ->assertStatus(422);

        // Yang paling penting: saldo TIDAK dikembalikan.
        $user->refresh();
        $this->assertEquals(0, $user->saldo_penarikan);
        $this->assertEquals(70000, $user->saldo_hold);
        $this->assertSame('PROCESSING', $wd->fresh()->status);
    }

    public function test_reject_tetap_boleh_selama_belum_dikirim(): void
    {
        $admin = $this->buatUser('admin');
        $user = $this->buatUser('user', saldoPenarikan: 0, hold: 70000);
        $wd = $this->buatWithdrawal($user, 'PENDING');

        $this->actingAs($admin)
            ->postJson('/admin/withdrawals/' . $wd->id . '/reject', ['reason' => 'Rekening salah'])
            ->assertOk();

        $user->refresh();
        $this->assertEquals(70000, $user->saldo_penarikan);
        $this->assertEquals(0, $user->saldo_hold);
        $this->assertSame('REJECTED', $wd->fresh()->status);
    }

    // -------------------------------------------------------------- set FAILED

    public function test_set_failed_ditolak_kalau_gateway_bilang_sudah_cair(): void
    {
        Http::fake(['*' => Http::response($this->jawabanGateway('SUCCESS'))]);

        $admin = $this->buatUser('admin');
        $user = $this->buatUser('user', saldoPenarikan: 0, hold: 70000);
        $wd = $this->buatWithdrawal($user, 'PROCESSING');

        $this->actingAs($admin)
            ->postJson('/admin/withdrawals/' . $wd->id . '/failed', [])
            ->assertStatus(422);

        $user->refresh();
        $this->assertEquals(0, $user->saldo_penarikan, 'saldo dikembalikan padahal dana sudah cair');
        $this->assertSame('PROCESSING', $wd->fresh()->status);
    }

    public function test_set_failed_ditolak_kalau_gateway_belum_memutuskan(): void
    {
        Http::fake(['*' => Http::response($this->jawabanGateway('WAIT PAY'))]);

        $admin = $this->buatUser('admin');
        $user = $this->buatUser('user', saldoPenarikan: 0, hold: 70000);
        $wd = $this->buatWithdrawal($user, 'PROCESSING');

        $this->actingAs($admin)
            ->postJson('/admin/withdrawals/' . $wd->id . '/failed', [])
            ->assertStatus(422);

        $this->assertEquals(0, $user->fresh()->saldo_penarikan);
    }

    public function test_set_failed_ditolak_kalau_gateway_tidak_bisa_dihubungi(): void
    {
        Http::fake(['*' => Http::response('gangguan', 500)]);

        $admin = $this->buatUser('admin');
        $user = $this->buatUser('user', saldoPenarikan: 0, hold: 70000);
        $wd = $this->buatWithdrawal($user, 'PROCESSING');

        $this->actingAs($admin)
            ->postJson('/admin/withdrawals/' . $wd->id . '/failed', [])
            ->assertStatus(422);

        $this->assertEquals(0, $user->fresh()->saldo_penarikan);
    }

    public function test_set_failed_diizinkan_kalau_gateway_menolak_pencairan(): void
    {
        Http::fake(['*' => Http::response($this->jawabanGateway('REFUSE'))]);

        $admin = $this->buatUser('admin');
        $user = $this->buatUser('user', saldoPenarikan: 0, hold: 70000);
        $wd = $this->buatWithdrawal($user, 'PROCESSING');

        $this->actingAs($admin)
            ->postJson('/admin/withdrawals/' . $wd->id . '/failed', ['reason' => 'Ditolak gateway'])
            ->assertOk();

        $user->refresh();
        $this->assertEquals(70000, $user->saldo_penarikan);
        $this->assertEquals(0, $user->saldo_hold);
        $this->assertSame('FAILED', $wd->fresh()->status);
    }

    public function test_force_melewati_verifikasi_untuk_keadaan_darurat(): void
    {
        Http::fake(['*' => Http::response($this->jawabanGateway('WAIT PAY'))]);

        $admin = $this->buatUser('admin');
        $user = $this->buatUser('user', saldoPenarikan: 0, hold: 70000);
        $wd = $this->buatWithdrawal($user, 'PROCESSING');

        $this->actingAs($admin)
            ->postJson('/admin/withdrawals/' . $wd->id . '/failed', ['force' => true])
            ->assertOk();

        $this->assertEquals(70000, $user->fresh()->saldo_penarikan);
    }
}
