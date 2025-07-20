<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class updatestudent extends FormRequest
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




    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [

            
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:student,email,' . $this->route('id'),
            'country_id' => 'required|exists:country,id',
            'enter_date' => 'required|date_format:Y-m-d H:i:s'



            //
        ];
    }
}
