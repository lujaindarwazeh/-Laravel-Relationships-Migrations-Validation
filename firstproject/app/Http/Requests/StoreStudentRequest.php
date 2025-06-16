<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return True;
    }




protected function failedValidation(Validator $validator)
{
    throw new HttpResponseException(response()->json([
        'message' => 'Validation Failed',
        'errors' => $validator->errors()
    ], 422));
}



    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
             'name' => 'required|string|max:255',
            'email' => 'required|email|unique:student,email',
            'country_id' => 'required|exists:country,id',

        ];
    }

    public function messages(): array
    {

        return [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'country_id.required' => 'The country ID field is required.',
            'country_id.exists' => 'The selected country ID is invalid.',
        ];
        
    }
}
