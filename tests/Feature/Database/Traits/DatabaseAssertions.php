<?php

namespace Tests\Feature\Database\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait DatabaseAssertions
{
    /**
     * Verificar se todos os campos definidos existem na tabela.
     *
     * @return void
     */
    public function verifyFields()
    {
        $columns = Schema::getColumnListing($this->table);

        foreach (static::$fields as $field) {
            $this->assertTrue(
                in_array($field, $columns),
                "The field '{$field}' does not exist in the '{$this->table}' table."
            );
        }
    }

    /**
     * Verificar se a chave primária está corretamente definida.
     *
     * @return void
     */
    public function verifyPrimaryKey()
    {
        if (empty(static::$primaryKey)) {
            $this->markTestSkipped("No primary key defined for table '{$this->table}'.");
            return;
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

        $this->assertNotEmpty($primaryKey, "The table '{$this->table}' does not have a primary key.");
        $this->assertEquals(
            static::$primaryKey, 
            $primaryKeyColumns, 
            "The primary key of the '{$this->table}' table is incorrect. Found: " . implode(', ', $primaryKeyColumns)
        );
    }


    /**
     * Verificar se os campos auto_increment estão corretamente definidos.
     *
     * @return void
     */
    public function verifyAutoIncrements()
    {
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
        if (empty(static::$foreignKeys)) {
            $this->markTestSkipped("No foreign keys for table '{$this->table}'.");
            return;
        }
    
        foreach (static::$foreignKeys as $foreignKey) {
            $this->assertTrue(
                hasForeignKeyExist($this->table, $foreignKey),
                "The foreign key '{$foreignKey}' does not exist in the '{$this->table}' table."
            );
        }
    }
}