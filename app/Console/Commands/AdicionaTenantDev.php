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

        $tenants = [];

        if (!$this->option('tenants')) {
            $tenants[] = $this->ask('What is the tenant id?');
        } else {
            $tenants = (array) $this->option('tenants');
        }

        $host = config('app.host');

        if (!is_string($host)) {
            throw new \RuntimeException('A configuração "app.host" deve ser uma string.');
        }

        foreach ($tenants as $tenant) {
            if (!is_string($tenant)) {
                throw new \RuntimeException('Tenant ID deve ser uma string.');
            }

            if (\App\Models\Tenant::find($tenant)) {
                $this->error("Tenant {$tenant} already exists!");

                continue;
            }

            $tenantModel = \App\Models\Tenant::create([
                'id' => $tenant,
            ]);

            $this->info("Tenant {$tenantModel->id} added successfully!");

            $domain = $tenant . '.' . $host;
            $tenantModel->domains()->create(['domain' => $domain]);

            $this->info('Default domain added successfully!');
            $this->info($domain);

            $this->info('Default domain added successfully!');
            $this->info($domain);
        }

        return 0;
    }
}
