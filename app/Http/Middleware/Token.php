<?php
namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Redis;

class Token
{
    /**
     * 返回请求过滤器
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        $token=$_GET['token'];
        $id=$_GET['id'];
        $lkey='hb_token'.$id;
        $redisToken=Redis::get($lkey);
        if($token!=$redisToken){
            $res=[
                'errno'=>1,
                'msg'=>'token无效，请重新登录',
            ];
           $response=json_encode($res,JSON_UNESCAPED_UNICODE);
        }else{
            $response=$next($request);
        }


        return $response;


    }
}
?>