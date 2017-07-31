<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class SysKeywordRequest extends FormRequest
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
            'weight' => 'integer',
            'name' => 'required',
            'url' => 'required',
        ];
    }

    /**
     * 错误信息提示
     *
     * @return array
     */
    public function messages()
    {
        return [
            'weight.integer' => '权重为数组',
            'name.required' => '请输入敏感词内容',
            'url.required' => '请输入连接地址',
        ];
    }

    protected function formatErrors(Validator $validator)
    {
        return ['status' => -1, 'msg' => $validator->errors()->first(), 'data' => []];
    }
}
