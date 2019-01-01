<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-01-01
 * Time: 19:51
 */

namespace app\User\Controller;


use think\Controller;

class Orders extends Controller
{
    protected $middleware = ['\app\http\middleware\Auth'];

    public function index(){
        return $this -> display();
    }

    //未发货订单列表
    public function Wait_Out(){
        return view('orders/wait_out',['title' => '待发货']);
    }

    //已发货订单列表
    public function Shipped(){
        return view('orders/shipped',['title' => '已发货']);
    }

}