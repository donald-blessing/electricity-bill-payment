<?php

namespace App\Services\Traits;

use App\Models\Bill;
use Valorin\Random\Random;

trait GenerateTokenTrait
{
    /**
     * Generate a 20-digit random number as a string
     *
     *
     */
    protected function generateToken(): string
    {
        $formattedNumber = '';
        do {
            $randomNumber = Random::string(length: 20, lower: false, upper: false, symbols: false);

            // Insert a hyphen after every 4 digits
            $formattedNumber = implode('-', str_split($randomNumber, 4));
        } while (Bill::query()->where('token', $formattedNumber)->exists());

        return $formattedNumber;
    }
}
