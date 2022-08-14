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
    public function test_ping()
    {
        $response = $this->get($this->tenantUrl . '/ping');

        $response->assertStatus(200);
    }
}
