<?php

namespace App\Http\Middleware;

use App\Models\AbTest;
use App\Services\AbTestService;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AssignAbTestVariant
{
    const SESSION_TESTS_KEY = 'ab_tests';

    private AbTestService $abTestService;

    public function __construct(AbTestService $abTestService)
    {
        $this->abTestService = $abTestService;
    }

    /**
     * Handle an incoming request.
     * @param Request $request
     * @param Closure $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $abTests = AbTest::getRunningTests();
        if (count($abTests) > 0 && $this->abTestNameNotFoundInSession($request)) {
            $abTestsArray = [];
            foreach ($abTests as $abTest) {
                $variant = $this->abTestService->getRandomVariant($abTest);
                $abTestsArray[$abTest['name']] = $variant['name'];

            }
            $request->session()->put(self::SESSION_TESTS_KEY, $abTestsArray);
            $request->session()->save();
        }
        else {
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

        return $next($request);
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function abTestNameNotFoundInSession(Request $request): bool
    {
        return !$request->session()->has(self::SESSION_TESTS_KEY);
    }
}
