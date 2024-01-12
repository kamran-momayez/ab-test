<?php

namespace App\Classes;

use App\Exceptions\IntegrityConstraintViolationException;
use App\Models\AbTest;
use App\Models\AbTestVariant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AbTestManager
{
    /**
     * @param $name
     * @param $variantsArray
     * @return void
     * @throws IntegrityConstraintViolationException
     */
    public function start($name, $variantsArray)
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
     * @param $name
     * @return bool
     */
    public function stop($name): bool
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


}
