<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-01-02
 * Time: 19:41
 * 客户跟踪模块
 */

namespace app\User\Controller;

use app\User\Command\Base;

class Customer extends Base
{
    protected $middleware = ['\app\http\middleware\Check'];

    public function Index(){
        return view('customer/index',['title' => '客户跟踪']);
    }

}