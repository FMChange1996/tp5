<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2018-12-29
 * Time: 20:46
 */

namespace app\Index\Controller;


use app\Index\Model\Users;
use think\Controller;
use think\facade\Request;
use think\facade\Session;


class Index extends Controller
{
    public function Index(){
        return view('index/login');
    }

    public function Registe(){
        return view('index/registe');
    }

    public function do_login(){
        if (Request::method('POST') == true){
            $username = Request::param('username');
            $password = hash_pbkdf2("sha256",Request::param('password'),"mukebuyi",2);
            $data = Users::where('username',$username) -> find();
            if ($data['username'] == $username && $data['password'] == $password){
                Session::set('token','310j20rc9qrn2rocq2r0if42moj');
                return ['code' => 200 , 'message' => '登录成功'];
            }elseif ($data == null){
                return ['code' => 400 , 'message' => '该用户不存在！'];
            }elseif ($data['username'] == $username && $data['password'] != $password){
                return ['code' => 400 , 'message' => '账号密码不正确！'];
            }
        }else{
            return redirect('/index');
        }

    }

}