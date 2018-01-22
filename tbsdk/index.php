<?php

header("Content-Type:text/html;charset=UTF-8");

require_once 'util.php';

$appKey = '23668846';

$appSecret = '8893c85ff8d93bf31c059bd8a80d9687';

$sessionkey= '';

//参数数组

$paramArr = array(

    'app_key' => $appKey,

    'session_key' => $sessionkey,

    'method' => 'taobao.tbk.item.coupon.get',

    'format' => 'json',

    'v' => '2.0',

    'sign_method'=>'md5',

    'timestamp' => date('Y-m-d H:i:s'),

    'fields' => 'nick,type,user_id',

    'partner_id'=>'top-apitools',

    'pid'=>'mm_45083776_19440138_71658942',

     'force_sensitive_param_fuzzy' => 'true'

);

//生成签名

$sign = createSign($paramArr);

//组织参数

$strParam = createStrParam($paramArr);

$strParam .= 'sign='.$sign;

//访问服务

$url = 'http://gw.api.taobao.com/router/rest?'.$strParam; //沙箱环境调用地址

$result = file_get_contents($url);

$result = json_decode($result);

echo "json的结构为:";

print_r($result);

//echo "<br>";

//echo "用户名称为:".$result->user_get_response->user->nick;

//echo "<br>";

//echo "买家信用等级为:".$result->user_get_response->user->buyer_credit->level;

?>