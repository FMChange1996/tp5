<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-01-02
 * Time: 19:47
 */

namespace app\User\Controller;

use think\Controller;

class System extends Controller
{
    protected $middleware = ['\app\http\middleware\Check'];

    public function User_List(){
        return view('system/user_list',['title' => '管理员列表']);
    }

    public function User_Rule(){
        return view('system/user_rule',['title' => '管理员权限']);
    }

    public function User_Info(){
        return view('system/user_info',['title' => '个人信息']);
    }

    public function Setting(){

    }

}