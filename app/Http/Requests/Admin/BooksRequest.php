<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class BooksRequest extends FormRequest
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
        // 'title', 'author', 'url', 'book_type', 'book_cover', 'type_id', 'status', 'update_fild', 'update_time', 'recom_num', 'read_num', 'profiles'
        return [
            'title'      => 'required|String|min:2|max:30',
            'author'   => 'required|String',
            'url' => 'required',
            'book_type' => 'required',
            'book_cover' => 'required',
            'type_id' => 'integer',
            'status' => 'integer',
            'recom_num' => 'integer',
            'read_num' => 'integer',
            'profiles' => 'required',
            'font_size' => 'integer'
        ];
    }

    public function messages()
    {
        return [
            'title.*'      => '书名格式错误',
            'author.*'      => '作者格式错误',
            'url.*'      => '来源地址错误',
            'book_type.required'      => '选择书本分类',
            'book_cover.required'      => '请上传封面图',
            'profiles.required'      => '请填写书本简介',
            'type_id.integer'      => '请选择书本状态',
            'status.integer'      => '请状态是否启用',
            'recom_num.integer'      => '请填写推荐的票数',
            'read_num.integer'      => '请填写阅读量',
            'font_size.integer'      => '请填写书本字数',
        ];
    }

    protected function formatErrors(Validator $validator)
    {
        $return['msg'] = $validator->errors()->first();
        $return['status'] = -1;
        return $return;
    }
}
