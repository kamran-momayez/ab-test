<?php

namespace App\Http\Middleware;

use App\Models\AbTest;
use App\Services\AssignVariant\AbstractAssignVariantStrategy;
use App\Services\AssignVariant\AssignVariantForExistingSession;
use App\Services\AssignVariant\AssignVariantForNewSession;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AssignAbTestVariant
{
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
            $assignVariantStrategy = new AssignVariantForNewSession();
        }
        else {
            $assignVariantStrategy = new AssignVariantForExistingSession();
        }
        $assignVariantStrategy->execute($request, $abTests);

        return $next($request);
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function abTestNameNotFoundInSession(Request $request): bool
    {
        return !$request->session()->has(AbstractAssignVariantStrategy::SESSION_TESTS_KEY);
    }
}
