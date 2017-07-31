<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;


class AdPositionSaveRequest extends FormRequest
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
            'call_key'      => 'required|string|max:30',
            'sub_channel'   => 'required|digits_between:1,5',
            'position_name' => 'required|string|max:50',
            'display_mode'  => 'required|in:1,2',
            'width'         => 'required|integer|min:1',
            'height'        => 'required|integer|min:1'
        ];
    }

    public function messages()
    {
        return [
            'call_key.required'      => '必须字段',
            'call_key.max'           => '控制在30个字符',
            'sub_channel.required'   => '必须字段',
            'position_name.required' => '必须字段',
            'position_name.max'      => '最长为50个字符',
            'display_mode.required'  => '必须字段',
            'width.required'         => '必须字段',
            'width.integer'          => '请填写数字',
            'width.min'              => '数字最小为1',
            'height.required'        => '必须字段',
            'height.integer'         => '请填写数字',
            'height.min'             => '数字最小为1',
        ];
    }

}
