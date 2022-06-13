<?php

namespace Rahman\Todos\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //This resource routes protected by auth middleware and no need to authorize a gain
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
            // 'title' =>  'required|max:255|min:3',
            // 'description' => 'required|min:8',
            'status' => 'numeric|default:0'
        ];
    }
}
