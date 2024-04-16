<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Tenant;

class RunTenantMigrations implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $tenantId;

    public function __construct($tenantId)
    {
        $this->tenantId = $tenantId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $tenant = Tenant::create([
                'id' => $this->tenantId,
            ]);

            $tenantIdLogs = $this->tenantId . '_logs';
            Tenant::create(['id' => $tenantIdLogs]);

            $tenant->domains()->create(['domain' => $this->tenantId . '.' . env('APP_HOST')]);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
