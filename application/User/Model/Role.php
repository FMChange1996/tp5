<?php
/**
 * Created by PhpStorm.
 * User: Hades
 * Date: 2019/1/26
 * Time: 15:21
 */

namespace app\User\Model;


use think\Model;

class Role extends Model
{
    //绑定特殊表
    protected $table = "auth_group";

    public function getStatusAttr($value){
        $status =[
            '0' => '禁用',
            '1' => '启用'
        ];
        return $status[$value];
    }
}