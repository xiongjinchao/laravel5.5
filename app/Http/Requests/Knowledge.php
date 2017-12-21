<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Knowledge extends FormRequest
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
            'content' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => '请输入知识标题',
            'title.max' => '知识标题的最大长度为255',
            'content.required'  => '请输入知识内容',
        ];
    }
}
