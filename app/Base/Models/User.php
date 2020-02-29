<?php

namespace App\Base\Models;

use App\Strava\Models\Athlete;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Base\Models\User.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Strava\Models\Athlete[] $stravaAthletes
 * @property-read int|null $strava_athletes_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Base\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Base\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Base\Models\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Base\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Base\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Base\Models\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Base\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Base\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Base\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Base\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Base\Models\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'gravatar_url',
    ];

    public function getGravatarUrlAttribute()
    {
        return 'https://www.gravatar.com/avatar/' . md5(trim($this->email));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stravaAthletes(): HasMany
    {
        return $this->hasMany(Athlete::class);
    }
}
