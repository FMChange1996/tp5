<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/12
 * Time: 14:27
 */

namespace app\api\controller;


use think\Controller;
use think\facade\Request;

class Hai extends Controller
{
    public function index(){
        $parameter = Request::param('parameter');
        $client = new HttpClient();
        $client -> put('/rest/logistics/LogisticsOfflineSendRequest?'.$parameter);
        return $client -> getContent();
    }

}