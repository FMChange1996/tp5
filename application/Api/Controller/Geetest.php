<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2018-12-30
 * Time: 22:23
 */

namespace app\Api\Controller;

use GeetestLib;
use think\Controller;


class Geetest extends Controller
{
    
    //使用GET返回challenge和captcha_id
    public function GetSteam(){
        $geetest = new GeetestLib(env('GeeId'),env('GeeKey'));
        session_start();
        $data = array(
            "user_id" => 'test',
            "client_type" => "web",
            "ip_address" => "127.0.0.1"
        );
        $status = $geetest -> pre_process($data, 1);
        $_SESSION['gtserver'] = $status;
        $_SESSION['user_id'] = $data['user_id'];
        echo $geetest -> get_response_str();
    }

    //输出二次验证结果
    public function VerifyLoginServlet(){
        $geetest = new GeetestLib(env('GeeId'),env('GeeKey'));
        $user_id = $_SESSION['user_id'];
        if ($_SESSION['gtserver'] == 1){
            $result = $geetest-> success_validate($_POST['geetest_challenge'].$_POST('geetest_validate'),$_POST['geetest_seccode']);
        }
    }

}