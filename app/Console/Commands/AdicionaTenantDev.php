<?php

namespace App\Console\Commands;

use Tenancy\Affects\Migrates\Database\Events\ConfigureMigrations;

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
     *
     * @return int
     */
    public function handle()
    {
        $handle = parent::handle();

        if ($handle === false) {
            return false;
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

            $tenant->domains()->create(['domain' => $tenant->id . '.' . env('APP_HOST')]);

            $this->info('Default domain added successfully!');
            $this->info("{$tenant->id}" . '.' . env('APP_HOST'));
        }
    }
}
