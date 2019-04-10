<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    //

    protected function jsonSuccess($data, $message = 'success')
    {
        return response()->json([
            'code' => 0,
            'message' => $message,
            'data' => $data
        ]);
    }

    protected function jsonFailed($code, $message = "failed")
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => null
        ]);
    }
}
