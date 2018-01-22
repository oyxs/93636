<?php
require_once ("../phpcms/base.php");
require_once ("../phpcms/libs/functions/global.func.php");
pc_base::load_app_class('foreground');
$session_storage = 'session_'.pc_base::load_config('system','session_storage');
pc_base::load_sys_class($session_storage);
$alidayu_db = pc_base::load_model('alidayu_model');
session_start();
$ip=ip();
$last_day = strtotime("-1 day");
$count_num = $alidayu_db->count("`time` > $last_day&&`ip`='$ip'");

if($count_num>=2){
    $data = array(
        'msg' => "短信接口错误"
    );
    die(json_encode($data));
}
if (! empty($_POST['tell']) && preg_match('/^1([0-9]{10})/', $_POST['tell'])) {
    
    if ((empty($_SESSION['connectid']) && $_SESSION['code'] != strtolower($_POST['code']) && $_POST['code'] !== NULL) || empty($_SESSION['code'])) {
        $data = array(
            'msg' => "验证码错误"
        );
        die(json_encode($data));
    } else {
        $_SESSION['code'] = '';
    }
    
    
    $tell = $_POST['tell'];
    $number = random(4);
    header("Content-type: text/html; charset=utf-8");
    include "TopSdk.php";
    date_default_timezone_set('Asia/Shanghai');
    $c = new TopClient();
    $c->appkey = '23434168';
    $c->secretKey = 'a8022713bef89f023fa8e42589bed429';
    $req = new AlibabaAliqinFcSmsNumSendRequest();
    $req->setExtend("$tell");
    $req->setSmsType("normal");
    $req->setSmsFreeSignName("93636手游");
    $req->setSmsParam("{name:'$tell',number:'$number'}");
    $req->setRecNum("$tell");
    $req->setSmsTemplateCode("SMS_13052760");
    $resp = $c->execute($req);
    $static = $resp->result->err_code; // 0鎴愬姛
    
    if ($static == '0') { // 璁板綍鏁版嵁搴�

        $alidayu_db->insert(array(
            'tell' => $tell,
            'code' => $number,
            'time' => time(),
            'ip' => ip()
        ), true);
        $data = array('msg'=>'短信已发送');
        die(json_encode($data));
    }
}

?>