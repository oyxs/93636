<?php
require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");
?>
<!DOCTYPE HTML>
<html>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyReturn();
if($verify_result) {//验证成功

    $out_trade_no = $_GET['out_trade_no'];//商户订单号
    $trade_no = $_GET['trade_no'];//支付宝交易号
    $trade_status = $_GET['trade_status'];//交易状态
    $total_fee = $_GET['total_fee'];//交易金额
    $buyer_email = $_GET['buyer_email'];//买家支付帐号

    define('IN_PHPCMS', true);
    defined('IN_PHPCMS') or exit('No permission resources.');
    require_once('../phpcms/base.php');
    $mb_db = pc_base::load_model('mb_model');

    if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
        $data = $mb_db ->get_one(array('number'=>$out_trade_no));
        if(/*$data['pay']==$total_fee&&*/$data['pay_status']==0){
        $info = array(
            'pay_user'=>$buyer_email,
            'pay_time'=>time(),
            //'pay_status'=>1,
            'pay_num'=>$trade_no

        );
        $result=$mb_db->update($info,array('number'=>$out_trade_no));
        }
    }
    else {
      echo "trade_status=".$_GET['trade_status'];
    }

    header("Location: http://www.93636.com/index.php?m=pay&c=deposit&a=pay_mb&msg=succer");

}
else {
    echo "验证失败";
}
?>
        <title>支付宝即时到账交易接口</title>
	</head>
    <body>
    </body>
</html>