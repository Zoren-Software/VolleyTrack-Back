<?php

namespace Tests\Feature\Tenant;

use App\Models\Central\ExternalAccessToken;
use App\Models\Tenant;
use Tests\TestCase;

class TenantTest extends TestCase
{
    protected bool $tenancy = true;

    protected string $tenant = 'graphql';

    /**
     * A basic test route horizon for login.
     *
     * @param  array<string, mixed>  $data
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('createTenantDataProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function create_tenant(array $data, string $expectedMessage, int $expectedStatus)
    {
        $this->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);

        if ($data['token'] === false) {
            $data['token'] = 'test';
        } else {
            $externalToken = ExternalAccessToken::first();

            if (!$externalToken) {
                $this->fail('Nenhum token de acesso externo encontrado para o teste.');
            }

            $data['token'] = $externalToken->token;
        }

        if (
            $expectedMessage === 'TenantCreate.tenantId.unique'
        ) {
            // Garante que o tenantId 'test' exista no banco
            Tenant::updateOrCreate(['id' => 'test'], [
                'email' => 'tenant-existente@test.com',
                'name' => 'Tenant Existente',
            ]);

            $data['tenantId'] = 'test';
        } elseif (
            $expectedMessage === 'TenantCreate.tenantId.string'
        ) {
            $data['tenantId'] = 1;
        } elseif (
            $expectedMessage !== 'TenantCreate.tenantId.required'
        ) {
            // Gera um tenantId único, apenas se o teste não exige que esteja ausente
            while (true) {
                $randomTenantId = 'tenant-test-' . rand(1, 1000);
                if (!Tenant::where('id', $randomTenantId)->exists()) {
                    $data['tenantId'] = $randomTenantId;
                    break;
                }
            }
        }

        $response = $this->postJson(
            $this->tenantUrl . '/v1/tenant',
            $data
        );

        $response->assertJson([
            'message' => trans($expectedMessage),
        ]);

        $response->assertStatus($expectedStatus);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function createTenantDataProvider()
    {
        return [
            'create tenant, success' => [
                'data' => [
                    'token' => true,
                    'tenantId' => 'tenant-test-' . rand(1, 1000),
                    'email' => 'tenant-test-' . rand(1, 1000) . '@test.com',
                    'name' => 'Tenant Test',
                ],
                'expectedMessage' => 'TenantCreate.messageSuccess',
                'expectedStatus' => 200,
            ],
            'create tenant, token incorrect, error' => [
                'data' => [
                    'token' => false,
                    'tenantId' => 'tenant-test-' . rand(1, 1000),
                    'email' => 'tenant-test-' . rand(1, 1000) . '@test.com',
                    'name' => 'Tenant Test',
                ],
                'expectedMessage' => 'validation.token_invalid',
                'expectedStatus' => 422,
            ],
            'create tenant, validation email field is required, error' => [
                'data' => [
                    'token' => true,
                    'tenantId' => 'tenant-test-' . rand(1, 1000),
                    'name' => 'Tenant Test',
                ],
                'expectedMessage' => 'TenantCreate.email.required',
                'expectedStatus' => 422,
            ],
            'create tenant, validation tenantId field is required, error' => [
                'data' => [
                    'token' => true,
                    'email' => 'tenant-test-' . rand(1, 1000) . '@test.com',
                    'name' => 'Tenant Test',
                ],
                'expectedMessage' => 'TenantCreate.tenantId.required',
                'expectedStatus' => 422,
            ],
            'create tenant, validation tenantId has already been taken, error' => [
                'data' => [
                    'token' => true,
                    'tenantId' => 'test',
                    'email' => 'tenant-test-' . rand(1, 1000) . '@test.com',
                    'name' => 'Tenant Test',
                ],
                'expectedMessage' => 'TenantCreate.tenantId.unique',
                'expectedStatus' => 422,
            ],
            'create tenant, validation email must be a valid email address, error' => [
                'data' => [
                    'token' => true,
                    'tenantId' => 'tenant-test-' . rand(1, 1000),
                    'email' => 'tenant-test-' . rand(1, 1000),
                    'name' => 'Tenant Test',
                ],
                'expectedMessage' => 'TenantCreate.email.email',
                'expectedStatus' => 422,
            ],
            'create tenant, validation tenantId must be a string, error' => [
                'data' => [
                    'token' => true,
                    'tenantId' => 1,
                    'email' => 'tenant-test-' . rand(1, 1000) . '@test.com',
                    'name' => 'Tenant Test',
                ],
                'expectedMessage' => 'TenantCreate.tenantId.string',
                'expectedStatus' => 422,
            ],
            'create tenant, validation name field is required, error' => [
                'data' => [
                    'token' => true,
                    'tenantId' => 'tenant-test-' . rand(1, 1000),
                    'email' => 'tenant-test-' . rand(1, 1000) . '@test.com',
                ],
                'expectedMessage' => 'TenantCreate.name.required',
                'expectedStatus' => 422,
            ],
            'create tenant, validation name must be a string, error' => [
                'data' => [
                    'token' => true,
                    'tenantId' => 'tenant-test-' . rand(1, 1000),
                    'email' => 'tenant-test-' . rand(1, 1000) . '@test.com',
                    'name' => 1,
                ],
                'expectedMessage' => 'TenantCreate.name.string',
                'expectedStatus' => 422,
            ],
        ];
    }
}
