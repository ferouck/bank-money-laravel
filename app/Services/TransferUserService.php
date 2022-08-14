<?php

namespace App\Services;

use App\Interfaces\TransferUserRepositoryInterface as Repository;
use App\Services\ExtractUserService;
use GuzzleHttp;

class TransferUserService
{
    private Repository $transferRepository;

    private ExtractUserService $extractUserService;

    public function __construct(Repository $transferRepository, ExtractUserService $extractUserService)
    {
        $this->transferRepository = $transferRepository;
        $this->extractUserService = $extractUserService;
        $this->authorization_url = env('AUTHORIZATION_URL');
    }

    public function createTransfer($data, $payerId)
    {
        $data['payer'] = $payerId;
        return $this->transferRepository->createTransfer($data);
    }

    public function validUserCanTransfer($userId, $request, $type)
    {
        $balance = $this->extractUserService->getBalanceUser($userId);
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
