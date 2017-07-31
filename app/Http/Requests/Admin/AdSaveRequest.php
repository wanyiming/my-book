<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class AdSaveRequest extends FormRequest
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
            'picture_url' => 'required|string|max:300',
            'ad_name'     => 'required|string|max:40',
            'ad_link'     => 'required|string|max:300',
            'begin_time'  => 'required|string|date_format:Y-m-d H:i:s',
            'end_time'    => 'required|string|date_format:Y-m-d H:i:s',
            'weight'      => 'integer|digits_between:1,4',
        ];
    }

    public function messages()
    {
        return [
            'picture_url.required'   => '必须字段',
            'ad_name.required'       => '必须字段',
            'ad_link.required'       => '必须字段',
            'begin_time.required'    => '必须字段',
            'end_time.required'      => '必须字段',
            'ad_name.max'            => '字符数最多300',
            'ad_link.max'            => '字符数最多300',
            'begin_time.date_format' => '时间格式不对例:2016-12-30 18:30:00',
            'end_time.date_format'   => '时间格式不对例:2016-12-30 18:30:00',
            'weight.integer'         => '只能是整数',
            'weight.min'             => '排序范围0-9999',
            'weight.max'             => '排序范围0-9999',
        ];
    }

}
