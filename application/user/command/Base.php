<?php
/**
 * Created by PhpStorm.
 * User: Hades
 * Date: 2019/1/23
 * Time: 11:09
 */

namespace app\user\command;

use Auth;
use think\Controller;
use think\Db;
use think\facade\Request;

class Base extends Controller
{
    protected function initialize()
    {
        if (!session('token')){
            return $this -> error('登录超时','/');
        }
        $uid = session('uid');
        if ($uid == 1){
            return true;
        }
        $userArr = Db::name("auth_group_access") -> where('uid',$uid) -> find();
        $access_id = $userArr['group_id'];
        $auth = new Auth();
        $request = Request::instance();
        $url = $request -> module().'/'.$request -> controller().'/'.$request -> action();
        if (!$auth -> check($url,$uid)){
            $this -> error('无权访问！');
        }
    }

}