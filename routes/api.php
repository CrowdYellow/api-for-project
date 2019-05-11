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

    // 需要 token 验证的接口
    $api->group(['middleware' => 'api.auth'], function($api) {
        // 当前登录用户信息
        $api->get('user', 'UsersController@me');
        // 修改密码
        $api->post('user/update/password', 'UsersController@updatePassword');
        // 修改头像
        $api->post('user/update/avatar', 'UsersController@updateAvatar');
        // 获取登录日志
        $api->get('user/logs', 'UsersController@log');
    });
});

$api->version('v2', function($api) {
    $api->get('version', function() {
        return response('this is version v2');
    });
});