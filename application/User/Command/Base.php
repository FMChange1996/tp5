<?php
/**
 * Created by PhpStorm.
 * User: Hades
 * Date: 2019/1/23
 * Time: 11:09
 */

namespace app\User\Command;

use hunankuai\Auth;
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
        $userArr = Db::name('kuai_auth_group_access') -> where('uid',$uid) -> find();
        $access_id = $userArr['group_id'];
        $auth = new Auth();
        $request = Request::instance();
        $url = $request -> module().'/'.$request -> controller().'/'.$request -> action();
        dump($auth -> check('Admin/Article/Delete','1'));
//        if (!$auth -> check('Admin/Article/Delete','1')){
//            $this -> error('无权访问！');
//        }
    }

}