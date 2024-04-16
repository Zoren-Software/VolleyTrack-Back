<?php

namespace Tests\Feature\Tenant;

use Tests\TestCase;

class TenantTest extends TestCase
{
    protected $tenancy = true;

    protected $tenant = 'graphql';

    /**
     * A basic test route horizon for login.
     *
     * @test
     *
     * @return void
     */
    public function createTenant()
    {
        $this->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);

        $response = $this->postJson(
            $this->tenantUrl . '/tenant',
            [
                'tenantId' => 'tenant-test-' . rand(1, 1000)
            ]
        );

        $response->assertStatus(200);
    }
}
