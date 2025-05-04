<?php

namespace Tests\Feature\Database\Central;

use Tests\Feature\Database\BaseDatabase;

class CentralBase extends BaseDatabase
{
    protected $graphql = false;

    protected $tenancy = false;

    protected $login = false;
}
