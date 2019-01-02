<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-01-01
 * Time: 22:11
 */

namespace app\User\Controller;


use think\Controller;

class Depot extends Controller
{
    protected $middleware = ['\app\http\middleware\Auth'];

    public function index(){
        return view('depot/index',['title' => '仓储管理']);
    }
}