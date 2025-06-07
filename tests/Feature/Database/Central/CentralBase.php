<?php

namespace Tests\Feature\Database\Central;

use Tests\Feature\Database\BaseDatabase;

class CentralBase extends BaseDatabase
{
    /**
     * @var bool
     */
    protected $graphql = false;

    /**
     * @var bool
     */
    protected $tenancy = false;

    /**
     * @var bool
     */
    protected $login = false;
}
