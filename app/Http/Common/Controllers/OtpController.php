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
    public function sendOtp(SendOtpAction $sendOtpAction, SendOtpRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $otp = $sendOtpAction->execute($request);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
        return (new SendOtpResource($otp))->response();
    }

    public function verifyOtp(VerifyOtpAction $verifyOtpAction, VerifyOtpRequest $request)
    {
        DB::beginTransaction();
        try {
            $otp = DB::transaction(fn () => $verifyOtpAction->execute($request));
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 422);
        }
        return $otp;
    }
}
