<?php

namespace App\Interfaces;

interface TransferUserRepositoryInterface
{
    public function getTransferByUuid($uuId);
    public function getAllTransferByUserId($userId);
    public function deleteTransfer($uuId);
    public function createTransfer(array $transferData);
    public function updateTransfer($uuId, array $transferData);
}
