<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;


class CenterController extends Controller
{


    public function center(Request $request){
//        echo 111;die;
        $token=$_GET['token'];
        $id=$_GET['id'];
//        $res=DB::table('zk_user')->where('id',$id)->first();

        $response=[
            'errno'=>0,
            'msg'=>'个人中心',
            'token'=>$token,
        ];
        return $response;
//        echo 222;
    }



}
