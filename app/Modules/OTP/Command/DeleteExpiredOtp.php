<?php

namespace App\Modules\OTP\Command;

use App\Modules\OTP\Models\Otp;
use Illuminate\Console\Command;

class DeleteExpiredOtp extends Command
{
    /**
     * name command artisan
     */
    protected $signature = 'otp:delete-expired';

    /**
     * desc command
     */
    protected $description = 'Delete expired OTP records older than 10 minutes';

    public function handle(): int
    {
        $deleted = Otp::where('created_at', '<', now()->subMinutes(10))->delete();

        $this->info("Deleted {$deleted} expired OTP(s).");

        return self::SUCCESS;
    }
}
