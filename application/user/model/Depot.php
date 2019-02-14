<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-02-10
 * Time: 19:19
 */

namespace app\user\model;


use think\Model;
use think\model\concern\SoftDelete;

class Depot extends Model
{
    use SoftDelete;

    public function getMethodAttr($value){
        $method = [
            '0' => '挂钩加工',
            '1' => '打孔加工',
            '2' => '韩式加工',
            '3' => '其他'
        ];
        return $method[$value];
    }

}