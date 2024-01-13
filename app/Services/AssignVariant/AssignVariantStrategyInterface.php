<?php

namespace App\Services\AssignVariant;

use Illuminate\Database\Eloquent\Collection;

interface AssignVariantStrategyInterface
{
    public function execute(Collection $abTests);
}
