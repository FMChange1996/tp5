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
        $client = new HttpClient('2b42ce7e77c18ff6f04c32c15325c14a6810dfc1166d1015d928d2fee82d05ec','218170bd06502f327545cc53680b71ba7629746e9aa1d1a7fa847c983db3a360');
        $client -> put('/JSB/rest/logistics/LogisticsDummySendRequest?tid='.$tid);
        return $client->status.$client->getContent();
    }
}