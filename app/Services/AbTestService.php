<?php

namespace App\Services;

use App\Exceptions\IntegrityConstraintViolationException;
use App\Models\AbTest;
use App\Models\AbTestVariant;
use Illuminate\Support\Facades\DB;

class AbTestService
{
    /**
     * @param string $name
     * @param array  $variantsArray
     * @return void
     * @throws IntegrityConstraintViolationException
     */
    public function createAbTestAndVariants(string $name, array $variantsArray)
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
     * @param AbTest $abTest
     * @return AbTestVariant|false
     */
    public function getRandomVariant(AbTest $abTest)
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
