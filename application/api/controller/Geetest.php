<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2018-12-30
 * Time: 22:23
 */

namespace app\Api\controller;

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


    //二次检验
    public function VerifyCaptcha(){
        session_start();
        $gtsdk = new GeetestLib(env('GeeId'),env('GeeKey'));
        $data = array(
            "user_id" => $_SESSION['user_id'], # 网站用户id
            "client_type" => "web", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
            "ip_address" => "127.0.0.1" # 请在此处传输用户请求验证时所携带的IP
        );
        if ($_SESSION['gtserver'] == 1) {   //服务器正常
            $result = $gtsdk->success_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode'], $data);
            if ($result) {
                echo '{"status":"success"}';
            } else {
                echo '{"status":"fail"}';
            }
        } else {  //服务器宕机,走failback模式
            if ($gtsdk->fail_validate($_POST['geetest_challenge'],$_POST['geetest_validate'],$_POST['geetest_seccode'])) {
                echo '{"status":"success"}';
            } else {
                echo '{"status":"fail"}';
            }
        }
    }
}