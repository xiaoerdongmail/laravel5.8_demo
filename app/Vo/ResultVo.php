<?php
/**
 * Created by PhpStorm.
 * User: EdwardShaw
 * Date: 2020/6/8
 * Time: 16:38
 * Description:
 */

namespace App\Vo;


class ResultVo
{
    public static function success($message = '成功。', $data = [], $code = 200, $httpCode = 200)
    {
        $json = [
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ];
        return response()->json($json, $httpCode);
    }


    public static function fail($message = '失败。', $data = [], $code = 500, $httpCode = 200)
    {
        $json = [
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ];
        return response()->json($json, $httpCode);
    }
}