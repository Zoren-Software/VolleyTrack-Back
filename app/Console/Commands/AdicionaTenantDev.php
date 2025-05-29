<?php

namespace App\Console\Commands;

class AdicionaTenantDev extends CommandDev
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:add-tenant {--tenants=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a new tenant to the database Central';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $handle = parent::handle();

        if ($handle !== 0) {
            return $handle;
        }

        if (!$this->option('tenants')) {
            $tenants[] = $this->ask('What is the tenant id?');
        } else {
            $tenants = $this->option('tenants');
        }

        foreach ($tenants as $tenant) {
            if (\App\Models\Tenant::find($tenant)) {
                $this->error("Tenant {$tenant} already exists!");

                continue;
            }

            $tenant = \App\Models\Tenant::create([
                'id' => $tenant,
            ]);

            $this->info("Tenant {$tenant->id} added successfully!");

            $tenant->domains()->create(['domain' => $tenant->id . '.' . config('app.host')]);

            $this->info('Default domain added successfully!');
            $this->info("{$tenant->id}" . '.' . config('app.host'));
        }

        return 0;
    }
}
