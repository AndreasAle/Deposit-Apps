<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('investments:settle-vip-profits')->everyMinute();
Schedule::command('deposits:settle-pending')->everyMinute()->withoutOverlapping();
Schedule::command('withdrawals:settle-pending')->everyMinute()->withoutOverlapping();
