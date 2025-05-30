<?php

namespace Tests\Feature\Database\Tenants;

use Tests\Feature\Database\BaseDatabase;

class TenantBase extends BaseDatabase
{
    /**
     * @var bool
     */
    protected $graphql = false;

    /**
     * @var bool
     */
    protected $tenancy = true;

    /**
     * @var string
     */
    protected $tenant = 'test';

    /**
     * @var bool
     */
    protected $login = false;
}
