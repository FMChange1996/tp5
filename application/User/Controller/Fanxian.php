<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-01-01
 * Time: 22:35
 * 好评返现模块
 */

namespace app\User\Controller;


use think\Controller;

class Fanxian extends Controller
{
    protected $middleware = ['\app\http\middleware\Auth'];

    public function Index(){
        return view('fanxian/index',['title' => '返现列表']);
    }

}