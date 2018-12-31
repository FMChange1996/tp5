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

    private $GtSdk;

    public function _initialize(){
        $this -> GtSdk = new GeetestLib(env('GeeId'),env('GeeKey'));
    }

    //使用GET返回challenge和captcha_id
    public function GetSteam(){

        session_start();
        $data = array(
            "user_id" => 'test',
            "client_type" => "web",
            "ip_address" => "127.0.0.1"
        );
        $status = $this -> GtSdk ->pre_process($data, 1);
        $_SESSION['gtserver'] = $status;
        $_SESSION['user_id'] = $data['user_id'];
        echo $this -> GtSdk ->get_response_str();
    }

    //输出二次验证结果
    public function VerifyLoginServlet(){
        $geetest = new GeetestLib(env('GeeId'),env('GeeKey'));
        $user_id = $_SESSION['user_id'];
        if ($_SESSION['gtserver'] == 1){
            $result = $this -> GtSdk -> success_validate($_POST['geetest_challenge'].$_POST('geetest_validate'),$_POST['geetest_seccode']);
        }
    }

}