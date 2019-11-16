<?php

namespace App\Strava\Models;

use App\Base\Models\Activity as BaseActivity;
use App\Strava\States\Activity\ActivityState;
use App\Strava\States\Activity\Decorated;
use App\Strava\States\Activity\Discarded;
use App\Strava\States\Activity\Fetched;
use App\Strava\States\Activity\Pruned;
use App\Strava\States\Activity\Reported;
use App\Strava\States\Activity\Sent;
use App\Strava\Transitions\Activity\ToDecorated;
use App\Strava\Transitions\Activity\ToDiscarded;
use App\Strava\Transitions\Activity\ToFetched;
use App\Strava\Transitions\Activity\ToPruned;
use App\Strava\Transitions\Activity\ToSent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\ModelStates\HasStates;

/**
 * App\Strava\Models\Activity
 *
 * @property \App\Strava\States\Activity\ActivityState $state
 * @property int $id
 * @property int $athlete_id
 * @property int $foreign_id
 * @property string|null $name
 * @property string|null $description
 * @property string|null $timezone
 * @property \Illuminate\Support\Carbon|null $start_time
 * @property float|null $start_longitude
 * @property float|null $start_latitude
 * @property \Illuminate\Support\Carbon|null $end_time
 * @property float|null $end_longitude
 * @property float|null $end_latitude
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $state_updated_at
 * @property-read \App\Strava\Models\Athlete $athlete
 * @property-read \App\Darksky\Models\Condition $condition
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Activity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Activity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Activity query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Activity whereAthleteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Activity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Activity whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Activity whereEndLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Activity whereEndLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Activity whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Activity whereForeignId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Activity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Activity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Activity whereNotState($field, $states)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Activity whereStartLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Activity whereStartLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Activity whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Activity whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Activity whereStateUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Activity whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Activity whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Activity extends BaseActivity
{
    use HasStates;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'strava_activities';

    /**
     * Register activity states and state transitions.
     *
     * @throws \Spatie\ModelStates\Exceptions\InvalidConfig
     */
    protected function registerStates(): void
    {
        $this
            ->addState('state', ActivityState::class)
            ->allowTransition(Reported::class, Fetched::class, ToFetched::class)
            ->allowTransition(Fetched::class, Discarded::class, ToDiscarded::class)
            ->allowTransition(Fetched::class, Decorated::class, ToDecorated::class)
            ->allowTransition(Decorated::class, Sent::class, ToSent::class)
            ->allowTransition(Sent::class, Pruned::class, ToPruned::class)
            ->allowTransition(Sent::class, Discarded::class, ToDiscarded::class)
            ->allowTransition(Discarded::class, Pruned::class, ToPruned::class)
            ->default(Reported::class);
    }

    /**
     * Check whether the activity has a given state.
     *
     * @param \Spatie\ModelStates\State|string $state
     * @return bool
     */
    public function hasState($state): bool
    {
        return $this->state->is($state);
    }

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
