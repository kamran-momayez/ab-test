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
use Illuminate\Support\Facades\Session;

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
        $assignVariantStrategy = $this->createAssignVariantStrategy();
        $assignVariantStrategy->execute($abTests);

        return $next($request);
    }

    /**
     * @return AbstractAssignVariantStrategy
     */
    private function createAssignVariantStrategy(): AbstractAssignVariantStrategy
    {
        return Session::has(AbstractAssignVariantStrategy::SESSION_TESTS_KEY)
            ? new AssignVariantForExistingSession()
            : new AssignVariantForNewSession();
    }
}
