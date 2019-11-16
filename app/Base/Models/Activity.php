<?php

namespace App\Base\Models;

use App\Darksky\Models\Condition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

abstract class Activity extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'athlete_id',
        'condition_id',
        'foreign_id',
        'name',
        'description',
        'timezone',
        'start_time',
        'start_longitude',
        'start_latitude',
        'end_time',
        'end_longitude',
        'end_latitude',
        'state',
        'state_updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $dates = [
        'start_time',
        'end_time',
        'created_at',
        'updated_at',
        'state_updated_at',
    ];

    /**
     * Get the start time converted to activity timezone.
     *
     * @param string $value
     * @return \Illuminate\Support\Carbon|null
     */
    public function getStartTimeAttribute(?string $value): ?Carbon
    {
        if ($value === null) {
            return null;
        }

        return Carbon::parse($value)->setTimezone($this->timezone);
    }

    /**
     * Get the end time converted to activity timezone.
     *
     * @param string $value
     * @return \Illuminate\Support\Carbon|null
     */
    public function getEndTimeAttribute(?string $value): ?Carbon
    {
        if ($value === null) {
            return null;
        }

        return Carbon::parse($value)->setTimezone($this->timezone);
    }

    /**
     * Get the matching condition.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function condition(): HasOne
    {
        return $this->hasOne(Condition::class);
    }
}
