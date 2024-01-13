<?php

namespace App\Console\Commands;

use App\Exceptions\IntegrityConstraintViolationException;
use App\Models\AbTest;
use App\Services\AbTestService;
use Illuminate\Console\Command;

class StartAbTest extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'ab-test:start
                            {name : name of the A/B Test}
                            {variants* : name and targeting_ratio of its variants (e.g. variantA:1 variantB:2)}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Start a new A/B test';

    /**
     * Execute the console command.
     */
    public function handle(AbTestService $abTestService)
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
            $variantsArray = $this->reformatArray($variants);
            $abTestService->createAbTestAndVariants($name, $variantsArray);
            $this->info("A/B test '{$name}' started with its {$variantsCount} variants.");

        }
        catch (IntegrityConstraintViolationException $e) {
            $this->error("A/B test with name {$name} can not start again!");
        }
        catch (\Exception $e) {
            $this->error('Failed to start A/B test. ' . $e->getMessage());
        }
    }

    /**
     * @param $variants
     * @return array
     */
    private function reformatArray($variants): array
    {
        return array_reduce($variants, function ($carry, $item) {
            $explodedItem = explode(":", $item);
            $carry[$explodedItem[0]] = $explodedItem[1];

            return $carry;
        });
    }
}
