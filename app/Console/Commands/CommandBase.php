<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CommandBase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Nome do processo utilizado em mensagens de progresso
     */
    protected string $nomeProcesso = 'default';

    /**
     * @var string|null
     */
    protected $tenant;

    /**
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->tenant = null;
    }

    /**
     * @param string $tenant
     * 
     * @return string
     */
    public function formatProgress($tenant): string
    {
        return "cron - tenant($tenant): PROCESSO {$this->nomeProcesso} %current%/%max% [%bar%] %percent:3s%% estimated: %estimated:-6s% - current: %elapsed:-3s%\n";
    }

    /**
     * @return int
     */
    public function handle(): int
    {
        return 0;
    }

    /**
     * @param string $nomeComando
     * @param string $mensagem
     * 
     * @return void
     */
    protected function infoComando($nomeComando, $mensagem): void
    {
        if (config('app.env') === 'test') {
            return;
        }

        $this->info('cron - ' . $mensagem . ': ' . $nomeComando);
    }

    /**
     * @param string $nomeComando
     * @param string $tenant
     * @param string $mensagem
     * 
     * @return void
     */
    protected function processoComando($nomeComando, $tenant, $mensagem): void
    {
        if (config('app.env') === 'test') {
            return;
        }

        $this->info('cron - tenant(' . $tenant . '): ' . $mensagem . ' PROCESSO ' . $nomeComando);
    }

    /**
     * @param string $nomeComando
     * @param string $tenant
     * @param string $mensagem
     * 
     * @return void
     */
    protected function processoComandoCancelado($nomeComando, $tenant, $mensagem): void
    {
        if (config('app.env') === 'test') {
            return;
        }

        $this->warn('cron - tenant(' . $tenant . '): ' . $mensagem . ' PROCESSO ' . $nomeComando);
    }

    /**
     * @param string $nomeComando
     * @param string $tenant
     * @param string $seeder
     * 
     * @return void
     */
    protected function execSeederComando($nomeComando, $tenant, $seeder): void
    {
        if (config('app.env') === 'test') {
            return;
        }

        $this->info('cron - tenant(' . $tenant . '): seeder: ' . $seeder . ' PROCESSO ' . $nomeComando);
    }
}
