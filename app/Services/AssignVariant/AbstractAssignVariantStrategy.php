<?php

namespace App\Services\AssignVariant;

use App\Services\AbTestService;
use Illuminate\Support\Facades\Session;

abstract class AbstractAssignVariantStrategy
{
    const SESSION_TESTS_KEY = 'ab_tests';

    protected AbTestService $abTestService;

    public function __construct()
    {
        $this->abTestService = new AbTestService();
    }

    protected function saveVariantsToSession($abTestsArray)
    {
        Session::put(self::SESSION_TESTS_KEY, $abTestsArray);
        Session::save();
    }
}
