<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-01-02
 * Time: 19:47
 */

namespace app\User\Controller;

use think\Controller;

class System extends Controller
{
    protected $middleware = ['\app\http\middleware\Auth'];

    public function User_List(){

    }

    public function User_Rule(){

    }

    public function User_Info(){

    }

}