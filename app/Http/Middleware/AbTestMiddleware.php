<?php

namespace App\Http\Middleware;

use App\Models\AbTest;
use App\Services\AbTestService;
use Closure;
use Illuminate\Http\Request;

class AbTestMiddleware
{
    const SESSION_TEST_NAME_KEY = 'ab_test_name';
    const SESSION_VARIANT_NAME_KEY = 'ab_test_variant_name';

    private AbTestService $abTestService;

    public function __construct(AbTestService $abTestService)
    {
        $this->abTestService = $abTestService;
    }

    /**
     * Handle an incoming request.
     * @param \Illuminate\Http\Request $request
     * @param Closure                  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $abTest = AbTest::getTest($request->abTestName);
        if (!$abTest)
            return abort(404);

        if ($this->abTestNameNotFoundInSession($request)) {
            $variant = $this->abTestService->getRandomVariant($abTest);
            $sessionVariantName = $variant['name'];
            $request->session()->put(self::SESSION_TEST_NAME_KEY, $request->abTestName);
            $request->session()->put(self::SESSION_VARIANT_NAME_KEY, $sessionVariantName);
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
        return !$request->session()->has(self::SESSION_TEST_NAME_KEY)
            || $request->session()->get(self::SESSION_TEST_NAME_KEY) != $request->abTestName;
    }
}
