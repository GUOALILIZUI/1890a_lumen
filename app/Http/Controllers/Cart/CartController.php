<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;

class CartController extends Controller
{

    public function cart(){
        $str=file_get_contents('php://input');
//        echo $str;
        $cartInfo=json_decode($str,true);
        $goods_id=$cartInfo['goods_id'];
        $uid=$cartInfo['uid'];
        $buy_number1=$cartInfo['buy_number'];
        $goods_selfpprice1=$cartInfo['goods_selfprice'];
        $selPriceAun1=$cartInfo['selPriceAun'];


        $goods=DB::table('cart')->where('goods_id',$goods_id)->first();
        if($goods){
            $buy_number=$goods->buy_number;
            $goods_selfpprice=$goods->goods_selfprice;
            $selPriceAun=$goods->selPriceAun;


            $buy_number2=$buy_number1+$buy_number;
            $selPriceAun=$selPriceAun+$selPriceAun1;
//        echo $buy_number2;die;


            $goods=DB::table('cart')->where(['goods_id'=>$goods_id,'uid'=>$uid])->count();
            if($goods){
                $info=[
                    'buy_number'=>$buy_number2,
                    'selPriceAun'=>$selPriceAun,
                    'ctime'=>time()
                ];
                $where=[
                    'goods_id'=>$goods_id,
                    'uid'=>$uid
                ];
                DB::table('cart')->where($where)->update($info);
                $response=[
                    'errno'=>0,
                    'msg'=>'加入购物车成功2',
                ];
                return json_encode($response,JSON_UNESCAPED_UNICODE);
            }else{
                $res=DB::table('cart')->insert($cartInfo);
                if($res){
                    $response=[
                        'errno'=>0,
                        'msg'=>'加入购物车成功',
                    ];
                    return json_encode($response,JSON_UNESCAPED_UNICODE);
                }else{
                    $response=[
                        'errno'=>2,
                        'msg'=>'加入购物车失败',
                    ];
                    return json_encode($response,JSON_UNESCAPED_UNICODE);

                }
            }

        }else{
            $res=DB::table('cart')->insert($cartInfo);
            if($res){
                $response=[
                    'errno'=>0,
                    'msg'=>'加入购物车成功',
                ];
                return json_encode($response,JSON_UNESCAPED_UNICODE);
            }else{
                $response=[
                    'errno'=>2,
                    'msg'=>'加入购物车失败',
                ];
                return json_encode($response,JSON_UNESCAPED_UNICODE);

            }
        }

    }


    public function cartList(){
        $str=file_get_contents('php://input');
        $cartInfo=json_decode($str,true);
        if($cartInfo){
            $response=[
                'errno'=>0,
                'msg'=>'购物车页面',
                'cartInfo'=>$cartInfo
            ];
            return json_encode($response,JSON_UNESCAPED_UNICODE);
        }else{
            $response=[
                'errno'=>2,
                'msg'=>'购物车是空的',
            ];
            return json_encode($response,JSON_UNESCAPED_UNICODE);
        }

    }
}
