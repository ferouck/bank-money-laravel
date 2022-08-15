<?php

namespace App\Repositories;

use App\Interfaces\TransferUserRepositoryInterface;
use App\Models\TransferUser;

class TransferUserRepository implements TransferUserRepositoryInterface
{
    public function getTransferByUuid($uuId)
    {
        return TransferUser::findOrFail($uuId);
    }

    public function getProtocolTransferById($id)
    {
        return TransferUser::findOrFail($id);
    }

    public function getAllTransferByUserId($userId)
    {
        return TransferUser::where('user_id', $userId)
                ->orderBy('created_at')
                ->get();
    }

    public function deleteTransfer($transferId)
    {
        TransferUser::destroy($transferId);
    }

    public function createTransfer(array $transferData)
    {
        return TransferUser::create($transferData);
    }

    public function updateTransferByProtocol($protocol, $data)
    {
        return TransferUser::where('transfer_protocol', $protocol)->update($data);
    }
}
