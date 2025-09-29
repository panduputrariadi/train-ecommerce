<?php

namespace App\Http\OTP\Controllers;

use App\Http\Controllers\Controller;
use App\Http\OTP\Resources\VerifyOtpResource;
use App\Modules\OTP\Actions\VerifyOtpAction;
use App\Modules\OTP\Requests\VerifyOtpRequest;
use Illuminate\Http\JsonResponse;

class VerifyOtpController extends Controller
{
    public function verify(VerifyOtpRequest $request): JsonResponse
    {
        $action = new VerifyOtpAction();
        $data = $action->execute($request);

        return response()->json([
            'message' => 'OTP verified successfully',
            'status' => 'success',
            'data' => new VerifyOtpResource($data)
        ]);
    }
}
