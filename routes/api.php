<?php

use App\Http\Controllers\Api\CreateBillController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\ProcessPaymentController;
use App\Http\Controllers\Api\RegisterUserController;
use App\Http\Controllers\Api\WalletController;
use Illuminate\Support\Facades\Route;

Route::post('/register', RegisterUserController::class);
Route::post('/login', LoginController::class);
Route::post('/logout', LogoutController::class)->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/electricity/verify', [CreateBillController::class, 'verify']);
    Route::post('/vend/{validationRef}/pay', ProcessPaymentController::class);
    Route::post('/wallets/{id}/add-funds', WalletController::class);
});
