<?php

namespace App\Http\Composers;

use App\Base\Models\User;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class Dashboard
{
    /**
     * @var \App\Base\Models\User
     */
    protected $user;

    /**
     * Home constructor.
     *
     * @param \App\Base\Models\User $user
     */
    public function __construct(User $user)
    {
        $this->user = auth()->user();
    }

    /**
     * Get the user.
     *
     * @return \App\Base\Models\User
     */
    protected function getUser(): User
    {
        return $this->user;
    }

    /**
     * Get the users connected strava accounts.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getStravaAccounts(): Collection
    {
        return $this->user->stravaAthletes;
    }

    /**
     * Compose the view data.
     *
     * @param \Illuminate\View\View $view
     */
    public function compose(View $view): void
    {
        $view->with([
            'user'           => $this->user,
            'stravaAccounts' => $this->getStravaAccounts(),
        ]);
    }
}
