<?php

namespace App\Services;

use App\Interfaces\UserRepositoryInterface as Repository;

class UserService
{
    private Repository $userRepository;

    public function __construct(Repository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function create($data)
    {
        $data['password'] = bcrypt($data['password']);
        return $this->userRepository->createUser($data);
    }
}
