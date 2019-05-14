<?php

namespace App\Http\Controllers;

class OneController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     *
     * 对称加密
     */
    public function info()
    {
        //
        // print_r($_POST);
        $str=file_get_contents('php://input');
        echo 'str：'.$str;
        echo "</br>";
        $method = 'AES-256-CBC';
        $passwd = 'zzxxcc';
        $option=OPENSSL_RAW_DATA;
        $iv='QWERTYUIOPQWERTY';
        $base=base64_decode($str);
        $oldPass=openssl_decrypt($base,$method,$passwd,$option,$iv);
        echo 'pass：'.$oldPass;


    }

    /**
     * @param int $n
     * 凯撒加密
     */
    public function int($n=3){
        $str=file_get_contents('php://input');
        $int2='';
        $length=strlen($str);
        for ($i=0;$i<$length;$i++){
            $int=ord($str[$i])-$n;
            $int2.=chr($int);
        }

        echo '密文：'.$str;echo "<br>";
        echo '明文：'.$int2;

    }

    /**
     * 非对称加密
     */
    public function rsaTest(){
        $str=file_get_contents('php://input');
        echo '密文：'.$str;echo "</br>";
        $b64_json=base64_decode($str);
        $key=openssl_get_publickey('file://'.storage_path('app/keys/public.pem'));
        openssl_public_decrypt($b64_json,$en_data,$key);
        $a=json_decode($en_data,true);
        echo '密文：';
        print_r($a) ;

    }

    /**
     * 签名
     */
    public function sign(){
        $sign=$_GET['sign'];
        echo '接受的签名：';
        print_r($sign);echo "<br>";
        $str=file_get_contents('php://input');
        echo 'srt：'.$str;echo "<br>";

        $key=openssl_get_publickey('file://'.storage_path('app/keys/public.pem'));
        $b=openssl_verify($str,base64_decode($sign),$key);
        var_dump($b);


    }
}
