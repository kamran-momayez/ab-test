<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbTest extends Model
{
    use HasFactory;

    public function variants()
    {
        return $this->hasMany(AbTestVariant::class);
    }
}
