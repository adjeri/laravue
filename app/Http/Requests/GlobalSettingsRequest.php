<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class GlobalSettingsRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'facebook_ads' => 'nullable',
            'google_analytics' => 'nullable',
            'logo' => 'required|image',
            'index_pages' => 'required|boolean',
        ];
    }
}
