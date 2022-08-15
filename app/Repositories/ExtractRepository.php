<?php

namespace App\Repositories;

use App\Interfaces\ExtractRepositoryInterface;
use App\Models\Extract;

class ExtractRepository implements ExtractRepositoryInterface
{
    public function getBalanceUser($userId)
    {
        return Extract::where('user_id', $userId)
                ->where('type', '<>', 'reversal')
                ->sum('value');
    }

    public function getTransferByProtocol($transferProtocol)
    {
        return Extract::where('protocol', $transferProtocol)
                ->orderBy('created_at')
                ->get();
    }

    public function createExtract(array $transferData)
    {
        return Extract::create($transferData);
    }

    public function updateExtract($id, array $transferData)
    {
        return Extract::whereId($id)->update($transferData);
    }
}
