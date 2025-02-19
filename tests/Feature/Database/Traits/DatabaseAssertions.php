<?php

namespace Tests\Feature\Database\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait DatabaseAssertions
{
    /**
     * Verifica se a tabela existe antes de rodar os testes.
     *
     * @return void
     */
    private function ensureTableExists()
    {
        if (!Schema::hasTable($this->table)) {
            $this->markTestSkipped("The table '{$this->table}' does not exist.");
        }
    }

    /**
     * Verificar se todos os campos definidos existem na tabela.
     *
     * @return void
     */
    public function verifyFields()
    {
        $this->ensureTableExists();

        $columns = Schema::getColumnListing($this->table);
        $missingFields = array_diff(static::$fields ?? [], $columns);

        foreach ($missingFields as $field) {
            $this->fail("The field '{$field}' does not exist in the '{$this->table}' table.");
        }

        $this->assertEmpty($missingFields, "Some fields are missing in the '{$this->table}' table.");
    }

    /**
     * Verificar se a chave primária está corretamente definida.
     *
     * @return void
     */
    public function verifyPrimaryKey()
    {
        $this->ensureTableExists();

        if (empty(static::$primaryKey)) {
            $this->markTestSkipped("No primary key defined for table '{$this->table}'.");
        }

        $databaseName = DB::getDatabaseName();

        $primaryKey = DB::select("
            SELECT COLUMN_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = ? 
            AND TABLE_NAME = ? 
            AND CONSTRAINT_NAME = 'PRIMARY'
        ", [$databaseName, $this->table]);

        $primaryKeyColumns = array_column($primaryKey, 'COLUMN_NAME');

        $missingPrimaryKeys = array_diff(static::$primaryKey ?? [], $primaryKeyColumns);

        $this->assertNotEmpty($primaryKey, "The table '{$this->table}' does not have a primary key.");
        $this->assertEmpty(
            $missingPrimaryKeys,
            "The primary key of the '{$this->table}' table is incorrect. Expected: " . implode(', ', static::$primaryKey) . ". Found: " . implode(', ', $primaryKeyColumns)
        );
    }

    /**
     * Verificar se os campos auto_increment estão corretamente definidos.
     *
     * @return void
     */
    public function verifyAutoIncrements()
    {
        $this->ensureTableExists();

        if (empty(static::$autoIncrements)) {
            $this->markTestSkipped("No foreign keys for table '{$this->table}'.");
        }

        foreach (static::$autoIncrements as $column) {
            $this->assertTrue(
                hasAutoIncrement($this->table, $column),
                "The column '{$column}' in the '{$this->table}' table is not auto_increment."
            );
        }
    }

    /**
     * Verificar se as chaves estrangeiras estão corretamente definidas.
     *
     * @return void
     */
    public function verifyForeignKeys()
    {
        $this->ensureTableExists();

        if (empty(static::$foreignKeys)) {
            $this->markTestSkipped("No foreign keys for table '{$this->table}'.");
        }

        $missingForeignKeys = [];

        foreach (static::$foreignKeys as $foreignKey) {
            if (!hasForeignKeyExist($this->table, $foreignKey)) {
                $missingForeignKeys[] = $foreignKey;
            }
        }

        $this->assertEmpty(
            $missingForeignKeys,
            "Some foreign keys are missing in the '{$this->table}' table: " . implode(', ', $missingForeignKeys)
        );
    }

    /**
     * Verificar se as chaves únicas estão corretamente definidas.
     *
     * @return void
     */
    public function verifyUniqueKeys()
    {
        $this->ensureTableExists();

        if (empty(static::$uniqueKeys)) {
            $this->markTestSkipped("No unique keys for table '{$this->table}'.");
        }

        $missingUniqueKeys = [];

        foreach (static::$uniqueKeys as $uniqueKey) {
            if (!hasIndexExist($this->table, $uniqueKey)) {
                $missingUniqueKeys[] = $uniqueKey;
            }
        }

        $this->assertEmpty(
            $missingUniqueKeys,
            "Some unique keys are missing in the '{$this->table}' table: " . implode(', ', $missingUniqueKeys)
        );
    }

    /**
     * Verificar o total de campos no array de campos e na tabela.
     *
     * @return void
     */
    public function verifyTotalFields()
    {
        $this->ensureTableExists();

        $columns = Schema::getColumnListing($this->table);
        $totalFieldsArray = count(static::$fields ?? []);
        $totalFieldsTable = count($columns);

        $this->assertEquals(
            $totalFieldsArray,
            $totalFieldsTable,
            "The total number of fields in the '{$this->table}' table does not match. Expected: {$totalFieldsArray}. Found: {$totalFieldsTable}."
        );
    }

    /**
     * Verificar o total de chaves estrangeiras no array de chaves estrangeiras e na tabela.
     *
     * @return void
     */
    public function verifyTotalForeignKeys()
    {
        $this->ensureTableExists();
        
        // Obtém a quantidade de chaves estrangeiras definidas no array da classe
        $totalForeignKeysArray = count(static::$foreignKeys ?? []);

        // Obtém a quantidade real de chaves estrangeiras da tabela no banco
        $foreignKeysFromTable = getForeignKeys($this->table);
        $totalForeignKeysTable = is_array($foreignKeysFromTable) ? count($foreignKeysFromTable) : 0;

        // Compara os valores e exibe erro caso sejam diferentes
        $this->assertEquals(
            $totalForeignKeysArray,
            $totalForeignKeysTable,
            "The total number of foreign keys in the '{$this->table}' table does not match. Expected: {$totalForeignKeysArray}. Found: {$totalForeignKeysTable}."
        );
    }
}
