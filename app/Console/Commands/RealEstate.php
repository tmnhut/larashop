<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RealEstate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'real:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Link real estate description';

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
     * @return mixed
     */
    public function handle()
    {
        //
    }
}
