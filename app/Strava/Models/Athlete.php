<?php

namespace App\Strava\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Athlete extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'strava_athletes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'foreign_id',
        'first_name',
        'last_name',
        'profile_picture',
        'refresh_token',
        'access_token',
        'expires_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
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
     * Check whether the access token has expired.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
