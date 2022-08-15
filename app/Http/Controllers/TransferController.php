<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Services\TransferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransferController extends ApiController
{
    private TransferService $transferService;

    public function __construct(TransferService $transferService)
    {
        $this->transferService = $transferService;
    }

    public function initTransfer(Request $request)
    {
        $data = $request->only('payee', 'value');
        $validator = Validator::make($data, [
            'payee' => 'required|exists:users,id',
            'value' => 'required'
        ]);

        if ($validator->fails())
            return $this->errorResponse($validator->messages(), 422);

        $canTransfer = $this->transferService->validUserCanTransfer($request);
        if (!$canTransfer['result'])
            return $this->errorResponse($canTransfer['message'], 422);

        $transfer = $this->registerTransfer($data);

        if (!$transfer)
            return $this->errorResponse('Transaction denied due to not being authorized, please try again later', 403);

        $this->transferService->registerBalanceToPayee($request->payee, $request->value, $transfer->protocol);

        return $this->successResponse($transfer, 'Transfer made successfully');
    }

    private function registerTransfer($data)
    {
        $transfer = $this->transferService->createTransfer($data);

        if (!$this->transferService->getAuthorizationForTransfer()) {
            $this->transferService->reverseBalance($transfer);
            return false;
        }

        return $transfer;
    }
}
