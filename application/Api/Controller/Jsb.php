<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-02-05
 * Time: 20:12
 */

namespace app\Api\Controller;


use think\Controller;
use HttpClient;

class Jsb extends Controller
{
    public function test($tid){
        $client = new HttpClient('','');
        $client -> put('/JSB/rest/logistics/LogisticsDummySendRequest?tid='.$tid);
        return $client->status.$client->getContent();
    }
}