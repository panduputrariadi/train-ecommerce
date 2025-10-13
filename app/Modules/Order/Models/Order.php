<?php

namespace App\Modules\Order\Models;

use App\Modules\Order\Enum\OrderStatus;
use App\Modules\Payment\Models\Payment;
use App\Modules\Share\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

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
}
