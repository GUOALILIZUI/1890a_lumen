<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;

class AccountController extends Controller
{
    public function account(Request $request)
    {
        $goods_id=$_POST;
        $uid=$_GET['uid'];
        $goodsInfo = DB::table('cart')->where('uid',$uid)->whereIn('goods_id',$goods_id)->get();
        $count=substr(rand(9999,10000).time().$uid,5,15);
        foreach ($goodsInfo as $k=>$v){
            if($v->cart_status==1){
                $price[]=$v->selPriceAun;
            }
        }
        $sun=array_sum($price);
        $data=[
            'uid'=>$uid,
            'order_num'=>$count,
            'sun'=>$sun,
            'ctime'=>time()
        ];
        $orderId=DB::table('order')->where('uid',$uid)->insertGetId($data);
//        $response=[
//            'errno'=>0,
//            'msg'=>'订单cg',
//            'orderInfo'=>$orderInfo
//        ];
//        echo json_encode($response,JSON_UNESCAPED_UNICODE);

        $goodsInfo2 = DB::table('cart')->where('uid',$uid)->whereIn('goods_id',$goods_id)->get();



        foreach ($goodsInfo2 as $k => $v){
            $data=[
                'uid'=>$uid,
                'order_num'=>$count,
                'order_id'=>$orderId,
                'goods_id'=>$v->goods_id,
                'goods_name'=>$v->goods_name,
                'buy_number'=>$v->buy_number,
                'goods_selfprice'=>$v->goods_selfprice,
                'selPriceAun'=>$v->selPriceAun,
                'ctime'=>time()
            ];
            $orderInfo=DB::table('order_detail')->where('uid',$uid)->insert($data);

        }
        if($orderInfo){
            $response=[
                'errno'=>0,
                'msg'=>'下单详情成功',
                'orderId'=>$orderId
            ];
            return json_encode($response,JSON_UNESCAPED_UNICODE);
        }else{
            $response=[
                'errno'=>2,
                'msg'=>'下单详情失败',
            ];
            return json_encode($response,JSON_UNESCAPED_UNICODE);
        }


    }
}