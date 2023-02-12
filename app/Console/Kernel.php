<?php

namespace App\Console;

use App\Jobs\Crons\TrainingNotificationDaily;
use App\Models\Tenant;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    private string $queueEmails = 'emails';

    /**
     * Define the application's command schedule.
     * NOTE - Ignorado nos testes unitários por ser um método programada agendamentos
     *
     * @codeCoverageIgnore
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $output = storage_path('app/output.txt');

        $tenants = Tenant::where('id', 'NOT LIKE', '%_logs')->pluck('id');

        foreach ($tenants as $tenant) {
            $schedule->job(new TrainingNotificationDaily($tenant), $this->queueEmails)
                ->appendOutputTo($output)
                ->dailyAt('03:00');
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
