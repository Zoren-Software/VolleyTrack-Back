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
     * @dataProvider createTenantDataProvider
     *
     * @return void
     */
    public function createTenant(array $data, string $expectedMessage, int $expected_status)
    {
        $this->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);

        $response = $this->postJson(
            $this->tenantUrl . '/tenant',
            $data
        );

        $response->assertJson([
            'message' => $expectedMessage,
        ]);
        
        $response->assertStatus($expected_status);
    }

    public static function createTenantDataProvider()
    {
        return [
            'create tenant, success' => [
                'data' => [
                    'tenantId' => 'tenant-test-' . rand(1, 1000),
                    'email' => 'tenant-test-' . rand(1, 1000) . '@test.com',
                ],
                'expected_message' => 'Tenant created successfully',
                'expected_status' => 200,
            ],
            'create tenant, validation email field is required, error' => [
                'data' => [
                    'tenantId' => 'tenant-test-' . rand(1, 1000),
                ],
                'expected_message' => 'The email field is required.',
                'expected_status' => 422,
            ],
            'create tenant, validation tenantId field is required, error' => [
                'data' => [
                    'email' => 'tenant-test-' . rand(1, 1000) . '@test.com',
                ],
                'expected_message' => 'The tenantId field is required.',
                'expected_status' => 422,
            ],
            'create tenant, validation tenantId has already been taken, error' => [
                'data' => [
                    'tenantId' => 'test',
                    'email' => 'tenant-test-' . rand(1, 1000) . '@test.com',
                ],
                'expected_message' => 'The tenantId has already been taken.',
                'expected_status' => 422,
            ],
            'create tenant, validation email must be a valid email address, error' => [
                'data' => [
                    'tenantId' => 'tenant-test-' . rand(1, 1000),
                    'email' => 'tenant-test-' . rand(1, 1000),
                ],
                'expected_message' => 'The email must be a valid email address.',
                'expected_status' => 422,
            ],
            'create tenant, validation tenantId must be a string, error' => [
                'data' => [
                    'tenantId' => 1,
                    'email' => 'tenant-test-' . rand(1, 1000) . '@test.com',
                ],
                'expected_message' => 'The tenantId must be a string.',
                'expected_status' => 422,
            ],
        ];
    }
}
