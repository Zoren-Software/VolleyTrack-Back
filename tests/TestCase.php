<?php

namespace Tests;

use App\Models\Tenant;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Nuwave\Lighthouse\Testing\RefreshesSchemaCache;
use App\Models\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    use MakesGraphQLRequests;

    use RefreshesSchemaCache;

    protected $tenancy = false;

    protected $graphql = false;

    protected $login = false;

    protected $token = '';

    public $tenantUrl;

    public function setUp(): void
    {
        parent::setUp();

        if ($this->tenancy) {
            $this->initializeTenancy();
            $this->tenantUrl = 'http://' . env('TENANT_TEST', 'test') . '.' . env('APP_HOST');
        }

        if ($this->graphql) {
            $this->bootRefreshesSchemaCache();
            $this->loginGraphQL();
        }
    }

    public function initializeTenancy(): void
    {
        $domain = env('TENANT_TEST', 'test');

        if (!Tenant::find($domain)) {
            $tenant = Tenant::create(['id' => env('TENANT_TEST', 'test')]);
            $tenant->domains()->create(['domain' => env('TENANT_TEST', 'test') . '.' . env('APP_HOST')]);
            $domain = $tenant;
        }

        tenancy()->initialize($domain);
    }

    public function graphQL(String $objectString, String $type = 'query')
    {
        switch ($type) {
            case 'mutation':
                $post = [
                    'query' => "
                        mutation {
                            $objectString
                        }"
                ];
                break;
            case 'query':
                $post = [
                    'query' => <<<GQL
                    {
                        $objectString
                    }
                    GQL
                ];
                break;
            default:
                break;
        }

        $headers= [
            'x-tenant' => env('TENANT_TEST', 'test'),
            'content-type' => 'application/json',
        ];

        if ($this->token != '' && $this->login) {
            $headers['Authorization'] = 'Bearer ' . $this->token;
        }

        return $this->withHeaders($headers)->postJson(
            $this->tenantUrl . '/graphql',
            $post
        );
    }

    public function loginGraphQL(): void { 
        $user = User::first();
        
        $response = $this->graphQL(/** @lang GraphQL */ '
            login(input: {
                email: "'.$user->email.'"
                password: "password"
            }) {
                token
            }
            
        ', 'mutation');
        
        $this->token = $response->json()['data']['login']['token'];
    }
}
