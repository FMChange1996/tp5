<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-01-01
 * Time: 19:51
 */

namespace app\User\Controller;

use app\User\Command\Base;
use think\facade\Request;
use think\Validate;
use app\User\Model\Orders as OrdersModel;

class Orders extends Base
{
    protected $middleware = ['\app\http\middleware\Check'];

    public function index(){
        return $this -> error("页面不存在！");
    }

    //未发货订单列表
    public function Wait_Out(){
        $list = OrdersModel::paginate(10);
        return view('orders/wait_out',['title' => '待发货', 'list' => $list , 'count' => $list -> count()]);
    }

    //已发货订单列表
    public function Shipped(){
        return view('orders/shipped',['title' => '已发货']);
    }

    //添加订单操作
    public function Order_Add(){
        if (Request::isPost()){
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
                    'urgent' => 'require|integer'
            ],$message);
            if (!$validate -> check($data)){
                return json(['code' => 400 , 'message' => $validate -> getError()]);
            }else{
                $order = new OrdersModel([
                    'name' => $data['name'],
                    'mobile' => $data['mobile'],
                    'address' => $data['address'],
                    'goods' => $data['goods'],
                    'urgent' => $data['urgent'],
                    'status' => '0',
                    'create_time' => time()
                ]);
                if ($order -> save()){
                    return json(['code' => '200' , 'message' => '添加成功']);
                }else{
                    return json(['code' => '500' , 'message' => '添加失败']);
                }
            }
        }else{
            return view('orders/orders_add',['title' => '添加订单']);
        }
    }
}