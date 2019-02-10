<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2018-12-30
 * Time: 21:11
 */

namespace app\Index\Model;


use think\Model;

class Users extends Model
{
    public function getStatusAttr($value){
        $status = [
            '0' => '已启用',
            '1' => '已禁用'
        ];
        return $status[$value];
    }

}