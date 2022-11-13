<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Training;
use App\Models\Tenant;

class SendTrainingNotificationDailyCommand extends Command
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
     * @return int
     */
    public function handle()
    {
        $tenants = $this->option('tenants') == [] ? Tenant::pluck('id') : $this->option('tenants');

        foreach ($tenants as $tenant) {
            tenancy()->initialize($tenant);

            Training::whereDate('date_start', now()->format('Y-m-d'))
                ->chunkById(500, function ($trainings) {
                    foreach ($trainings as $training) {
                        $training->sendNotificationPlayers();
                    }
                });
        }
    }
}
