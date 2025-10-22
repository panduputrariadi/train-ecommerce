<?php

namespace App\Modules\Share\Models;

use Database\Factories\AddressFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $table = 'addresses';

    protected $fillable = [
        'user_id',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    protected static function newFactory(): AddressFactory
    {
        return AddressFactory::new();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
