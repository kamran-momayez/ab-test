<?php

namespace App\Services\AssignVariant;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface AssignVariantStrategyInterface
{
    public function execute(Request $request, Collection $abTests);
}
