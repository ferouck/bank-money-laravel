<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserRegisterTest extends TestCase
{
    use DatabaseMigrations;

    public function test_register_user_with_wrong_name()
    {

        $response = $this->json('POST', '/api/v1/user/register', [
            'name' => '',
            'email' => 'test@user.com',
            'cpf_cnpj' => '85748949078',
            'type' => 'client',
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

    public function test_user_gets_a_validation_error_if_data_is_empty()
    {
        $response = $this->json('POST', '/api/v1/user/register', []);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'data'
            ]);
    }

    public function test_user_route_is_wrong()
    {
        $response = $this->json('POST', '/api/v1/users/register', []);

        $response
            ->assertStatus(400)
            ->assertJson(['error' => 'Bad request']);
    }
}
