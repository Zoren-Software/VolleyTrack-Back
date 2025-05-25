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
    public function handle(): int
    {
        $env = config('app.env');
        $debug = config('app.debug');

        if ($env === 'production') {
            $this->error('It is not possible to run this command in the production environment');
            return 1;
        }

        if (!$debug || $env === 'staging') {
            $this->error('It is not possible to run this command in this environment');
            return 1;
        }

        return 0;
    }
}
