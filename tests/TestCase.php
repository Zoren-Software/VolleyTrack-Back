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

        if ($this->graphql) {
            $this->bootRefreshesSchemaCache();
        }

        if ($this->tenancy) {
            $this->initializeTenancy();
            $this->tenantUrl = 'http://' . env('TENANT_TEST', 'test') . '.' . env('APP_HOST');
        }
    }

    public function initializeTenancy(): void
    {
        $domain = env('TENANT_TEST', 'test');

        if(!Tenant::find($domain)) {
            $tenant = Tenant::create(['id' =>  env('TENANT_TEST', 'test')]);
            $tenant->domains()->create(['domain' =>  env('TENANT_TEST', 'test') . '.' . env('APP_HOST', 'planneranimal.local')]);
            $domain = $tenant;
        }

        tenancy()->initialize($domain);
    }
}
