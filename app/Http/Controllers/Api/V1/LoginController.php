<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
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

        $info = [
            'name'     => $request->name,
            'password' => $request->password,
        ];

        if (!$token = Auth::guard('api')->attempt($info)) {
            return $this->data(config('code.validate_err'), '用户名或密码错误');
        }

        $data = [
            'token' => 'Bearer '.$token,
            'expires_in' => Auth::guard('api')->factory()->getTTL()
        ];

        return $this->data(config('code.success'), '登陆成功', $data);
    }
}
