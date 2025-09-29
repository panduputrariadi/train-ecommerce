<?php

namespace App\Modules\OTP\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    public const TYPE_EMAIL = 'email';
    public const TYPE_WHATSAPP = 'whatsapp';

    public const USED_FOR_REGISTER = 'register';
    public const USED_FOR_FORGOT_PASSWORD = 'forgot_password';

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
}
