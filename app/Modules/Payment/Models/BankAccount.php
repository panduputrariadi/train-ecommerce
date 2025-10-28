<?php

namespace App\Modules\Payment\Models;

use App\Modules\Payment\Enum\PaymentMethodEnum;
use Database\Factories\BankAccountFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_name',
        'account_number',
        'account_name',
        'payment_method_id',
        'is_active',
    ];

    protected $casts = [
        'payment_method_id' => PaymentMethodEnum::class,
        'is_active' => 'boolean',
    ];

    protected static function newFactory(): BankAccountFactory
    {
        return BankAccountFactory::new();
    }

    /**
     * Get the payment method that owns the bank account.
     */
    public function method(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
}
