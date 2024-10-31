<?php

namespace App\Http\Controllers\Api;

use App\Actions\ProcessBillAction;
use App\Data\ProcessBillPaymentData;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProcessPaymentController extends Controller
{
    /**
     * @throws Exception
     */
    public function __invoke(
        Request $request,
        ProcessBillAction $processBillAction,
        string $validationRef
    ): JsonResponse {
        $response = $processBillAction->execute(ProcessBillPaymentData::from(['validationRef' => $validationRef]));

        if (isset($response['message'])) {
            return response()->json($response, 422);
        }

        $data = array_merge($response, [
            'message' => 'Payment successful',
        ]);

        return response()->json($data);
    }
}
