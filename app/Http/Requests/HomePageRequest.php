<?php

namespace App\Http\Requests;

use Facade\FlareClient\Api;
use Illuminate\Foundation\Http\FormRequest;

class HomePageRequest extends ApiRequest
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
            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
            'image' => 'required|image',
            'content' => 'required',
        ];
    }
}
