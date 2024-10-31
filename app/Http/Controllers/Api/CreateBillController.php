<?php

namespace App\Http\Controllers\Api;

use App\Actions\CreateBillAction;
use App\Data\CreateBillData;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBillRequest;
use Illuminate\Http\JsonResponse;

class CreateBillController extends Controller
{
    public function verify(
        CreateBillRequest $request,
        CreateBillAction $createBillAction
    ): JsonResponse {
        $bill = $createBillAction->execute(CreateBillData::from($request->validated()));
        return response()->json([
            'message' => 'Bill created successfully',
            'bill'    => $bill,
        ]);
    }
}
