<?php

namespace App\Strava\Models;

use App\Base\Models\Cleaner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Strava\Models\Athlete.
 *
 * @property int $id
 * @property int $user_id
 * @property int $foreign_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $profile_picture
 * @property string $refresh_token
 * @property string $access_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon $expires_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Strava\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Athlete newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Athlete newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Athlete query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Athlete whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Athlete whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Athlete whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Athlete whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Athlete whereForeignId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Athlete whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Athlete whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Athlete whereProfilePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Athlete whereRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Athlete whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Strava\Models\Athlete whereUserId($value)
 * @mixin \Eloquent
 */
class Athlete extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'strava_athletes';

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
    protected $dates = [
        'created_at',
        'updated_at',
        'paused_at',
        'expires_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Get the athletes enabled cleaners.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function cleaners(): BelongsToMany
    {
        return $this
            ->belongsToMany(Cleaner::class, 'cleaner_strava_athlete', 'strava_athlete_id')
            ->withTimestamps();
    }

    /**
     * Find an instance by its foreign id.
     *
     * @param int $foreignId
     * @return \App\Strava\Models\Athlete|null
     */
    public static function findByForeignId(int $foreignId): ?self
    {
        return static::query()
            ->where('foreign_id', $foreignId)
            ->firstOrFail();
    }

    /**
     * Check whether this account is paused.
     *
     * @return bool
     */
    public function isPaused(): bool
    {
        return $this->paused_at !== null;
    }

    /**
     * Check whether the refresh token is empty, thus the account is disconnected.
     *
     * @return bool
     */
    public function isDisconnected(): bool
    {
        return $this->refresh_token === null;
    }

    /**
     * Check whether the access token has expired.
     *
     * @return bool
     */
    public function tokenHasExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
