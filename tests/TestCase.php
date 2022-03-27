<?php

namespace Tests;

use App\Models\Tenant;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Nuwave\Lighthouse\Testing\RefreshesSchemaCache;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use MakesGraphQLRequests;
    use RefreshesSchemaCache;

    protected $tenancy = false;
    protected $graphql = false;
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
        }
    }

    public function initializeTenancy(): void
    {
        $domain = env('TENANT_TEST', 'test');

        if(!Tenant::find($domain)) {
            $tenant = Tenant::create(['id' =>  env('TENANT_TEST', 'test')]);
            $tenant->domains()->create(['domain' =>  env('TENANT_TEST', 'test') . '.' . env('APP_HOST')]);
            $domain = $tenant;
        }

        tenancy()->initialize($domain);
    }

    public function graphQL(String $objectString){

        return $this->withHeaders([
            'x-tenant' => env('TENANT_TEST', 'test'),
        ])->postJson($this->tenantUrl . '/graphql',
        [
            'query' => <<<GQL
            {
                $objectString
            }
            GQL
          ]
        );
    }
}
