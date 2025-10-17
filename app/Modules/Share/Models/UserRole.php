<?php

namespace App\Modules\Share\Models;

use Database\Factories\UserRoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRole extends Pivot
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_roles';

    protected $fillable = ['user_id', 'role_id'];

    protected static function newFactory(): UserRoleFactory
    {
        return UserRoleFactory::new();
    }
}
