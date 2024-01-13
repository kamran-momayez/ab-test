<?php

namespace App\Console\Commands;

use App\Models\AbTest;
use Illuminate\Console\Command;

class StopAbTest extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'ab-test:stop {name}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Stop an A/B test';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        if (AbTest::stop($name))
            $this->info("A/B test $name stopped.");
        else {
            $this->error("A/B test $name not found.");
        }
    }
}
