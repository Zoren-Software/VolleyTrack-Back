<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
//use Stancl\Tenancy\Database\Models\Tenant;
use App\Models\Tenant;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $tenancy = false;
    public $tenantUrl;

    public function setUp(): void
    {
        parent::setUp();

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
