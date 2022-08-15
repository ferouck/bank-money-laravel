<?php

namespace App\Services;

use JWTAuth;
use GuzzleHttp;

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

    public function getTokenPayload()
    {
        $token = JWTAuth::getToken();
        return JWTAuth::getPayload($token)->toArray();
    }

    public function authorization()
    {
        $client = new GuzzleHttp\Client();
        $url = strval($this->authorization_url);
        $res = $client->get($url);
        $data = json_decode($res->getBody());

        if($data->message != 'Autorizado')
            return false;

        return true;
    }
}
