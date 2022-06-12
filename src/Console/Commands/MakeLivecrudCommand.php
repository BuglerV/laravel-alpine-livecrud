<?php

namespace Buglerv\Livecrud\Console\Commands;

use Illuminate\Console\Command;

class MakeLivecrudCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stepper:create
                              {controller : Creating controller name}
                              {-s|--steps=1 : Steps count}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create controllers for stepper';

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
    public function handle()
    {
        $steps = (int)$this->option('steps');
        $steps = $steps ?: 1;
        
        $controller = $this->argument('controller');
      
        for($step = 1; $step <= $steps; $step++){
            $this->callSilently('make:controller', [
                'name' => "{$controller}_step{$step}",
            ]);
            
            $this->info("Controller {$controller}_step{$step} created or already exists.");
        }
        
        return 0;
    }
}
