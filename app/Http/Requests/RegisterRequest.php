<?php

namespace App\Http\Requests;


use App\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
class RegisterRequest extends FormRequest
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
            'name' => 'required | string | max:255',
            'email' => 'required | email | max:255 | unique:users,email',
            'password' => 'required | confirmed| min:8',

        ];
    }
     public function attributes()
    {
        return [
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',

        ];
    }
  
}
