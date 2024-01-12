<?php

namespace App\Console\Commands;

use App\Classes\AbTestManager;
use Illuminate\Console\Command;

class ViewAbTests extends Command
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
     * Execute the console command.
     */
    public function handle()
    {
        $abTestManager = new AbTestManager();
        $abTests = $abTestManager->getAll();

        if ($abTests->isEmpty()) {
            $this->info('No A/B tests found.');
        } else {
            $this->table(['ID', 'Name', 'Is Running', 'Created At', 'Updated At'], $abTests->toArray());
        }
    }
}
