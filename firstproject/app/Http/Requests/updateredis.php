<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class updateredis extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [


            'id' => 'required|integer|exists:student,id',
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
           'email' => 'string|email|max:255|unique:student,email,' . $this->id,
            'start_date' => 'date',
            'end_date' => 'date|after_or_equal:start_date',



       

            
        ];
    }
}
