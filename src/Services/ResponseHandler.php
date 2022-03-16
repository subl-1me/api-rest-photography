<?php

namespace App\Services;

use PhpParser\Node\Expr\Cast\Array_;

class ResponseHandler{

    public function successResponse(int $code, $data):Array
    {
        $response = [
            'code' => $code,
            'status' => 'success',
            'data' => $data
        ];

        return $response;
    }

    public function errorResponse(int $code, string $message):Array
    {
        $response = [
            'code' => $code,
            'status'=> 'error',
            'message_status' => $message
        ];

        return $response;
    }

}