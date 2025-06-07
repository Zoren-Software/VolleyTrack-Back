<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
     */
    public function handle(): int
    {
        $handle = parent::handle();

        if ($handle !== 0) {
            return $handle;
        }

        $secret = $this->option('secret')[0] ?? $this->ask('What is the new password?');

        if (!is_string($secret)) {
            throw new \RuntimeException('Senha deve ser uma string.');
        }

        $tenants = $this->option('tenants');
        $tenants = empty($tenants) ? Tenant::pluck('id')->toArray() : $tenants;

        foreach ($tenants as $tenant) {
            if (!is_string($tenant)) {
                throw new \RuntimeException('Tenant ID deve ser uma string.');
            }

            tenancy()->initialize($tenant);

            $this->processoComando('update_passwords', $tenant, 'INICIO');

            try {
                $total = (int) max(1, ceil(User::count() / 100));
                $bar = $this->output->createProgressBar($total);
                $bar->start();

                User::chunk(100, function ($users) use ($secret, $bar) {
                    foreach ($users as $user) {

                        $user->password = Hash::make((string) $secret);
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

        return 0;
    }
}
