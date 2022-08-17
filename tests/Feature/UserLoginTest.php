<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserLoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use DatabaseMigrations;

    public function test_user_route_is_wrong()
    {
        $response = $this->json('POST', '/api/v1/user/login', []);

        $response
            ->assertStatus(400)
            ->assertJson(['error' => 'Bad request']);
    }

    public function test_user_gets_a_validation_error_if_data_is_empty()
    {
        $response = $this->json('POST', '/api/v1/auth/login', []);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'data'
            ]);
    }

    public function test_login_user_with_wrong_email()
    {
        $response = $this->json('POST', '/api/v1/auth/login', [
            'email' => '',
            'password' => '123test'
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'status',
                'message',
                'data'
            ]);
    }
}
