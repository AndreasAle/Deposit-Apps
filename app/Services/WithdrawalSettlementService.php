<?php

namespace App\Services;

use App\Models\Withdrawal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Menutup sebuah withdrawal dan merapikan saldo user.
 *
 * Alur saldo penarikan:
 *   request  : saldo_penarikan -> saldo_hold   (dana ditahan)
 *   sukses   : saldo_hold dikurangi            (dana benar-benar keluar)
 *   gagal    : saldo_hold -> saldo_penarikan   (dana dikembalikan)
 *
 * Semua jalur penutupan (notifikasi gateway, poller cron, aksi admin) memakai
 * service ini supaya saldo tidak pernah dikembalikan dua kali atau terlewat.
 * Idempoten: baris dikunci dan status final (PAID/FAILED) tidak diproses ulang.
 */
class WithdrawalSettlementService
{
    /**
     * @param  array<string, mixed>  $response
     * @return bool  true kalau status baru saja berubah karena pemanggil ini
     */
    public function markPaid(int $withdrawalId, array $response = [], string $via = 'gateway'): bool
    {
        return DB::transaction(function () use ($withdrawalId, $response, $via) {
            $row = Withdrawal::lockForUpdate()->find($withdrawalId);

            if (!$row || in_array($row->status, ['PAID', 'FAILED'], true)) {
                return false;
            }

            $user = $row->user()->lockForUpdate()->first();

            if ($user) {
                // Dana keluar: hold dilepas permanen, tidak dikembalikan.
                $user->saldo_hold = max(0, (float) $user->saldo_hold - (float) $row->amount);
                $user->save();
            }

            $row->status = 'PAID';
            $row->paid_at = now();
            $row->gateway_status = 'SUCCESS';

            if ($response !== []) {
                $row->gateway_callback = $response;
            }

            $row->save();

            Log::info('Withdrawal lunas', [
                'withdrawal_id' => $row->id,
                'order_id' => $row->order_id,
                'via' => $via,
            ]);

            return true;
        });
    }

    /**
     * @param  array<string, mixed>  $response
     * @return bool  true kalau status baru saja berubah karena pemanggil ini
     */
    public function markFailed(
        int $withdrawalId,
        string $reason = 'Penarikan gagal diproses gateway',
        array $response = [],
        string $via = 'gateway'
    ): bool {
        return DB::transaction(function () use ($withdrawalId, $reason, $response, $via) {
            $row = Withdrawal::lockForUpdate()->find($withdrawalId);

            if (!$row || in_array($row->status, ['PAID', 'FAILED'], true)) {
                return false;
            }

            // REJECTED sudah mengembalikan dana lebih dulu — jangan dobel.
            if ($row->status !== 'REJECTED') {
                $user = $row->user()->lockForUpdate()->first();

                if ($user) {
                    $user->saldo_penarikan = (float) $user->saldo_penarikan + (float) $row->amount;
                    $user->saldo_hold = max(0, (float) $user->saldo_hold - (float) $row->amount);
                    $user->save();
                }
            }

            $row->status = 'FAILED';
            $row->failed_at = now();
            $row->failed_reason = $reason;
            $row->gateway_status = 'FAILED';

            if ($response !== []) {
                $row->gateway_callback = $response;
            }

            $row->save();

            Log::warning('Withdrawal gagal', [
                'withdrawal_id' => $row->id,
                'order_id' => $row->order_id,
                'reason' => $reason,
                'via' => $via,
            ]);

            return true;
        });
    }
}
