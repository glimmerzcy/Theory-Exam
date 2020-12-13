<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    /**
     * 成功返回（数据）
     * @param $version
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public static function SuccessReturnData($version,$data){
        return response()->json([
            'version'=>$version,
            'status'=>'succeed',
            'data'=>$data
        ]);
    }

    /**
     * 成功返回（信息）
     * @param $version
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function SuccessReturnMessage($version,$message){
        return response()->json([
            'version'=>$version,
            'status'=>'succeed',
            'message'=>$message
        ]);
    }

    /**
     * 失败返回
     * @param $version
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function ErrorReturn($version,$message){
        return response()->json([
            'version'=>$version,
            'status'=>'error',
            'message'=>$message
        ]);
    }
}
