<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Support\Str;
use Gregwar\Captcha\CaptchaBuilder;

class CaptchasController extends Controller
{
    public function getCaptchas(CaptchaBuilder $captchaBuilder)
    {
        $key = 'captcha-'.Str::random(15);

        $captcha = $captchaBuilder->build();

        \Cache::put($key, ['code' => $captcha->getPhrase()]);

        $data = [
            'captcha_key' => $key,
            'captcha_image_content' => $captcha->inline()
        ];

        return $this->data(config('code.success'), 'success', $data);
    }
}
