<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-01-01
 * Time: 22:11
 * 仓库管理控制器
 */

namespace app\User\controller;

use app\user\command\Base;
use app\user\model\Depot as DepotModel;
use think\facade\Request;
use think\facade\Session;
use think\facade\Validate;


class Depot extends Base
{
    protected $middleware = ['\app\http\middleware\Check'];

    public function Index(){
        $list = DepotModel::paginate(15);
        return view('depot/index',['title' => '仓储管理', 'list' => $list ,'count' => $list -> count()]);
    }

    //添加操作
    public function Add(){
        if (Request::isPost()){
            $data = [
                'model' => Request::param('model'),
                'width' => Request::param('width'),
                'height' => Request::param('height'),
                'number' => Request::param('number'),
                'method' => Request::param('method'),
                'text' => Request::param('text')
            ];

            $message = [
                'model.require' => '库存型号不能为空',
                'width.require' => '宽度不能为空',
                'height.require' => '高度不能为空',
                'number.require' => '片数不能为空',
                'number.integer' => '片数只能是整数',
                'method.require' => '加工方式必须选择'
            ];

            $validate = Validate::make([
                'model' => 'require',
                'width' => 'require',
                'height' => 'require',
                'number' => 'require|integer',
                'method' => 'require'
            ],$message);

            if (!$validate -> check($data)){
                return json(['code' => 400 , 'message' => $validate -> getError()]);
            }else{
                $depot = new DepotModel([
                    'model' => $data['model'],
                    'width' => $data['width'],
                    'height' => $data['height'],
                    'number' => $data['number'],
                    'method' => $data['method'],
                    'text' => $data['text'],
                    'create' => Session::get('username')
                ]);

                if ($depot -> save()){
                    return json(['code' => 200 , 'message' => '库存添加成功']);
                }else{
                    return json(['code' => 400 , 'message' => '库存添加失败']);
                }
            }

        }elseif(Request::isGet()){
            return view('depot/add',['title' => '添加库存']);
        }else{
            return $this -> error('访问错误');
        }
    }

    //删除操作
    public function Del(){
        if (Request::isPost()){
            $id = Request::param('id');
            $find = DepotModel::where('id',$id) -> find();
            if ($find -> delete()){
                return json(['code' => 200 , 'message' => '删除成功！']);
            }else{
                return json(['code' => 400 , 'message' => '删除失败！']);
            }
        }else{
            return $this -> error('访问错误！');
        }
    }
}