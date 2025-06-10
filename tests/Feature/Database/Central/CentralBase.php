<?php

namespace Tests\Feature\Database\Central;

use Tests\Feature\Database\BaseDatabase;

class CentralBase extends BaseDatabase
{
    protected bool $graphql = false;

    protected bool $tenancy = false;

    protected bool $login = false;
}
