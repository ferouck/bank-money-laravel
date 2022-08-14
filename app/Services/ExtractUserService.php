<?php

namespace App\Services;

use App\Interfaces\ExtractUserRepositoryInterface as Repository;

class ExtractUserService
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
}