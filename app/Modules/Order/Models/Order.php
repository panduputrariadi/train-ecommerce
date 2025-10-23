<?php

namespace App\Modules\Order\Models;

use App\Modules\Order\Casts\AddressDataCast;
use App\Modules\Order\Enum\OrderStatus;
use App\Modules\Payment\Models\Payment;
use App\Modules\Share\Models\User;
use App\Modules\Share\Traits\HasActivityUser;
use App\Modules\Share\Traits\HasGenerateCode;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    use HasGenerateCode, SoftDeletes, HasActivityUser;

    protected $table = 'orders';

    protected $fillable = [
        'code',
        'status',
        'sub_total',
        'tax_amount',
        'grand_total',
        'note',
        'user_id',
        'user_data',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'user_data' => AddressDataCast::class,
    ];

    protected bool $skipCreatedLog = true;

    /**
     * Get the name of the route key for the order.
     */
    public function getRouteKeyName(): string
    {
        return 'code';
    }

    /**
     * Get the prefix code for the order.
     *
     * This method returns the prefix code for the order, which is 'ORD'.
     */
    protected function getCodePrefix(): string
    {
        return 'ORD';
    }

    /**
     * Get the name of the user who made the order.
     *
     * If the user is logged in, this method returns the user's name.
     * Otherwise, it returns 'UNKNOWN'.
     */
    public function getCodeName(): string
    {
        return Auth::user()->loadMissing(['profile', 'roles']);
    }

    /**
     * Create a new order with the given attributes and associate it with the given user.
     *
     * This method generates a code for the order using the given user's name (or 'UNKNOWN' if the user is not logged in).
     *
     * @param  array  $attributes  The attributes for the order.
     * @param  \App\Modules\Share\Models\User  $user  The user to associate with the order.
     * @return self The created order.
     */
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

    /**
     * Get the user associated with the order.
     *
     * @return \App\Modules\Share\Models\User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the details of the order.
     */
    public function details(): HasMany
    {
        return $this->hasMany(DetailOrder::class);
    }

    /**
     * Get the payment associated with the order.
     *
     * @return \App\Modules\Payment\Models\Payment
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Scope a query to search orders by code, note, product name or product data.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
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
    
    /**
     * Scope a query to filter orders by creation date range.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \DateTimeInterface|string|null  $from  The start date of the creation date range.
     * @param  \DateTimeInterface|string|null  $to  The end date of the creation date range.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCreatedBetween($query, $from = null, $to = null): Builder
    {
        return $query
            ->when($from, fn ($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('created_at', '<=', $to));
    }

    /**
     * Scope a query to filter orders by status.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $status  The status to filter by.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus($query, String $status): Builder
    {
        return $query->when($status, fn ($q) => $q->where('status', $status));
    }
}
