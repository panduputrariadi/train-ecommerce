<?php

namespace App\Modules\Auth\Models;

use App\Modules\OTP\Models\Otp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_profiles';
    protected $fillable = [
        'user_id',
        'name',
        'photo',
        'phone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function otps()
    {
        return $this->hasMany(Otp::class);
    }
}
