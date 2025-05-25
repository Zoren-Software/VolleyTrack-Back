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
     *
     * @var string
     */
    protected string $nomeProcesso = 'default';

    protected $tenant;

    public function __construct()
    {
        parent::__construct();
        $this->tenant = null;
    }

    public function formatProgress($tenant): string
    {
        return "cron - tenant($tenant): PROCESSO {$this->nomeProcesso} %current%/%max% [%bar%] %percent:3s%% estimated: %estimated:-6s% - current: %elapsed:-3s%\n";
    }

    public function handle()
    {
        //
    }

    protected function infoComando($nomeComando, $mensagem): void
    {
        if (config('app.env') === 'test') {
            return;
        }

        $this->info('cron - ' . $mensagem . ': ' . $nomeComando);
    }

    protected function processoComando($nomeComando, $tenant, $mensagem): void
    {
        if (config('app.env') === 'test') {
            return;
        }

        $this->info('cron - tenant(' . $tenant . '): ' . $mensagem . ' PROCESSO ' . $nomeComando);
    }

    protected function processoComandoCancelado($nomeComando, $tenant, $mensagem): void
    {
        if (config('app.env') === 'test') {
            return;
        }

        $this->warn('cron - tenant(' . $tenant . '): ' . $mensagem . ' PROCESSO ' . $nomeComando);
    }

    protected function execSeederComando($nomeComando, $tenant, $seeder): void
    {
        if (config('app.env') === 'test') {
            return;
        }

        $this->info('cron - tenant(' . $tenant . '): seeder: ' . $seeder . ' PROCESSO ' . $nomeComando);
    }
}
