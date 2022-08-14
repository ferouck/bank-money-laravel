<?php

namespace App\Services;

use App\Interfaces\TransferUserRepositoryInterface as Repository;
use App\Services\AuthService;
use GuzzleHttp;

class TransferUserService
{
    private Repository $transferRepository;

    private AuthService $authService;

    public function __construct(Repository $transferRepository, AuthService $authService)
    {
        $this->transferRepository = $transferRepository;
        $this->authService = $authService;
        $this->authorization_url = env('AUTHORIZATION_URL');
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

    public function authorization()
    {
        $client = new GuzzleHttp\Client();
        $url = strval($this->authorization_url);
        $res = $client->get($url);
        $data = json_decode($res->getBody());

        if($data->message != 'Autorizado')
            return false;

        return true;
    }

    public function removeTransfer($uuId)
    {
        // Adicionar log que foi removido
        $this->transferRepository->deleteTransfer($uuId);
    }
}
