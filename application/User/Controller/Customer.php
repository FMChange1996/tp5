<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-01-02
 * Time: 19:41
 */

namespace app\User\Controller;


use think\Controller;

class Customer extends Controller
{
    protected $middleware = ['\app\http\middleware\Auth'];

    public function index(){

    }

}