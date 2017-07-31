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
            'username' => 'required|max:255',
            'password' => 'required|min:8|max:16',
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
            'username.required' => '账号不能为空',
            'username.max' => '账号长度不能超过225位',
            'password.required' => '密码不能为空',
            'password.min' => '密码长度不能小于8位',
            'password.max' => '密码长度不能大与16位'
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function formatErrors(Validator $validator)
    {
        $return['info'] = $validator->errors()->first();
        $return['status'] = -1;
        return $return;
    }
}
