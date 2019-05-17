<?php

namespace App\Http\Controllers\Api\V1;

use App\Handlers\GetAddressByIp;
use App\Http\Requests\Api\LoginRequest;
use App\Models\User;
use App\Models\UserMemberLog;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(LoginRequest $request, CaptchasController $captcha)
    {
        if ($captcha->verifyCaptchas($request->captcha_key, $request->captcha_code)) {
            return $this->data(config('code.validate_err'), '验证码有误');
        }

        $info = [
            'name'     => $request->name,
            'password' => $request->password,
        ];

        if (!$token = Auth::guard('api')->attempt($info)) {
            return $this->data(config('code.validate_err'), '用户名或密码错误');
        }

        // 登录日志
        $this->rememberThis($request->name, $request->getClientIp());

        $data = [
            'token' => 'Bearer '.$token,
            'expires_in' => Auth::guard('api')->factory()->getTTL()
        ];

        return $this->data(config('code.success'), '登陆成功', $data);
    }

    /**
     * 登录日志
     * @param $name
     * @param $ip
     */
    public function rememberThis($name, $ip)
    {
        $user = User::where('name', $name)->first();

        $getAddress = new GetAddressByIp();

        $address    = $getAddress->getAddress($ip);

        $region     = $address['region'];

        $city       = $address['city'];

        if ($this->isMobile()) {
            $type = "手机登录";
        }else{
            $type = "PC登录";
        }

        $log = new UserMemberLog();

        $log->user_id = $user->id;
        $log->ip      = $ip;
        $log->area    = $city.'/'.$region;
        $log->type    = $type;

        $log->save();
    }

    /**
     * 判断是手机端还是PC端
     * @return bool
     */
    public function isMobile()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {

            return TRUE;
        }

        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA'])) {

            // 找不到为false,否则为TRUE
            return stristr($_SERVER['HTTP_VIA'], "wap") ? TRUE : FALSE;

        }

        // 判断手机发送的客户端标志,兼容性有待提高

        if (isset ($_SERVER['HTTP_USER_AGENT'])) {

            $clientKeyWords = array(

                'mobile',

                'nokia',

                'sony',

                'ericsson',

                'mot',

                'samsung',

                'htc',

                'sgh',

                'lg',

                'sharp',

                'sie-',

                'philips',

                'panasonic',

                'alcatel',

                'lenovo',

                'iphone',

                'ipod',

                'blackberry',

                'meizu',

                'android',

                'netfront',

                'symbian',

                'ucweb',

                'windowsce',

                'palm',

                'operamini',

                'operamobi',

                'openwave',

                'nexusone',

                'cldc',

                'midp',

                'wap'

            );

            // 从HTTP_USER_AGENT中查找手机浏览器的关键字

            if (preg_match("/(" . implode('|', $clientKeyWords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {

                return TRUE;
            }
        }

        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT'])) {

            // 如果只支持wml并且不支持html那一定是移动设备

            // 如果支持wml和html但是wml在html之前则是移动设备

            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== FALSE) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === FALSE || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {

                return TRUE;
            }

        }

        return FALSE;
    }
}
