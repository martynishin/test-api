<?php

namespace Tests\Feature\Services;

use App\Services\TokenService;
use Tests\TestCase;

class TokenServiceTest extends TestCase
{
    /**
     * @return void
     */
    public function test_it_can_issue_token(): void
    {
        /** @var TokenService $service */
        $service = resolve(TokenService::class);

        $data = $service->issueToken('sony@example.com', 'password');

        $this->assertIsString($data);
    }
}
