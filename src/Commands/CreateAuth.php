<?php

namespace AlexTigaer\TeamAuth\Commands;

use Illuminate\Console\Command;

class CreateAuth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'team-auth:create
        {name               :   The name of the role to create}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A simple multi-auth package for Laravel';

    /**
     * The name of the role.
     *
     * @var string
     */
    protected $name;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->baseDir = base_path('vendor\\alextigaer\\TeamAuth\\src\\team-auth');
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Save role's name
        $this->name = $this->argument('name');

        // Check the name is not empty...
        if($this->name == "")
            // ...and show a message if it is
            $this->error('ERROR: role name missing');
        else{
            // Print repo name
            $this->info('-------------------------------');
            $this->info('- CREATing \'' . $this->name . '\' AUTH... -');
            $this->info('-------------------------------');
            $this->line('');
        }
    }
}
