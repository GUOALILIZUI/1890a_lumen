<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;

class OrderController extends Controller
{
    public function orderList(Request $request)
    {
        $uid=$_GET['uid'];
        $str=file_get_contents('php://input');
        $info=json_decode($str,true);
        if($info){
            $response=[
                'errno'=>0,
                'msg'=>'支付',
                'pay'=>$info
            ];
            return json_encode($response,JSON_UNESCAPED_UNICODE);
        }else{
            $response=[
                'errno'=>2,
                'msg'=>'支付失败',
            ];
            return json_encode($response,JSON_UNESCAPED_UNICODE);
        }



    }
}