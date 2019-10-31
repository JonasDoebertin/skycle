<?php

namespace App\Strava\Models;

use App\Base\Models\Activity as BaseActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends BaseActivity
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'strava_activities';

    /**
     * Get the owning Strava athlete.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function athlete(): BelongsTo
    {
        return $this->belongsTo(Athlete::class);
    }
}
