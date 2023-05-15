<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class ExecCommand implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * O nome do comando console.
     */
    public string $command;

    public string $tenant;

    /**
     * @codeCoverageIgnore
     *
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * @codeCoverageIgnore
     *
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Artisan::call("$this->command --multi_tenants=$this->tenant");
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array<int, string>
     */
    public function tags(): array
    {
        return ['tenant:' . $this->tenant];
    }
}
