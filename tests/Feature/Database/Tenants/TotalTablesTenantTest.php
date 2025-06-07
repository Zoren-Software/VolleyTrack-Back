<?php

namespace Tests\Feature\Database\Tenants;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TotalTablesTenantTest extends TestCase
{
    /**
     * @var bool
     */
    protected $graphql = false;

    /**
     * @var bool
     */
    protected $tenancy = true;

    /**
     * @var bool
     */
    protected $login = false;

    /**
     * Verificar o número total de tabelas existentes.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function verify_total_tables(): void
    {
        $tables = DB::select('SHOW TABLES');
        $totalTables = count($tables);

        $tenantId = tenant('id');
        $tenantString = is_scalar($tenantId) || $tenantId === null ? (string) $tenantId : 'desconhecido';

        $this->assertEquals(
            31,
            $totalTables,
            PHP_EOL . PHP_EOL .
            'O número total de tabelas está incorreto.' . PHP_EOL .
            '    Verifique o tenant: ' . $tenantString . '.' . PHP_EOL .
            '    Verifique se todas as tabelas estão corretamente definidas.' . PHP_EOL .
            '    Ou se foram criadas mais tabelas e não foram consideradas.' . PHP_EOL .
            '    Este valor deve ser alterado manualmente no desenvolvimento.'
        );
    }
}
