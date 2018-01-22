<?php defined('IN_ADMIN') or exit('No permission resources.'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title>后台登录</title>
<!-- 全局强制性样式 -->
<link href="http://www.93636.com/statics/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="http://www.93636.com/statics/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
<link href="http://www.93636.com/statics/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="http://www.93636.com/statics/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<!-- 页面样式 -->
<link href="http://www.93636.com/statics/assets/admin/pages/css/login-soft.css" rel="stylesheet" type="text/css"/>
<!-- 主题风格 -->
<link href="http://www.93636.com/statics/assets/global/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
<link href="http://www.93636.com/statics/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="http://www.93636.com/statics/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
<link href="http://www.93636.com/statics/assets/admin/layout/css/themes/darkblue.css" rel="stylesheet" type="text/css" id="style_color"/>
<link href="http://www.93636.com/statics/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
<link rel="shortcut icon" href="http://www.93636.com/statics/assets/favicon.ico"/>


<script language="JavaScript">
<!--
	if(top!=self)
	if(self!=top) top.location=self.location;
//-->
</script>
</head>

<body onload="javascript:document.myform.username.focus();">
<body class="login">

<!-- LOGO -->
  <div class="logo">后台登录</div>
<!-- LOGO end -->

<!-- 登录框 -->
<div class="content">
	 <form action="index.php?m=admin&c=index&a=login&dosubmit=1" method="post" name="myform">
		<h3 class="form-title">用户登录</h3>
		<div class="alert alert-danger display-hide">
			<button class="close" data-close="alert"></button>
			<span>请输入用户名和密码。</span>
		</div>
		<div class="form-group">			
			<label class="control-label visible-ie8 visible-ie9"><?php echo L('username')?>：</label>
			<div class="input-icon">
				<i class="fa fa-user"></i>
				<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="用户名" name="username"/>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9"><?php echo L('password')?>：</label>
			<div class="input-icon">
				<i class="fa fa-lock"></i>
				<input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="密码" name="password"/>
			</div>
		</div>
        <div class="form-group" id="yzm">
			<label class="control-label visible-ie8 visible-ie9"><?php echo L('security_code')?></label>
			<div class="input-icon">
				<i class="fa fa-key"></i>
				<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="验证码" name="code"  onfocus="document.getElementById('yzm').style.display='block'">
			    <div id="yzm" class="yzm" style=" margin-top:15px;"><?php echo form::checkcode('code_img')?><br /><a href="javascript:document.getElementById('code_img').src='<?php echo SITE_PROTOCOL.SITE_URL.WEB_PATH;?>api.php?op=checkcode&m=admin&c=index&a=checkcode&time='+Math.random();void(0);"><?php echo L('click_change_validate')?></a></div>
			</div>
		</div>
		<div class="form-actions">
			<label class="checkbox">
			<!--<input type="checkbox" name="remember" value="1"/>记住用户名</label>-->
			<button type="submit" class="btn blue pull-right" name="dosubmit">登录 <i class="m-icon-swapright m-icon-white" ></i></button>
		</div>
	</form>
</div>
<!-- 登录框 end -->

<div class="copyright">93636手游网-版权所有 ©</div>

<!-- 核心插件 -->
<!--[if lt IE 9]>
<script src="assets/global/plugins/respond.min.js"></script>
<script src="assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<!-- 页面级脚本 -->
<script src="assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/select2/select2.min.js"type="text/javascript" ></script>
<script src="assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="assets/admin/pages/scripts/login-soft.js" type="text/javascript"></script>
<script>
jQuery(document).ready(function() {     
  Metronic.init(); // 初始化核心插件
  Layout.init(); // 初始化布局
  Login.init();
       $.backstretch([
        "assets/admin/pages/media/bg/1.jpg",
        "assets/admin/pages/media/bg/2.jpg",
        "assets/admin/pages/media/bg/3.jpg",
        "assets/admin/pages/media/bg/4.jpg"
        ], {
          fade: 1000,
          duration: 8000
    }
    );
});
</script>

</body>
</html>