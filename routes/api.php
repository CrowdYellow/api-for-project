<?php

use Illuminate\Http\Request;

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api\V1'
], function($api) {
    // 获取验证码
    $api->get('captchas', 'CaptchasController@getCaptchas');
    // 用户注册
    $api->post('user/registered', 'RegisterController@registered');
    // 用户登陆
    $api->post('user/login', 'LoginController@login');
});

$api->version('v2', function($api) {
    $api->get('version', function() {
        return response('this is version v2');
    });
});