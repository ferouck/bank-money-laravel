<?php

namespace App\Repositories;

use App\Interfaces\ExtractUserRepositoryInterface;
use App\Models\ExtractUser;

class ExtractUserRepository implements ExtractUserRepositoryInterface
{
    public function getBalanceUser($userId)
    {
        return ExtractUser::where('user_id', $userId)->sum('value');
    }

    public function getTransferByProtocol($transferProtocol)
    {
        return ExtractUser::where('protocol', $transferProtocol)
                ->orderBy('created_at')
                ->get();
    }

    public function createExtract(array $transferData)
    {
        return ExtractUser::create($transferData);
    }

    public function updateExtract($id, array $transferData)
    {
        return ExtractUser::whereId($id)->update($transferData);
    }
}
