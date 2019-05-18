<?php

namespace App\Http\Controllers\Goods;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;

class GoodsController extends Controller
{


    public function goodsList(Request $request){
        $goodsInfo=DB::table('goods')->get();
        $response=[
            'data'=>$goodsInfo
        ];
        return $response;

    }

    public function cenTent(){
        $str=file_get_contents('php://input');
        $goodsInfo=json_decode($str,true);
        $response=[
            'errno'=>0,
            'msg'=>'商品详情页',
            'goodsInfo'=>$goodsInfo
        ];
        return json_encode($response,JSON_UNESCAPED_UNICODE);

    }
}
