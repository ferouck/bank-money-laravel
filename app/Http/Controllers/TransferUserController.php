<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Services\TransferUserService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TransferUserController extends ApiController
{
    private TransferUserService $transferUserService;

    private UserService $userService;

    public function __construct(TransferUserService $transferUserService, UserService $userService)
    {
        $this->transferUserService = $transferUserService;
        $this->userService = $userService;
    }

    public function make(Request $request)
    {
        $data = $request->only('payee', 'value');
        $validator = Validator::make($data, [
            'payee' => 'required|exists:users,id',
            'value' => 'required'
        ]);

        if ($validator->fails())
            return response()->json(['error' => $validator->messages()], Response::HTTP_BAD_REQUEST);

        $payerInfo = $this->userService->getInfoUserByToken();
        $canTransfer = $this->transferUserService->validUserCanTransfer($payerInfo->id, $request->payee, $payerInfo->type);
        if (!$canTransfer['result'])
            return $this->errorResponse($canTransfer['message'], 401);


        $transfer = $this->transferUserService->createTransfer($data);
    }
}
