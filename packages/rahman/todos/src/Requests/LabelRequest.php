<?php

namespace Rahman\Todos\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use \Illuminate\Validation\ValidationException;

class LabelRequest extends FormRequest
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
            'title' =>  'required|unique:tasks|max:255|min:3',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        //All validation errors' messages
        $errors = $validator->errors()->messages(); 
        $exp = ValidationException::withMessages($errors);
        throw($exp->status(422));

    }
}
