<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2018-12-29
 * Time: 20:46
 */

namespace app\Index\controller;


use app\Index\model\Users;
use think\Controller;
use think\facade\Request;
use think\facade\Session;
use app\User\model\UserAccess;
use app\User\model\Role;


class Index extends Controller
{
    public function Index(){
        return view('index/login');
    }

    public function Register(){
        return view('index/register');
    }

    public function do_login(){
        if (Request::method('POST') == true){
            $username = Request::param('username');
            $password = hash_pbkdf2("sha256",Request::param('password'),"mukebuyi",2);
            $data = Users::where('username',$username) -> find();
            if ($data['username'] == $username && $data['password'] == $password){
                Session::set('uid',$data['id']);
                Session::set('token','dcn3ocqe2mroqw23r0');
                Session::set('username',$username);
                $group = UserAccess::where('uid',$data['id']) -> find();
                $name = Role::where('id',$group['group_id']) -> find();
                Session::set('group',$name['title']);
                return ['code' => 200 , 'message' => '登录成功'];
            }elseif ($data == null){
                return ['code' => 400 , 'message' => '该用户不存在！'];
            }elseif ($data['username'] == $username && $data['password'] != $password){
                return ['code' => 400 , 'message' => '账号密码不正确！'];
            }
        }else{
            return redirect('/');
        }
    }

    public function Login_Out(){
        if (Session::pull('token')){
            $this -> success('退出成功，正在跳转','/');
        }else{
            $this -> error('退出失败');
        }
    }

}