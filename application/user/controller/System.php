<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-01-02
 * Time: 19:47
 * 系统控制器
 */

namespace app\User\controller;

use app\index\model\Users;
use app\user\command\Base;
use app\user\model\Role;
use app\user\model\Rule;
use app\user\model\UserAccess;
use think\Exception;
use think\facade\Request;
use think\Validate;
use think\facade\Session;

class System extends Base
{
    protected $middleware = ['\app\http\middleware\Check'];

    public function index(){
        return $this -> error('Not Found');
    }

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

    //成员个人信息
    public function UserInfo(){
        $uid = Session::get('uid');
        $user = Users::where('id',$uid) -> select();
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
            $data = [
                'username' => Request::param('username'),
                'password' => Request::param('password'),
                'reastpw' => Request::param('reastpw'),
                'mobile' => Request::param('mobile'),
                'mail' => Request::param('mail'),
                'role' => Request::param('role'),
                'status' => Request::param('status')
            ];

            $message = [
                'username.require' => '用户名不能为空',
                'password.require' => '密码不能为空',
                'password.min' => '密码长度不能少于8位',
                'restpw.require' => '验证密码不能为空',
                'mobile.require' => '手机号不能为空',
                'mobile.min' => '手机号长度不符合规则',
                'mobile.max' => '手机号长度不符合规则',
                'mail.require' => '邮箱不能为空',
                'role.require' => '成员角色必须选择',
                'status.require' => '成员状态必须选择'
            ];

            $validate = Validate::make([
                'username' => 'require',
                'password' => 'require|min:8',
                'reastpw' => 'require',
                'mobile' => 'require|min:11|max:12',
                'mail' => 'require',
                'role' => 'require',
                'status' => 'require'
            ],$message);

            if ($data['password'] != $data['reastpw']){
                return json(['code' => 400 , 'message' => '两次输入密码不同']);
            }else{
                if (!$validate -> check($data)){
                    return json(['code' => 400 , 'message' => $validate -> getError()]);
                }else{
                    $user = new Users([
                        'username' => $data['username'],
                        'password' => hash_pbkdf2("sha256",Request::param('password'),"mukebuyi",2),
                        'mobile' => $data['mobile'],
                        'mail' => $data['mail'],
                        'status' => $data['status']
                    ]);
                    if ($user ->save()){
                        $find = Users::where('username',$data['username']) -> find();
                        $role = new UserAccess([
                            'uid' => $find['id'],
                            'group_id' => $data['role']
                        ]);
                        if ($role -> save()){
                            return json(['code' => 200 , 'message' => '创建成功']);
                        }else{
                            return json(['code' => 400 , 'message' => '成员已创建，角色权限创建失败']);
                        }
                    }else{
                        return json(['code' => 400 , 'message' => '成员添加失败']);
                    }
                }
            }

        }elseif (Request::isGet()){
            $role = Role::all();
            return view('system/add_admin',['title' => '添加成员', 'role' => $role]);
        }else{
            return $this -> error('访问失败');
        }
    }

    //更新成员信息
    public function UpdateInfo(){
        if (Request::isPost()){
            $password = hash_pbkdf2("sha256",Request::param('password'),"mukebuyi",2);
            $username = Request::param('username');
            $find = Users::where('username',$username) -> find();
            if ($password == $find['password']){
                $data = [
                    'mobile' => Request::param('mobile'),
                    'mail' => Request::param('mail')
                ];
                $message = [
                    'mobile.require' => '手机号不能为空',
                    'mobile.min' => '手机号格式不正确',
                    'mobile.max' => '手机号格式不正确',
                    'mail.require' => '邮箱账号不能为空',
                    'mail.email' => '邮箱账号格式不正确'
                ];

                $validate = Validate::make([
                    'mobile' => 'require|min:11|max:12',
                    'mail' => 'require|email'
                ],$message);

                if ($validate -> check($data)){
                    if ($validate -> getError() == null){
                        return json(['code' => 400 , 'message' => '出现未知错误！']);
                    }else{
                        return json(['code' => 400 , 'message' => $validate -> getError()]);
                    }
                }else{
                    $find -> mobile = $data['mobile'];
                    $find -> mail = $data['mail'];
                    if ($find -> save()){
                        return json(['code' => 200 , 'message' => '资料更新成功']);
                    }else{
                        return json(['code' => 400 , 'message' => '资料更新失败']);
                    }
                }

            }elseif ($password != $find['password']){
                $data = [
                    'username' => Request::param('username'),
                    'password' => Request::param('password'),
                    'mobile' => Request::param('mobile'),
                    'mail' => Request::param('mail'),
                ];

                $message = [
                    'username.require' => '用户名不能为空',
                    'password.require' => '密码不能为空',
                    'password.min' => '密码长度不能短于8位',
                    'mobile.require' => '手机号不能为空',
                    'mobile.min' => '手机号格式不正确',
                    'mobile.max' => '手机号格式不正确',
                    'mail.require' => '邮箱不能为空',
                    'mail.email' => '邮箱格式不正确'
                ];

                $validate = Validate::make([
                    'username' => 'require',
                    'password' => 'require|min:8',
                    'mobile' => 'require|min:11|max:12',
                    'mail' => 'require|email'
                ],$message);

                if (!$validate -> check($data)){
                    return json(['code' => 400 , 'message' => $validate -> getError()]);
                }else{
                    $find = Users::where('username',$data['username']) -> find();
                    $find -> password = hash_pbkdf2("sha256",Request::param('password'),"mukebuyi",2);
                    $find -> mobile = $data['mobile'];
                    $find -> mail = $data['mail'];
                    if ($find -> save()){
                        return json(['code' => 200 , 'message' => '资料更新成功']);
                    }else{
                        return json(['code' => 400 , 'message' => '资料更新失败']);
                    }
                }
            }else{
                return $this -> error('系统错误');
            }
        }else{
            return $this -> error('访问错误');
        }
    }

    //编辑成员信息
    public function EditUser(){
        if (Request::isPut()){
            if (!empty(Request::param('username'))){
                    $find = Users::where('username',Request::param('username')) -> find();
                    $find -> password = hash_pbkdf2("sha256",Request::param('password'),"mukebuyi",2);
                    $find -> mobile = Request::param('mobile');
                    $find -> mail = Request::param('mail');
                    if ($find -> save()){
                        $role = UserAccess::where('uid',$find['id']) ->find();
                        $role -> group_id = Request::param('role');
                        if ($role -> save()){
                            return json(['code' => 200 , 'message' => '更新成功！']);
                        }else{
                            return json(['code' => 400 , 'message' => '更新失败！']);
                        }
                    }else{
                        return json(['code' => 400 , 'message' => '更新失败！']);
                    }
            }else{
                return json(['code' => 400 , 'message' => '接受默认参数失败！']);
            }
        }elseif(Request::isGet()){
            $id = Request::param('id');
            if (!empty($id)){
                $vo = Users::where('id',$id) -> find();
                $role = Role::all();
                return view('system/edit_user',['title' => '编辑成员信息', 'vo' => $vo , 'role' => $role]);
            }else{
                return $this -> error('未接收到默认参数！');
            }
        }else{

        }
    }

    //改变成员状态
    public function ChangeUserStatus(){
        if (Request::isPut()){
            $data= Request::param();
            if (!empty($data['id'])){

                if ($data['id'] == 1){
                    return json(['code' => 1 , 'message' => '系统管理员不可停用']);
                }else{
                    $find = Users::where('id',$data['id']) -> find();
                    $find -> status = $data['status'];
                    if ($find ->save()){
                        return json(['code' => 0]);
                    }else{
                        return json(['code' => 1 , 'message' => '修改失败！']);
                    }
                }
            }else{
                return $this -> error("接受默认参数失败！");
            }
        }else{
            return $this -> error('该请求不被支持！');
        }
    }

    //删除成员
    public function DeleteUser(){
        if (Request::isDelete()){
            $id = Request::param('id');
            if (!empty($id)){
                if (Users::destroy($id)){
                    return json(['code' => 200 , 'message' => '删除成功！']);
                }else{
                    return json(['code' => 400 , 'message' => '删除失败！']);
                }
            }else{
                return $this -> error('系统错误！');
            }
        }else{
            return $this -> error('非法请求！');
        }
    }

    //编辑角色权限
    public function EditRole(){
        if (Request::isPut()){
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
                $role = Role::where('title',$data['roleName']) -> find();
                $role -> rules = implode(',',$data['role']);
                $role -> status = $data['status'];
                if ($role->save()){
                    return json(['code' => 200, 'message' => '角色权限修改成功！']);
                }else{
                    return json(['code' => 400, 'message' => '角色权限修改失败！']);
                }
            }

        }elseif (Request::isGet()){
            if (!empty(Request::param('id'))){
                $vo = Role::where('id',Request::param('id')) -> find();
                $list = Rule::select();
                return view('system/role_edit',['title' => '编辑角色','list' => $list , 'count' => $list -> count() ,'vo' => $vo]);
            }

        }else{
            return $this -> error('系统错误!');
        }
    }

    //删除角色
    public function DelRole(){
        if (Request::isDelete()){
            $id = Request::param('id');
            if (!empty($id)){
                $role = Role::where('id',$id);
                if ($role -> delete()){
                    return json(['code' => 200 , 'message' => '操作成功，角色已删除！']);
                }else{
                    return json(['code' => 400 , 'message' => '操作失败，角色未删除，请重试！']);
                }
            }else{
                return $this -> error('未接收到实际参数！');
            }
        }else{
            return $this -> error('系统错误！');
        }
    }

}