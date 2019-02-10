<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-01-01
 * Time: 19:33
 */

namespace app\User\Controller;

use app\User\Model\Orders;
use app\User\Model\Role;
use app\User\Model\UserAccess;
use think\Controller;
use think\facade\Session;

class Index extends Controller
{
    protected $middleware = ['\app\http\middleware\Check'];

    public function index(){
        $orders_count = Orders::where('status',0) -> count();
        return view('index/welcome',['title' => '首页' , 'order_count' => $orders_count ]);
    }

}