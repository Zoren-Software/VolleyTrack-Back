<?php

namespace Tests\Feature\Database\Tenants;

use Tests\TestCase;

class TotalTablesTenantTest extends TestCase
{
    protected $graphql = false;

    protected $tenancy = true;

    protected $login = false;

    /**
     * Verificar o número total de tabelas existentes.
     *
     * @test
     *
     * @return void
     */
    public function verifyTotalTables()
    {
        $tables = \DB::select('SHOW TABLES');
        $totalTables = count($tables);

        $this->assertEquals(
            29,
            $totalTables,
            PHP_EOL . PHP_EOL .
            'O número total de tabelas está incorreto.' . PHP_EOL .
            '    Verifique o tenant: ' . tenant('id') . '.' . PHP_EOL .
            '    Verifique se todas as tabelas estão corretamente definidas.' . PHP_EOL .
            '    Ou se foram criadas mais tabelas e não foram consideradas.' . PHP_EOL .
            '    Este valor deve ser alterado manualmente no desenvolvimento.'
        );
    }
}
