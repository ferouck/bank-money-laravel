<?php

namespace App\Interfaces;

interface ExtractUserRepositoryInterface
{
    public function getBalanceUser($userId);
    public function getTransferByProtocol($protocol);
    public function createExtract(array $extractData);
    public function updateExtract($id, array $extractData);
}
