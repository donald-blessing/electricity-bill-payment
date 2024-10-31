<?php

namespace App\Actions;

use App\Data\LoginUserData;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginUserAction
{
    public static function execute(LoginUserData $data): array
    {
        $user = User::query()->where('email', $data->email)->first();

        if (!$user || !Hash::check($data->password, $user->password)) {
            return ['message' => 'Invalid login credentials'];
        }

        $token = $user->createToken('Electricity Bill')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }
}
