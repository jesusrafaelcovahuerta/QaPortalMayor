<?php

namespace App\Traits;

trait ApiResponse
{
    public function successResponse($data, $code = 200, $msg = '')
    {
        return response()->json(
            [
                'data'=> $data,
                'code'=> $code,
                'msg'=> $msg,
            ]
        );
    }

    public function errorResponse($data, $code = 500, $msg = '')
    {
        return response()->json(
            [
                'data'=> $data,
                'code'=> $code,
                'msg'=> $msg,
            ]
        );
    }
}
