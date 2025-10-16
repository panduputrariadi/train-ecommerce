<?php

namespace App\Modules\OTP\Requests;

use App\Modules\OTP\DTOs\VerifyOtpDto;
use App\Modules\OTP\Rule\FindIdOtp;
use App\Modules\OTP\Rule\MatchesHashedOtp;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class VerifyOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string|FindIdOtp|MatchesHashedOtp|callable>|string>
     */
    public function rules(): array
    {
        // $otpId = $this->get('id');
        $otpId = $this['id'];
        Log::info('OTP ID:', ['id' => $otpId]);

        return [
            'id' => [
                'required',
                new FindIdOtp($otpId),
            ],
            'otp' => [
                'required',
                'string',
                'digits:6',
                new MatchesHashedOtp($otpId),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'id.exists' => 'The provided OTP is invalid, expired, or already used.',
            'otp.matches_hasshed_otp' => 'Invalid OTP code.',
        ];
    }

    public function validateVerifyDto(): VerifyOtpDto
    {
        return VerifyOtpDto::fromArray($this->validated());
    }
}
