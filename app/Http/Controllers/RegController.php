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

//    $str=file_get_contents('php://input');
////       echo 'str；'.$str;
//       $b64=base64_decode($str);
//       $k=openssl_get_publickey('file://'.storage_path('app/keys/public.pem'));
//       openssl_public_decrypt($b64,$en_data,$k);
////       echo 'a：'.$en_data;
//       $info=json_decode($en_data,true);
//       $user_name=$info['user_name'];
//       $email=$info['email'];
//       $pass1=$info['pass1'];
//       $pass2=$info['pass2'];
       header("Access-Control-Allow-Origin: https://alili.gege12.vip");
       $user_name=$request->input('user_name');
       $email=$request->input('email');
       $pass1=$request->input('pass1');
       $pass2=$request->input('pass2');


       //判断邮箱
       $emailInfo=DB::table('zk_user')->where('email',$email)->first();
       if(!empty($emailInfo)){
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
     * 登录视图
     */
    public function loginIndex(){
        return view('reg.logindex');
    }

    /**
     * 登录
     */

    public function logInfo(Request $request){
        header("Access-Control-Allow-Origin: *");
        $user_name=$request->input('user_name');
        $pass=$request->input('pass');

        $info=DB::table('zk_user')->where('user_name',$user_name)->first();
        $id=$info->id;
        if($info){
            if(!password_verify($pass,$info->pass)){
                $response=[
                    'errno'=>5016,
                    'msg'=>'密码不正确'
                ];
               return $response;
            }else{
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

    /*
    public function logInfo(Request $request){
        header("Access-Control-Allow-Origin: https://alili.gege12.vip");
        $user_name=$request->input('user_name');
        $pass=$request->input('pass1');

        $info=DB::table('zk_user')->where('user_name',$user_name)->first();
        $id=$info->id;

        if($info){
            if(!password_verify($pass,$info->pass)){
                $response=[
                    'errno'=>5016,
                    'msg'=>'密码不正确'
                ];
                die(json_encode($response,JSON_UNESCAPED_UNICODE));
            }else{
                //生成token
                $token=$this->getLoginUserToken($id);
                $lkey='zk_token'.$id;
                Redis::set($lkey,$token);
                Redis::expire($lkey,604800);

                $response=[
                    'errno'=>0,
                    'msg'=>'登陆成功',
                    'data'=>[
                        'token'=>$token
                    ],
                ];
                header('refresh:3;url=http://client_1809a.com/token?token='.$token.'&&'.'id='.$id);
                die(json_encode($response,JSON_UNESCAPED_UNICODE));

            }

        }else{
            $response=[
                'errno'=>5015,
                'msg'=>'没有此用户'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
    }
    */



    public function getLoginUserToken($id){
        $token=substr(sha1(Str::random(15).md5(time()).$id),5,15);
//        print_r($token);

        return $token;
    }


    public function b()
    {
        //header("Access-Control-Allow-Origin: http://client_1809a.com");
        echo time();
    }

}
