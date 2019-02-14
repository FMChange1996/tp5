<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-01-01
 * Time: 22:29
 * 售后服务模块
 */

namespace app\User\controller;

use app\user\command\Base;
use app\user\model\After;
use think\facade\Request;
use think\facade\Validate;
use think\facade\Session;


class Customerservice extends Base
{
    protected $middleware = ['\app\http\middleware\Check'];

    public function Index(){
        $list = After::paginate(10);
        return view('customerservice/index',['title' => '售后登记' , 'list' => $list , 'count' => $list -> count()]);
    }

    public function PayOut(){
        return view('customerservice/pay_out',['title' => '售后支出']);
    }

    public function AddAfter(){
        if (Request::isPost()){
            $data = [
                'wangwang' => Request::param('wangwang'),
                'text' => Request::param('text')
            ];

            $message = [
                'wangwang.require' => '旺旺名不能为空',
                'text.require' => '事件不能为空'
            ];

            $validate = Validate::make([
                'wangwang' => 'require',
                'text' => 'require'
            ],$message);

            if (!$validate -> check($data)){
                return json(['code' => 400 , 'message' => $validate -> getError()]);
            }else{
                $after = new After([
                    'wangwang' => $data['wangwang'],
                    'text' => $data['text'],
                    'status' => 0,
                    'create' => Session::get('username')
                ]);
                if ($after -> save()){
                    return json(['code' => 200 , 'message' => '添加成功！']);
                }else{
                    return json(['code' => 400 , 'message' => '添加失败！']);
                }
            }
        }elseif (Request::isGet()){
            return view('customerservice/add_after',['title' => '添加售后']);
        }else{
            return $this -> error('访问错误！');
        }
    }


}