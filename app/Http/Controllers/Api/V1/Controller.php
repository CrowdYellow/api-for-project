<?php

namespace App\Http\Controllers\Api\V1;

class Controller extends \App\Http\Controllers\Api\Controller
{
    public function data($code, string $msg, array $data = null)
    {
        $info = [
            'status'  => $code,
            'message' => $msg,
            'data'    => $data,
        ];

        return $this->response->array($info);
    }
}
