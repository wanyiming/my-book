<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;

class SavePasswordRequest extends Request
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
            'old_pass'=>'required|between:8,16',
            'password'=>'required|between:8,16|confirmed',
            'password_confirmation'=>'required|between:8,16'
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
            'old_pass.required' => '原密码不能为空',
            'old_pass.between' => '原密码长度必须在8-16位',
            'password.required' => '新密码不能为空',
            'password.between' => '新密码长度必须在8-16位',
            'password.confirmed' => '新密码必须与确认密码一致',
            'password_confirmation.required' => '确认密码不能为空',
            'password_confirmation.between' => '确认密码长度必须在8-16位'
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
