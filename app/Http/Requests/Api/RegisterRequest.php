<?php

namespace App\Http\Requests\Api;


class RegisterRequest extends Request
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
            'name'     => 'required|between:6,25|string|unique:users,name',
            'password' => 'required|string|confirmed|min:6',
        ];
    }

    public function messages()
    {
        return [
            'name.required'      => '用户名不能为空。',
            'name.between'       => '用户名必须介于 6 - 25 个字符之间。',
            'name.string'        => '用户名必须是字符串。',
            'name.unique'        => '用户名已被占用，请重新填写',
            'password.required'  => '密码不能为空。',
            'password.confirmed' => '两次输入密码不一致。',
            'password.min'       => '密码最低6位。',
            'password.string'    => '密码必须是字符串。',
        ];
    }
}
