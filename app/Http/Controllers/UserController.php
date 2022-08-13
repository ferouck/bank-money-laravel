<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(Request $request)
    {
        $data = $request ->only('name', 'cpf_cnpj', 'email', 'password', 'type');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'cpf_cnpj' => 'required|unique:users',
            'type' => 'required|string',
            'password' => 'required|string|min:6|max:50'
        ]);

        if ($validator->fails())
            return response()->json(['error' => $validator->messages()], Response::HTTP_BAD_REQUEST);

        $user = $this->userService->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Usuario criado com sucesso',
            'data' => $user
        ], Response::HTTP_OK);
    }
}
