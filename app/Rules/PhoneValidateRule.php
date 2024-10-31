<?php

namespace App\Rules;

use App\Traits\PhoneNumberFormatter;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class PhoneValidateRule implements ValidationRule
{
    use PhoneNumberFormatter;

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->isValidPhoneNumber($value) === false) {
            $fail($attribute.' is not a valid phone number.');
        }
    }
}
