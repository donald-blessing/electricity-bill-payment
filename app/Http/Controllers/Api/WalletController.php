<?php

namespace App\Http\Controllers\Api;

use App\Actions\AddFundsToWalletAction;
use App\Data\AddFundsToWalletData;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddFundsToWalletRequest;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;

class WalletController extends Controller
{
    public function __invoke(
        AddFundsToWalletRequest $request,
        AddFundsToWalletAction $addFundsToWalletAction,
        string $id
    ): JsonResponse {
        $data = array_merge($request->validated(), ['wallet' => Wallet::find($id)]);

        $wallet = $addFundsToWalletAction->execute(AddFundsToWalletData::from($data));

        return response()->json([
            'message' => 'Funds added successfully',
            'wallet'  => $wallet,
        ]);
    }
}
