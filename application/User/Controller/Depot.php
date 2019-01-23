<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-01-01
 * Time: 22:11
 */

namespace app\User\Controller;

use app\User\Command\Base;

class Depot extends Base
{
    protected $middleware = ['\app\http\middleware\Check'];

    public function Index(){
        return view('depot/index',['title' => '仓储管理']);
    }
}