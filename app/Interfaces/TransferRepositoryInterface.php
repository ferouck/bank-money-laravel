<?php

namespace App\Interfaces;

interface TransferRepositoryInterface
{
    public function getTransferByUuid($uuId);
    public function getAllTransferByUserId($userId);
    public function getProtocolTransferById($id);
    public function deleteTransferByProtocol($protocol);
    public function createTransfer(array $transferData);
    public function updateTransferByProtocol($protocol, array $transferData);
}
