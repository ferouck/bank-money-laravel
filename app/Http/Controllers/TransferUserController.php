<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Jobs\NotificationPayee;
use App\Services\TransferUserService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransferUserController extends ApiController
{
    private TransferUserService $transferUserService;

    private UserService $userService;

    public function __construct(TransferUserService $transferUserService, UserService $userService)
    {
        $this->transferUserService = $transferUserService;
        $this->userService = $userService;
        $this->payeerInfo = $this->userService->getInfoUserByToken();
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

        $canTransfer = $this->validTransfer($request);
        if(!$canTransfer['result'])
            return $this->errorResponse($canTransfer['message'], 422);

        $transfer = $this->registerTransfer($data);

        if(!$transfer)
            return $this->errorResponse('Transaction denied due to not being authorized, please try again later', 403);

        $this->registerBalanceToPayee($request->payee, $request->value, $transfer->protocol);

        NotificationPayee::dispatch();
        return $this->successResponse($transfer, 'Transfer made successfully');
    }

    private function validTransfer($request)
    {
        $validated = $this->transferUserService->validUserCanTransfer($this->payeerInfo->id, $request, $this->payeerInfo->type);
        return $validated;
    }

    private function registerTransfer($data)
    {
        $transfer = $this->transferUserService->createTransfer($data, $this->payeerInfo->id);
        $dataPayeer = $this->transferUserService->makePayeerData($this->payeerInfo->id, $transfer->value, $transfer->transfer_protocol);
        $extractPayeer = $this->transferUserService->insertBalance($dataPayeer);

        if(!$this->transferUserService->authorization()){
            $this->transferUserService->removeTransfer($transfer->id);
            $this->transferUserService->reverseBalance($extractPayeer);
            return false;
        }

        return $extractPayeer;
    }

    private function registerBalanceToPayee($userId, $value, $protocol)
    {
        $dataPayee = $this->transferUserService->makePayeeData($userId, $value, $protocol);
        $this->transferUserService->insertBalance($dataPayee);
        $this->transferUserService->updateStatusTransfer($protocol);
    }
}

