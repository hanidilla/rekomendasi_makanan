<?php


namespace App\Traits;


trait Response
{
    public function success($data, $message, $code = 200)
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function error($message, $code = 400)
    {
        return response()->json([
            'status' => $code,
            'message' => $message
        ], $code);
    }
}
