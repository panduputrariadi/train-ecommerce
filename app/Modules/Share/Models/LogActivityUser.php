<?php

namespace App\Modules\Share\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogActivityUser extends Model
{
    protected $fillable =  [
        'type',
        'description',
        'user_id'
    ];

    protected $cast = [
        'description' => 'array'
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
