<?php

namespace App\Services;

use App\Interfaces\ExtractRepositoryInterface as Repository;

class ExtractService
{
    private Repository $extractRepository;

    public function __construct(Repository $extractRepository)
    {
        $this->extractRepository = $extractRepository;
    }

    public function createExtract($data)
    {
        return $this->extractRepository->createExtract($data);
    }

    public function getBalanceUser($userId)
    {
        return $this->extractRepository->getBalanceUser($userId);
    }

    public function updateExtract($id, $data)
    {
        return $this->extractRepository->updateExtract($id, $data);
    }
}
