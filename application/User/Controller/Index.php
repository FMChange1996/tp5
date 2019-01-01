<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-01-01
 * Time: 19:33
 */

namespace app\User\Controller;


use think\Controller;

class Index extends Controller
{
    public function index(){
        return view('index/welcome',['title' => '首页']);
    }

}