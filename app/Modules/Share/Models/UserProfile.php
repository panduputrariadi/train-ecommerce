<?php

namespace App\Modules\Share\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_profiles';

    protected $fillable = [
        'user_id',
        'code',
        'name',
        'photo',
        'phone',
    ];

    public function getRouteKeyName(): string
    {
        return 'code';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeCustomer($query): void
    {
        $query->whereHas('user.roles', fn ($q) => $q->where('slug', 'like', 'customer.%')
        );
    }
}
