<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function successResponse($message = 'Successfull', $data = null)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ]);
    }

    public function failureResponse($message = 'Successfull', $errors = null, $code = 400)
    {
        return response()->json([
            'message' => $message,
            'errors' => $errors
        ], $code);
    }
}
