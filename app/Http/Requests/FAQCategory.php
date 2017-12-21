<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FAQCategory extends FormRequest
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
            'name' => 'required|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '请输入分类名称',
            'name.max' => '分类名称的最大长度为255'
        ];
    }
}
