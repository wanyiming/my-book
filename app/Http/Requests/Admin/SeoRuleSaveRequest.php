<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class SeoRuleSaveRequest extends FormRequest
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
            'call_key'    => 'required|string',
            'page_name'   => 'required|string',
            'title'       => 'required|string',
            'keywords'    => 'required|string',
            'description' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'call_key.required'    => '字段必须',
            'page_name.required'   => '字段必须',
            'title.required'       => '字段必须',
            'keywords.required'    => '字段必须',
            'description.required' => '字段必须'
        ];
    }


    protected function formatErrors(Validator $validator)
    {
        return ['status' => -1, 'msg' => $validator->errors()->first(), 'data' => []];
    }
}
