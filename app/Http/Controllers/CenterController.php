<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;


class CenterController extends Controller
{


    public function center(Request $request){
        echo 11;exit;
        $token=$_GET['token'];
        $id=$_GET['id'];
        $response=[
            'errno'=>0,
            'msg'=>'个人中心',
            'token'=>$token,
        ];
        return $response;
//        echo(json_encode($response,JSON_UNESCAPED_UNICODE));
    }



}
