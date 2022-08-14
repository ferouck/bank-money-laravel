<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends ApiController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(Request $request)
    {
        $data = $request->only('name', 'cpf_cnpj', 'email', 'password', 'type');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'cpf_cnpj' => 'required|unique:users',
            'type' => 'required|string',
            'password' => 'required|string|min:6|max:50'
        ]);

        if ($validator->fails())
            return $this->errorResponse($validator->messages(), 422);

        $user = $this->userService->create($data);

        return $this->successResponse($user);
    }
}
