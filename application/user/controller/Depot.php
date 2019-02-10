<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-01-01
 * Time: 22:11
 * 仓库管理控制器
 */

namespace app\User\controller;

use app\user\command\Base;
use think\facade\Request;


class Depot extends Base
{
    protected $middleware = ['\app\http\middleware\Check'];

    public function Index(){
        return view('depot/index',['title' => '仓储管理']);
    }

    public function Add(){
        if (Request::isPost()){

        }elseif(Request::isGet()){
            return view('',['title' => '添加库存']);
        }else{
            return $this -> error('访问错误');
        }
    }
}