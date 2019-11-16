<?php

namespace App\Darksky\Models;

use App\Darksky\Components\Converter;
use App\Strava\Models\Activity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
