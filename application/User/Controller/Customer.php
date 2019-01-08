<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-01-02
 * Time: 19:41
 * 客户跟踪模块
 */

namespace app\User\Controller;


use think\Controller;

class Customer extends Controller
{
    protected $middleware = ['\app\http\middleware\Auth'];

    public function Index(){
        return view('customer/index',['title' => '客户跟踪']);
    }

}