<?php

namespace App\Base\Models;

use App\Strava\Models\Athlete;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cleaner extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
        'last_used_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the athletes with this cleaner enabled.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function stravaAthletes(): BelongsToMany
    {
        return $this
            ->belongsToMany(Athlete::class, 'cleaner_strava_athlete', 'cleaner_id', 'strava_athlete_id')
            ->withTimestamps();
    }
}
