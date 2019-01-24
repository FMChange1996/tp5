<?php
/**
 * Created by PhpStorm.
 * User: Hades
 * Date: 2019/1/23
 * Time: 11:09
 */

namespace app\User\Command;

use think\auth\Auth;
use think\Controller;
use think\Db;
use think\facade\Request;

class Base extends Controller
{
    protected function initialize()
    {
        $uid = session('uid');
        if ($uid == 1){
            return true;
        }
        $userArr = Db::name('role') -> where('uid',$uid) -> find();
        $access_id = $userArr['role'];
        $auth = new Auth();
        $request = Request::instance();
        $url = $request -> module().'/'.$request -> controller().'/'.$request -> action();
        if (!$auth -> check($url,$access_id)){
            $this -> error('无权访问！');
        }
    }

}