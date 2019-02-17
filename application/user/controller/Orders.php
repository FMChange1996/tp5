<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-01-01
 * Time: 19:51
 */

namespace app\User\controller;

use app\user\command\Base;
use think\facade\Request;
use think\Validate;
use app\user\model\Orders as OrdersModel;
use Excel;
use Seven;

class Orders extends Base
{
    protected $middleware = ['\app\http\middleware\Check'];

    public function index(){
        return $this -> error("页面不存在！");
    }

    //未发货订单列表
    public function WaitOut(){
        if (Request::isGet()){
            if (!empty(Request::param('searchName'))){
                $list = OrdersModel::where('status',0) -> where('name',Request::param('searchName')) -> paginate(10);
                return view('orders/wait_out',['title' => '待发货', 'list' => $list , 'count' => $list -> count()]);
            }else{
                $list = OrdersModel::where('status',0) -> paginate(10);
                return view('orders/wait_out',['title' => '待发货', 'list' => $list , 'count' => $list -> count()]);
            }
        }else{
            return $this -> error('访问错误');
        }
    }

    //已发货订单列表
    public function Shipped(){
        if (Request::isGet()){
            if (!empty(Request::param('searchName'))){
                $list =OrdersModel::where('status',1) -> where('name',Request::param('searchName')) -> paginate(10);
                return view('orders/shipped',['title' => '已发货','list' => $list ,'count' => $list -> count()]);
            }else{
                $list = OrdersModel::where('status',1) -> paginate(10);
                return view('orders/shipped',['title' => '已发货','list' => $list ,'count' => $list -> count()]);
            }
        }else{
            return $this -> error('访问错误');
        }

    }

    //添加订单操作
    public function Add(){
        if (Request::isPost()) {
            $data = [
                'name' => Request::param('name'),
                'mobile' => Request::param('mobile'),
                'address' => Request::param('address'),
                'goods' => Request::param('goods'),
                'urgent' => Request::param('urgent')
            ];
            $message = [
                'name.require' => '收件人名字不能为空',
                'mobile.require' => '收件人手机号不能为空',
                'mobile.min' => '收件人手机号格式不正确',
                'mobile.max' => '收件人手机号格式不正确',
                'address.require' => '收件人地址不能为空',
                'goods.require' => '发货清单不能为空',
                'urgent.require' => '紧急状态必须选择',
                'urgent.integer' => '紧急状态未选择'
            ];
            $validate = Validate::make([
                'name' => 'require',
                'mobile' => 'require|min:11|max:12',
                'address' => 'require',
                'goods' => 'require',
                'urgent' => 'require'
            ], $message);
            if (!$validate->check($data)) {
                return json(['code' => 400, 'message' => $validate->getError()]);
            } else {
                $order = new OrdersModel([
                    'order_id' => date('YmdHi') . rand(10000, 99999),
                    'name' => $data['name'],
                    'mobile' => $data['mobile'],
                    'address' => $data['address'],
                    'goods' => $data['goods'],
                    'urgent' => $data['urgent'],
                    'status' => 0,
                    'create' => session('username'),
                    'create_time' => time()
                ]);
                if ($order->save()) {
                    if ($order['urgent'] == "正常"){
                        $status = "正常";
                    }else{
                        $status = "加急";
                    }
                    $push = new Seven();
                    $push -> SetTitle($order['order_id'])
                        -> SetMessage("收件人名字：".$order['name']."\n\n收件人电话：".$order['mobile']."\n\n收件人地址：".$order['address']."\n\n发货清单：".$order['goods']."\n\n订单状态：".$status)
                        -> SetChannel('1943-b81f7a0058d1b527f8315aee1a81e2d0')
                        -> pushbear();
                    return json(['code' => '200', 'message' => '添加成功']);
                } else {
                    return json(['code' => '500', 'message' => '添加失败']);
                }
            }
        } else {
            return view('orders/orders_add', ['title' => '添加订单']);
        }
    }

    //删除订单操作
    public function Del(){
        if (Request::isPost()){
            $id = Request::param('id');
            if (empty($id)){
                return json(['code' => 400 ,'message' => '删除订单号不能为空！']);
            }else{
                if (OrdersModel::destroy($id)){
                    return json(['code' => 200 ,'message' => '删除成功']);
                }else{
                    return json(['code' => 400 ,'message' => '删除失败']);
                }
            }
        }else{
            $this -> error('不支持Get请求');
        }
    }

    //订单修改操作
    public function Edit(){
        if (Request::isGet()){
            $id = Request::param('id');
            $list = OrdersModel::where('id',$id) -> find();
            return view('orders/edit',['title' => '订单编辑','vo' => $list]);
        }elseif (Request::isPut()){
            $data = [
                'id' => Request::param('id'),
                'name' => Request::param('name'),
                'mobile' => Request::param('mobile'),
                'address' => Request::param('address'),
                'goods' => Request::param('goods'),
                'urgent' => Request::param('urgent')
            ];

            $message =[
                'name.require' => '收件人名字不能为空',
                'mobile.require' => '收件人电话不能为空',
                'mobile.mix' => '电话格式不正确',
                'mobile.min' => '电话格式不正确',
                'address.require' => '收件地址不能为空',
                'goods' => '发货清单不能为空',
                'urgent' => '紧急状态必须选择'
            ];

            $validate = Validate::make([
                'name' => 'require',
                'mobile' => 'require|min:11|max:12',
                'address' => 'require',
                'goods' => 'require',
                'urgent' => 'require|integer'
            ],$message);

            if (!$validate -> check($data)){
                return json(['code' => 400 , 'message' => $validate -> getError()]);
            }else{
                $find = OrdersModel::where('id',$data['id']) -> data($data);
                if ($find -> update()){
                    return json(['code' => 200 , 'message' => '更新成功']);
                }else{
                    return json(['code' => 400 , 'message' => '更新失败']);
                }
            }
        }

    }

    //物流发货
    public function Send(){
        if (Request::isPost()){
            $id = Request::param('id');
            $exp_num = Request::param('exp_num');
            if (empty($exp_num)){
                return json(['code' => 400 ,'message' => '物流单号不能为空']);
            }else{
                $find = OrdersModel::where('id',$id) -> data([
                    'exp_number' => $exp_num,
                    'status' => 1
                ]);
                if ($find -> update()){

                    return json(['code' => 200 , 'message' => '发货成功']);
                }else{
                    return json(['code' => 400 , 'message' => '发货失败']);
                }
            }
        }elseif (Request::isGet()){
            $id = Request::param('id');
            $vo = OrdersModel::where('id',$id) -> find();
            return view('orders/send',['title' => '订单发货', 'vo' => $vo]);
        }else{
            return $this -> error('操作失败！');
        }
    }

    //批量下载订单操作
    public function DownloadFile(){
        $id = Request::param('id');
        $list = OrdersModel::all($id);
        $excel = new Excel();
        return $excel -> ExportExcel($list);
    }

}