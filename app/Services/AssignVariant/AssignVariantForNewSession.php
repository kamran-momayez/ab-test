<?php

namespace App\Services\AssignVariant;

use Illuminate\Database\Eloquent\Collection;

class AssignVariantForNewSession extends AbstractAssignVariantStrategy implements AssignVariantStrategyInterface
{
    public function execute(Collection $abTests)
    {
        $abTestsArray = [];
        foreach ($abTests as $abTest) {
            $variant = $this->abTestService->getRandomVariant($abTest);
            $abTestsArray[$abTest['name']] = $variant['name'];
        }

        $this->saveVariantsToSession($abTestsArray);
    }
}
