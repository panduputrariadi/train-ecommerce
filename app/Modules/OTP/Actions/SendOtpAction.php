<?php

namespace App\Modules\OTP\Actions;

use App\Modules\OTP\DTOs\SendOtpDtos;
use App\Modules\OTP\Jobs\SendEmailJobs;
use App\Modules\OTP\Models\Otp;
use App\Modules\OTP\Requests\SendOtpRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SendOtpAction
{

    public function execute(SendOtpRequest $request): Otp
    {
        DB::beginTransaction();
        try {
            $otpCode = random_int(100000, 999999);
            $data = $request->validated();
            $dto = SendOtpDtos::fromArray($data);
            $otp = Otp::create([
                'email' => $dto->email,
                'otp' => Hash::make($otpCode),
                'type' => Otp::TYPE_EMAIL,
                'used_for' => Otp::USED_FOR_REGISTER,
                'expired_at' => now()->addMinutes(5)
            ]);
            SendEmailJobs::dispatch($dto->email, $otpCode);
            DB::commit();
            return $otp;
        }catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
