<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-01-01
 * Time: 22:29
 * 售后服务模块
 */

namespace app\User\Controller;

use think\Controller;

class CustomerService extends Controller
{
    protected $middleware = ['\app\http\middleware\Auth'];

    public function Index(){
        return view('customerservice/index',['title' => '售后登记']);
    }

    public function Pay_Out(){
        return view('customerservice/pay_out',['title' => '售后支出']);
    }


}