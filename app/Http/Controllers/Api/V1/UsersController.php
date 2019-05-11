<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\PasswordRequest;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * 获取登陆用户
     * @return mixed
     */
    public function me()
    {
        $user = $this->user();
        $user->avatar = env('APP_URL').$user->avatar;

        return $this->data(config('code.success'), 'success', $user);
    }

    public function updatePassword(PasswordRequest $request)
    {
        $user = $this->user();
        $user->avatar = env('APP_URL').$user->avatar;

        $oldPassword = $request->old_password;

        if(!Hash::check($oldPassword, $user->password)) {

            return $this->data(config('code.validate_err'), '修改失败');
        }

        $attributes['password'] = bcrypt($request->password);

        $user->update($attributes);

        return $this->data(config('code.success'), '修改成功', $user);
    }
}
