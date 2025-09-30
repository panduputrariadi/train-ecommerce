<?php

namespace App\Modules\Auth\Rules;

use App\Modules\Auth\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class ValidUserCredential implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $email = request()->input('email');
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($value, $user->password)) {
            $fail('The provided credentials are incorrect.');
        }

        if ($user && ! $user->is_active->value) {
            $fail('This account is inactive.');
        }
    }
}
