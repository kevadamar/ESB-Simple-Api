<?php

namespace App\Services;

use \Illuminate\Support\Facades\Response;

class ApiResponse
{

    public static function success($data, $message = 'success', $code = 200)
    {
        return Response::json([
            'message' => $message,
            'code' => $code,
            'data' => $data,
        ], $code);
    }

    function list($data, $columns, $message = 'success', $code = 200)
    {
        return Response::json([
            'message' => $message,
            'code' => $code,
            'data' => $data,
            'columns' => $columns->columns(),
        ], $code);
    }

    public static function error($message = 'Data tidak ditemukan', $code = 404, $data = [])
    {
        return Response::json([
            'message' => $message,
            'code' => $code,
            'data' => $data,
        ], $code);
    }

    public static function error_code_log($message = 'Tidak Ditemukan', $log = "Failed", $code = 500, $data = null)
    {
        if ($code != 500 && $code != "42S22") {
            $msg = json_decode($log);

            if ($code == 404) {
                $msg = $log;
            }

            
            return self::error($msg, $code);
        }
        
        if ($code == "42S22") {
            $log = "Column Not Found";
            $code = 500;
        }

        return Response::json([
            'message' => $message,
            'log' => $log,
            'code' => $code,
            'data' => $data,
        ], 500);
    }

    public static function store($data, $message = 'Success')
    {
        return static::success($data, $message, 201);
    }

    public static function update($data, $message = 'Success')
    {
        return static::success($data, $message);
    }

    public static function delete($data, $message = 'Berhasil Menghapus Data')
    {
        return static::success($data, $message);
    }
}
