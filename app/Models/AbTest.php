<?php

namespace App\Models;

use App\Exceptions\IntegrityConstraintViolationException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class AbTest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    public function variants()
    {
        return $this->hasMany(AbTestVariant::class);
    }

    public function save(array $options = [])
    {
        try {
            return parent::save($options);
        } catch (QueryException $exception) {
            if ($exception->getCode() == 23000) {
                throw new IntegrityConstraintViolationException();
            }

            throw $exception;
        }
    }
}
