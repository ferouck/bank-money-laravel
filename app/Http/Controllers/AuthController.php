<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends ApiController
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:60'
        ]);

        if ($validator->fails())
            return $this->errorResponse($validator->messages(), 422);

        try {
            if (!$token = $this->authService->auth($credentials))
                return $this->errorResponse('Login credentials are invalid', 422);
        } catch (JWTException $e) {
            $this->errorResponse('Could not create token error:' . $e, 500);
        }

        return $this->successResponse($token);
    }

    public function logout(Request $request)
    {
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        if ($validator->fails())
            return $this->errorResponse($validator->messages(), 422);

        try {
            $this->authService->invalidateJWT($request->token);
            return $this->successResponse($request->token, 'User has been logged out');
        } catch (JWTException $e) {
            return $this->errorResponse('Sorry, user cannot be logged out', 500);
        }
    }
}
