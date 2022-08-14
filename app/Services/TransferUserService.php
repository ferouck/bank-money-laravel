<?php

namespace App\Services;

use App\Interfaces\TransferUserRepositoryInterface as Repository;
use App\Services\AuthService;

class TransferUserService
{
    private Repository $transferRepository;

    private AuthService $authService;

    public function __construct(Repository $transferRepository, AuthService $authService)
    {
        $this->transferRepository = $transferRepository;
        $this->authService = $authService;
    }

    public function createTransfer($data, $payerId)
    {
        $data['payer'] = $payerId;
        return $this->transferRepository->createTransfer($data);
    }

    public function validUserCanTransfer($userId, $payee, $type)
    {
        $array = array('result' => true, 'message' => '');
        // Adiciona regra com saldo depois
        if($type == 'shop')
            $array['result'] = false;
            $array['message'] = "Users of type shop can't transfer for another users";

        if($userId == $payee)
            $array['result'] = false;
            $array['message'] = "Unable to transfer to yourself";

        return $array;
    }
}
