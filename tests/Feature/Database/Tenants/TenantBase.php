<?php

namespace Tests\Feature\Database\Tenants;

use App\Models\Position;
use Faker\Factory as Faker;
use Tests\TestCase;
use Tests\Feature\Database\Traits\DatabaseAssertions;

class TenantBase extends TestCase
{
    use DatabaseAssertions;
    
    protected $graphql = false;

    protected $tenancy = true;

    protected $tenant = 'test';

    protected $login = false;

    /**
     * Verificar se os campos estão corretamente definidos.
     * @test
     * @return void
     */
    public function databaseVerifyFields()
    {
        $this->verifyFields();
    }

    /**
     * Verificar se a chave primária está corretamente definida.
     * @test
     * @return void
     */
    public function databaseVerifyPrimaryKey()
    {
        $this->verifyPrimaryKey();
    }


    /**
     * Verificar se as chaves estrangeiras estão corretamente definidas.
     * @test
     * @return void
     */
    public function databaseVerifyForeignKeys()
    {
        $this->verifyForeignKeys();
    }

    /**
     * Verificar se os campos auto_increment estão corretamente definidos.
     * @test
     * @return void
     */
    public function databaseVerifyAutoIncrements()
    {
        $this->verifyAutoIncrements();
    }

    /**
     * Verificar se as chaves únicas estão corretamente definidas.
     * @test
     * @return void
     */
    public function databaseVerifyUniqueKeys()
    {
        $this->verifyUniqueKeys();
    }
}
