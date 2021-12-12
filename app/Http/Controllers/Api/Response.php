<?php

namespace App\Http\Controllers\Api;

class Response
{

    public static function fail(string $message)
    {
        return response()->json([
            "success" => false,
            "message" => $message
        ]);
        # code...
    }

    public static function pass(string $message, $data = null)
    {

        $response = [
            "success" => true,
            "message" => $message,
        ];

        if (!is_null($data)) $response['data'] = $data;
        return response()->json($response);

    }


}