<?php

namespace App\Strava\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CallbackRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'object_type'     => 'required|string|in:activity,athlete',
            'object_id'       => 'required|integer',
            'aspect_type'     => 'required|string|in:create,update,delete',
            'owner_id'        => 'required|integer|exists:strava_athletes,foreign_id',
            'subscription_id' => 'required|integer',
            'event_time'      => 'required|integer',
        ];
    }

    /**
     * Check whether this request reports a new activity.
     *
     * @return bool
     */
    public function reportsActivity(): bool
    {
        return $this->get('object_type') === 'activity'
            && in_array($this->get('aspect_type'), ['create', 'update']);
    }
}
