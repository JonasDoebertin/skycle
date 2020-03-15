<?php

namespace App\Strava\Http\Requests;

use App\Strava\Concerns\HandlesWebhooks;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CallbackValidationRequest extends FormRequest
{
    use HandlesWebhooks;

    /**
     * Determine if the current request is asking for JSON.
     *
     * @return bool
     */
    public function wantsJson(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'hub_mode'         => 'required|string|in:subscribe',
            'hub_challenge'    => 'required|string',
            'hub_verify_token' => ['required', Rule::in([$this->getVerifyToken()])],
        ];
    }
}
