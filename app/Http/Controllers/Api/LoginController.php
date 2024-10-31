<?php

namespace App\Http\Controllers\Api;

use App\Actions\LoginUserAction;
use App\Data\LoginUserData;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function __invoke(LoginUserRequest $request, LoginUserAction $action): JsonResponse
    {
        $response = $action->execute(LoginUserData::from($request->validated()));

        if (isset($response['message'])) {
            return response()->json($response, 422);
        }

        $data = array_merge($response, ['message' => 'Login successful']);

        return response()->json($data);
    }
}
