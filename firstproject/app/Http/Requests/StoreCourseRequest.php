<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\CourseStatus;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class StoreCourseRequest extends FormRequest
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
    public function rules():array
    {
        return [

            'title'=> 'required|string|max:255',
            'status'=> ['required',new Enum (CourseStatus::class)],

            //
        ];
    }


    public function messages():array
    {
        return [
            'title.required' => 'The title field is required.',
            'title.string' => 'The title must be a string.',
            'status.required' => 'The status field is required.',
            'status.enum' => 'The status must be a valid course status.',
        ];
        
    }
}
