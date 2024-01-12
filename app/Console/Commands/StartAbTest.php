<?php

namespace App\Console\Commands;

use App\Exceptions\IntegrityConstraintViolationException;
use App\Models\AbTest;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use mysql_xdevapi\Exception;

class StartAbTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ab-test:start {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start a new A/B test';

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

        try {
            $abTest = AbTest::create([
                'name' => $name,
                'is_running' => true,
            ]);
            if ($abTest)
                $this->info("A/B test '{$name}' started");

        } catch (IntegrityConstraintViolationException $e) {
            $this->error("A/B test with name {$name} can not start again!");
        }
    }
}
