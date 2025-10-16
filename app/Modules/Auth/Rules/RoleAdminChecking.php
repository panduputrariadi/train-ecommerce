<?php

namespace App\Modules\Auth\Rules;

use App\Modules\Share\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RoleAdminChecking implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = User::where('email', request()->input('email'))->first();

        if (! $user) {
            return;
        }

        if (! $user->roles()->where('slug', 'like', 'admin.%')->exists()) {
            $fail('Credential invalid');
        }
    }
}
