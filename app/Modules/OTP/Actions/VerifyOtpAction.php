<?php

namespace App\Modules\OTP\Actions;

use App\Modules\OTP\DTOs\VerifyOtpDtos;
use App\Modules\OTP\Models\Otp;
use App\Modules\OTP\Requests\VerifyOtpRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VerifyOtpAction
{
    public function execute(VerifyOtpRequest $request): Otp
    {
        $dto = VerifyOtpDtos::fromArray($request->validated());

        DB::beginTransaction();
        try {
            $otp = Otp::findOrFail($dto->id);
            // $otp = Otp::where('id', $dto->id)
            //     ->whereNull('verified_at')
            //     ->where('expired_at', '>', now())
            //     ->first();

            // if (!$otp) {
            //     throw new ModelNotFoundException('OTP not found, expired, or already used.');
            // }

            // if (!Hash::check($dto->otp, $otp->otp)) {
            //     throw new \InvalidArgumentException('Invalid OTP.');
            // }

            $otp->update(['verified_at' => now()]);

            DB::commit();
            return $otp;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
