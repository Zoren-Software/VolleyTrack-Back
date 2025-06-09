<?php

namespace Tests\Feature\Database\Tenants\DataInitials;

use Tests\TestCase;

abstract class DataAbstract extends TestCase
{
    protected bool $graphql = false;

    protected bool $tenancy = true;

    protected bool $login = false;
}
