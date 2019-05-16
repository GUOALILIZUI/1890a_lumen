<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;


class RegController extends Controller
{
    /**
     * 注册
     */
   public function regInfo(Request $request){
       $str=file_get_contents('php://input');
       $info=json_decode($str,true);
       $user_name=$info['user_name'];
       $email=$info['email'];
       $pass1=$info['pass1'];
       $pass2=$info['pass2'];
       print_r($user_name);die;

//       //验证用户
       if(empty($user_name)){
           $response=[
               'errno'=>5021,
               'msg'=>'用户名不能为空'
           ];
           return $response;
       }else if(empty($email)){
           $response=[
               'errno'=>5018,
               'msg'=>'邮箱不能为空'
           ];
           return $response;
       }else if(empty($email)){
           $response=[
               'errno'=>5019,
               'msg'=>'密码不能为空'
           ];
           return $response;
       }else if(empty($email)){
           $response=[
               'errno'=>5020,
               'msg'=>'确认密码不能为空'
           ];
           return $response;
       }
       //判断邮箱
       $emailInfo=DB::table('zk_user')->where('email',$email)->first();
       if(empty($emailInfo)){
           $response=[
               'errno'=>5011,
               'msg'=>'此邮箱注册过了'
           ];
           return $response;

       }

       //判断密码
       if($pass1!=$pass2){
           $response=[
               'errno'=>5012,
               'msg'=>'两次密码输入不一致'
           ];
           return $response;
       }

       //处理密码
       $newPass=password_hash($pass2,PASSWORD_BCRYPT);

       //入库
       $userInfo=[
           'user_name'=>$user_name,
           'email'=>$email,
           'pass'=>$newPass,
       ];

       $info=DB::table('zk_user')->insertGetId($userInfo);
       if($info){
           $response=[
               'errno'=>0,
               'msg'=>'注册成功，去登陆页面啦'
           ];
           return $response;
       }else{
           $response=[
               'errno'=>5013,
               'msg'=>'注册失败'
           ];
           return $response;
       }




   }

    /**
     * 登录
     */

    public function logInfo(Request $request){
        $str=file_get_contents('php://input');
//        echo $str;die;
        $info=json_decode($str,true);
        $user_name=$info['user_name'];
        $pass=$info['pass'];

        //验证用户
        if(empty($user_name)){
            $response=[
                'errno'=>5017,
                'msg'=>'用户名不能为空'
            ];
            return $response;
        }

        $info=DB::table('zk_user')->where('user_name',$user_name)->first();
        if($info){
            if(!password_verify($pass,$info->pass)){
                $response=[
                    'errno'=>5016,
                    'msg'=>'密码不正确'
                ];
               return $response;
            }else{
                $id=$info->id;
                //生成token
                $token=$this->getLoginUserToken($id);
                $lkey='hb_token'.$id;
                Redis::set($lkey,$token);
                Redis::expire($lkey,604800);

                $response=[
                    'errno'=>0,
                    'msg'=>'登陆成功',
                    'token'=>$token,
                    'id'=>$id,
                ];
//                die(json_encode($response,JSON_UNESCAPED_UNICODE));
                return $response;

            }

        }else{
            $response=[
                'errno'=>5015,
                'msg'=>'没有此用户,快去注册吧'
            ];
            return $response;
        }
    }


    public function getLoginUserToken($id){
        $token=substr(sha1(Str::random(15).md5(time()).$id),5,15);
        return $token;
    }


    public function b()
    {
        echo time();
    }

}
