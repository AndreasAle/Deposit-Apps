<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('investments:settle-vip-profits')->everyMinute();
Schedule::command('deposits:settle-pending')->everyTenSeconds()->withoutOverlapping();
Schedule::command('withdrawals:settle-pending')->everyTenSeconds()->withoutOverlapping();

// Bebaskan nominal unik dari invoice QRIS yang sudah lewat waktu.
Schedule::command('deposits:expire')->everyMinute()->withoutOverlapping();
