<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
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
        $this->payerInfo = $this->userService->getInfoUserByToken();
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
    }

    private function validTransfer($request)
    {
        $validated = $this->transferUserService->validUserCanTransfer($this->payerInfo->id, $request->payee, $this->payerInfo->type);
        return $validated;
    }

    private function registerTransfer($data)
    {
        $transfer = $this->transferUserService->createTransfer($data, $this->payerInfo->id);
        if(!$this->transferUserService->authorization())
            $this->transferUserService->removeTransfer($transfer->id);
    }
}
