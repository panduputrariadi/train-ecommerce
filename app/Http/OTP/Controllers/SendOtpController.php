<?php

namespace App\Http\OTP\Controllers;

use App\Http\Controllers\Controller;
use App\Http\OTP\Resources\SendOtpResource;
use App\Modules\OTP\Actions\SendOtpAction;
use App\Modules\OTP\Requests\SendOtpRequest;
use Illuminate\Http\JsonResponse;

class SendOtpController extends Controller
{
    public function send(SendOtpRequest $request): JsonResponse
    {
        $action = new SendOtpAction();
        $data = $action->execute($request);

        return response()->json([
            'message' => 'OTP sent successfully',
            'status' => 'success',
            'data' => new SendOtpResource($data)
        ]);
    }
}
