<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function getAllUsers()
    {
        return User::all();
    }

    public function getUserById($userId)
    {
        return User::findOrFail($userId);
    }

    public function getUserNameById($userId)
    {
        return User::findOrFail($userId, ['name']);
    }

    public function deleteUser($userId)
    {
        User::destroy($userId);
    }

    public function createUser(array $userData)
    {
        return User::create($userData);
    }
}
