<?php

namespace App\Jobs\Crons;

use App\Jobs\ExecCommand;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TrainingNotificationDaily extends ExecCommand
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * O nome do comando console.
     *
     * @var string
     */
    public string $command = 'cron:training-notification-daily';
}
