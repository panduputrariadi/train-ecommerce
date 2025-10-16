<?php

namespace App\Modules\Payment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the bank accounts of the payment method.
     */
    public function bankAccounts(): HasMany
    {
        return $this->hasMany(BankAccount::class, 'payment_method_id');
    }

    /**
     * Get the payments of the payment method.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'payment_method_id');
    }
}
