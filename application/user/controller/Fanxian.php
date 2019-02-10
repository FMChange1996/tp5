<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-01-01
 * Time: 22:35
 * 好评返现模块
 */

namespace app\User\controller;


use app\user\command\Base;

class Fanxian extends Base
{
    protected $middleware = ['\app\http\middleware\Check'];

    public function Index(){
        return view('fanxian/index',['title' => '返现列表']);
    }

}