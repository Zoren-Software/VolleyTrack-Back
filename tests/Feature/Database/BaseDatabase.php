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
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function database_verify_fields()
    {
        $this->verifyFields();
    }

    /**
     * Verificar se a chave primária está corretamente definida.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function database_verify_primary_key()
    {
        $this->verifyPrimaryKey();
    }

    /**
     * Verificar se as chaves estrangeiras estão corretamente definidas.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function database_verify_foreign_keys()
    {
        $this->verifyForeignKeys();
    }

    /**
     * Verificar se os campos auto_increment estão corretamente definidos.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function database_verify_auto_increments()
    {
        $this->verifyAutoIncrements();
    }

    /**
     * Verificar se as chaves únicas estão corretamente definidas.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function database_verify_unique_keys()
    {
        $this->verifyUniqueKeys();
    }

    /**
     * Verificar número total de campos na tabela.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function database_verify_total_fields()
    {
        $this->verifyTotalFields();
    }

    /**
     * Verificar o total de chaves estrangeiras no array de chaves estrangeiras e na tabela.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function database_verify_total_foreign_keys()
    {
        $this->verifyTotalForeignKeys();
    }

    /**
     * Verificar se o total de unique keys está correto.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function database_verify_total_unique_keys()
    {
        $this->verifyTotalUniqueKeys();
    }
}
