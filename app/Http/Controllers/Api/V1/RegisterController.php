<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\RegisterRequest;
use App\Models\User;

class RegisterController extends Controller
{
    public function registered(RegisterRequest $request)
    {
        $captchaData = \Cache::get($request->captcha_key);

        if (!$captchaData) {
            return $this->data(config('code.validate_err'), '图片验证码已失效');
        }

        if (!hash_equals($captchaData['code'], $request->captcha_code)) {
            // 验证错误就清除缓存
            \Cache::forget($request->captcha_key);

            return $this->data(config('code.validate_err'), '验证码错误');
        }

        // 清除图片验证码缓存
        \Cache::forget($request->captcha_key);

        $user = User::create([
            'name'     => $request->name,
            'password' => bcrypt($request->password),
            'avatar'   => '/images/users/default.png',
            'ip'       => $request->getClientIp(),
        ]);

        //注册成功 返回用户与token信息
        $data = [
            'user' => $user,
        ];

        return $this->data(config('code.success'), 'success', $data);
    }
}
