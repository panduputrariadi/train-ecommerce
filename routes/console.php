<?php

use App\Modules\OTP\Jobs\DeleteExpiredOtpJob;
use App\Modules\OTP\Models\Otp;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule::command('otp:delete-expired')->everyFiveMinutes();

Schedule::call(function () {
    Otp::whereNull('verified_at')->where('created_at', '<', now()->subMinutes(10))->delete();
})->everyTenMinutes();
// Schedule::job(new DeleteExpiredOtpJob())->everyFiveMinutes();
