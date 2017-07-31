<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;

class FriendlyLinkRequest extends Request
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
            'website' => 'required|String',
            'weburl' => 'required|String',
            'email.required|email' => '',//邮箱
            'sort' => 'integer|min:0',//排序值
            'content' => 'required|String'//网站介绍
        ];
    }

    public function messages()
    {
        return [
            'website.required' => '请输入网站名称',
            'weburl.required' => '请输入网址',
            'email.email' => '输入的邮箱格式不正确',
            'sort.integer' => '排序值为空',
            'content.required' => '网站简介不能为空'
        ];
    }

    protected function formatErrors(Validator $validator)
    {
        $return['msg'] = $validator->errors()->first();
        $return['status'] = -1;
        return $return;
    }
}