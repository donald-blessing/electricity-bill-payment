<?php

namespace App\Traits;

use Propaganistas\LaravelPhone\PhoneNumber;
use Throwable;

trait PhoneNumberFormatter
{
    private function isValidPhoneNumber(string $value): bool
    {
        return (substr($value, 0, 5) == "+2340" && strlen($value) == 15)
            || (substr($value, 0, 4) == "+234" && strlen($value) == 14)
            || (substr($value, 0, 3) == "234" && strlen($value) == 13)
            || (substr($value, 0, 1) == "0" && strlen($value) == 11)
            || (substr($value, 0, 1) != "0" && strlen($value) == 10);
    }


    private function formatPhoneNumber(string $value)
    {
        try {
            return PhoneNumber::make($value)->ofCountry('NG')->formatE164();
        } catch (Throwable $e) {
            report($e);
            return false;
        }
    }

    /**
     * @param  string  $value
     *
     * @return false
     */
    public function getFormattedPhoneNumber(string $value): false|string
    {
        if (!$this->isValidPhoneNumber($value)) {
            return false;
        }

        return $this->formatPhoneNumber($value);
    }
}
