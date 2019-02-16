<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-02-16
 * Time: 10:33
 */
namespace alipay;

require '../extend/alipay/pay/aop/AopClient.php';
require '../extend/alipay/pay/aop/request/AlipayFundTransToaccountTransferRequest.php';

use AopClient;
use AlipayFundTransToaccountTransferRequest;

class Alipay
{
    protected $aop;


    public function __construct(){
        $this -> aop = new AopClient();
        $this -> aop -> gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $this -> aop -> appId = "2019021463231497"; //  应用AppId
        $this -> aop -> apiVersion = '1.0';
        $this -> aop -> signType = 'RSA2';
        $this -> aop -> postCharset = 'UTF-8';
        $this -> aop -> format = 'json';
    }


    public function AlipayTransfer($account,$money){
        $order_id = date('YmdHis').rand(10000,99999);
        $request = new AlipayFundTransToaccountTransferRequest();
        $request -> setBizContent("{" .
            "\"out_biz_no\":\".$order_id.\"," .
            "\"payee_type\":\"ALIPAY_LOGONID\"," .
            "\"payee_account\":\".$account.\"," .
            "\"amount\":\".$money.\"" .
            "  }");
        $result = $this -> aop ->execute ($request);

        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        return  $result -> $responseNode -> sub_msg;
    }

}