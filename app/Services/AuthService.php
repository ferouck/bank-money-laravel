<?php

namespace App\Services;

use JWTAuth;

class AuthService
{
    public function auth($credentials)
    {
        return JWTAuth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']]);
    }

    public function invalidateJWT($token)
    {
        JWTAuth::invalidate($token);
    }
}
