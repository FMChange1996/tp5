<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-01-09
 * Time: 21:01
 */

namespace app\User\Model;

use think\Model;

class Orders extends Model
{
    public function getUrgentAttr($value){
        $urgent = [
            '0' => '正常',
            '1' => '加急'
        ];
        return $urgent[$value];
    }

    public function getStatusAttr($value){
        $status = [
            '0' => '未发货',
            '1' => '已发货'
        ];
        return $status[$value];
    }

}