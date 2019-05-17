<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Support\Str;
use Gregwar\Captcha\CaptchaBuilder;

class CaptchasController extends Controller
{
    /**
     * 获取验证码
     * @param CaptchaBuilder $captchaBuilder
     * @return mixed
     */
    public function getCaptchas(CaptchaBuilder $captchaBuilder)
    {
        $key = 'captcha-'.Str::random(15);

        $captcha = $captchaBuilder->build();

        \Cache::put($key, ['code' => $captcha->getPhrase()], 60);

        $data = [
            'captcha_key' => $key,
            'captcha_image_content' => $captcha->inline()
        ];

        return $this->data(config('code.success'), 'success', $data);
    }

    /**
     * 校验验证码
     * @param $key
     * @param $code
     * @return bool
     */
    public function verifyCaptchas($key, $code)
    {
        $captchaData = \Cache::get($key);

        if (!$captchaData) {
            return false;
        }

        if (!hash_equals($captchaData['code'], $code)) {
            // 验证错误就清除缓存
            \Cache::forget($key);

            return false;
        }

        // 清除图片验证码缓存
        \Cache::forget($key);

        return true;
    }
}
