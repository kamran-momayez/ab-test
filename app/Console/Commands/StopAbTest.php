<?php

namespace App\Console\Commands;

use App\Models\AbTest;
use Illuminate\Console\Command;

class StopAbTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ab-test:stop {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stop an A/B test';

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
     */
    public function handle()
    {
        $name = $this->argument('name');

        $abTest = AbTest::firstWhere('name', $name);

        if ($abTest) {
            $abTest->update(['is_running' => false]);
            $this->info("A/B test '{$abTest->name}' stopped.");
        } else {
            $this->error("A/B test with name {$name} not found.");
        }
    }
}
