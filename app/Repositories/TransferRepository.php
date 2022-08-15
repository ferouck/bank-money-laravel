<?php

namespace App\Repositories;

use App\Interfaces\TransferRepositoryInterface;
use App\Models\Transfer;

class TransferRepository implements TransferRepositoryInterface
{
    public function getTransferByUuid($uuId)
    {
        return Transfer::findOrFail($uuId);
    }

    public function getProtocolTransferById($id)
    {
        return Transfer::findOrFail($id);
    }

    public function getAllTransferByUserId($userId)
    {
        return Transfer::where('user_id', $userId)
                ->orderBy('created_at')
                ->get();
    }

    public function deleteTransferByProtocol($protocol)
    {
        Transfer::where('transfer_protocol', $protocol)->delete();
    }

    public function createTransfer(array $transferData)
    {
        return Transfer::create($transferData);
    }

    public function updateTransferByProtocol($protocol, $data)
    {
        return Transfer::where('transfer_protocol', $protocol)->update($data);
    }
}
