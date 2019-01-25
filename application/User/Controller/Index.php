<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-01-01
 * Time: 19:33
 */

namespace app\User\Controller;

use app\User\Model\Orders;
use think\Controller;

class Index extends Controller
{
    protected $middleware = ['\app\http\middleware\Check'];

    public function index(){
        $orders_count = Orders::count();
        return view('index/welcome',['title' => '首页' , 'order_count' => $orders_count ]);
    }

}