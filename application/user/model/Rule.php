<?php
/**
 * Created by PhpStorm.
 * User: Hades
 * Date: 2019/1/25
 * Time: 15:45
 */

namespace app\User\model;


use think\Model;

class Rule extends Model
{
    //绑定特定表
    protected $table = "auth_rule";

    public function getStatusAttr($value){
        $status = [
            '0' => '禁止',
            '1' => '正常'
        ];
        return $status[$value];
    }
}