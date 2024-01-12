<?php

namespace App\Http\Middleware;

use App\Classes\AbTestManager;
use Closure;
use Illuminate\Http\Request;

class AbTestMiddleware
{
    const SESSION_TEST_ID_KEY = 'ab_test_id';
    const SESSION_VARIANT_ID_KEY = 'ab_test_variant_id';

    /**
     * Handle an incoming request.
     * @param \Illuminate\Http\Request $request
     * @param Closure                  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $abTestManager = new AbTestManager();
        $abTest = $abTestManager->getTest($request->abTestName);
        if (!$abTest)
            return abort(404);

        if ($this->abTestNameNotFoundInSession($request)) {
            $variant = $abTestManager->getVariant($abTest);
            $sessionVariantName = $variant['name'];
            $request->session()->put(self::SESSION_TEST_ID_KEY, $request->abTestName);
            $request->session()->put(self::SESSION_VARIANT_ID_KEY, $sessionVariantName);
            $request->session()->save();
        }

        return $next($request);
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function abTestNameNotFoundInSession(Request $request): bool
    {
        return !$request->session()->has(self::SESSION_TEST_ID_KEY)
            || $request->session()->get(self::SESSION_TEST_ID_KEY) != $request->abTestName;
    }
}
