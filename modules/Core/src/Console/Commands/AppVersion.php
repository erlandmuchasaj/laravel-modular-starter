<?php

namespace Modules\Core\Console\Commands;

use Illuminate\Console\Command;
use Modules\Core\Utils\EmCms;
use Symfony\Component\Console\Command\Command as CommandAlias;

class AppVersion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:version';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Displays current version of App installed';

    /**
     * Create a new command instance.
     *
     * @return void
     */
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
        $this->comment('App version: '.config('app.version'));
        $this->comment('Platform version: '.EmCms::NAME.' '.EmCms::VERSION);

        return CommandAlias::SUCCESS;
    }
}
