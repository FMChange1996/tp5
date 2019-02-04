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
use think\Db;
use think\facade\Request;
use think\facade\Session;
use think\facade\Validate;

class Customer extends Base
{
    protected $middleware = ['\app\http\middleware\Check'];

    //首页
    public function Index(){
        $list = CustomerModel::paginate(10);
        return view('customer/index',['title' => '客户跟踪' , 'list' => $list]);
    }

    //客户添加操作
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
                    'create_time' => time()
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

    //客户删除操作
    public function Del(){
        if (Request::isPost()){
            $id = Request::param('id');
            if (CustomerModel::destroy($id)){
                return json(['code' => 200 , 'message' => '删除成功']);
            }else{
                return json(['code' => 400 , 'message' => '删除失败']);
            }
        }else{
            return $this -> error('系统错误');
        }
    }

    //添加跟踪时间
    public function AddDate(){
        if (Request::isPost()){
            $data = [
                'id' => Request::param('id'),
                'key' => Request::param('key'),
                'time' => Request::param('time')
            ];

            $message =[
                'time.require' => '时间选择不能为空',
                'id.require' => '默认参数缺损',
                'key.require' => '默认参数缺损'
            ];

            $check = Validate::make([
                'id' => 'require',
                'key' => 'require',
                'time' => 'require'
            ],$message);

            if (!$check -> check($data)){
                return json(['code' => 400 , 'message' => $check -> getError()]);
            }else{
                $find =Db::name('customer') -> where('id',$data['id']);
                if ($find -> setField($data['key'],$data['time'])){
                    return json(['code' => 200 , 'message' => '添加成功']);
                }else{
                    return json(['code' => 400 , 'message' => '添加失败']);
                }
            }

        }elseif (Request::isGet()){
            $key = Request::param('key');
            $id = Request::param('id');
            $find = CustomerModel::where('id',$id) -> find();
            return view('customer/add_date',['title' => '添加时间','id' => $id , 'key' => $key , 'wangwang' => $find['wangwang']]);
        }else{
            return $this -> error('访问错误');
        }
    }

    //添加备注
    public function AddRemark(){
        if (Request::isPost()){
            $data = [
                'id' => Request::param('id'),
                'remarks' => Request::param('remark')
            ];

            $message = [
                'id.require' => '默认参数缺损',
                'remark.require' => '无法添加空备注'
            ];

            $validate = Validate::make([
                'id' => 'require',
                'remarks' => 'require'
            ],$message);

            if (!$validate -> check($data)){
                return json(['code' => 400 ,'message' => $validate -> getError()]);
            }else{
                $find = Db::name('customer') -> where('id',$data['id']);
                if ($find -> setField('remarks',$data['remarks'])){
                    return json(['code' => 200 , 'message' => '添加成功']);
                }else{
                    return json(['code' => 200 , 'message' => '添加成功']);
                }
            }

        }elseif (Request::isGet()){
            $id = Request::param('id');
            $find = CustomerModel::where('id',$id) -> find();
            return view('customer/add_remark',['title' => '添加备注' , 'id' => $id , 'wangwang' => $find['wangwang']]);
        }else{
            return $this -> error('访问错误');
        }
    }

}