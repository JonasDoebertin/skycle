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
 * @property \App\Strava\States\Activity\ActivityState $state
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
