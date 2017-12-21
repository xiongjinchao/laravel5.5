<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class User extends FormRequest
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
        $rules = [
            'name' => 'required|max:255',
            'password' => 'required|min:6|max:16',
            'organization_id' => 'required',
            'email' => 'required|email',
            'mobile' => 'required|regex:/^1[34578][0-9]{9}$/',
        ];
        if(request()->isMethod('post')){
            return $rules;
        }else{
            unset($rules['password']);
            return $rules;
        }
    }

    public function messages()
    {
        return [
            'name.required' => '请输入中文姓名',
            'name.max' => '中文姓名的最大长度为255',
            'password.required' => '请输入密码',
            'password.min' => '密码的最小长度为6位',
            'password.max' => '密码的最大长度为16位',
            'organization_id.required' => '请选择组织架构',
            'email.required' => '请输入邮箱',
            'email.email' => '邮箱格式不合法',
            'mobile.required' => '请输入手机号码',
            'mobile.regex' => '手机号码格式不合法',
        ];
    }
}
