<?php

namespace Tests\Feature\Database\Tenants;

use Tests\Feature\Database\BaseDatabase;

class TenantBase extends BaseDatabase
{
    protected $graphql = false;

    protected $tenancy = true;

    protected $tenant = 'test';

    protected $login = false;
}
