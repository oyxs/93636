<?php
include_once $_SERVER ['DOCUMENT_ROOT'] . '/UnionPay/sdk/acp_service.php';

?>
<!DOCTYPE unspecified PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<?php
	//计算得出通知验证结果
	if($_POST) {//验证成功

		$out_trade_no = $_POST['orderId'];//商户订单号
		$trade_no = "银联";//银联交易账号
		$trade_status = $_POST['respCode'];//交易状态
		$total_fee = $_POST['txnAmt']/100;//交易金额
		$buyer_email = "银联";//买家支付帐号
//		$out_trade_no = $_GET['out_trade_no'];//商户订单号
//		$trade_no = $_GET['trade_no'];//支付宝交易号
//		$trade_status = $_GET['trade_status'];//交易状态
//		$total_fee = $_GET['total_fee'];//交易金额
//		$buyer_email = $_GET['buyer_email'];//买家支付帐号

		define('IN_PHPCMS', true);
		defined('IN_PHPCMS') or exit('No permission resources.');
		require_once('../../../phpcms/base.php');
		$mb_db = pc_base::load_model('mb_model');

		if($_POST['respCode'] == '00' || $_POST['respCode'] == 'A6') {
			$data = $mb_db ->get_one(array('number'=>$out_trade_no));
//			if($data['pay']==$total_fee&&$data['pay_status']==0){
			if($data['pay_status']==0){
				$info = array(
					'pay_user'=>$buyer_email,
					'pay_time'=>time(),
					'pay_status'=>1,
					'pay_num'=>$trade_no

				);
				$result=$mb_db->update($info,array('number'=>$out_trade_no));
				if($data['ptb_status']==0){//更新admin.93636.com 平台币
					$str = "ss223SSM84LX5894opwxDxXAdmxeLaZVYww2009";
					$time=strtotime("+1 week 3 days 7 hours 5 seconds");
					$sign=md5(md5($str)."22dDKEZA".$time);
					$post_data = array(
						'username'=>$data['username'],
						'mb'=>$data['mb'],
						'pay'=>$data['pay'],
						'sign'=>$sign
					);
					$url = "http://admin.93636.com/admin.php/Api/Appapi/ptb_add.html";
					require_once("../../../phpcms/libs/functions/global.func.php");
					$post_result = send_post($url,$post_data);

					$status = array('ptb_status'=>1);
					$mb_db->update($status,array('number'=>$out_trade_no));

				}
			}
		}
		else {
			echo "trade_status=".$_GET['trade_status'];
		}

		header("Location:http://www.93636.com/index.php?m=pay&c=deposit&a=pay_mb&msg=succer");

	}
	else {
		echo "签名为空";
	}
	?>
<title>银联在线交易测试-结果</title>
</head>
<body>

</body>
</html>