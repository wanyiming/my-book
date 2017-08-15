<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
class UserLoginRequest extends Request
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
            'username' => 'required|max:16|min:6',
            'password' => 'required|min:6|max:16',
        ];
    }

    /**
     * 获取已定义验证规则的错误消息。
     *
     * @return array
     */
    public function messages()
    {
        return [
            'username.required' => '登录名不能为空',
            'username.min' => '登录名长度不能低于6位',
            'username.max' => '登录名长度不能超过16位',
            'password.required' => '密码不能为空',
            'password.min' => '密码长度不能小于6位',
            'password.max' => '密码长度不能大与16位'
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function formatErrors(Validator $validator)
    {
        $return['msg'] = $validator->errors()->first();
        $return['status'] = -1;
        return $return;
    }
}
