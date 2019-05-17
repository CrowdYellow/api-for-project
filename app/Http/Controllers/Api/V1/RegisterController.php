<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\RegisterRequest;
use App\Models\User;

class RegisterController extends Controller
{
    /**
     * 用户注册
     * @param RegisterRequest $request
     * @param CaptchasController $captcha
     * @return mixed
     */
    public function registered(RegisterRequest $request, CaptchasController $captcha)
    {
        if ($captcha->verifyCaptchas($request->captcha_key, $request->captcha_code)) {
            return $this->data(config('code.validate_err'), '验证码有误');
        }

        $request['ip'] = $request->getClientIp();

        $user = $this->create($request);

        $user->avatar = env('APP_URL').$user->avatar;

        return $this->data(config('code.success'), '注册成功', $user);
    }

    /**
     * 创建用户
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        return User::create([
            'name' => $data['name'],
            'nickname' => $data['name'],
            'password' => bcrypt($data['password']),
            'avatar' => '/images/users/default.png',
            'ip' => $data['ip'],
        ]);
    }
}
