<?php

namespace App\Base\Models\Policies;

use App\Base\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the Horizon dashboard.
     *
     * @param \App\Base\Models\User  $user
     * @return mixed
     */
    public function viewHorizon(User $user)
    {
        return in_array($user->email, [
            config('skycle.admin.email'),
        ]);
    }

    /**
     * Determine whether the user can view the Telescope dashboard.
     *
     * @param \App\Base\Models\User  $user
     * @return mixed
     */
    public function viewTelescope(User $user)
    {
        return in_array($user->email, [
            config('skycle.admin.email'),
        ]);
    }
}
