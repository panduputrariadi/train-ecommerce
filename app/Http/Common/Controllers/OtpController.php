<?php

namespace App\Http\Common\Controllers;

use App\Http\Common\Resources\ForgetPasswordResource;
use App\Http\Common\Resources\SendOtpResource;
use App\Http\Controllers\Controller;
use App\Modules\OTP\Actions\ForgetPasswordAction;
use App\Modules\OTP\Actions\ResetPasswordAction;
use App\Modules\OTP\Actions\SendOtpAction;
use App\Modules\OTP\Actions\VerifyOtpAction;
use App\Modules\OTP\Requests\ForgetPasswordRequest;
use App\Modules\OTP\Requests\ResetPasswordRequest;
use App\Modules\OTP\Requests\SendOtpRequest;
use App\Modules\OTP\Requests\VerifyOtpRequest;
use Illuminate\Support\Facades\DB;

class OtpController extends Controller
{
    public function sendOtp(SendOtpAction $sendOtpAction, SendOtpRequest $request)
    {
        $otp = DB::transaction(fn () => $sendOtpAction->execute($request));

        return new SendOtpResource($otp);
    }

    public function verifyOtp(VerifyOtpAction $verifyOtpAction, VerifyOtpRequest $request)
    {
        $otp = DB::transaction(fn () => $verifyOtpAction->execute($request));

        return response()->json(['message' => 'OTP verified successfully']);
    }

    public function sendOtpForgetPassword(ForgetPasswordAction $forgetPasswordAction, ForgetPasswordRequest $request)
    {
        $otp = DB::transaction(fn () => $forgetPasswordAction->execute($request));

        return new ForgetPasswordResource($otp);
    }

    public function resetPassword(ResetPasswordRequest $request, ResetPasswordAction $resetPasswordAction)
    {
        $user = $resetPasswordAction->execute($request);

        return response()->json([
            'message' => 'Reset Password Successfully',
        ]);
    }
}
