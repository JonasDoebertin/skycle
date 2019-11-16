<?php

namespace App\Darksky\Models;

use App\Darksky\Components\Converter;
use App\Strava\Models\Activity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Darksky\Models\Condition.
 *
 * @property int $id
 * @property int|null $activity_id
 * @property string|null $summary
 * @property string|null $icon
 * @property int|null $temperature
 * @property int|null $apparent_temperature
 * @property int|null $wind_bearing
 * @property int|null $wind_speed
 * @property int|null $wind_gust
 * @property float|null $moon_phase
 * @property float|null $cloud_coverage
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Strava\Models\Activity|null $activity
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Darksky\Models\Condition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Darksky\Models\Condition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Darksky\Models\Condition query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Darksky\Models\Condition whereActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Darksky\Models\Condition whereApparentTemperature($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Darksky\Models\Condition whereCloudCoverage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Darksky\Models\Condition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Darksky\Models\Condition whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Darksky\Models\Condition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Darksky\Models\Condition whereMoonPhase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Darksky\Models\Condition whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Darksky\Models\Condition whereTemperature($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Darksky\Models\Condition whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Darksky\Models\Condition whereWindBearing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Darksky\Models\Condition whereWindGust($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Darksky\Models\Condition whereWindSpeed($value)
 * @mixin \Eloquent
 */
class Condition extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'conditions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'summary',
        'icon',
        'temperature',
        'apparent_temperature',
        'wind_bearing',
        'wind_speed',
        'wind_gust',
        'moon_phase',
        'cloud_coverage',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the owning activity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function toEmoji(): string
    {
        return app(Converter::class)->toEmoji($this);
    }

    public function toSummary(): string
    {
        return app(Converter::class)->toSummary($this);
    }
}
