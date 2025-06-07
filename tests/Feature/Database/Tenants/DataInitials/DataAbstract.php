<?php

namespace Tests\Feature\Database\Tenants\DataInitials;

use Tests\TestCase;

abstract class DataAbstract extends TestCase
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
     * @var bool
     */
    protected $login = false;
}
