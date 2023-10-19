<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function sendResponse($result, $message)
    {
        $response = [
            "success" => true,
            "message" => $message,
            "data" => $result
        ];

        return response()->json($response, 200);
    }

    public function sendError($error, $errorMesaages = [], $code = 404)
    {
        $response = [
            "success" => false,
            "message" => $error,
        ];

        if (!empty($errorMesaages)) {
            $response["data"] = $errorMesaages;
        }

        return response()->json($response, $code);
    }
}
