<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\RegisterRequest;
use App\Models\User;

class RegisterController extends Controller
{
    public function registered(RegisterRequest $request, CaptchasController $captcha)
    {
        if ($captcha->verifyCaptchas($request->captcha_key, $request->captcha_code)) {
            return $this->data(config('code.validate_err'), '验证码有误');
        }

        $user = User::create([
            'name'     => $request->name,
            'nickname' => $request->name,
            'password' => bcrypt($request->password),
            'avatar'   => '/images/users/default.png',
            'ip'       => $request->getClientIp(),
        ]);

        $user->avatar = env('APP_URL').$user->avatar;

        //注册成功 返回用户与token信息
        $data = [
            'user' => $user,
        ];

        return $this->data(config('code.success'), '注册成功', $data);
    }
}
