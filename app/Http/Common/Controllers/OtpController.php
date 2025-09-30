<?php

namespace App\Http\Common\Controllers;

use App\Http\Common\Resources\SendOtpResource;
use App\Http\Controllers\Controller;
use App\Modules\OTP\Actions\SendOtpAction;
use App\Modules\OTP\Actions\VerifyOtpAction;
use App\Modules\OTP\Requests\SendOtpRequest;
use App\Modules\OTP\Requests\VerifyOtpRequest;
use Illuminate\Http\JsonResponse;
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
}
