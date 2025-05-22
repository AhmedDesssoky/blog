<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
class SubscriberRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
   public function rules(): array
    {
        return [
           
            'email' => 'required|email|unique:subscribers,email',
       
        ];
    }
}
