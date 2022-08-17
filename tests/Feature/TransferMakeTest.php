<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TransferMakeTest extends TestCase
{
    use DatabaseMigrations;

    public function test_transfer_route_is_wrong()
    {
        $response = $this->json('POST', '/api/v1/transfers/make', []);

        $response
            ->assertStatus(400)
            ->assertJson(['error' => 'Bad request']);
    }
}
