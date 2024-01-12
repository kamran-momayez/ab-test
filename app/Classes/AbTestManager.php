<?php

namespace App\Classes;

use App\Models\AbTest;
use App\Models\AbTestVariant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AbTestManager
{
    /**
     * @param string $name
     * @param array  $variantsArray
     * @return void
     */
    public function start(string $name, array $variantsArray)
    {
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
    }

    /**
     * @param string $name
     * @return bool
     */
    public function stop(string $name): bool
    {
        $abTest = AbTest::firstWhere('name', $name);

        if ($abTest) {
            $abTest->update(['is_running' => false]);

            return true;
        }

        return false;
    }

    /**
     * @return AbTest[]|Collection
     */
    public function getAll()
    {
        return AbTest::all();
    }

    /**
     * @param $abTestName
     * @return AbTest|Collection
     */
    public function getTest($abTestName)
    {
        return AbTest::firstWhere(['name' => $abTestName, 'is_running' => 1]);
    }

    /**
     * @param AbTest $abTest
     * @return AbTestVariant|false
     */
    public function getVariant(AbTest $abTest)
    {
        $variants = $abTest->variants->toArray();
        $totalRatio = array_sum(array_column($variants, 'targeting_ratio'));
        $randomValue = mt_rand(1, $totalRatio);
        foreach ($variants as $variant) {
            $randomValue -= $variant['targeting_ratio'];
            if ($randomValue <= 0) {
                return $variant;
            }
        }

        return $variants[0];
    }

}
