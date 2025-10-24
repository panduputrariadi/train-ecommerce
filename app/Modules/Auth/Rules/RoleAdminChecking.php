<?php

namespace App\Modules\Auth\Rules;

use App\Modules\Share\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RoleAdminChecking implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $email = request()->input('email');

        $user = User::with('roles')
            ->where('email', $email)
            ->first();

        if (! $user) {
            return;
        }

        $isAdmin = $user->roles->contains(fn ($role) => str_starts_with($role->slug, 'admin.'));

        if (! $isAdmin) {
            $fail('Credential invalid');
        }
    }
}
