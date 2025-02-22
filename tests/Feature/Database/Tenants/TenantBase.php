<?php

namespace Tests\Feature\Database\Tenants;

use Tests\Feature\Database\BaseDatabaseTest;

class TenantBase extends BaseDatabaseTest
{
    protected $graphql = false;

    protected $tenancy = true;

    protected $tenant = 'test';

    protected $login = false;
}
