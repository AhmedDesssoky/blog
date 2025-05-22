<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;


class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        if($this->is('api/*'))
        {
            $response = ApiResponse::sendResponse(422, 'Validation Errors ', $validator->errors());
            throw new ValidationException($validator,$response);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [

            'email' => 'required | email ',
            'password' => 'required ',
        ];
    }
     public function attributes()
    {
        return [
            'email' => 'Email',
            'password' => 'Password',

        ];
    }
}
