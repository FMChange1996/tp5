<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-01-02
 * Time: 19:47
 */

namespace app\User\Controller;

use app\Index\Model\Users;
use app\User\Command\Base;
use app\User\Model\Role;
use app\User\Model\Rule;
use think\facade\Request;
use think\Validate;

class System extends Base
{
    protected $middleware = ['\app\http\middleware\Check'];

    //管理员列表
    public function UserList(){
        $list = Users::paginate(10);
        return view('system/user_list',['title' => '管理员列表' , 'list' =>  $list , 'count' => $list -> count()]);
    }

    //权限管理
    public function UserRule(){
        $list = Rule::paginate(10);
        return view('system/user_rule',['title' => '管理员权限' , 'list' => $list ,  'count' => $list -> count()]);
    }

    public function UserInfo(){
        $user = Users::all();
        return view('system/user_info',['title' => '个人信息','user' => $user]);
    }

    //角色管理
    public function UserRole(){
        $list = Role::select();
        return view('system/user_role',['title' => '角色管理','list' => $list ,'count' => $list -> count()]);
    }

    //权限添加操作
    public function AddRule(){
        if (Request::isPost()){
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
        }else{
            return view('system/rule_add',['title' => '添加权限']);
        }
    }

    //角色添加操作
    public function AddRole(){
        if (Request::isPost()){
            if (!Request::param('role')){
                return json(['code' => 400, 'message' => '权限必须选择']);
            }else {
                $data = Request::param();
                $data = [
                    'roleName' => $data['roleName'],
                    'text' => $data['text'],
                    'status' => $data['status'],
                    'role' => $data['role']
                ];
                $message = [
                    'roleName.require' => '角色名不能为空',
                    'role.require' => '角色权限必须选择',
                    'status.require' => '状态必须选择'
                ];
                $validate = Validate::make([
                    'roleName' => 'require',
                    'role' => 'require',
                    'status' => 'require'
                ], $message);
                if (!$validate->check($data)) {
                    return json(['code' => 400, 'message' => $validate->getError()]);
                } else {
                    $role = new Role([
                        'title' => $data['roleName'],
                        'status' => $data['status'],
                        'rules' => implode(',',$data['role'])
                    ]);
                    if ($role->save()){
                        return json(['code' => 200, 'message' => '角色添加成功']);
                    }else{
                        return json(['code' => 400, 'message' => '角色添加失败']);
                    }
                }
            }
        }else{
            $list = Rule::select();
            return view('system/role_add',['title' => '添加角色','list' => $list , 'count' => $list -> count()]);
        }

    }

    //添加成员
    public function AddUser(){
        if (Request::isPost()){

        }elseif (Request::isGet()){
            $role = Role::all();
            return view('system/add_admin',['title' => '添加成员', 'role' => $role]);
        }else{
            return $this -> error('访问失败');
        }
    }

}