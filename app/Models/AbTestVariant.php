<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbTestVariant extends Model
{
    use HasFactory;

    public function abTest()
    {
        return $this->belongsTo(AbTest::class);
    }
}
