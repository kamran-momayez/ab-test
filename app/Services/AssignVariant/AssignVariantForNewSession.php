<?php

namespace App\Services\AssignVariant;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class AssignVariantForNewSession extends AbstractAssignVariantStrategy implements AssignVariantStrategyInterface
{
    public function execute(Request $request, Collection $abTests)
    {
        $abTestsArray = [];
        foreach ($abTests as $abTest) {
            $variant = $this->abTestService->getRandomVariant($abTest);
            $abTestsArray[$abTest['name']] = $variant['name'];

        }
        $request->session()->put(self::SESSION_TESTS_KEY, $abTestsArray);
        $request->session()->save();
    }
}
