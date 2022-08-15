<?php

namespace App\Services;

use App\Interfaces\TransferRepositoryInterface as Repository;
use App\Services\AuthService;
use App\Services\ExtractService;

class TransferService
{
    private Repository $transferRepository;

    private ExtractService $extractService;

    private AuthService $authService;

    public function __construct(Repository $transferRepository, ExtractService $extractService, AuthService $authService)
    {
        $this->transferRepository = $transferRepository;
        $this->extractService = $extractService;
        $this->authService = $authService;
    }

    public function createTransfer($data, $payerId)
    {
        $data['payer'] = $payerId;
        $register =$this->transferRepository->createTransfer($data);
        return $this->transferRepository->getProtocolTransferById($register->id);
    }

    public function insertBalance($data)
    {
        return $this->extractService->createExtract($data);
    }

    public function reverseBalance($extract)
    {
        $data = array(
            'reference' => $extract->reference,
            'value' => abs($extract->value),
            'type' => 'reversal',
            'protocol' => $extract->protocol
        );

        $this->extractService->updateExtract($extract->id, $data);
    }

    public function updateStatusTransfer($protocol)
    {
        $data = array('status' => 'fulfilled');
        return $this->transferRepository->updateTransferByProtocol($protocol, $data);
    }

    public function makePayeerData($userId, $value, $protocol)
    {
        //Adiciona nome do usuario que recebeu
        return array(
            'user_id' => $userId,
            'reference' => 'Transferencia para nome',
            'value' => "-$value",
            'type' => 'paid',
            'protocol' => $protocol
        );
    }

    public function makePayeeData($userId, $value, $protocol)
    {
        return array(
            'user_id' => $userId,
            'reference' => 'Transferencia recebida do nome',
            'value' => $value,
            'type' => 'recive',
            'protocol' => $protocol
        );
    }

    public function validUserCanTransfer($userId, $request, $type)
    {
        $balance = $this->extractService->getBalanceUser($userId);
        $array = array('result' => true, 'message' => '');

        if($balance < $request->value){
            $array['result'] = false;
            $array['message'] = "The user does not have this balance for the transaction";
        }

        if($type == 'shop'){
            $array['result'] = false;
            $array['message'] = "Users of type shop can't transfer for another users";
        }

        if($userId == $request->payee){
            $array['result'] = false;
            $array['message'] = "Unable to transfer to yourself";
        }

        return $array;
    }

    public function getAuthorizationForTransfer()
    {
        return $this->authService->authorization();
    }

    public function removeTransfer($uuId)
    {
        // Adicionar log que foi removido
        $this->transferRepository->deleteTransfer($uuId);
    }
}
