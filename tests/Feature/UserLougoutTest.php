<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserLougoutTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_route_is_wrong()
    {
        $response = $this->json('POST', '/api/v1/user/logout', []);

        $response
            ->assertStatus(400)
            ->assertJson(['error' => 'Bad request']);
    }
}
