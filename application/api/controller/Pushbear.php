<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-01-12
 * Time: 22:11
 */

namespace app\Api\controller;

use Seven;

class Pushbear
{
    public function test(){
        $seven = new Seven();
        $result = $seven -> SetTitle('test')
            -> SetMessage('1111')
            -> SetChannel('')
            -> pushbear();
        return $result;
    }

}