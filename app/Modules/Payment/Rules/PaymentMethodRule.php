<?php

namespace App\Modules\Payment\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PaymentMethodRule implements ValidationRule
{
    protected ?string $fileField;
    protected ?string $fileValue;

    public function __construct(?string $fileField = null, ?string $fileValue = null)
    {
        $this->fileField = $fileField;
        $this->fileValue = $fileValue;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ((int)$value === 2 && empty($this->fileValue)) {
            $fail("Must upload evidence of payment");
        }
    }
}
