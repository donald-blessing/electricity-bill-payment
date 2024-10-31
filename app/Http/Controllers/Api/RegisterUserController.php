<?php

namespace App\Http\Controllers\Api;

use App\Actions\RegisterUserAction;
use App\Data\RegisterUserData;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Http\JsonResponse;

class RegisterUserController extends Controller
{
    public function __invoke(
        RegisterUserRequest $request,
        RegisterUserAction $registerUserAction
    ): JsonResponse {
        $response = $registerUserAction->execute(RegisterUserData::from($request->validated()));

        $data = array_merge($response, ['message' => 'User registered successfully',]);
        return response()->json($data, 201);
    }
}
