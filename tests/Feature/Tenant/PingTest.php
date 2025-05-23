<?php

namespace Tests\Feature\Tenant;

use Tests\TestCase;

class PingTest extends TestCase
{
    protected $tenancy = true;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function ping()
    {
        $response = $this->get($this->tenantUrl . '/v1/ping');

        $response->assertStatus(200);
    }
}
