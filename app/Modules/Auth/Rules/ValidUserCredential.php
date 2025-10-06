<?php

namespace App\Modules\Auth\Rules;

use App\Modules\Share\Enum\UserStatus;
use App\Modules\Share\Models\User;
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
            $fail('email or password are incorrect');
        }

        if ($user && $user->status !== UserStatus::ACTIVE) {
            $fail('This account is inactive.');
        }
    }
}
