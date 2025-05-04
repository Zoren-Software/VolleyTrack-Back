<?php

namespace Tests\Feature\Database;

use Tests\Feature\Database\Traits\DatabaseAssertions;
use Tests\TestCase;

abstract class BaseDatabase extends TestCase
{
    use DatabaseAssertions;

    protected $graphql = false;

    protected $tenancy = false;

    protected $login = false;

    /**
     * Verificar se os campos estão corretamente definidos.
     *
     * @test
     */
    public function databaseVerifyFields()
    {
        $this->verifyFields();
    }

    /**
     * Verificar se a chave primária está corretamente definida.
     *
     * @test
     */
    public function databaseVerifyPrimaryKey()
    {
        $this->verifyPrimaryKey();
    }

    /**
     * Verificar se as chaves estrangeiras estão corretamente definidas.
     *
     * @test
     */
    public function databaseVerifyForeignKeys()
    {
        $this->verifyForeignKeys();
    }

    /**
     * Verificar se os campos auto_increment estão corretamente definidos.
     *
     * @test
     */
    public function databaseVerifyAutoIncrements()
    {
        $this->verifyAutoIncrements();
    }

    /**
     * Verificar se as chaves únicas estão corretamente definidas.
     *
     * @test
     */
    public function databaseVerifyUniqueKeys()
    {
        $this->verifyUniqueKeys();
    }

    /**
     * Verificar número total de campos na tabela.
     *
     * @test
     */
    public function databaseVerifyTotalFields()
    {
        $this->verifyTotalFields();
    }

    /**
     * Verificar o total de chaves estrangeiras no array de chaves estrangeiras e na tabela.
     *
     * @test
     */
    public function databaseVerifyTotalForeignKeys()
    {
        $this->verifyTotalForeignKeys();
    }

    /**
     * Verificar se o total de unique keys está correto.
     *
     * @test
     */
    public function databaseVerifyTotalUniqueKeys()
    {
        $this->verifyTotalUniqueKeys();
    }
}
