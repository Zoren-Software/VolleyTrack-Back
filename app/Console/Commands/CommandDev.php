<?php

namespace App\Console\Commands;

class CommandDev extends CommandBase
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:new_command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Description command base';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (env('APP_ENV') == 'production') {
            $this->error('It is not possible to run this command in the production environment');

            return false;
        }

        if (!(env('APP_DEBUG')) || env('APP_ENV') == 'staging') {
            $this->error('It is not possible to run this command in this environment');

            return false;
        }
    }
}
