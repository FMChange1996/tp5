<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-01-01
 * Time: 22:29
 * 售后服务模块
 */

namespace app\User\controller;

use app\user\command\Base;
use app\user\model\After;

class Customerservice extends Base
{
    protected $middleware = ['\app\http\middleware\Check'];

    public function Index(){
        $list = After::paginate(10);
        return view('customerservice/index',['title' => '售后登记' , 'list' => $list , 'count' => $list -> count()]);
    }

    public function PayOut(){
        return view('customerservice/pay_out',['title' => '售后支出']);
    }


}