<?php

namespace App\Modules\Payment\Models;

use App\Modules\Order\Models\Order;
use App\Modules\Payment\Enum\PaymentStatus;
use App\Modules\Share\Models\User;
use App\Modules\Share\Traits\HasGenerateCode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory, HasGenerateCode;

    protected $fillable = [
        'code',
        'order_id',
        'payment_method_id',
        'bank_account_id',
        'amount',
        'status',
        'note',
        'paid_at',
        'created_by',
        'verified_by',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'status' => PaymentStatus::class,
    ];

    public function getRouteKeyName(): string
    {
        return 'code';
    }

    protected function getCodePrefix(): string
    {
        return 'PAY';
    }

    public function getCodeName(): ?string
    {
        return optional($this->order->user->profile)->name;
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function method(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(PaymentReceipt::class, 'payment_id');
    }
}
