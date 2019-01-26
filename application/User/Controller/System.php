<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-01-02
 * Time: 19:47
 */

namespace app\User\Controller;

use app\User\Command\Base;
use app\User\Model\Role;
use app\User\Model\Rule;
use think\facade\Request;
use think\Validate;

class System extends Base
{
    protected $middleware = ['\app\http\middleware\Check'];

    public function UserList(){
        return view('system/user_list',['title' => '管理员列表']);
    }

    public function UserRule(){
        $list = Rule::select();
        return view('system/user_rule',['title' => '管理员权限' , 'list' => $list , 'count' => $list -> count()]);
    }

    public function UserInfo(){
        return view('system/user_info',['title' => '个人信息']);
    }

    public function Setting(){

    }

    public function UserRole(){
        return view('system/user_role',['title' => '角色管理']);
    }

    //权限添加操作
    public function AddRule(){
        if (Request::isPost()){
            if (!Request::param('role')){
                return json(['code' => 400 , 'message' => '权限必须选择']);
            }else{
                $data = [
                  'name' => Request::param('name'),
                  'title' => Request::param('title'),
                  'status' => Request::param('status')
                ];
                $message =[
                    'name.require' => '权限路径不能为空',
                    'title.require' => '权限名称不能为空',
                    'status.require' => '权限状态不能为空'
                ];
                $validate = Validate::make([
                    'name' => 'require',
                    'title' => 'require',
                    'status' => 'require'
                ],$message);
                if (!$validate ->check($data)){
                    return json(['code' => 400 , 'message' => $validate -> getError()]);
                }else{
                    $rule = new Rule([
                        'name' => $data['name'],
                        'title' => $data['title'],
                        'type' => 1,
                        'status' => $data['status']
                    ]);
                    if ($rule -> save()){
                        return json(['code' => 200 , 'message' => '添加权限成功']);
                    }else{
                        return json(['code' => 400 , 'message' => '添加权限失败']);
                    }
                }
            }
        }else{
            return view('system/rule_add',['title' => '添加权限']);
        }
    }

    //角色添加操作
    public function AddRole(){
        if (Request::isPost()){
            $data = Request::param();
            $data = [
                'roleName' => $data['roleName'],
                'text' => $data['text'],
                'role' => $data['role']
            ];
            $message = [
                'roleName.require' => '角色名不能为空',
                'role.require' => '角色权限必须选择'
            ];
            $validate = Validate::make([
                'roleName' => 'require',
                'role' => 'require'
            ],$message);

            if (!$validate ->check($data)){
                return json(['code' => 400 ,'message' => $validate -> getError()]);
            }else{

            }

        }else{
            $list = Rule::select();
            return view('system/role_add',['title' => '添加角色','list' => $list , 'count' => $list -> count()]);
        }

    }

}