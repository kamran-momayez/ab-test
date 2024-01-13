<?php

namespace App\Models;

use App\Exceptions\IntegrityConstraintViolationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\QueryException;

class AbTest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['name'];

    public function variants(): HasMany
    {
        return $this->hasMany(AbTestVariant::class);
    }

    /**
     * @param array $options
     * @return bool
     * @throws IntegrityConstraintViolationException
     */
    public function save(array $options = []): bool
    {
        try {
            return parent::save($options);
        }
        catch (QueryException $exception) {
            if ($exception->getCode() == 23000) {
                throw new IntegrityConstraintViolationException();
            }

            throw $exception;
        }
    }


    /**
     * @param string $name
     * @return bool
     */
    public static function stop(string $name): bool
    {
        return self::where('name', $name)
            ->update(['is_running' => false]);
    }

    /**
     * @param $abTestName
     * @return AbTest|Collection
     */
    public static function getTest($abTestName)
    {
        return self::firstWhere(['name' => $abTestName, 'is_running' => 1]);
    }

    /**
     * @return AbTest[]|Collection
     */
    public static function getRunningTests()
    {
        return self::where(['is_running' => 1])->get();
    }
}
