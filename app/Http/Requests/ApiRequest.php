<?php

namespace App\Http\Requests;


use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Resources\ErrorResource;

abstract class ApiRequest extends FormRequest
{
    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     * @throw HttpResponseException
     */
    protected function failedValidation(Validator $validator): void
    {
        $data = [
            'message' => 'Invalid input',
            'errors' => $validator->errors()->toArray(),
        ];

        throw new HttpResponseException(response()->json(ErrorResource::make($data), 422));
    }
}
