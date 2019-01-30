<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-01-02
 * Time: 19:41
 * 客户跟踪模块
 */

namespace app\User\Controller;

use app\User\Command\Base;
use app\User\Model\Customer as CustomerModel;
use think\facade\Request;
use think\facade\Session;
use think\facade\Validate;

class Customer extends Base
{
    protected $middleware = ['\app\http\middleware\Check'];

    public function Index(){
        $list = CustomerModel::paginate(10);
        return view('customer/index',['title' => '客户跟踪' , 'list' => $list]);
    }

    public function Add(){
        if (Request::isPost()){
            $message = [
                'wangwang.require' => '旺旺ID不能为空！'
            ];
            $data = [
                'wangwang' => Request::param('wangwang')
            ];
            $validate = Validate::make(['wangwang' => 'require'],$message);

            if (!$validate -> check($data)){
                return json(['code' => 400 , 'message' => $validate -> getError()]);
            }else{
                $customer = new CustomerModel([
                    'wangwang' => $data['wangwang'],
                    'remarks' => Request::param('remarks'),
                    'create' => Session::get('username'),
                    'create_time' => intval(time())
                ]);

                if ($customer -> save()){
                    return json(['code' => 200 ,'message' => '添加成功']);
                }else{
                    return json(['code' => 400 ,'message' => '添加失败']);
                }

            }

        }else{
            return view('customer/add',['title' => '添加客户']);
        }
    }

}