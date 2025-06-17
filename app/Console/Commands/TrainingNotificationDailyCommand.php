<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Models\Training;
use Illuminate\Console\Command;

class TrainingNotificationDailyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:training-notification-daily {--tenants=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Sends the current day's training notifications";

    /**
     * Execute the console command.
     *
     * @codeCoverageIgnore
     *
     * @return void
     */
    public function handle()
    {
        $tenantsPluck = Tenant::where('id', 'NOT LIKE', '%_logs')->pluck('id');

        $tenants = $this->option('tenants') == [] ? $tenantsPluck : $this->option('tenants');

        foreach ($tenants as $tenant) {

            if (!is_string($tenant)) {
                throw new \RuntimeException('Tenant ID deve ser uma string.');
            }

            tenancy()->initialize($tenant);

            Training::whereBetween('date_start', [
                now()->format('Y-m-d') . ' 00:00:00',
                now()->format('Y-m-d') . ' 23:59:59',
            ])->chunk(500, function ($trainings) {
                foreach ($trainings as $training) {
                    $training->sendNotificationPlayers();
                }
            });
        }
    }
}
