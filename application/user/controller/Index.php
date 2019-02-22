<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-01-01
 * Time: 19:33
 */

namespace app\User\controller;

use app\index\model\Users;
use app\user\model\Orders;
use app\user\model\After;
use app\user\model\Payout;
use think\Controller;


class Index extends Controller
{
    protected $middleware = ['\app\http\middleware\Check'];

    public function index(){
        $orders_count = Orders::where('status',0) -> count();
        $customer_count = After::where('status',0) -> count();
        $payout_count = Payout::where('status','<',2) -> count();
        return view('index/welcome',[
            'title' => '首页' ,
            'order_count' => $orders_count ,
            'after_count' => $customer_count ,
            'pay_count'  => $payout_count
        ]);
    }

}