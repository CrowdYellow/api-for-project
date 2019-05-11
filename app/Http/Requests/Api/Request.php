<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class Request extends FormRequest
{
    public function failedValidation(Validator $validator)
    {
        $data = [
            'status_code' => config('code.validate_err'),
            'message'     => '参数验证失败',
            'data'        => $validator->getMessageBag()->all(),
        ];

        exit(json_encode($data));
    }
}
