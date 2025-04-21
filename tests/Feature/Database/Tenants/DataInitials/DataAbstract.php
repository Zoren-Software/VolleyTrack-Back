<?php

namespace Tests\Feature\Database\Tenants\DataInitials;

use Tests\TestCase;

abstract class DataAbstract extends TestCase
{
    protected $graphql = false;

    protected $tenancy = true;

    protected $login = false;
}
