<?php


namespace App\Http\Controllers\Api\V1;

use Laravel\Lumen\Routing\Controller;

class BaseController extends Controller
{
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
