<?php

namespace App\Modules\Payment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'file_path',
        'mime_type',
        'uploaded_at',
    ];

    protected $casts = [
        'payment_id' => 'integer',
        'file_path' => 'string',
        'mime_type' => 'string',
        'uploaded_at' => 'datetime',
    ];

    /**
     * Get the payment that owns the payment receipt.
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
}
