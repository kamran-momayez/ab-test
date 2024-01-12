<?php

namespace App\Console\Commands;

use App\Exceptions\IntegrityConstraintViolationException;
use App\Models\AbTest;
use App\Models\AbTestVariant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class StartAbTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ab-test:start
                            {name : name of the A/B Test}
                            {variants* : name and targeting_ratio of its variants (e.g. variantA:1 variantB:2)}';

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
        $variants = $this->argument('variants');

        if (empty($name)) {
            $this->error('A/B Test name cannot be empty.');
            return;
        }

        $variantsCount = count($variants);
        if ($variantsCount < 2) {
            $this->error('You must provide at least two variants.');
            return;
        }

        try {
        $variantsArray = array_reduce($variants, function ($carry, $item) {
            $explodedItem = explode(":", $item);
            $carry[$explodedItem[0]] = $explodedItem[1];
            return $carry;
        });

            DB::beginTransaction();

            $abTest = AbTest::create([
                'name' => $name,
            ]);

            if ($abTest) {
                foreach ($variantsArray as $variantName => $variantTargetingRatio) {
                    $variant = new AbTestVariant([
                        'name'            => $variantName,
                        'targeting_ratio' => $variantTargetingRatio,
                    ]);

                    $abTest->variants()->save($variant);
                }
            }

            DB::commit();

            $this->info("A/B test '{$name}' started with its {$variantsCount} variants.");

        } catch (IntegrityConstraintViolationException $e) {
            $this->error("A/B test with name {$name} can not start again!");
        } catch (\Exception $e) {
            $this->error('Failed to start A/B test. ' . $e->getMessage());
        }
    }
}
