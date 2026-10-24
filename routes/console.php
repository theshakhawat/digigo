<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Daily at 12:00 AM (midnight) check and update expired subscriptions
Schedule::command('subscriptions:check-expired')
    ->dailyAt('00:00')
    ->name('check-expired-subscriptions')
    ->withoutOverlapping();
