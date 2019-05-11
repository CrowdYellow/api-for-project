<?php

namespace App\Http\Requests\Api;

class PasswordRequest extends Request
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
            'password' => 'required|string|confirmed|min:6',
        ];
    }

    public function messages()
    {
        return [
            'password.required'  => '密码不能为空。',
            'password.confirmed' => '两次输入密码不一致。',
            'password.min'       => '密码长度不够。',
            'password.string'    => '密码必须是字符串。',
        ];
    }
}
