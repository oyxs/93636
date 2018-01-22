<?php
include_once $_SERVER ['DOCUMENT_ROOT'] . '/UnionPay/sdk/acp_service.php';


include("../../../phpcms/base.php");
$mb_db = pc_base::load_model('mb_model');
if($verify_result) {//验证成功
//	$out_trade_no = $_POST['out_trade_no']; //支付宝交易号
//	$trade_no = $_POST['trade_no'];//交易状态
//	$trade_status = $_POST['trade_status'];
//	$total_fee = $_POST['total_fee'];//交易金额
//	$gmt_payment=$_POST['gmt_payment'];//交易时间
//	$buyer_email = $_POST['buyer_email'];//买家支付帐号

	$out_trade_no = $_POST['orderId'];//商户订单号
	$trade_no = "银联";//银联交易账号
	$trade_status = $_POST['respCode'];//交易状态
	$total_fee = $_POST['txnAmt']/100;//交易金额
	$buyer_email = "银联";//买家支付帐号

	if ($_POST['respCode'] == '00' || $_POST['respCode'] == 'A6') {//开通了高级即时到账或机票分销产品后的交易成功状态

		$data = $mb_db ->get_one(array('number'=>$out_trade_no));
		if($data['pay_status']==0){
			$info = array(
				'pay_user'=>$buyer_email,
				'pay_status'=>1,
				'pay_num'=>$trade_no
			);
			$result=$mb_db->update($info,array('number'=>$out_trade_no));//支付成功更新状态
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
		//判断该笔订单是否在商户网站中已经做过处理
		//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
		//请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
		//如果有做过处理，不执行商户的业务程序

		//注意：
		//付款完成后，支付宝系统发送该交易状态通知

		//调试用，写文本函数记录程序运行情况是否正常
		//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
	}

	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

	echo "fail";		//请不要修改或删除

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}

