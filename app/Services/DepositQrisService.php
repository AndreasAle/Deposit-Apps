<?php

namespace App\Services;

use App\Models\Deposit;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * Membuat invoice deposit memakai QRIS statis milik sendiri, tanpa gateway.
 *
 * Cara membedakan siapa yang bayar: nominal dibuat unik per deposit
 * (nominal diminta + kode unik 1..999). Karena QRIS dinamis MENGUNCI nominal,
 * user tidak bisa salah ketik, sehingga nominal = identitas deposit.
 */
class DepositQrisService
{
    public function __construct(
        private QrisDinamisService $qris
    ) {}

    /**
     * Buat deposit UNPAID lengkap dengan nominal unik dan payload QRIS.
     *
     * @throws RuntimeException kalau kuota nominal unik habis atau QRIS invalid
     */
    public function createInvoice(User $user, int $amount, string $channel, string $orderId): Deposit
    {
        $statis = (string) config('deposit.qris.statis');

        if (!$this->qris->isValidStatic($statis)) {
            throw new RuntimeException(
                'QRIS statis belum dikonfigurasi atau formatnya tidak valid. Isi QRIS_STATIS di .env.'
            );
        }

        $min = (int) config('deposit.qris.kode_min', 1);
        $max = (int) config('deposit.qris.kode_max', 999);
        $ttl = (int) config('deposit.qris.expiry_minutes', 30);

        // Coba kode unik satu per satu. Yang menjaga keunikan adalah UNIQUE
        // INDEX di database (ux_deposits_pending_unique_amount), bukan
        // pengecekan di PHP - jadi dua request bersamaan tidak mungkin
        // mendapat nominal yang sama.
        for ($code = $min; $code <= $max; $code++) {
            $payAmount = $amount + $code;

            try {
                return Deposit::create([
                    'user_id' => $user->id,
                    'order_id' => $orderId,
                    'amount' => $amount,
                    'method' => $channel,
                    'selected_channel' => $channel,
                    'status' => 'UNPAID',
                    'unique_code' => $code,
                    'real_amount' => $payAmount,
                    'pay_fee' => 0,
                    'pay_url' => null,
                    'pay_data' => $this->qris->toDynamic($statis, $payAmount),
                    'expired_at' => now()->addMinutes($ttl),
                    'gateway_response' => [
                        'driver' => 'qris_statis',
                        'unique_code' => $code,
                        'pay_amount' => $payAmount,
                    ],
                ]);
            } catch (UniqueConstraintViolationException $e) {
                if ($this->isDuplicateAmount($e)) {
                    continue; // nominal ini sedang dipakai deposit UNPAID lain
                }

                throw $e;
            }
        }

        Log::error('Kuota nominal unik QRIS habis', [
            'user_id' => $user->id,
            'amount' => $amount,
            'range' => [$min, $max],
        ]);

        throw new RuntimeException(
            'Sedang banyak transaksi dengan nominal sama. Coba lagi sebentar lagi.'
        );
    }

    /**
     * Bedakan bentrok nominal unik dari bentrok unique lain (mis. order_id).
     *
     * Pesan errornya beda antar database: MySQL menyebut nama index, SQLite
     * menyebut nama kolom. Dua-duanya dicek supaya jalan di produksi (MySQL)
     * maupun di test suite (SQLite).
     */
    private function isDuplicateAmount(UniqueConstraintViolationException $e): bool
    {
        $pesan = $e->getMessage();

        foreach (['ux_deposits_pending_unique_amount', 'pending_unique_amount', 'real_amount'] as $petunjuk) {
            if (str_contains($pesan, $petunjuk)) {
                return true;
            }
        }

        return false;
    }
}
