<?php

namespace App\Strava\Http\Requests;

use App\Rules\OwnedCleaner;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAthleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->route('athlete'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cleaners'   => ['sometimes', 'required', 'nullable', 'array'],
            'cleaners.*' => ['in:0,1', new OwnedCleaner()],
            'paused_at'  => 'sometimes|nullable|date',
        ];
    }
}
