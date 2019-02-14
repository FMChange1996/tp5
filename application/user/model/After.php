<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-02-14
 * Time: 15:26
 */

namespace app\user\model;


use think\Model;
use think\model\concern\SoftDelete;

class After extends Model
{
    use SoftDelete;

    public function getStatusAttr($value){
        $status = [
            '0' => '待处理',
            '1' => '处理中',
            '2' => '已完结'
        ];
        return $status[$value];
    }

}