<?php
if(isset($_POST['dosubmit'])){
    $out_trade_no=$_POST['number'];
    $subject = $_POST['trade_name'];
    $total_fee = $_POST['pay']*100;
    $body = $_POST['trade_description'];
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>微信扫码支付</title>
    <link href="css/pay.css" rel="stylesheet" type="text/css"/>
    <link href="css/sprite.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="js/jquery-2.1.0.min.js"></script>
    <script type="text/javascript" src="js/pay.js"></script>
</head>

<body>
<input type="hidden" value="<?php echo $out_trade_no;?>" id="number"/>
<input type="hidden" value="<?php echo $body;?>" id="body"/>
<input type="hidden" value="<?php echo $total_fee;?>" id="total_fee"/>
<div id="pay_platform">
    <div class="auto_center" id="auto_center"></div>
	</div>
</div>
</body>
</html>