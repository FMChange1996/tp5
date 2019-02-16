<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-02-14
 * Time: 13:07
 */

namespace app\user\model;


use think\Model;
use think\model\concern\SoftDelete;

class Payout extends Model
{
    use SoftDelete;

    public function getStatusAttr($value){
        $status = [
            '0' => '待审核',
            '1' => '待转账',
            '2' => '已完结'
        ];
        return $status[$value];
    }

}