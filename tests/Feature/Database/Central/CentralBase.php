<?php

namespace Tests\Feature\Database\Central;

use Tests\Feature\Database\BaseDatabaseTest;

class CentralBase extends BaseDatabaseTest
{
    protected $graphql = false;

    protected $tenancy = false;

    protected $login = false;
}
