<?php

namespace App\Modules\OTP\Models;

use App\Modules\OTP\Enum\TypeOtp;
use App\Modules\OTP\Enum\UseOtp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = [
        'otp',
        'email',
        'used_for',
        'type',
        'expired_at',
        'verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function casts(): array
    {
        return [
            'otp' => 'hashed',
            'type' => TypeOtp::class,
            'used_for' => UseOtp::class,
        ];
    }
}
