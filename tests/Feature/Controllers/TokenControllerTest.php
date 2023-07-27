<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;

class TokenControllerTest extends TestCase
{
    /**
     * @return void
     */
    public function test_it_fails_for_non_existing_organization(): void
    {
        $this->post('/api/tokens', [
            'email' => 'inexist@example.com',
            'password' => 'password',
        ])
            ->assertNotFound();
    }

    /**
     * @return void
     */
    public function test_it_fails_for_wrong_password(): void
    {
        $this->post('/api/tokens', [
            'email' => 'sony@example.com',
            'password' => 'wrong',
        ])
            ->assertBadRequest();
    }

    /**
     * @return void
     */
    public function test_it_can_return_token(): void
    {
        $this->post('/api/tokens', [
            'email' => 'sony@example.com',
            'password' => 'password',
        ])
            ->assertOk()
            ->assertJsonStructure([
                'token'
            ]);
    }
}
