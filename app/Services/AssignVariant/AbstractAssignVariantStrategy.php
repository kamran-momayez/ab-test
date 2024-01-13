<?php

namespace App\Services\AssignVariant;

use App\Services\AbTestService;

abstract class AbstractAssignVariantStrategy
{
    const SESSION_TESTS_KEY = 'ab_tests';

    protected AbTestService $abTestService;

    public function __construct()
    {
        $this->abTestService = new AbTestService();
    }
}
