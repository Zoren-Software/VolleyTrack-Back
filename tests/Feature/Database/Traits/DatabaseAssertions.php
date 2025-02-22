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
            $this->fail("The table '{$this->table}' does not exist.");
        }
    }

    /**
     * Verifica se todos os campos definidos existem na tabela.
     */
    public function verifyFields()
    {
        $this->ensureTableExists();
        $this->checkMissingFields();
    }

    /**
     * Verifica se a chave primária está corretamente definida.
     */
    public function verifyPrimaryKey()
    {
        $this->ensureTableExists();

        // Se a tabela não deve ter uma primary key, o teste deve passar
        if (empty(static::$primaryKey)) {
            $this->assertTrue(true, "No primary key expected for table '{$this->table}'.");

            return;
        }

        $primaryKeyColumns = getPrimaryKeyColumns($this->table);
        $missingPrimaryKeys = array_diff(static::$primaryKey, $primaryKeyColumns);

        $this->assertNotEmpty($primaryKeyColumns, "The table '{$this->table}' does not have a primary key.");
        $this->assertEmpty(
            $missingPrimaryKeys,
            "The primary key of the '{$this->table}' table is incorrect. Expected: " . implode(', ', static::$primaryKey) . '. Found: ' . implode(', ', $primaryKeyColumns)
        );
    }

    /**
     * Verifica se os campos auto_increment estão corretamente definidos.
     */
    public function verifyAutoIncrements()
    {
        $this->ensureTableExists();

        // Se a tabela não deve ter auto_increment, o teste deve passar sem erro
        if (empty(static::$autoIncrements)) {
            $this->assertTrue(true, "No auto_increment expected for table '{$this->table}'.");

            return;
        }

        foreach (static::$autoIncrements as $column) {
            $this->assertTrue(
                hasAutoIncrement($this->table, $column),
                "The column '{$column}' in the '{$this->table}' table is not auto_increment."
            );
        }
    }

    /**
     * Verifica se as chaves estrangeiras estão corretamente definidas.
     */
    public function verifyForeignKeys()
    {
        $this->ensureTableExists();

        if (empty(static::$foreignKeys)) {
            $this->assertTrue(true, "No foreign keys expected for table '{$this->table}'.");

            return;
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
     * Verifica se as chaves únicas estão corretamente definidas.
     */
    public function verifyUniqueKeys()
    {
        $this->ensureTableExists();
        $this->checkUniqueKeys(static::$uniqueKeys ?? []);
    }

    /**
     * Verifica o total de campos na tabela e no array de definição.
     */
    public function verifyTotalFields()
    {
        $this->ensureTableExists();
        $this->assertCountFieldsMatch(static::$fieldTypes ?? []);
    }

    /**
     * Verifica o total de chaves estrangeiras no array e na tabela.
     */
    public function verifyTotalForeignKeys()
    {
        $this->ensureTableExists();
        $this->assertCountForeignKeysMatch(static::$foreignKeys ?? []);
    }

    /**
     * Verifica o total de unique keys no array e na tabela.
     */
    public function verifyTotalUniqueKeys()
    {
        $this->ensureTableExists();
        $this->assertCountUniqueKeysMatch(static::$uniqueKeys ?? []);
    }

    /**
     * Verifica se os campos da tabela possuem os tipos e atributos esperados.
     */
    public function verifyFieldTypes()
    {
        $this->ensureTableExists();
        $this->checkFieldTypes(static::$fieldTypes ?? []);
    }

    // MÉTODOS AUXILIARES PARA REUTILIZAÇÃO
    private function checkMissingFields(): void
    {
        $columns = Schema::getColumnListing($this->table);
        $missingFields = array_diff(array_keys(static::$fieldTypes), $columns);

        $this->assertEmpty(
            $missingFields,
            "The following fields are missing in the '{$this->table}' table: " . implode(', ', $missingFields)
        );
    }

    private function checkForeignKeys(array $foreignKeys): void
    {
        $missingForeignKeys = [];

        foreach ($foreignKeys as $foreignKey) {
            if (!hasForeignKeyExist($this->table, $foreignKey)) {
                $missingForeignKeys[] = $foreignKey;
            }
        }

        $this->assertEmpty(
            $missingForeignKeys,
            "Some foreign keys are missing in the '{$this->table}' table: " . implode(', ', $missingForeignKeys)
        );
    }

    private function checkUniqueKeys(array $uniqueKeys): void
    {
        $missingUniqueKeys = [];

        foreach ($uniqueKeys as $uniqueKey) {
            if (!hasIndexExist($this->table, $uniqueKey)) {
                $missingUniqueKeys[] = $uniqueKey;
            }
        }

        $this->assertEmpty(
            $missingUniqueKeys,
            "Some unique keys are missing in the '{$this->table}' table: " . implode(', ', $missingUniqueKeys)
        );
    }

    private function assertCountFieldsMatch(array $fieldTypes): void
    {
        $columns = Schema::getColumnListing($this->table);
        $totalFieldsArray = count($fieldTypes);
        $totalFieldsTable = count($columns);

        $this->assertEquals(
            $totalFieldsArray,
            $totalFieldsTable,
            "The total number of fields in the '{$this->table}' table does not match. Expected: {$totalFieldsArray}. Found: {$totalFieldsTable}."
        );
    }

    private function assertCountForeignKeysMatch(array $foreignKeys): void
    {
        $totalForeignKeysArray = count($foreignKeys);
        $totalForeignKeysTable = count(getForeignKeys($this->table) ?? []);

        $this->assertEquals(
            $totalForeignKeysArray,
            $totalForeignKeysTable,
            "The total number of foreign keys in the '{$this->table}' table does not match. Expected: {$totalForeignKeysArray}. Found: {$totalForeignKeysTable}."
        );
    }

    private function assertCountUniqueKeysMatch(array $uniqueKeys): void
    {
        $totalUniqueKeysArray = count($uniqueKeys);
        $totalUniqueKeysTable = count(getUniqueKeys($this->table) ?? []);

        $this->assertEquals(
            $totalUniqueKeysArray,
            $totalUniqueKeysTable,
            "The total number of unique keys in the '{$this->table}' table does not match. Expected: {$totalUniqueKeysArray}. Found: {$totalUniqueKeysTable}."
        );
    }

    private function checkFieldTypes(array $fieldTypes): void
    {
        $databaseName = DB::getDatabaseName();
        $mismatchedTypes = [];

        foreach ($fieldTypes as $column => $expectedConfig) {
            $columnInfo = DB::selectOne('
                SELECT COLUMN_TYPE, DATA_TYPE, IS_NULLABLE, CHARACTER_MAXIMUM_LENGTH, COLLATION_NAME, COLUMN_KEY, EXTRA
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?
            ', [$databaseName, $this->table, $column]);

            if (!$columnInfo) {
                $mismatchedTypes[] = "Column '{$column}' does not exist in the '{$this->table}' table.";

                continue;
            }

            if ($columnInfo->DATA_TYPE !== $expectedConfig['type']) {
                $mismatchedTypes[] = "Column '{$column}' in '{$this->table}' has type '{$columnInfo->DATA_TYPE}', expected '{$expectedConfig['type']}'.";
            }
        }

        $this->assertEmpty(
            $mismatchedTypes,
            "Field type mismatches in the '{$this->table}' table:\n" . implode("\n", $mismatchedTypes)
        );
    }
}
