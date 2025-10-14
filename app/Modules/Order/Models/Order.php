<?php

namespace App\Modules\Order\Models;

use App\Modules\Order\Enum\OrderStatus;
use App\Modules\Payment\Models\Payment;
use App\Modules\Share\Models\User;
use App\Modules\Share\Traits\HasActivityUser;
use App\Modules\Share\Traits\HasGenerateCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    use SoftDeletes, HasGenerateCode;

    protected $table = 'orders';

    protected $fillable = [
        'code',
        'status',
        'sub_total',
        'tax_amount',
        'grand_total',
        'note',
        'user_id',
    ];

    protected $casts = [
        'status' => OrderStatus::class
    ];

    public function getRouteKeyName(): string
    {
        return 'code';
    }

    protected function getCodePrefix(): string
    {
        return 'ORD';
    }

    public function getCodeName(): string
    {
        $user = Auth::user();
        return $user?->profile?->name ?? 'UNKNOWN';
    }

    public static function createWithUser(array $attributes, $user): self
    {
        $attributes['code'] = self::generateCode(
            'orders',
            'ORD',
            $user->profile->name ?? 'UNKNOWN'
        );

        $attributes['user_id'] = $user->id;

        return static::create($attributes);
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(DetailOrder::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function scopeSearch($query, ?string $search): void
    {
        if (blank($search)) {
            return;
        }

        $query->where(function ($q) use ($search) {
            $q->where('code', 'like', "%{$search}%")
                ->orWhere('note', 'like', "%{$search}%")
                ->orWhereHas('details.product', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('details', function ($q3) use ($search) {
                    $q3->where('product_data', 'like', "%{$search}%");
                })
                ->orWhereHas('details.product', function ($q3) use ($search) {
                    $q3->where('name', 'like', "%{$search}%");
                });
        });
    }
}
