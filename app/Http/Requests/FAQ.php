<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FAQ extends FormRequest
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
            'title' => 'required|max:255',
            'category_id' => 'required',
            'assign_user_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => '请输入问题标题',
            'title.max' => '问题标题的最大长度为255',
            'category_id.required' => '请选择分类',
            'assign_user_id.required' => '请选择指派人',
        ];
    }
}
