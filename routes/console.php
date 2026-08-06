<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('investments:settle-vip-profits')->everyMinute();

/*
| Poller gateway sengaja tiap MENIT, bukan tiap sepuluh detik.
|
| Konfirmasi pembayaran yang sesungguhnya datang dari notifikasi server dan
| sifatnya instan; dua perintah di bawah cuma jaring pengaman kalau notifikasi
| itu tidak sampai. Pada interval sepuluh detik, tiap invoice yang menggantung
| ditanyakan ~8.600 kali per hari — membanjiri gateway tanpa mempercepat apa
| pun, dan berisiko kena pembatasan laju justru saat konfirmasi paling
| dibutuhkan. Antarmuka pencairan bahkan punya batas resmi 5 permintaan/detik.
*/
Schedule::command('deposits:settle-pending')->everyMinute()->withoutOverlapping();
Schedule::command('withdrawals:settle-pending')->everyMinute()->withoutOverlapping();

// Bebaskan nominal unik dari invoice QRIS yang sudah lewat waktu.
Schedule::command('deposits:expire')->everyMinute()->withoutOverlapping();
