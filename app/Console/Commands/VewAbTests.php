<?php

namespace App\Console\Commands;

use App\Models\AbTest;
use Illuminate\Console\Command;

class VewAbTests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ab-test:view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'View all A/B tests';

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
        $abTests = AbTest::all();

        if ($abTests->isEmpty()) {
            $this->info('No A/B tests found.');
        } else {
            $this->table(['ID', 'Name', 'Is Running', 'Created At', 'Updated At'], $abTests->toArray());
        }
    }
}
