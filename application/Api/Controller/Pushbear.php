<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-01-12
 * Time: 22:11
 */

namespace app\Api\Controller;

use Seven;

class Pushbear
{
    public function test(){
        $seven = new Seven();
        $result = $seven -> SetTitle('test')
            -> SetMessage('1111')
            -> SetChannel('5579-bcaa5f5b575a556c408a199c88942d78')
            -> pushbear();
        return $result;
    }

}