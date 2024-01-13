<?php

namespace App\Services\AssignVariant;

use App\Models\AbTest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Session;

class AssignVariantForExistingSession extends AbstractAssignVariantStrategy implements AssignVariantStrategyInterface
{
    public function execute(Collection $abTests)
    {
        $sessionAbTestsArray = Session::get(self::SESSION_TESTS_KEY);
        $abTestsArray = $abTests->pluck('name')->toArray();

        $sessionAbTestsArray = array_intersect_key($sessionAbTestsArray, array_flip($abTestsArray));
        $newAbTests = array_diff_key(array_flip($abTestsArray), $sessionAbTestsArray);

        foreach ($newAbTests as $name => $value) {
            $variant = $this->abTestService->getRandomVariant(AbTest::getTest($name));
            $sessionAbTestsArray[$name] = $variant['name'];
        }

        $this->saveVariantsToSession($sessionAbTestsArray);
    }
}
