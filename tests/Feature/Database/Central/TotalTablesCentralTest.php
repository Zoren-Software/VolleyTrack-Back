<?php

namespace Tests\Feature\Database\Central;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TotalTablesCentralTest extends TestCase
{
    /**
     * @var bool
     */
    protected bool $graphql = false;

    /**
     * @var bool
     */
    protected bool $tenancy = false;

    /**
     * @var bool
     */
    protected bool $login = false;

    /**
     * Verificar o número total de tabelas existentes.
     *
     * @test
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function verify_total_tables()
    {
        $tables = DB::select('SHOW TABLES');
        $totalTables = count($tables);

        $databaseName = config('database.connections.mysql.database');
        $databaseString = is_scalar($databaseName) || $databaseName === null ? (string) $databaseName : 'desconhecido';

        $this->assertEquals(
            6,
            $totalTables,
            PHP_EOL . PHP_EOL .
            'O número total de tabelas está incorreto.' . PHP_EOL .
            '    Verifique o tenant central: ' . $databaseString . '.' . PHP_EOL .
            '    Verifique se todas as tabelas estão corretamente definidas.' . PHP_EOL .
            '    Ou se foram criadas mais tabelas e não foram consideradas.' . PHP_EOL .
            '    Este valor deve ser alterado manualmente no desenvolvimento.'
        );
    }
}
