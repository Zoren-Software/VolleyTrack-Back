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

    protected $tenant;

    public function __construct()
    {
        parent::__construct();

        $this->tenant = null;
    }

    public function formatProgress($tenant)
    {
        return "cron - tenant($tenant): PROCESSO $this->nomeProcesso %current%/%max% [%bar%] %percent:3s%% estimated: %estimated:-6s% - current: %elapsed:-3s% \n";
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
    }

    /**
     * @param  mixed  $nomeComando
     * @param  mixed  $mensagem
     */
    protected function infoComando($nomeComando, $mensagem): void
    {
        if (env('APP_ENV') == 'test') {
            return;
        }
        $this->info('cron - ' . $mensagem . ': ' . $nomeComando);
    }

    /**
     * @param  mixed  $nomeComando
     * @param  mixed  $tenant
     * @param  mixed  $mensagem
     */
    protected function processoComando($nomeComando, $tenant, $mensagem): void
    {
        if (env('APP_ENV') == 'test') {
            return;
        }
        $this->info('cron - tenant(' . $tenant . '): ' . $mensagem . ' PROCESSO ' . $nomeComando);
    }

    /**
     * @param  mixed  $nomeComando
     * @param  mixed  $tenant
     * @param  mixed  $mensagem
     */
    protected function processoComandoCancelado($nomeComando, $tenant, $mensagem): void
    {
        if (env('APP_ENV') == 'test') {
            return;
        }
        $this->warn('cron - tenant(' . $tenant . '): ' . $mensagem . ' PROCESSO ' . $nomeComando);
    }

    /**
     * @param  mixed  $nomeComando
     * @param  mixed  $tenant
     * @param  mixed  $seeder
     */
    protected function execSeederComando($nomeComando, $tenant, $seeder): void
    {
        if (env('APP_ENV') == 'test') {
            return;
        }
        $this->info('cron - tenant(' . $tenant . '): seeder: ' . $seeder . ' PROCESSO ' . $nomeComando);
    }
}
