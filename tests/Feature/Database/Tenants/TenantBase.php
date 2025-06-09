<?php

namespace Tests\Feature\Database\Tenants;

use Tests\Feature\Database\BaseDatabase;

class TenantBase extends BaseDatabase
{
    protected bool $graphql = false;

    protected bool $tenancy = true;

    protected string $tenant = 'test';

    protected bool $login = false;
}
