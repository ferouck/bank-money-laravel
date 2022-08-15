<?php

namespace App\Services;

use App\Interfaces\TransferRepositoryInterface as Repository;
use App\Services\AuthService;
use App\Services\ExtractService;
use App\Services\UserService;
use App\Jobs\NotificationPayee;

class TransferService
{
    private Repository $transferRepository;

    private ExtractService $extractService;

    private AuthService $authService;

    private UserService $userService;

    public function __construct(Repository $transferRepository, ExtractService $extractService, AuthService $authService, UserService $userService)
    {
        $this->transferRepository = $transferRepository;
        $this->extractService = $extractService;
        $this->authService = $authService;
        $this->userService = $userService;
        $this->payeerInfo = $this->userService->getInfoUserByToken();
    }

    public function createTransfer($data)
    {
        $data['payer'] = $this->payeerInfo->id;
        $register = $this->transferRepository->createTransfer($data);
        $transfer = $this->transferRepository->getProtocolTransferById($register->id);
        $dataPayeer = $this->makePayeerData($transfer->value, $transfer->transfer_protocol);

        return $this->insertBalance($dataPayeer);
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
        $this->removeTransfer($extract->protocol);
    }

    public function validUserCanTransfer($request)
    {
        $balance = $this->extractService->getBalanceUser($this->payeerInfo->id);
        $array = array('result' => true, 'message' => '');

        if ($balance < $request->value) {
            $array['result'] = false;
            $array['message'] = "The user does not have this balance for the transaction";
        }

        if ($this->payeerInfo->type == 'shop') {
            $array['result'] = false;
            $array['message'] = "Users of type shop can't transfer for another users";
        }

        if ($this->payeerInfo->id == $request->payee) {
            $array['result'] = false;
            $array['message'] = "Unable to transfer to yourself";
        }

        return $array;
    }

    public function getAuthorizationForTransfer()
    {
        return $this->authService->authorization();
    }

    public function registerBalanceToPayee($userId, $value, $protocol)
    {
        $dataPayee = $this->makePayeeData($userId, $value, $protocol);
        $this->insertBalance($dataPayee);
        $this->updateStatusTransfer($protocol);
        NotificationPayee::dispatch();
    }

    private function makePayeerData($value, $protocol)
    {
        //Adiciona nome do usuario que recebeu
        return array(
            'user_id' => $this->payeerInfo->id,
            'reference' => 'Transferencia para nome',
            'value' => "-$value",
            'type' => 'paid',
            'protocol' => $protocol
        );
    }

    private function makePayeeData($userId, $value, $protocol)
    {
        return array(
            'user_id' => $userId,
            'reference' => 'Transferencia recebida do nome',
            'value' => $value,
            'type' => 'recive',
            'protocol' => $protocol
        );
    }

    private function insertBalance($data)
    {
        return $this->extractService->createExtract($data);
    }

    private function updateStatusTransfer($protocol)
    {
        $data = array('status' => 'fulfilled');
        return $this->transferRepository->updateTransferByProtocol($protocol, $data);
    }

    private function removeTransfer($protocol)
    {
        $this->transferRepository->deleteTransferByProtocol($protocol);
    }
}
