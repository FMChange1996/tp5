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
use app\user\model\Payout;
use alipay\Alipay;

class Customerservice extends Base
{
    protected $middleware = ['\app\http\middleware\Check'];

    public function Index(){
        $list = After::paginate(10);
        return view('customerservice/index',['title' => '售后登记' , 'list' => $list , 'count' => $list -> count()]);
    }

    public function PayOut(){
        $list = Payout::paginate(15);
        return view('customerservice/pay_out',['title' => '售后支出','list' => $list , 'count' => $list -> count()]);
    }

    //添加售后事件
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

    //编辑售后事件
    public function EditAfter(){
        if (Request::isGet()){
            $id = Request::param('id');
            if (!empty($id)){
                $find = After::where('id',$id) -> find();
                return view('customerservice/edit_after',['title' => '编辑售后事件' ,'vo' => $find]);
            }else{
                return $this -> error('默认参数缺损！');
            }
        }elseif (Request::isPost()){
            $data = [
                'wangwang' => Request::param('wangwang'),
                'text' => Request::param('text'),
                'status' => Request::param('status')
            ];

            $message = [
                'wangwang.require' => '旺旺名参数缺损',
                'status.require' => '处理状态必须选择'
            ];

            $validate = Validate::make([
                'wangwang' => 'require',
                'status' => 'require'
            ],$message);

            if (!$validate -> check($data)){
                return json(['code' => 400 , 'message' => $validate -> getError()]);
            }else{
                $find = After::where('wangwang',$data['wangwang']) -> data([
                    'text' => $data['text'],
                    'status' => $data['status']
                ]);

                if ($find -> update()){
                    return json(['code' => 200 , 'message' => '更新成功！']);
                }else{
                    return json(['code' => 400 , 'message' => '更新失败！']);
                }
            }
        }else{
            return $this -> error('访问错误！');
        }
    }

    //删除售后事件
    public function DelAfter(){
        if (Request::isDelete()){
            $id = Request::param('id');
            if (!empty($id)){
                if (After::destroy($id)){
                    return json(['code' => 200 , 'message' => '删除成功！']);
                }else{
                    return json(['code' => 400 , 'message' => '删除失败！']);
                }
            }else{
                return json(['code' => 400 , 'message' => '参数缺损，请联系开发者！']);
            }
        }else{
            return $this -> error('非法访问！');
        }
    }

    //售后支出添加操作
    public function AddPayout(){
        if (Request::isPost()){
            $data = [
                'wangwang' => Request::param('wangwang'),
                'zhifubao' => Request::param('zhifubao'),
                'number' => Request::param('number'),
                'text' => Request::param('text')
            ];

            $message = [
                'wangwang.require' => '旺旺名不能为空',
                'zhifubao.require' => '支付宝账号不能为空',
                'number.require' => '转账金额不能为空',
                'number.number' => '转账金额不符合规则',
                'text.require' => '转账理由不能为空'
            ];

            $validate = Validate::make([
                'wangwang' => 'require',
                'zhifubao' => 'require',
                'number' => 'require|float',
                'text' => 'require'
            ],$message);
            if (!$validate -> check($data)){
                return json(['code' => 400 , 'message' => $validate -> getError()]);
            }else{
                $payout = new Payout([
                    'wangwang' => $data['wangwang'],
                    'zhifubao' => $data['zhifubao'],
                    'number' => $data['number'],
                    'text' => $data['text'],
                    'status' => 0,
                    'create' => Session::get('username')
                ]);

                if ($payout -> save()){
                    return json(['code' => 200 , 'message' => '添加成功！']);
                }else{
                    return json(['code' => 400 , 'message' => '添加失败！']);
                }
            }

        }elseif (Request::isGet()){
            return view('customerservice/add_payout',['title' => '添加售后支出']);
        }else{
            return $this -> error('非法访问！');
        }
    }

    //删除售后支出
    public function DelPayout(){
        if (Request::isDelete()){
            $id = Request::param('id');
            if (!empty($id)){
                if (Payout::destroy($id)){
                    return json(['code' => 200 , 'message' => '删除成功!']);
                }else{
                    return json(['code' => 400 , 'message' => '删除失败！']);
                }
            }else{
                return json(['code' => 400 , 'message' => '默认参数缺损!']);
            }
        }else{
            return $this -> error('非法访问');
        }
    }

    //审核操作
    public function VerifyPayout(){
        if (Request::isPut()){
            $id = Request::param('id');
            if (!empty($id)){
                $find = Payout::where('id',$id) -> data(['status' => 1]);
                if ($find -> update()){
                    return json(['code' => 200]);
                }else{
                    return json(['code' => 400 , 'message' => '未知错误']);
                }
            }else{
                return json(['code' => 400 , 'message' => '接受参数缺损！']);
            }
        }else{
            return $this -> error('非法访问！');
        }
    }

    //转账操作
    public function TransferPayout(){
       $alipay = new Alipay();
       if (Request::isPut()){
            $id = Request::param('id');
            if (!empty($id)){
                $find = Payout::where('id',$id) -> find();
                $result = $alipay -> AlipayTransfer($find['zhifubao'],$find['number']);
                if ($result['code'] == 10000){
                    $find -> zhifubao_number = $result['order_id'];
                    $find ->  status =  2;
                    if ($find -> save()){
                        return json(['code' => 200 , 'message' => '转账成功，支付宝交易号：'.$result['order_id']]);
                    }else{
                        return json(['code' => 400 , 'message' => '转账成功，数据更新失败']);
                    }
                    return $result;
                }else{
                    return json(['code' => 400 , 'message' => $result['sub_msg']]);
                }

            }else{
                return json(['code' => 400 , 'message' => '默认参数缺损']);
            }
       }else{
           return $this -> error('非法访问！');
       }
    }

    public function EditPayout(){
        if (Request::isGet()){

        }elseif (Request::isPut()){

        }else{
            return $this -> error('非法访问！');
        }
    }


}