<?php

namespace Tests\Feature\Database;

use Tests\Feature\Database\Traits\DatabaseAssertions;
use Tests\TestCase;

abstract class BaseDatabase extends TestCase
{
    use DatabaseAssertions;

    protected bool $graphql = false;

    protected bool $tenancy = false;

    protected bool $login = false;

    protected string $table = ''; // cada classe filha sobrescreve

    /**
     * @var array<int, string>
     */
    protected static array $primaryKey = [];

    /**
     * @var array<int, string>
     */
    protected static array $autoIncrements = [];

    /**
     * @var array<int, string>
     */
    protected static array $foreignKeys = [];

    /**
     * @var array<int, string>
     */
    protected static array $uniqueKeys = [];

    /**
     * @var array<string, mixed>
     */
    protected static array $fieldTypes = [];

    /**
     * Verificar se os campos estão corretamente definidos.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function database_verify_fields(): void
    {
        $this->verifyFields();
    }

    /**
     * Verificar se a chave primária está corretamente definida.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function database_verify_primary_key(): void
    {
        $this->verifyPrimaryKey();
    }

    /**
     * Verificar se as chaves estrangeiras estão corretamente definidas.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function database_verify_foreign_keys(): void
    {
        $this->verifyForeignKeys();
    }

    /**
     * Verificar se os campos auto_increment estão corretamente definidos.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function database_verify_auto_increments(): void
    {
        $this->verifyAutoIncrements();
    }

    /**
     * Verificar se as chaves únicas estão corretamente definidas.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function database_verify_unique_keys(): void
    {
        $this->verifyUniqueKeys();
    }

    /**
     * Verificar número total de campos na tabela.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function database_verify_total_fields(): void
    {
        $this->verifyTotalFields();
    }

    /**
     * Verificar o total de chaves estrangeiras no array de chaves estrangeiras e na tabela.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function database_verify_total_foreign_keys(): void
    {
        $this->verifyTotalForeignKeys();
    }

    /**
     * Verificar se o total de unique keys está correto.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function database_verify_total_unique_keys(): void
    {
        $this->verifyTotalUniqueKeys();
    }
}
