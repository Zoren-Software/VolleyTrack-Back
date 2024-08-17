<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Tenant;
use Hash;

class ResetPasswordDev extends CommandDev
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:reset-password {--secret=*} {--tenants=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the password for all users';

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

        if (!$this->option('secret')) {
            $secret = $this->ask('What is the new password?');
        } else {
            $secret = $this->option('secret')[0];
        }

        $tenants = $this->option('tenants') == [] 
            ? Tenant::pluck('id')->toArray()
            : $this->option('tenants');

        foreach ($tenants as $tenant) {
            tenancy()->initialize($tenant);

            $this->processoComando('update_passwords', $tenant, 'INICIO');

            try {
                $total = User::count();
                $total = $total > 0 ? $total : 1;
                $total = ceil($total / 100);
                $bar = $this->output->createProgressBar($total);
                $bar->start();

                User::chunk(100, function ($users) use ($secret, $bar) {
                    foreach ($users as $user) {
                        $user->password = Hash::make($secret);
                        $user->save();
                    }
                    $bar->advance();
                });
                $this->processoComando('update_passwords', $tenant, 'FIM');
            } catch (\Throwable $error) {
                $this->processoComando('update_passwords', $tenant, 'ERRO');
                throw $error;
            }
        }
    }
}
