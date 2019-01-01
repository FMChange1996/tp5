<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-01-01
 * Time: 22:29
 */

namespace app\User\Controller;


use think\Controller;

class Customer extends Controller
{
    protected $middleware = ['\app\http\middleware\Auth'];

    public function index(){
        return $this -> display();
    }



}