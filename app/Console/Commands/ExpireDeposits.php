<?php

namespace App\Console\Commands;

use App\Services\MutationMatcher;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Menandai deposit UNPAID yang sudah lewat expired_at menjadi EXPIRED.
 *
 * Wajib jalan untuk driver qris_statis: selama status masih UNPAID, nominal
 * uniknya terkunci dan tidak bisa dipakai deposit lain. Tanpa command ini
 * kuota nominal unik akan habis pelan-pelan.
 */
class ExpireDeposits extends Command
{
    protected $signature = 'deposits:expire';

    protected $description = 'Tandai deposit UNPAID yang kedaluwarsa dan bebaskan nominal uniknya';

    public function handle(MutationMatcher $matcher): int
    {
        $count = $matcher->expireStale();

        if ($count > 0) {
            Log::info('Deposit kedaluwarsa ditandai EXPIRED', ['jumlah' => $count]);
            $this->info("{$count} deposit ditandai EXPIRED.");
        } else {
            $this->info('Tidak ada deposit yang kedaluwarsa.');
        }

        return self::SUCCESS;
    }
}
