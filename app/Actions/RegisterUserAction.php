<?php

namespace App\Actions;

use App\Data\RegisterUserData;
use App\Models\User;

class RegisterUserAction
{
    public static function execute(RegisterUserData $data): array
    {
        $user = User::query()->create($data->toArray());

        // Generate a new token for the user
        $token = $user->createToken('Electricity Bill')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }
}
