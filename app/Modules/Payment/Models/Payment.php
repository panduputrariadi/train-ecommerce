<?php

namespace App\Modules\Payment\Models;

use App\Modules\Order\Models\Order;
use App\Modules\Payment\Enum\PaymentMethodEnum;
use App\Modules\Payment\Enum\PaymentStatus;
use App\Modules\Share\Models\User;
use App\Modules\Share\Traits\HasActivityUser;
use App\Modules\Share\Traits\HasGenerateCode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Payment extends Model
{
    use HasActivityUser, HasFactory, HasGenerateCode;

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
        'amount' => 'float',
        'order_id' => Order::class,
        'bank_account_id' => BankAccount::class,
        'created_by' => User::class,
        'verified_by' => User::class,
        'paid_at' => 'datetime',
        'status' => PaymentStatus::class,
        'payment_method_id' => PaymentMethodEnum::class,
    ];

    /**
     * Get the name of the route key for the payment.
     */
    public function getRouteKeyName(): string
    {
        return 'code';
    }

    /**
     * Get the prefix code of the payment.
     */
    protected function getCodePrefix(): string
    {
        return 'PAY';
    }

    /**
     * Get the name of the customer who made the payment.
     */
    public function getCodeName(): User
    {
        return Auth::user()->load(['profile', 'roles']);
    }

    /**
     * Get the order that owns the payment.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Get the payment method that owns the payment.
     */
    public function method(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    /**
     * Get the bank account that owns the payment.
     */
    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }

    /**
     * Relation to the user who created the payment.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relation to the user who verified the payment.
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Relation to payment receipt
     */
    public function receipts(): HasMany
    {
        return $this->hasMany(PaymentReceipt::class, 'payment_id');
    }
}
