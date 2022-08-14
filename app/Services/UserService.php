<?php

namespace App\Services;

use App\Interfaces\UserRepositoryInterface as Repository;
use App\Services\AuthService;

class UserService
{
    private Repository $userRepository;

    private AuthService $authService;

    public function __construct(Repository $userRepository, AuthService $authService)
    {
        $this->userRepository = $userRepository;
        $this->authService = $authService;
    }

    public function create($data)
    {
        $data['password'] = bcrypt($data['password']);
        return $this->userRepository->createUser($data);
    }

    public function getInfoUserByToken()
    {
        $data = $this->authService->getTokenPayload();
        return $data['user'];
    }
}
