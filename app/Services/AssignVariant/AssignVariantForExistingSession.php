<?php

namespace App\Services\AssignVariant;

use App\Models\AbTest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class AssignVariantForExistingSession extends AbstractAssignVariantStrategy implements AssignVariantStrategyInterface
{
    public function execute(Request $request, Collection $abTests)
    {
        $shouldSessionUpdate = false;
        $sessionAbTestsArray = $request->session()->get(self::SESSION_TESTS_KEY);
        $abTestsArray = $abTests->toArray();

        $abTestNamesArray = array_reduce($abTestsArray, function ($carry, $item) {
            $carry[] = $item['name'];

            return $carry;
        });

        if (!empty($sessionAbTestsArray))
            foreach ($sessionAbTestsArray as $name => $value) {
                if (empty($abTestNamesArray) || !in_array($name, $abTestNamesArray)) {
                    unset($sessionAbTestsArray[$name]);
                    $shouldSessionUpdate = true;
                }
            }

        if (!empty($abTestNamesArray))
            foreach ($abTestNamesArray as $name) {
                if (!array_key_exists($name, $sessionAbTestsArray)) {
                    $variant = $this->abTestService->getRandomVariant(AbTest::getTest($name));
                    $sessionAbTestsArray[$name] = $variant['name'];
                    $shouldSessionUpdate = true;
                }
            }
        if ($shouldSessionUpdate) {
            $request->session()->put(self::SESSION_TESTS_KEY, $sessionAbTestsArray);
            $request->session()->save();
        }
    }
}
