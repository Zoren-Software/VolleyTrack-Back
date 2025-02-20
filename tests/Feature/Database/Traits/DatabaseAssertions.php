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
     * Verifica se todos os campos definidos existem na tabela.
     */
    public function verifyFields()
    {
        $this->ensureTableExists();

        $columns = Schema::getColumnListing($this->table);
        $missingFields = array_diff(array_keys(static::$fieldTypes), $columns);

        $this->assertEmpty(
            $missingFields,
            "The following fields are missing in the '{$this->table}' table: " . implode(', ', $missingFields)
        );
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
            "The primary key of the '{$this->table}' table is incorrect. Expected: " . implode(', ', static::$primaryKey) . '. Found: ' . implode(', ', $primaryKeyColumns)
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
        $totalFieldsArray = count(static::$fieldTypes ?? []);
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

    /**
     * Verificar o total de unique keys definidos no array e na tabela do banco.
     *
     * @return void
     */
    public function verifyTotalUniqueKeys()
    {
        $this->ensureTableExists();

        // Total de unique keys definidos no array de testes
        $totalUniqueKeysArray = count(static::$uniqueKeys ?? []);

        // Total de unique keys reais no banco
        $uniqueKeysFromTable = getUniqueKeys($this->table);
        $totalUniqueKeysTable = is_array($uniqueKeysFromTable) ? count($uniqueKeysFromTable) : 0;

        // Comparação e erro se os valores forem diferentes
        $this->assertEquals(
            $totalUniqueKeysArray,
            $totalUniqueKeysTable,
            "The total number of unique keys in the '{$this->table}' table does not match. Expected: {$totalUniqueKeysArray}. Found: {$totalUniqueKeysTable}."
        );
    }

    /**
     * Verificar se os campos da tabela possuem os tipos e atributos esperados.
     *
     * @return void
     */
    public function verifyFieldTypes()
    {
        $this->ensureTableExists();

        if (empty(static::$fieldTypes)) {
            $this->markTestSkipped("No field types defined for table '{$this->table}'.");
        }

        $databaseName = DB::getDatabaseName();
        $mismatchedTypes = [];

        foreach (static::$fieldTypes as $column => $expectedConfig) {
            // Obtém informações detalhadas da coluna no INFORMATION_SCHEMA
            $columnInfo = DB::selectOne('
                SELECT COLUMN_TYPE, DATA_TYPE, IS_NULLABLE, CHARACTER_MAXIMUM_LENGTH, COLLATION_NAME, COLUMN_KEY, EXTRA
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?
            ', [$databaseName, $this->table, $column]);

            if (!$columnInfo) {
                $mismatchedTypes[] = "Column '{$column}' does not exist in the '{$this->table}' table.";

                continue;
            }

            // Verifica o tipo da coluna
            $actualType = $columnInfo->DATA_TYPE;
            $expectedType = $expectedConfig['type'];

            if ($actualType !== $expectedType) {
                $mismatchedTypes[] = "Column '{$column}' in '{$this->table}' has type '{$actualType}', expected '{$expectedType}'.";
            }

            // Verifica se a coluna é UNSIGNED corretamente
            $isUnsigned = strpos($columnInfo->COLUMN_TYPE, 'unsigned') !== false;
            if (isset($expectedConfig['unsigned']) && $expectedConfig['unsigned'] !== $isUnsigned) {
                $mismatchedTypes[] = "Column '{$column}' in '{$this->table}' expected to be " . ($expectedConfig['unsigned'] ? 'UNSIGNED' : 'SIGNED') . '.';
            }

            // Verifica se a coluna é AUTO_INCREMENT
            $isAutoIncrement = strpos($columnInfo->EXTRA, 'auto_increment') !== false;
            if (isset($expectedConfig['auto_increment']) && $expectedConfig['auto_increment'] !== $isAutoIncrement) {
                $mismatchedTypes[] = "Column '{$column}' in '{$this->table}' expected to be AUTO_INCREMENT.";
            }

            // Verifica se a coluna é nullable
            $isNullable = $columnInfo->IS_NULLABLE === 'YES';
            if (isset($expectedConfig['nullable']) && $expectedConfig['nullable'] !== $isNullable) {
                $mismatchedTypes[] = "Column '{$column}' in '{$this->table}' should be " . ($expectedConfig['nullable'] ? 'NULLABLE' : 'NOT NULLABLE') . '.';
            }

            // Verifica o tamanho de colunas VARCHAR e CHAR
            if (isset($expectedConfig['length']) && in_array($expectedType, ['varchar', 'char'])) {
                $actualLength = $columnInfo->CHARACTER_MAXIMUM_LENGTH;

                if ($actualLength != $expectedConfig['length']) {
                    $mismatchedTypes[] = "Column '{$column}' in '{$this->table}' has length '{$actualLength}', expected '{$expectedConfig['length']}'.";
                }
            }

            // Verifica collation
            if (isset($expectedConfig['collation']) && $columnInfo->COLLATION_NAME !== $expectedConfig['collation']) {
                $mismatchedTypes[] = "Column '{$column}' in '{$this->table}' has collation '{$columnInfo->COLLATION_NAME}', expected '{$expectedConfig['collation']}'.";
            }

            // Para campos numéricos, verificamos precisão e escala apenas se o tipo suportar
            if (in_array($expectedType, ['decimal', 'float', 'double']) && isset($expectedConfig['precision'])) {
                if (preg_match('/\((\d+),?(\d+)?\)/', $columnInfo->COLUMN_TYPE, $matches)) {
                    $actualPrecision = (int) $matches[1];
                    $actualScale = isset($matches[2]) ? (int) $matches[2] : 0;

                    if ($actualPrecision !== $expectedConfig['precision']) {
                        $mismatchedTypes[] = "Column '{$column}' in '{$this->table}' has precision '{$actualPrecision}', expected '{$expectedConfig['precision']}'.";
                    }

                    if (isset($expectedConfig['scale']) && $actualScale !== $expectedConfig['scale']) {
                        $mismatchedTypes[] = "Column '{$column}' in '{$this->table}' has scale '{$actualScale}', expected '{$expectedConfig['scale']}'.";
                    }
                }
            }
        }

        $this->assertEmpty(
            $mismatchedTypes,
            "Field type mismatches in the '{$this->table}' table:\n" . implode("\n", $mismatchedTypes)
        );
    }
}
