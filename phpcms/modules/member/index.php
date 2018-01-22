<?php
/**
 * 会员前台管理中心、账号管理、收藏操作类
 */

defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('foreground');
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);

class index extends foreground {

	private $times_db;

	function __construct() {
		parent::__construct();
		$this->http_user_agent = $_SERVER['HTTP_USER_AGENT'];
	}

	public function init() {
		$memberinfo = $this->memberinfo;
		$this->detail_db = pc_base::load_model('member_detail_model');
	    $member_detail=$this->detail_db->get_one(array('userid'=>$memberinfo['userid']));
	    $member=$this->member_detail();
	    $new=$this->message_ts();
	    $paihang=$this->paihang();
		$grouplist = getcache('grouplist');
		$memberinfo['groupname'] = $grouplist[$memberinfo[groupid]]['name'];
		include template('member', 'index');
	}

	public function myfav() {
		$memberinfo = $this->memberinfo;

		//初始化phpsso
		$phpsso_api_url = $this->_init_phpsso();
		//获取头像数组
		$avatar = $this->client->ps_getavatar($this->memberinfo['phpssouid']);

		$grouplist = getcache('grouplist');
		$memberinfo['groupname'] = $grouplist[$memberinfo[groupid]]['name'];
		include template('member', 'myfav');
	}

	public function mydown() {
		$memberinfo = $this->memberinfo;

		//初始化phpsso
		$phpsso_api_url = $this->_init_phpsso();
		//获取头像数组
		$avatar = $this->client->ps_getavatar($this->memberinfo['phpssouid']);

		$grouplist = getcache('grouplist');
		$memberinfo['groupname'] = $grouplist[$memberinfo[groupid]]['name'];
		include template('member', 'mydown');
	}

	/*
     * 礼包注册ajax
     */
	public function register_new() {
		$this->_session_start();
        //获取用户siteid
		$siteid = isset($_REQUEST['siteid']) && trim($_REQUEST['siteid']) ? intval($_REQUEST['siteid']) : 1;
		//定义站点id常量
		if (!defined('SITEID')) {
			define('SITEID', $siteid);
		}
        //加载用户模块配置
		$member_setting = getcache('member_setting');
		if(!$member_setting['allowregister']) {
			$data=array('static'=>0,'msg'=>'禁止注册');
			die(json_encode($data));
		}
		//加载短信模块配置
		$sms_setting_arr = getcache('sms','sms');
		$sms_setting = $sms_setting_arr[$siteid];

		header("Cache-control: private");
		if(isset($_POST['dosubmit'])) {
            $url=$_SERVER['HTTP_REFERER'];
            if(empty($url)){
                exit;
            }
			/*if($member_setting['enablcodecheck']=='1'){//开启验证码
				if ((empty($_SESSION['connectid']) && $_SESSION['code'] != strtolower($_POST['code']) && $_POST['code']!==NULL) || empty($_SESSION['code'])) {
					$data=array('static'=>0,'msg'=>'验证码错误');
					die(json_encode($data));
				} else {
					$_SESSION['code'] = '';
				}
			}*/

			$userinfo = array();
			$userinfo['encrypt'] = create_randomstr(6);


			if(!empty($_POST['username'])&& is_username($_POST['username'])){
				$userinfo['username']=$_POST['username'];
			}else{
				$data=array('static'=>0,'msg'=>'用户名不允许为空或不合法');
				die(json_encode($data));
			}
			if(!empty($_POST['nickname'])&& is_username($_POST['nickname'])){
				$userinfo['nickname']=$_POST['nickname'];
			}else{
				$userinfo['nickname']='';
			}
			if(!empty($_POST['tell'])&& is_tell($_POST['tell'])){
				$userinfo['tell']=$_POST['tell'];
			}else{
				$data=array('static'=>0,'msg'=>'手机格式不合法');
				die(json_encode($data));
			}

			if(empty($_POST['m_code'])){
				$data=array('static'=>0,'msg'=>'请输入手机验证码');
				die(json_encode($data));
			}

			if(!empty($_POST['email'])&& is_email($_POST['email'])){
				$userinfo['email']=$_POST['email'];
			}else{
				$data=array('static'=>0,'msg'=>'邮箱格式不合法');
				die(json_encode($data));
			}
			if(!empty($_POST['password'])){
				$userinfo['password']=$_POST['password'];
			}else{
				$data=array('static'=>0,'msg'=>'密码不允许为空');
				die(json_encode($data));
			}

			$userinfo['modelid'] = isset($_POST['modelid']) ? intval($_POST['modelid']) : 10;
			$userinfo['regip'] = ip();
			$userinfo['origin']='new_register';
			/*
			 * 注册时间判断
			 */
			$member_db = pc_base::load_model('member_model');
			$get_date=$member_db->get_one(array('regip'=>$userinfo['regip']),'regdate','1','userid desc');
			if(!empty($get_date)) {
				$reg_time = time();
				if ($reg_time - $get_date['regdate'] < 300) {
					$data=array('static'=>0,'msg'=>'系统错误');
					die(json_encode($data));
				}
			}

			/*
			 *阿里大于手机验证
			 */
			$alidayu_db = pc_base::load_model('alidayu_model');
			$tell_code = $alidayu_db->get_one(array('tell'=>$userinfo['tell']),'tell,code','id desc');
			if(empty($_POST['m_code'])||intval($_POST['m_code'])!=$tell_code['code']){
				$data=array('static'=>0,'msg'=>'手机验证码错误');
				die(json_encode($data));
			}

			$get_tell = $member_db->get_one(array('tell'=>$userinfo['tell']),'tell');
			if(!empty($get_tell)){
				$data=array('static'=>0,'msg'=>'该手机已经被注册');
				die(json_encode($data));
			}

			$get_email = $member_db->get_one(array('email'=>$userinfo['email']),'email');
			if(!empty($get_email)){
				$data=array('static'=>0,'msg'=>'该邮箱已经被注册');
				die(json_encode($data));
			}



			$userinfo['point'] = $member_setting['defualtpoint'] ? $member_setting['defualtpoint'] : 0;
			$userinfo['amount'] = $member_setting['defualtamount'] ? $member_setting['defualtamount'] : 0;
			$userinfo['regdate'] = $userinfo['lastdate'] = SYS_TIME;
			$userinfo['siteid'] = $siteid;
			$userinfo['connectid'] = isset($_SESSION['connectid']) ? $_SESSION['connectid'] : '';
			$userinfo['from'] = isset($_SESSION['from']) ? $_SESSION['from'] : '';
			//手机强制验证

			if($member_setting[mobile_checktype]=='1'){
				//取用户手机号
				$mobile_verify = $_POST['mobile_verify'] ? intval($_POST['mobile_verify']) : '';
				if($mobile_verify==''){
					$data=array('static'=>0,'msg'=>'请提供正确的手机验证码');
					die(json_encode($data));
				}
				$sms_report_db = pc_base::load_model('sms_report_model');
				$posttime = SYS_TIME-360;
				$where = "`id_code`='$mobile_verify' AND `posttime`>'$posttime'";
				$r = $sms_report_db->get_one($where,'*','id DESC');
				if(!empty($r)){
					$userinfo['mobile'] = $r['mobile'];
				}else{

				}
			}elseif($member_setting[mobile_checktype]=='2'){
				//获取验证码，直接通过POST，取mobile值
				$userinfo['mobile'] = isset($_POST['mobile']) ? $_POST['mobile'] : '';
			}
			if($userinfo['mobile']!=""){
				if(!preg_match('/^1([0-9]{9})/',$userinfo['mobile'])) {
					$data=array('static'=>0,'msg'=>'请输入正确的手机号码');
					die(json_encode($data));
				}
			}
			$userinfo['gold'] = 5;
			$userinfo['integral'] = 5;
			unset($_SESSION['connectid'], $_SESSION['from']);

			if($member_setting['enablemailcheck']) {	//是否需要邮件验证
				$userinfo['groupid'] = 7;
			} elseif($member_setting['registerverify']) {	//是否需要管理员审核
				$modelinfo_str = $userinfo['modelinfo'] = isset($_POST['info']) ? array2string(array_map("safe_replace", new_html_special_chars($_POST['info']))) : '';
				$this->verify_db = pc_base::load_model('member_verify_model');
				unset($userinfo['lastdate'],$userinfo['connectid'],$userinfo['from']);
				$userinfo['modelinfo'] = $modelinfo_str;
				$this->verify_db->insert($userinfo);
				$data=array('static'=>0,'msg'=>'operation_success');
				die(json_encode($data));
			} else {
				//查看当前模型是否开启了短信验证功能
				$model_field_cache = getcache('model_field_'.$userinfo['modelid'],'model');
				if(isset($model_field_cache['mobile']) && $model_field_cache['mobile']['disabled']==0) {
					$mobile = $_POST['info']['mobile'];
					if(!preg_match('/^1([0-9]{10})/',$mobile)){
						$data=array('static'=>0,'msg'=>'input_right_mobile');
						die(json_encode($data));

					}

					$sms_report_db = pc_base::load_model('sms_report_model');
					$posttime = SYS_TIME-300;
					$where = "`mobile`='$mobile' AND `posttime`>'$posttime'";
					$r = $sms_report_db->get_one($where);
					if(!$r || $r['id_code']!=$_POST['mobile_verify']) {
						$data=array('static'=>0,'msg'=>'短信验证错误');
						die(json_encode($data));
					}
				}
				$userinfo['groupid'] = $this->_get_usergroup_bypoint($userinfo['point']);
			}

			if(pc_base::load_config('system', 'phpsso')) {
				$this->_init_phpsso();
				$status = $this->client->ps_member_register($userinfo['username'], $userinfo['password'], $userinfo['email'], $userinfo['regip'], $userinfo['encrypt']);
				if($status > 0) {
					$userinfo['phpssouid'] = $status;
					//传入phpsso为明文密码，加密后存入phpcms_v9
					$password = $userinfo['password'];
					$userinfo['password'] = password($userinfo['password'], $userinfo['encrypt']);
					$userid = $this->db->insert($userinfo, 1);
					if($member_setting['choosemodel']) {	//如果开启选择模型
						//通过模型获取会员信息
						require_once CACHE_MODEL_PATH.'member_input.class.php';
						require_once CACHE_MODEL_PATH.'member_update.class.php';
						$member_input = new member_input($userinfo['modelid']);
                        //print_r($_POST['info']);exit();
						//$_POST['info'] = array_map('new_html_special_chars',$_POST['info']);
						$user_model_info = $member_input->get($_POST['info']);
						$user_model_info['userid'] = $userid;

						//插入会员模型数据
						$this->db->set_model($userinfo['modelid']);
						$this->db->insert($user_model_info);
					}

					if($userid > 0) {
						//执行登陆操作
						if(!$cookietime) $get_cookietime = param::get_cookie('cookietime');
						$_cookietime = $cookietime ? intval($cookietime) : ($get_cookietime ? $get_cookietime : 0);
						$cookietime = $_cookietime ? TIME + $_cookietime : 0;

						if($userinfo['groupid'] == 7) {
							param::set_cookie('_username', $userinfo['username'], $cookietime);
							param::set_cookie('email', $userinfo['email'], $cookietime);
						} else {
							$phpcms_auth_key = md5(pc_base::load_config('system', 'auth_key').$this->http_user_agent);
							$phpcms_auth = sys_auth($userid."\t".$userinfo['password'], 'ENCODE', $phpcms_auth_key);

							param::set_cookie('auth', $phpcms_auth, $cookietime);
							param::set_cookie('_userid', $userid, $cookietime);
							param::set_cookie('_username', $userinfo['username'], $cookietime);
							param::set_cookie('_nickname', $userinfo['nickname'], $cookietime);
							param::set_cookie('_groupid', $userinfo['groupid'], $cookietime);
							param::set_cookie('cookietime', $_cookietime, $cookietime);

							$s = $_SERVER['SERVER_PORT'] == '443' ? 1 : 0;
							setcookie("nickname",$nickname, $cookietime, pc_base::load_config('system','cookie_path'), pc_base::load_config('system','cookie_domain'), $s);

						}
					}
					//如果需要邮箱认证
					if($member_setting['enablemailcheck']) {
						pc_base::load_sys_func('mail');
						$phpcms_auth_key = md5(pc_base::load_config('system', 'auth_key'));
						$code = sys_auth($userid.'|'.SYS_TIME, 'ENCODE', $phpcms_auth_key);
						$url = APP_PATH."index.php?m=member&c=index&a=register&code=$code&verify=1";
						$message = $member_setting['registerverifymessage'];
						$message = str_replace(array('{click}','{url}','{username}','{email}','{password}'), array('<a href="'.$url.'">'.L('please_click').'</a>',$url,$userinfo['username'],$userinfo['email'],$password), $message);
						sendmail($userinfo['email'], L('reg_verify_email'), $message);
						//设置当前注册账号COOKIE，为第二步重发邮件所用
						param::set_cookie('_regusername', $userinfo['username'], $cookietime);
						param::set_cookie('_reguserid', $userid, $cookietime);
						param::set_cookie('_reguseruid', $userinfo['phpssouid'], $cookietime);
						$data=array('static'=>1,'msg'=>'注册成功,请登录邮箱激活!');
						die(json_encode($data));
					} else {
						//如果不需要邮箱认证、直接登录其他应用
						$synloginstr = $this->client->ps_member_synlogin($userinfo['phpssouid']);
						if(empty($forward)){
							$forward = isset($_POST['forward']) && !empty($_POST['forward']) ? $_POST['forward'] : '/';
						}else{
							$forward = isset($_POST['forward']) && trim($_POST['forward']) ? urlencode($_POST['forward']) : '/';
						}
						$data=array('static'=>1,'msg'=>'注册成功');
						die(json_encode($data));
					}

				}
			} else {

				$data=array('static'=>0,'msg'=>'enable_register');
				die(json_encode($data));
			}
			$data=array('static'=>0,'msg'=>'enable_register');
			die(json_encode($data));
		} else {
			if(!pc_base::load_config('system', 'phpsso')) {

				$data=array('static'=>0,'msg'=>'enable_register');
				die(json_encode($data));
			}

			if(!empty($_GET['verify'])) {


				if(isset($_GET['code'])){
					$code=trim($_GET['code']);
				}else{
					$data=array('static'=>0,'msg'=>'operation_failure');
					die(json_encode($data));
				}

				$phpcms_auth_key = md5(pc_base::load_config('system', 'auth_key'));
				$code_res = sys_auth($code, 'DECODE', $phpcms_auth_key);
				$code_arr = explode('|', $code_res);
				$userid = isset($code_arr[0]) ? $code_arr[0] : '';
				if(is_numeric($userid)){
					$userid=$userid;
				}else{
					$data=array('static'=>0,'msg'=>'operation_failure');
					die(json_encode($data));
				}
				$this->db->update(array('groupid'=>$this->_get_usergroup_bypoint()), array('userid'=>$userid));

				$data=array('static'=>1,'msg'=>'operation_success');
				die(json_encode($data));
			} elseif(!empty($_GET['protocol'])) {

				include template('member', 'protocol');
			} else {
				//过滤非当前站点会员模型
				$modellist = getcache('member_model', 'commons');
				foreach($modellist as $k=>$v) {
					if($v['siteid']!=$siteid || $v['disabled']) {
						unset($modellist[$k]);
					}
				}
				if(empty($modellist)) {
					// showmessage(L('site_have_no_model').L('deny_register'), HTTP_REFERER);
					$data=array('static'=>0,'msg'=>'site_have_no_model');
					die(json_encode($data));
				}
				//是否开启选择会员模型选项
				if($member_setting['choosemodel']) {
					$first_model = array_pop(array_reverse($modellist));
					$modelid = isset($_GET['modelid']) && in_array($_GET['modelid'], array_keys($modellist)) ? intval($_GET['modelid']) : $first_model['modelid'];

					if(array_key_exists($modelid, $modellist)) {
						//获取会员模型表单
						require CACHE_MODEL_PATH.'member_form.class.php';
						$member_form = new member_form($modelid);
						$this->db->set_model($modelid);
						$forminfos = $forminfos_arr = $member_form->get();

						//万能字段过滤
						foreach($forminfos as $field=>$info) {
							if($info['isomnipotent']) {
								unset($forminfos[$field]);
							} else {
								if($info['formtype']=='omnipotent') {
									foreach($forminfos_arr as $_fm=>$_fm_value) {
										if($_fm_value['isomnipotent']) {
											$info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'], $info['form']);
										}
									}
									$forminfos[$field]['form'] = $info['form'];
								}
							}
						}

						$formValidator = $member_form->formValidator;
					}
				}



			}
		}
	}

	public function register() {
	    
        $this->_session_start();
		$siteid = isset($_REQUEST['siteid']) && trim($_REQUEST['siteid']) ? intval($_REQUEST['siteid']) : 1;
		//定义站点id常量
		if (!defined('SITEID')) {
			define('SITEID', $siteid);
		}
		$member_setting = getcache('member_setting');
		if(!$member_setting['allowregister']) {
			showmessage(L('网站升级暂停注册！'),'index.php','5000');
		}
        if(isset($_GET['t'])&&$_GET['t']==2){
            showmessage(L('验证邮件已经发送到您的邮箱，请登录邮箱进行验证'),'index.php?m=member&c=index&a=login','5000');
        }

		//加载用户模块配置
		$member_setting = getcache('member_setting');
		if(!$member_setting['allowregister']) {
			showmessage(L('deny_register'), 'index.php?m=member&c=index&a=login');
		}
		//加载短信模块配置
		$sms_setting_arr = getcache('sms','sms');
		$sms_setting = $sms_setting_arr[$siteid];

		header("Cache-control: private");
		if(isset($_POST['dosubmit'])) {

            $url=$_SERVER['HTTP_REFERER'];
			if($url!='http://www.93636.com/index.php?m=member&c=index&a=register'){
                exit;
            }
			/*if($member_setting['enablcodecheck']=='1'){//开启验证码
				if ((empty($_SESSION['connectid']) && $_SESSION['code'] != strtolower($_POST['code']) && $_POST['code']!==NULL) || empty($_SESSION['code'])) {
					showmessage(L('code_error'));
				} else {
					$_SESSION['code'] = '';
				}
			}*/



			/////////////////////////////////
			$userinfo = array();
			$userinfo['encrypt'] = create_randomstr(6);

			$userinfo['username'] = (isset($_POST['useername']) && is_username($_POST['useername'])) ? $_POST['useername'] : showmessage('请输入正确的用户名', HTTP_REFERER);
			$userinfo['nickname'] = (isset($_POST['nickname']) && is_username($_POST['nickname'])) ? $_POST['nickname'] : '';

			$userinfo['email'] = (isset($_POST['emaill']) && is_email($_POST['emaill'])) ? $_POST['emaill'] : showmessage('请输入正确的邮箱', HTTP_REFERER);
			$userinfo['password'] = (isset($_POST['passsword']) && is_badword($_POST['passsword'])==false) ? $_POST['passsword'] : showmessage('请输入密码', HTTP_REFERER);
			$userinfo['modelid'] = isset($_POST['modelid']) ? intval($_POST['modelid']) : 10;
			$userinfo['regip'] = ip();
			$userinfo['origin']='register';
			$userinfo['tell'] = (isset($_POST['tell']) && is_tell($_POST['tell'])) ? $_POST['tell'] : showmessage('请输入正确的手机号', HTTP_REFERER);



			/*
			 * 注册时间判断
			 */
			$member_db = pc_base::load_model('member_model');
			$get_date=$member_db->get_one(array('regip'=>$userinfo['regip']),'regdate','1','userid desc');
			if(!empty($get_date)) {
				$reg_time = time();
				if ($reg_time - $get_date['regdate'] < 300) {
					showmessage('注册太频繁', HTTP_REFERER);
				}
			}

			/*
			 *阿里大于手机验证
			 */
			$alidayu_db = pc_base::load_model('alidayu_model');
			$tell_code = $alidayu_db->get_one(array('tell'=>$userinfo['tell']),'tell,code','id desc');
			if(empty($_POST['m_code'])||intval($_POST['m_code'])!=$tell_code['code']){
				showmessage('手机验证码错误', HTTP_REFERER);
			}

			$get_tell = $member_db->get_one(array('tell'=>$userinfo['tell']),'tell');
			if(!empty($get_tell)){
				showmessage('手机号已被注册！', HTTP_REFERER);
			}

			$userinfo['point'] = $member_setting['defualtpoint'] ? $member_setting['defualtpoint'] : 0;
			$userinfo['amount'] = $member_setting['defualtamount'] ? $member_setting['defualtamount'] : 0;
			$userinfo['regdate'] = $userinfo['lastdate'] = SYS_TIME;
			$userinfo['siteid'] = $siteid;
			$userinfo['connectid'] = isset($_SESSION['connectid']) ? $_SESSION['connectid'] : '';
			$userinfo['from'] = isset($_SESSION['from']) ? $_SESSION['from'] : '';
			//手机强制验证

			/*if($member_setting[mobile_checktype]=='1'){
				//取用户手机号
				$mobile_verify = $_POST['mobile_verify'] ? intval($_POST['mobile_verify']) : '';
				if($mobile_verify=='') showmessage('请提供正确的手机验证码！', HTTP_REFERER);
				$sms_report_db = pc_base::load_model('sms_report_model');
				$posttime = SYS_TIME-360;
				$where = "`id_code`='$mobile_verify' AND `posttime`>'$posttime'";
				$r = $sms_report_db->get_one($where,'*','id DESC');
				if(!empty($r)){
					$userinfo['mobile'] = $r['mobile'];
				}else{
					showmessage('未检测到正确的手机号码！', HTTP_REFERER);
				}
			}elseif($member_setting[mobile_checktype]=='2'){
				//获取验证码，直接通过POST，取mobile值
				$userinfo['mobile'] = isset($_POST['mobile']) ? $_POST['mobile'] : '';
			}
			if($userinfo['mobile']!=""){
				if(!preg_match('/^1([0-9]{9})/',$userinfo['mobile'])) {
					showmessage('请提供正确的手机号码！', HTTP_REFERER);
				}
			}*/
			$userinfo['gold']='5';
			$userinfo['integral']='5';
			unset($_SESSION['connectid'], $_SESSION['from']);

			if($member_setting['enablemailcheck']) {	//是否需要邮件验证
				$userinfo['groupid'] = 7;
			} elseif($member_setting['registerverify']) {	//是否需要管理员审核
				$modelinfo_str = $userinfo['modelinfo'] = isset($_POST['info']) ? array2string(array_map("safe_replace", new_html_special_chars($_POST['info']))) : '';
				$this->verify_db = pc_base::load_model('member_verify_model');
				unset($userinfo['lastdate'],$userinfo['connectid'],$userinfo['from']);
				$userinfo['modelinfo'] = $modelinfo_str;
				$this->verify_db->insert($userinfo);
				showmessage(L('operation_success'), 'index.php?m=member&c=index&a=register&t=3');
			} else {
				//查看当前模型是否开启了短信验证功能
				$model_field_cache = getcache('model_field_'.$userinfo['modelid'],'model');
				if(isset($model_field_cache['mobile']) && $model_field_cache['mobile']['disabled']==0) {
					$mobile = $_POST['info']['mobile'];
					if(!preg_match('/^1([0-9]{10})/',$mobile)) showmessage(L('input_right_mobile'));
					$sms_report_db = pc_base::load_model('sms_report_model');
					$posttime = SYS_TIME-300;
					$where = "`mobile`='$mobile' AND `posttime`>'$posttime'";
					$r = $sms_report_db->get_one($where);
					if(!$r || $r['id_code']!=$_POST['mobile_verify']) showmessage(L('error_sms_code'));
				}
				$userinfo['groupid'] = $this->_get_usergroup_bypoint($userinfo['point']);
			}

			if(pc_base::load_config('system', 'phpsso')) {
				$this->_init_phpsso();
				$status = $this->client->ps_member_register($userinfo['username'], $userinfo['password'], $userinfo['email'], $userinfo['regip'], $userinfo['encrypt']);
				if($status > 0) {
					$userinfo['phpssouid'] = $status;
					//传入phpsso为明文密码，加密后存入phpcms_v9
					$password = $userinfo['password'];
					$userinfo['password'] = password($userinfo['password'], $userinfo['encrypt']);
					$userid = $this->db->insert($userinfo, 1);
					if($member_setting['choosemodel']) {	//如果开启选择模型
						//通过模型获取会员信息					
						require_once CACHE_MODEL_PATH.'member_input.class.php';
						require_once CACHE_MODEL_PATH.'member_update.class.php';
						$member_input = new member_input($userinfo['modelid']);

						$_POST['info'] = array_map('new_html_special_chars',$_POST['info']);
						$user_model_info = $member_input->get($_POST['info']);
						$user_model_info['userid'] = $userid;

						//插入会员模型数据
						$this->db->set_model($userinfo['modelid']);
						$this->db->insert($user_model_info);
					}

					if($userid > 0) {
						//执行登陆操作
						if(!$cookietime) $get_cookietime = param::get_cookie('cookietime');
						$_cookietime = $cookietime ? intval($cookietime) : ($get_cookietime ? $get_cookietime : 0);
						$cookietime = $_cookietime ? TIME + $_cookietime : 0;

						if($userinfo['groupid'] == 7) {
							param::set_cookie('_username', $userinfo['username'], $cookietime);
							param::set_cookie('email', $userinfo['email'], $cookietime);
						} else {
							$phpcms_auth_key = md5(pc_base::load_config('system', 'auth_key').$this->http_user_agent);
							$phpcms_auth = sys_auth($userid."\t".$userinfo['password'], 'ENCODE', $phpcms_auth_key);

							param::set_cookie('auth', $phpcms_auth, $cookietime);
							param::set_cookie('_userid', $userid, $cookietime);
							param::set_cookie('_username', $userinfo['username'], $cookietime);
							param::set_cookie('_nickname', $userinfo['nickname'], $cookietime);
							param::set_cookie('_groupid', $userinfo['groupid'], $cookietime);
							param::set_cookie('cookietime', $_cookietime, $cookietime);

							$s = $_SERVER['SERVER_PORT'] == '443' ? 1 : 0;
							setcookie("nickname",$nickname, $cookietime, pc_base::load_config('system','cookie_path'), pc_base::load_config('system','cookie_domain'), $s);
                        }
					}
					//如果需要邮箱认证
					if($member_setting['enablemailcheck']) {
						pc_base::load_sys_func('mail');
						$phpcms_auth_key = md5(pc_base::load_config('system', 'auth_key'));
						$code = sys_auth($userid.'|'.SYS_TIME, 'ENCODE', $phpcms_auth_key);
						$url = APP_PATH."index.php?m=member&c=index&a=register&code=$code&verify=1";
						$message = $member_setting['registerverifymessage'];
						$message = str_replace(array('{click}','{url}','{username}','{email}','{password}'), array('<a href="'.$url.'">'.L('please_click').'</a>',$url,$userinfo['username'],$userinfo['email'],$password), $message);
						sendmail($userinfo['email'], L('reg_verify_email'), $message);
						//设置当前注册账号COOKIE，为第二步重发邮件所用
						param::set_cookie('_regusername', $userinfo['username'], $cookietime);
						param::set_cookie('_reguserid', $userid, $cookietime);
						param::set_cookie('_reguseruid', $userinfo['phpssouid'], $cookietime);
						showmessage(L('operation_success'), 'index.php?m=member&c=index&a=register&t=2');
					} else {
						//如果不需要邮箱认证、直接登录其他应用
						$synloginstr = $this->client->ps_member_synlogin($userinfo['phpssouid']);
						if(empty($forward)){
							$forward = isset($_POST['forward']) && !empty($_POST['forward']) ? $_POST['forward'] : '/';
						}else{
							$forward = isset($_POST['forward']) && trim($_POST['forward']) ? urlencode($_POST['forward']) : '/';
						}
						showmessage(L('operation_success').$synloginstr, 'index.php?m=member&c=index&a=init');
					}

				}
			} else {
				showmessage(L('enable_register').L('enable_phpsso'), 'index.php?m=member&c=index&a=login');
			}
			showmessage(L('operation_failure'), HTTP_REFERER);
		} else {
			if(!pc_base::load_config('system', 'phpsso')) {
				showmessage(L('enable_register').L('enable_phpsso'), 'index.php?m=member&c=index&a=login');
			}

			if(!empty($_GET['verify'])) {
				$code = isset($_GET['code']) ? trim($_GET['code']) : showmessage(L('operation_failure'), 'index.php?m=member&c=index');
				$phpcms_auth_key = md5(pc_base::load_config('system', 'auth_key'));
				$code_res = sys_auth($code, 'DECODE', $phpcms_auth_key);
				$code_arr = explode('|', $code_res);
				$userid = isset($code_arr[0]) ? $code_arr[0] : '';
				$userid = is_numeric($userid) ? $userid : showmessage(L('operation_failure'), 'index.php?m=member&c=index');

				$this->db->update(array('groupid'=>$this->_get_usergroup_bypoint()), array('userid'=>$userid));
				showmessage(L('operation_success'), 'index.php?m=member&c=index');
			} elseif(!empty($_GET['protocol'])) {

				include template('member', 'protocol');
			} else {
				//过滤非当前站点会员模型
				$modellist = getcache('member_model', 'commons');
				foreach($modellist as $k=>$v) {
					if($v['siteid']!=$siteid || $v['disabled']) {
						unset($modellist[$k]);
					}
				}
				if(empty($modellist)) {
					showmessage(L('site_have_no_model').L('deny_register'), HTTP_REFERER);
				}
				//是否开启选择会员模型选项
				if($member_setting['choosemodel']) {
					$first_model = array_pop(array_reverse($modellist));
					$modelid = isset($_GET['modelid']) && in_array($_GET['modelid'], array_keys($modellist)) ? intval($_GET['modelid']) : $first_model['modelid'];

					if(array_key_exists($modelid, $modellist)) {
						//获取会员模型表单
						require CACHE_MODEL_PATH.'member_form.class.php';
						$member_form = new member_form($modelid);
						$this->db->set_model($modelid);
						$forminfos = $forminfos_arr = $member_form->get();

						//万能字段过滤
						foreach($forminfos as $field=>$info) {
							if($info['isomnipotent']) {
								unset($forminfos[$field]);
							} else {
								if($info['formtype']=='omnipotent') {
									foreach($forminfos_arr as $_fm=>$_fm_value) {
										if($_fm_value['isomnipotent']) {
											$info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'], $info['form']);
										}
									}
									$forminfos[$field]['form'] = $info['form'];
								}
							}
						}

						$formValidator = $member_form->formValidator;
					}
				}
				$description = $modellist[$modelid]['description'];

				include template('member', 'register');
			}
		}
	}


	/*
	 * 测试邮件配置
	 */
	public function send_newmail() {
		$_username = param::get_cookie('_regusername');
		$_userid = param::get_cookie('_reguserid');
		$_ssouid = param::get_cookie('_reguseruid');
		$newemail = $_GET['newemail'];

		if($newemail==''){//邮箱为空，直接返回错误
			return '2';
		}
		$this->_init_phpsso();
		$status = $this->client->ps_checkemail($newemail);
		if($status=='-5'){//邮箱被占用
			exit('-1');
		}
		if ($status==-1) {
			$status = $this->client->ps_get_member_info($newemail, 3);
			if($status) {
				$status = unserialize($status);	//接口返回序列化，进行判断
				if (!isset($status['uid']) || $status['uid'] != intval($_ssouid)) {
					exit('-1');
				}
			} else {
				exit('-1');
			}
		}
		//验证邮箱格式
		pc_base::load_sys_func('mail');
		$phpcms_auth_key = md5(pc_base::load_config('system', 'auth_key'));
		$code = sys_auth($userid.'|'.SYS_TIME, 'ENCODE', $phpcms_auth_key);
		$url = APP_PATH."index.php?m=member&c=index&a=register&code=$code&verify=1";

		//读取配置获取验证信息
		$member_setting = getcache('member_setting');
		$message = $member_setting['registerverifymessage'];
		$message = str_replace(array('{click}','{url}','{username}','{email}','{password}'), array('<a href="'.$url.'">'.L('please_click').'</a>',$url,$_username,$newemail,$password), $message);

		if(sendmail($newemail, L('reg_verify_email'), $message)){
			//更新新的邮箱，用来验证
			$this->db->update(array('email'=>$newemail), array('userid'=>$_userid));
			$this->client->ps_member_edit($_username, $newemail, '', '', $_ssouid);
			$return = '1';
		}else{
			$return = '2';
		}
		echo $return;
	}

	public function account_manage() {
		$memberinfo = $this->memberinfo;
		//初始化phpsso
		$phpsso_api_url = $this->_init_phpsso();
		//获取头像数组
		$avatar = $this->client->ps_getavatar($this->memberinfo['phpssouid']);

		$grouplist = getcache('grouplist');
		$member_model = getcache('member_model', 'commons');

		//获取用户模型数据
		$this->db->set_model($this->memberinfo['modelid']);
		$member_modelinfo_arr = $this->db->get_one(array('userid'=>$this->memberinfo['userid']));
		$model_info = getcache('model_field_'.$this->memberinfo['modelid'], 'model');
		foreach($model_info as $k=>$v) {
			if($v['formtype'] == 'omnipotent') continue;
			if($v['formtype'] == 'image') {
				$member_modelinfo[$v['name']] = "<a href='$member_modelinfo_arr[$k]' target='_blank'><img src='$member_modelinfo_arr[$k]' height='40' widht='40' onerror=\"this.src='$phpsso_api_url/statics/images/member/nophoto.gif'\"></a>";
			} elseif($v['formtype'] == 'datetime' && $v['fieldtype'] == 'int') {	//如果为日期字段
				$member_modelinfo[$v['name']] = format::date($member_modelinfo_arr[$k], $v['format'] == 'Y-m-d H:i:s' ? 1 : 0);
			} elseif($v['formtype'] == 'images') {
				$tmp = string2array($member_modelinfo_arr[$k]);
				$member_modelinfo[$v['name']] = '';
				if(is_array($tmp)) {
					foreach ($tmp as $tv) {
						$member_modelinfo[$v['name']] .= " <a href='$tv[url]' target='_blank'><img src='$tv[url]' height='40' widht='40' onerror=\"this.src='$phpsso_api_url/statics/images/member/nophoto.gif'\"></a>";
					}
					unset($tmp);
				}
			} elseif($v['formtype'] == 'box') {	//box字段，获取字段名称和值的数组
				$tmp = explode("\n",$v['options']);
				if(is_array($tmp)) {
					foreach($tmp as $boxv) {
						$box_tmp_arr = explode('|', trim($boxv));
						if(is_array($box_tmp_arr) && isset($box_tmp_arr[1]) && isset($box_tmp_arr[0])) {
							$box_tmp[$box_tmp_arr[1]] = $box_tmp_arr[0];
							$tmp_key = intval($member_modelinfo_arr[$k]);
						}
					}
				}
				if(isset($box_tmp[$tmp_key])) {
					$member_modelinfo[$v['name']] = $box_tmp[$tmp_key];
				} else {
					$member_modelinfo[$v['name']] = $member_modelinfo_arr[$k];
				}
				unset($tmp, $tmp_key, $box_tmp, $box_tmp_arr);
			} elseif($v['formtype'] == 'linkage') {	//如果为联动菜单
				$tmp = string2array($v['setting']);
				$tmpid = $tmp['linkageid'];
				$linkagelist = getcache($tmpid, 'linkage');
				$fullname = $this->_get_linkage_fullname($member_modelinfo_arr[$k], $linkagelist);

				$member_modelinfo[$v['name']] = substr($fullname, 0, -1);
				unset($tmp, $tmpid, $linkagelist, $fullname);
			} else {
				$member_modelinfo[$v['name']] = $member_modelinfo_arr[$k];
			}
		}

		include template('member', 'account_manage');
	}

	public function account_manage_avatar() {
		$memberinfo = $this->memberinfo;
		//初始化phpsso
		$phpsso_api_url = $this->_init_phpsso();
		$ps_auth_key = pc_base::load_config('system', 'phpsso_auth_key');
		$auth_data = $this->client->auth_data(array('uid'=>$this->memberinfo['phpssouid'], 'ps_auth_key'=>$ps_auth_key), '', $ps_auth_key);
		$upurl = base64_encode($phpsso_api_url.'/index.php?m=phpsso&c=index&a=uploadavatar&auth_data='.$auth_data);
		//获取头像数组
		$member=$this->member_detail();
		$paihang=$this->paihang();
		$new=$this->message_ts();
		$avatar = $this->client->ps_getavatar($this->memberinfo['phpssouid']);
		if($avatar){
		    $img=$avatar['180'];
		}
		include template('member', 'account_manage_avatar');
	}

	public function account_manage_security() {
		$memberinfo = $this->memberinfo;
		include template('member', 'account_manage_security');
	}

	public function account_manage_info() {
		if(isset($_POST['dosubmit'])) {
			//更新用户昵称
			$nickname = isset($_POST['nickname']) && is_username(trim($_POST['nickname'])) ? trim($_POST['nickname']) : '';
			$nickname = safe_replace($nickname);
			if($nickname) {
				$this->db->update(array('nickname'=>$nickname), array('userid'=>$this->memberinfo['userid']));
				if(!isset($cookietime)) {
					$get_cookietime = param::get_cookie('cookietime');
				}
				$_cookietime = $cookietime ? intval($cookietime) : ($get_cookietime ? $get_cookietime : 0);
				$cookietime = $_cookietime ? TIME + $_cookietime : 0;
				param::set_cookie('_nickname', $nickname, $cookietime);
			}
			require_once CACHE_MODEL_PATH.'member_input.class.php';
			require_once CACHE_MODEL_PATH.'member_update.class.php';
			$member_input = new member_input($this->memberinfo['modelid']);
			//7.12
			$birthday=$_POST['year'].'-'.$_POST['month'].'-'.$_POST['day'];
			$_POST['info']=array('birthday'=>$birthday,'sex'=>$_POST['sex'],'province'=>$_POST['selectp'],'city'=>$_POST['selectc']);
			$modelinfo = $member_input->get($_POST['info']);

			$this->db->set_model($this->memberinfo['modelid']);
			$membermodelinfo = $this->db->get_one(array('userid'=>$this->memberinfo['userid']));
			if(!empty($membermodelinfo)) {
				$this->db->update($modelinfo, array('userid'=>$this->memberinfo['userid']));
			} else {
				$modelinfo['userid'] = $this->memberinfo['userid'];
				$this->db->insert($modelinfo);
			}

			showmessage(L('operation_success'), HTTP_REFERER);
		} else {
		    $member=$this->member_detail();
		    $paihang=$this->paihang();
		    $new=$this->message_ts();
			$memberinfo = $this->memberinfo;
			//获取会员模型表单
			require CACHE_MODEL_PATH.'member_form.class.php';
			$member_form = new member_form($this->memberinfo['modelid']);
			$this->db->set_model($this->memberinfo['modelid']);

			$membermodelinfo = $this->db->get_one(array('userid'=>$this->memberinfo['userid']));
			$forminfos = $forminfos_arr = $member_form->get($membermodelinfo);

			//万能字段过滤
			foreach($forminfos as $field=>$info) {
				if($info['isomnipotent']) {
					unset($forminfos[$field]);
				} else {
					if($info['formtype']=='omnipotent') {
						foreach($forminfos_arr as $_fm=>$_fm_value) {
							if($_fm_value['isomnipotent']) {
								$info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'], $info['form']);
							}
						}
						$forminfos[$field]['form'] = $info['form'];
					}
				}
			}
			//7.12
			$birthday=explode("-", $membermodelinfo['birthday']);
			$year=$birthday[0];
			$month=$birthday[1];
			$day=$birthday[2];
			$formValidator = $member_form->formValidator;

			include template('member', 'account_manage_info');
		}
	}

	public function account_manage_password() {
		if(isset($_POST['dosubmit'])) {
			$updateinfo = array();
			if(!is_password($_POST['info']['password'])) {
				showmessage(L('password_format_incorrect'), HTTP_REFERER);
			}
			if($this->memberinfo['password'] != password($_POST['info']['password'], $this->memberinfo['encrypt'])) {
				showmessage(L('old_password_incorrect'), HTTP_REFERER);
			}
			//7.25
			if($_POST['info']['newpassword'] != $_POST['info']['renewpassword']){
			    showmessage(L('两次密码输入不一致'), HTTP_REFERER);
			}
			if(!is_password($_POST['info']['newpassword'])) {
			    showmessage(L('新密码格式错误'), HTTP_REFERER);
			}
			//修改会员邮箱
			if($this->memberinfo['email'] != $_POST['info']['email'] && is_email($_POST['info']['email'])) {
				$email = $_POST['info']['email'];
				$updateinfo['email'] = $_POST['info']['email'];
			} else {
				$email = '';
			}
			$newpassword = password($_POST['info']['newpassword'], $this->memberinfo['encrypt']);
			$updateinfo['password'] = $newpassword;

			$this->db->update($updateinfo, array('userid'=>$this->memberinfo['userid']));
			if(pc_base::load_config('system', 'phpsso')) {
				//初始化phpsso
				$this->_init_phpsso();
				$res = $this->client->ps_member_edit('', $email, $_POST['info']['password'], $_POST['info']['newpassword'], $this->memberinfo['phpssouid'], $this->memberinfo['encrypt']);
				$message_error = array('-1'=>L('user_not_exist'), '-2'=>L('old_password_incorrect'), '-3'=>L('email_already_exist'), '-4'=>L('email_error'), '-5'=>L('param_error'));
				if ($res < 0) showmessage($message_error[$res]);
			}

			showmessage(L('operation_success'), HTTP_REFERER);
		} else {
			$show_validator = true;
			$memberinfo = $this->memberinfo;
			$member=$this->member_detail();
			$paihang=$this->paihang();
			$new=$this->message_ts();

			include template('member', 'account_manage_password');
		}
	}
	//更换手机号码
	public function account_change_mobile() {
		$memberinfo = $this->memberinfo;
		if(isset($_POST['dosubmit'])) {
			if(!is_password($_POST['password'])) {
				showmessage(L('password_format_incorrect'), HTTP_REFERER);
			}
			if($this->memberinfo['password'] != password($_POST['password'], $this->memberinfo['encrypt'])) {
				showmessage(L('old_password_incorrect'));
			}
			$sms_report_db = pc_base::load_model('sms_report_model');
			$mobile_verify = $_POST['mobile_verify'];
			$mobile = $_POST['mobile'];
			if($mobile){
				if(!preg_match('/^1([0-9]{10})$/',$mobile)) exit('check phone error');
				$posttime = SYS_TIME-600;
				$where = "`mobile`='$mobile' AND `send_userid`='".$memberinfo['userid']."' AND `posttime`>'$posttime'";
				$r = $sms_report_db->get_one($where,'id,id_code','id DESC');
				if($r && $r['id_code']==$mobile_verify) {
					$sms_report_db->update(array('id_code'=>''),$where);
					$this->db->update(array('mobile'=>$mobile),array('userid'=>$memberinfo['userid']));
					showmessage("手机号码更新成功！",'?m=member&c=index&a=account_change_mobile&t=1');
				} else {
					showmessage("短信验证码错误！请重新获取！");
				}
			}else{
				showmessage("短信验证码已过期！请重新获取！");
			}
		} else {
			include template('member', 'account_change_mobile');
		}
	}

	//选择密码找回方式
	public function public_get_password_type() {
		$siteid = intval($_GET['siteid']);
		include template('member', 'get_password_type');
	}

	public function account_manage_upgrade() {
		$memberinfo = $this->memberinfo;
		$grouplist = getcache('grouplist');
		if(empty($grouplist[$memberinfo['groupid']]['allowupgrade'])) {
			showmessage(L('deny_upgrade'), HTTP_REFERER);
		}
		if(isset($_POST['upgrade_type']) && intval($_POST['upgrade_type']) < 0) {
			showmessage(L('operation_failure'), HTTP_REFERER);
		}

		if(isset($_POST['upgrade_date']) && intval($_POST['upgrade_date']) < 0) {
			showmessage(L('operation_failure'), HTTP_REFERER);
		}

		if(isset($_POST['dosubmit'])) {
			$groupid = isset($_POST['groupid']) ? intval($_POST['groupid']) : showmessage(L('operation_failure'), HTTP_REFERER);

			$upgrade_type = isset($_POST['upgrade_type']) ? intval($_POST['upgrade_type']) : showmessage(L('operation_failure'), HTTP_REFERER);
			$upgrade_date = !empty($_POST['upgrade_date']) ? intval($_POST['upgrade_date']) : showmessage(L('operation_failure'), HTTP_REFERER);

			//消费类型，包年、包月、包日，价格
			$typearr = array($grouplist[$groupid]['price_y'], $grouplist[$groupid]['price_m'], $grouplist[$groupid]['price_d']);
			//消费类型，包年、包月、包日，时间
			$typedatearr = array('366', '31', '1');
			//消费的价格
			$cost = $typearr[$upgrade_type]*$upgrade_date;
			//购买时间
			$buydate = $typedatearr[$upgrade_type]*$upgrade_date*86400;
			$overduedate = $memberinfo['overduedate'] > SYS_TIME ? ($memberinfo['overduedate']+$buydate) : (SYS_TIME+$buydate);

			if($memberinfo['amount'] >= $cost) {
				$this->db->update(array('groupid'=>$groupid, 'overduedate'=>$overduedate, 'vip'=>1), array('userid'=>$memberinfo['userid']));
				//消费记录
				pc_base::load_app_class('spend','pay',0);
				spend::amount($cost, L('allowupgrade'), $memberinfo['userid'], $memberinfo['username']);
				showmessage(L('operation_success'), 'index.php?m=member&c=index&a=init');
			} else {
				showmessage(L('operation_failure'), HTTP_REFERER);
			}

		} else {

			$groupid = isset($_GET['groupid']) ? intval($_GET['groupid']) : '';
			//初始化phpsso
			$phpsso_api_url = $this->_init_phpsso();
			//获取头像数组
			$avatar = $this->client->ps_getavatar($this->memberinfo['phpssouid']);


			$memberinfo['groupname'] = $grouplist[$memberinfo[groupid]]['name'];
			$memberinfo['grouppoint'] = $grouplist[$memberinfo[groupid]]['point'];
			unset($grouplist[$memberinfo['groupid']]);
			include template('member', 'account_manage_upgrade');
		}
	}

	/*
     * 领取礼包AJAX登录
     */
	public function login_new(){
		$this->_session_start();
		$siteid = isset($_REQUEST['siteid']) && trim($_REQUEST['siteid']) ? intval($_REQUEST['siteid']) : 1;
		if (!defined('SITEID')) {
			define('SITEID', $siteid);
		}
		if(isset($_POST['dosubmit'])) {
			if(empty($_SESSION['connectid'])) {
				//判断验证码
				if(isset($_POST['code']) && trim($_POST['code'])){
					$code=trim($_POST['code']);
				}else{
					$data=array('static'=>0,'msg'=>'请输入验证码');
					die(json_encode($data));
				}
				if ($_SESSION['code'] != strtolower($code)) {
					$data=array('static'=>0,'msg'=>'验证码错误');
					die(json_encode($data));
				}
			}
			if(isset($_POST['username']) && is_username($_POST['username'])){
				$username=trim($_POST['username']);

			}else{
				$data=array('static'=>0,'msg'=>'用户名不能为空');
				die(json_encode($data));
			}
			// $username = isset($_POST['username']) && is_username($_POST['username']) ? trim($_POST['username']) : showmessage(L('username_empty'), HTTP_REFERER);
			if(isset($_POST['password']) && trim($_POST['password'])){
				$password=trim($_POST['password']);
			}else{
				$data=array('static'=>0,'msg'=>'密码不能为空');
				die(json_encode($data));
			}
			$cookietime = intval($_POST['cookietime']);
			$synloginstr = ''; //同步登陆js代码

			if(pc_base::load_config('system', 'phpsso')) {
				$this->_init_phpsso();//初始化phpsso
				$status = $this->client->ps_member_login($username, $password);

				$memberinfo = unserialize($status);

				if(isset($memberinfo['uid'])) {

					//查询帐号
					$r = $this->db->get_one(array('phpssouid'=>$memberinfo['uid']));
                    if($r['groupid']==7){
                        $data=array('static'=>0,'msg'=>'账户未激活，请登录邮箱验证');
                        die(json_encode($data));
                    }
					if(!$r) {
						//插入会员详细信息，会员不存在 插入会员
						$info = array(
							'phpssouid'=>$memberinfo['uid'],
							'username'=>$memberinfo['username'],
							'password'=>$memberinfo['password'],
							'encrypt'=>$memberinfo['random'],
							'email'=>$memberinfo['email'],
							'regip'=>$memberinfo['regip'],
							'regdate'=>$memberinfo['regdate'],
							'lastip'=>$memberinfo['lastip'],
							'lastdate'=>$memberinfo['lastdate'],
							'groupid'=>$this->_get_usergroup_bypoint(),	//会员默认组
							'modelid'=>10,	//普通会员
						);

						//如果是connect用户
						if(!empty($_SESSION['connectid'])) {
							$userinfo['connectid'] = $_SESSION['connectid'];
						}
						if(!empty($_SESSION['from'])) {
							$userinfo['from'] = $_SESSION['from'];
						}
						unset($_SESSION['connectid'], $_SESSION['from']);

						$this->db->insert($info);
						unset($info);
						$r = $this->db->get_one(array('phpssouid'=>$memberinfo['uid']));
					}
					$password = $r['password'];
					$synloginstr = $this->client->ps_member_synlogin($r['phpssouid']);
				} else {
					if($status == -1) {	//用户不存在
						$data=array('static'=>0,'msg'=>'用户不存在');
						die(json_encode($data));
					} elseif($status == -2) { //密码错误
						$data=array('static'=>0,'msg'=>'密码错误');
						die(json_encode($data));
					} else {
						$data=array('static'=>0,'msg'=>'登录失败');
						die(json_encode($data));
					}
				}

			} else {
				//密码错误剩余重试次数
				$this->times_db = pc_base::load_model('times_model');
				$rtime = $this->times_db->get_one(array('username'=>$username));
				if($rtime['times'] > 4) {
					$data=array('static'=>0,'msg'=>'密码错误');
					die(json_encode($data));
				}

				//查询帐号
				$r = $this->db->get_one(array('username'=>$username));

				if(!$r){
					$data=array('static'=>0,'msg'=>'用户不存在2');
					die(json_encode($data));
				}

				//验证用户密码
				$password = md5(md5(trim($password)).$r['encrypt']);
				if($r['password'] != $password) {
					$ip = ip();
					if($rtime && $rtime['times'] < 5) {
						$times = 5 - intval($rtime['times']);
						$this->times_db->update(array('ip'=>$ip, 'times'=>'+=1'), array('username'=>$username));
					} else {
						$this->times_db->insert(array('username'=>$username, 'ip'=>$ip, 'logintime'=>SYS_TIME, 'times'=>1));
						$times = 5;
					}
					$data=array('static'=>0,'msg'=>'密码错误');
					die(json_encode($data));
				}
				$this->times_db->delete(array('username'=>$username));
			}

			//如果用户被锁定
			if($r['islock']) {
				$data=array('static'=>0,'msg'=>'用户被锁定');
				die(json_encode($data));
			}

			$userid = $r['userid'];
			$groupid = $r['groupid'];
			$username = $r['username'];
			$nickname = empty($r['nickname']) ? $username : $r['nickname'];

			$updatearr = array('lastip'=>ip(), 'lastdate'=>SYS_TIME,'integral'=>'+=5','gold'=>'+=5');//每天登录送5积分,5金币
			//vip过期，更新vip和会员组
			if($r['overduedate'] < SYS_TIME) {
				$updatearr['vip'] = 0;
			}

			//检查用户积分，更新新用户组，除去邮箱认证、禁止访问、游客组用户、vip用户，如果该用户组不允许自助升级则不进行该操作
			if($r['point'] >= 0 && !in_array($r['groupid'], array('1', '7', '8')) && empty($r[vip])) {
				$grouplist = getcache('grouplist');
				if(!empty($grouplist[$r['groupid']]['allowupgrade'])) {
					$check_groupid = $this->_get_usergroup_bypoint($r['point']);

					if($check_groupid != $r['groupid']) {
						$updatearr['groupid'] = $groupid = $check_groupid;
					}
				}
			}

			//如果是connect用户
			if(!empty($_SESSION['connectid'])) {
				$updatearr['connectid'] = $_SESSION['connectid'];
			}
			if(!empty($_SESSION['from'])) {
				$updatearr['from'] = $_SESSION['from'];
			}
			unset($_SESSION['connectid'], $_SESSION['from']);

			$this->db->update($updatearr, array('userid'=>$userid));

			if(!isset($cookietime)) {
				$get_cookietime = param::get_cookie('cookietime');
			}
			$_cookietime = $cookietime ? intval($cookietime) : ($get_cookietime ? $get_cookietime : 0);
			$cookietime = $_cookietime ? SYS_TIME + $_cookietime : 0;

			$phpcms_auth_key = md5(pc_base::load_config('system', 'auth_key').$this->http_user_agent);
			$phpcms_auth = sys_auth($userid."\t".$password, 'ENCODE', $phpcms_auth_key);

			param::set_cookie('auth', $phpcms_auth, $cookietime);
			param::set_cookie('_userid', $userid, $cookietime);
			param::set_cookie('_username', $username, $cookietime);
			param::set_cookie('_groupid', $groupid, $cookietime);
			param::set_cookie('_nickname', $nickname, $cookietime);
			//param::set_cookie('cookietime', $_cookietime, $cookietime);
			$_SESSION['username']=$username;
			$_SESSION['userid']=$userid;
			$s = $_SERVER['SERVER_PORT'] == '443' ? 1 : 0;
			setcookie("nickname",$nickname, $cookietime, pc_base::load_config('system','cookie_path'), pc_base::load_config('system','cookie_domain'), $s);

			$forward = isset($_POST['forward']) && !empty($_POST['forward']) ? urldecode($_POST['forward']) : 'index.php?m=member&c=index';
			/*
                        if(empty($forward)){
                            $forward = isset($_GET['forward']) && !empty($_GET['forward']) ? $_GET['forward'] : '/';
                        }else{
                            $forward = isset($_GET['forward']) && trim($_GET['forward']) ? urlencode($_GET['forward']) : '/';
                        }
            */

			$data=array('static'=>1,'msg'=>'登录成功');
			die(json_encode($data));
		}else{
			$setting = pc_base::load_config('system');
			$forward = isset($_GET['forward']) && trim($_GET['forward']) ? urlencode($_GET['forward']) : '';
			$siteid = isset($_REQUEST['siteid']) && trim($_REQUEST['siteid']) ? intval($_REQUEST['siteid']) : 1;
			$siteinfo = siteinfo($siteid);
			if($_SERVER['HTTP_HOST']=='m.93636.com'){
				include template('wap', 'login');
			}
		}

	}
	public function login() {
		$this->_session_start();
		//获取用户siteid
		$siteid = isset($_REQUEST['siteid']) && trim($_REQUEST['siteid']) ? intval($_REQUEST['siteid']) : 1;
		//定义站点id常量
		if (!defined('SITEID')) {
			define('SITEID', $siteid);
		}
        if(isset($_POST['dosubmit'])) {
            if(empty($_SESSION['connectid'])) {
				//判断验证码
				$code = isset($_POST['code']) && trim($_POST['code']) ? trim($_POST['code']) : showmessage(L('input_code'), HTTP_REFERER);
				if ($_SESSION['code'] != strtolower($code)) {
					showmessage(L('code_error'), HTTP_REFERER);
				}
			}

			$username = isset($_POST['username']) && is_username($_POST['username']) ? trim($_POST['username']) : showmessage(L('username_empty'), HTTP_REFERER);
			$password = isset($_POST['password']) && trim($_POST['password']) ? trim($_POST['password']) : showmessage(L('password_empty'), HTTP_REFERER);

			$cookietime = intval($_POST['cookietime']);
			$synloginstr = ''; //同步登陆js代码
			
			$from = $_GET['from'];//判断登录来源于maxip
			if($from !=''){
				$forward2='index.php?m=member&c=index&a=login&from=ip';
			}else{
				$forward2='index.php?m=member&c=index&a=login';
			}

			if(pc_base::load_config('system', 'phpsso')) {
				$this->_init_phpsso();
				$status = $this->client->ps_member_login($username, $password);
				$memberinfo = unserialize($status);

				if(isset($memberinfo['uid'])) {
					//查询帐号
					$r = $this->db->get_one(array('phpssouid'=>$memberinfo['uid']));
                    if($r['groupid']==7){
                        showmessage("请登录邮箱验证", 'index.php?m=member&c=index&a=login',5000);
                    }
					if(!$r) {
						//插入会员详细信息，会员不存在 插入会员
						$info = array(
							'phpssouid'=>$memberinfo['uid'],
							'username'=>$memberinfo['username'],
							'password'=>$memberinfo['password'],
							'encrypt'=>$memberinfo['random'],
							'email'=>$memberinfo['email'],
							'regip'=>$memberinfo['regip'],
							'regdate'=>$memberinfo['regdate'],
							'lastip'=>$memberinfo['lastip'],
							'lastdate'=>$memberinfo['lastdate'],
							'groupid'=>$this->_get_usergroup_bypoint(),	//会员默认组
							'modelid'=>10,	//普通会员
						);

						//如果是connect用户
						if(!empty($_SESSION['connectid'])) {
							$userinfo['connectid'] = $_SESSION['connectid'];
						}
						if(!empty($_SESSION['from'])) {
							$userinfo['from'] = $_SESSION['from'];
						}
						unset($_SESSION['connectid'], $_SESSION['from']);

						$this->db->insert($info);
						unset($info);
						$r = $this->db->get_one(array('phpssouid'=>$memberinfo['uid']));
					}
					$password = $r['password'];
					$synloginstr = $this->client->ps_member_synlogin($r['phpssouid']);
				} else {
					
					if($status == -1) {	//用户不存在
						//showmessage(L('user_not_exist'), 'index.php?m=member&c=index&a=login');
						showmessage(L('user_not_exist'), $forward2);
					} elseif($status == -2) { //密码错误
						//showmessage(L('password_error'), 'index.php?m=member&c=index&a=login');
						showmessage(L('password_error'), $forward2);
					} else {
						//showmessage(L('login_failure'), 'index.php?m=member&c=index&a=login');
						showmessage(L('login_failure'), $forward2);
					}
				}

			} else {
				//密码错误剩余重试次数
				$this->times_db = pc_base::load_model('times_model');
				$rtime = $this->times_db->get_one(array('username'=>$username));
				if($rtime['times'] > 4) {
					$minute = 60 - floor((SYS_TIME - $rtime['logintime']) / 60);
					showmessage(L('wait_1_hour', array('minute'=>$minute)));
				}

				//查询帐号
				$r = $this->db->get_one(array('username'=>$username));

				//if(!$r) showmessage(L('user_not_exist'),'index.php?m=member&c=index&a=login');
				if(!$r) showmessage(L('user_not_exist'),$forward2);

				//验证用户密码
				$password = md5(md5(trim($password)).$r['encrypt']);
				if($r['password'] != $password) {
					$ip = ip();
					if($rtime && $rtime['times'] < 5) {
						$times = 5 - intval($rtime['times']);
						$this->times_db->update(array('ip'=>$ip, 'times'=>'+=1'), array('username'=>$username));
					} else {
						$this->times_db->insert(array('username'=>$username, 'ip'=>$ip, 'logintime'=>SYS_TIME, 'times'=>1));
						$times = 5;
					}
					//showmessage(L('password_error', array('times'=>$times)), 'index.php?m=member&c=index&a=login', 3000);
					showmessage(L('password_error', array('times'=>$times)), $forward2, 3000);
				}
				$this->times_db->delete(array('username'=>$username));
			}

			//如果用户被锁定
			if($r['islock']) {
				showmessage(L('user_is_lock'));
			}

			$userid = $r['userid'];
			$groupid = $r['groupid'];
			$username = $r['username'];
			$nickname = empty($r['nickname']) ? $username : $r['nickname'];

			//7.25
			$start = mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));
            if($r['lastdate']<$start){
			    $updatearr = array('lastip'=>ip(), 'lastdate'=>SYS_TIME,'integral'=>'+=5','gold'=>'+=5');//每天登录送5积分,5金币
			}else{
			    $updatearr = array('lastip'=>ip(), 'lastdate'=>SYS_TIME);
			}
			
			//vip过期，更新vip和会员组
			if($r['overduedate'] < SYS_TIME) {
				$updatearr['vip'] = 0;
			}

			//检查用户积分，更新新用户组，除去邮箱认证、禁止访问、游客组用户、vip用户，如果该用户组不允许自助升级则不进行该操作		
			if($r['point'] >= 0 && !in_array($r['groupid'], array('1', '7', '8')) && empty($r[vip])) {
				$grouplist = getcache('grouplist');
				if(!empty($grouplist[$r['groupid']]['allowupgrade'])) {
					$check_groupid = $this->_get_usergroup_bypoint($r['point']);

					if($check_groupid != $r['groupid']) {
						$updatearr['groupid'] = $groupid = $check_groupid;
					}
				}
			}

			//如果是connect用户
			if(!empty($_SESSION['connectid'])) {
				$updatearr['connectid'] = $_SESSION['connectid'];
			}
			if(!empty($_SESSION['from'])) {
				$updatearr['from'] = $_SESSION['from'];
			}
			unset($_SESSION['connectid'], $_SESSION['from']);

			$this->db->update($updatearr, array('userid'=>$userid));

			if(!isset($cookietime)) {
				$get_cookietime = param::get_cookie('cookietime');
			}
			$_cookietime = $cookietime ? intval($cookietime) : ($get_cookietime ? $get_cookietime : 0);
			$cookietime = $_cookietime ? SYS_TIME + $_cookietime : 0;

			$phpcms_auth_key = md5(pc_base::load_config('system', 'auth_key').$this->http_user_agent);
			$phpcms_auth = sys_auth($userid."\t".$password, 'ENCODE', $phpcms_auth_key);

			param::set_cookie('auth', $phpcms_auth, $cookietime);
			param::set_cookie('_userid', $userid, $cookietime);
			param::set_cookie('_username', $username, $cookietime);
			param::set_cookie('_groupid', $groupid, $cookietime);
			param::set_cookie('_nickname', $nickname, $cookietime);
			//param::set_cookie('cookietime', $_cookietime, $cookietime);
			$_SESSION['username']=$username;
			$_SESSION['userid']=$userid;

			$s = $_SERVER['SERVER_PORT'] == '443' ? 1 : 0;
			setcookie("nickname",$nickname, $cookietime, pc_base::load_config('system','cookie_path'), pc_base::load_config('system','cookie_domain'), $s);

			$forward = isset($_POST['forward']) && !empty($_POST['forward']) ? urldecode($_POST['forward']) : 'index.php?m=member&c=index';
			/*
                        if(empty($forward)){
                            $forward = isset($_GET['forward']) && !empty($_GET['forward']) ? $_GET['forward'] : '/';
                        }else{
                            $forward = isset($_GET['forward']) && trim($_GET['forward']) ? urlencode($_GET['forward']) : '/';
                        }
            */

			//showmessage(L('login_success').$synloginstr, $forward);
			if($from !=''){
				showmessage(L('login_success').$synloginstr, 'http://ip.93636.com');
			}else{
				showmessage(L('login_success').$synloginstr, $forward); 
			}
		} else {
			$setting = pc_base::load_config('system');
			$forward = isset($_GET['forward']) && trim($_GET['forward']) ? urlencode($_GET['forward']) : '';

			$siteid = isset($_REQUEST['siteid']) && trim($_REQUEST['siteid']) ? intval($_REQUEST['siteid']) : 1;
			$siteinfo = siteinfo($siteid); 

			include template('member', 'login');
		}
	}

	public function logout() {
		$setting = pc_base::load_config('system');
		//snda退出
		if($setting['snda_enable'] && param::get_cookie('_from')=='snda') {
			param::set_cookie('_from', '');
			$forward = isset($_GET['forward']) && trim($_GET['forward']) ? urlencode($_GET['forward']) : '';
			$logouturl = 'https://cas.sdo.com/cas/logout?url='.urlencode(APP_PATH.'index.php?m=member&c=index&a=logout&forward='.$forward);
			header('Location: '.$logouturl);
		} else {
			$synlogoutstr = '';	//同步退出js代码
			if(pc_base::load_config('system', 'phpsso')) {
				$this->_init_phpsso();
				$synlogoutstr = $this->client->ps_member_synlogout();
			}

			param::set_cookie('auth', '');
			param::set_cookie('_userid', '');
			param::set_cookie('_username', '');
			param::set_cookie('_groupid', '');
			param::set_cookie('_nickname', '');
			param::set_cookie('cookietime', '');
			//if(isset($_SESSION['username'])){

				unset($_SESSION['username']);

			//}

			setcookie ("nickname",'', time() - 3600);

			$forward = isset($_GET['forward']) && trim($_GET['forward']) ? $_GET['forward'] : 'index.php?m=member&c=index&a=login';
			if($_SERVER['HTTP_HOST']=='m.93636.com'){
				$forward = isset($_GET['forward']) && trim($_GET['forward']) ? $_GET['forward'] : 'index.php?m=member&c=index&a=login_new';
			}
			$from = $_GET['from'];//判断登录来源于maxip
			if($from !=''){
				showmessage(L('logout_success').$synlogoutstr, 'http://ip.93636.com');
			}else{
				showmessage(L('logout_success').$synlogoutstr, $forward);
			}
			
			
		}
	}

	/**
	 * 我的收藏
	 *
	 */
	public function favorite() {
		$this->favorite_db = pc_base::load_model('favorite_model');
		$memberinfo = $this->memberinfo;
		if(isset($_GET['id']) && trim($_GET['id'])) {
			$this->favorite_db->delete(array('userid'=>$memberinfo['userid'], 'id'=>intval($_GET['id'])));
			showmessage(L('operation_success'), HTTP_REFERER);
		} else {
			$page = isset($_GET['page']) && trim($_GET['page']) ? intval($_GET['page']) : 1;
			$favoritelist = $this->favorite_db->listinfo(array('userid'=>$memberinfo['userid']), 'id DESC', $page, 10);
			$pages = $this->favorite_db->pages;

			include template('member', 'favorite_list');
		}
	}

	/**
	 * 我的好友
	 */
	public function friend() {
		$memberinfo = $this->memberinfo;
		$this->friend_db = pc_base::load_model('friend_model');
		if(isset($_GET['friendid'])) {
			$this->friend_db->delete(array('userid'=>$memberinfo['userid'], 'friendid'=>intval($_GET['friendid'])));
			showmessage(L('operation_success'), HTTP_REFERER);
		} else {
			//初始化phpsso
			$phpsso_api_url = $this->_init_phpsso();

			//我的好友列表userid
			$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
			$friendids = $this->friend_db->listinfo(array('userid'=>$memberinfo['userid']), '', $page, 10);
			$pages = $this->friend_db->pages;
			foreach($friendids as $k=>$v) {
				$friendlist[$k]['friendid'] = $v['friendid'];
				$friendlist[$k]['avatar'] = $this->client->ps_getavatar($v['phpssouid']);
				$friendlist[$k]['is'] = $v['is'];
			}
			include template('member', 'friend_list');
		}
	}

	/**
	 * 积分兑换
	 */
	public function change_credit() {
		$memberinfo = $this->memberinfo;
		//加载用户模块配置
		$member_setting = getcache('member_setting');
		$this->_init_phpsso();
		$setting = $this->client->ps_getcreditlist();
		$outcredit = unserialize($setting);
		$setting = $this->client->ps_getapplist();
		$applist = unserialize($setting);

		if(isset($_POST['dosubmit'])) {
			//本系统积分兑换数
			$fromvalue = intval($_POST['fromvalue']);
			//本系统积分类型
			$from = $_POST['from'];
			$toappid_to = explode('_', $_POST['to']);
			//目标系统appid
			$toappid = $toappid_to[0];
			//目标系统积分类型
			$to = $toappid_to[1];
			if($from == 1) {
				if($memberinfo['point'] < $fromvalue) {
					showmessage(L('need_more_point'), HTTP_REFERER);
				}
			} elseif($from == 2) {
				if($memberinfo['amount'] < $fromvalue) {
					showmessage(L('need_more_amount'), HTTP_REFERER);
				}
			} else {
				showmessage(L('credit_setting_error'), HTTP_REFERER);
			}

			$status = $this->client->ps_changecredit($memberinfo['phpssouid'], $from, $toappid, $to, $fromvalue);
			if($status == 1) {
				if($from == 1) {
					$this->db->update(array('point'=>"-=$fromvalue"), array('userid'=>$memberinfo['userid']));
				} elseif($from == 2) {
					$this->db->update(array('amount'=>"-=$fromvalue"), array('userid'=>$memberinfo['userid']));
				}
				showmessage(L('operation_success'), HTTP_REFERER);
			} else {
				showmessage(L('operation_failure'), HTTP_REFERER);
			}
		} elseif(isset($_POST['buy'])) {
			if(!is_numeric($_POST['money']) || $_POST['money'] < 0) {
				showmessage(L('money_error'), HTTP_REFERER);
			} else {
				$money = intval($_POST['money']);
			}

			if($memberinfo['amount'] < $money) {
				showmessage(L('short_of_money'), HTTP_REFERER);
			}
			//此处比率读取用户配置
			$point = $money*$member_setting['rmb_point_rate'];
			$this->db->update(array('point'=>"+=$point"), array('userid'=>$memberinfo['userid']));
			//加入消费记录，同时扣除金钱
			pc_base::load_app_class('spend','pay',0);
			spend::amount($money, L('buy_point'), $memberinfo['userid'], $memberinfo['username']);
			showmessage(L('operation_success'), HTTP_REFERER);
		} else {
			$credit_list = pc_base::load_config('credit');

			include template('member', 'change_credit');
		}
	}

	//mini登陆条
	public function mini() {
		$_username = param::get_cookie('_username');
		$_userid = param::get_cookie('_userid');
		$siteid = isset($_GET['siteid']) ? intval($_GET['siteid']) : '';
		//定义站点id常量
		if (!defined('SITEID')) {
			define('SITEID', $siteid);
		}

		$snda_enable = pc_base::load_config('system', 'snda_enable');
		include template('member', 'mini');
	}

	/**
	 * 初始化phpsso
	 * about phpsso, include client and client configure
	 * @return string phpsso_api_url phpsso地址
	 */
	private function _init_phpsso() {
		pc_base::load_app_class('client', '', 0);
		define('APPID', pc_base::load_config('system', 'phpsso_appid'));
		$phpsso_api_url = pc_base::load_config('system', 'phpsso_api_url');
		$phpsso_auth_key = pc_base::load_config('system', 'phpsso_auth_key');
		$this->client = new client($phpsso_api_url, $phpsso_auth_key);
		return $phpsso_api_url;
	}

	protected function _checkname($username) {
		$username =  trim($username);
		if ($this->db->get_one(array('username'=>$username))){
			return false;
		}
		return true;
	}

	private function _session_start() {

		$session_storage = 'session_'.pc_base::load_config('system','session_storage');
		pc_base::load_sys_class($session_storage);
	}

	/*
	 * 通过linkageid获取名字路径
	 */
	protected function _get_linkage_fullname($linkageid,  $linkagelist) {
		$fullname = '';
		if($linkagelist['data'][$linkageid]['parentid'] != 0) {
			$fullname = $this->_get_linkage_fullname($linkagelist['data'][$linkageid]['parentid'], $linkagelist);
		}
		//所在地区名称
		$return = $fullname.$linkagelist['data'][$linkageid]['name'].'>';
		return $return;
	}

	/**
	 *根据积分算出用户组
	 * @param $point int 积分数
	 */
	protected function _get_usergroup_bypoint($point=0) {
		$groupid = 2;
		if(empty($point)) {
			$member_setting = getcache('member_setting');
			$point = $member_setting['defualtpoint'] ? $member_setting['defualtpoint'] : 0;
		}
		$grouplist = getcache('grouplist');

		foreach ($grouplist as $k=>$v) {
			$grouppointlist[$k] = $v['point'];
		}
		arsort($grouppointlist);

		//如果超出用户组积分设置则为积分最高的用户组
		if($point > max($grouppointlist)) {
			$groupid = key($grouppointlist);
		} else {
			foreach ($grouppointlist as $k=>$v) {
				if($point >= $v) {
					$groupid = $tmp_k;
					break;
				}
				$tmp_k = $k;
			}
		}
		return $groupid;
	}

	/**
	 * 检查用户名
	 * @param string $username	用户名
	 * @return $status {-4：用户名禁止注册;-1:用户名已经存在 ;1:成功}
	 */
	public function public_checkname_ajax() {
		$username = isset($_GET['username']) && trim($_GET['username']) ? trim($_GET['username']) : exit(0);
		if(CHARSET != 'utf-8') {
			$username = iconv('utf-8', CHARSET, $username);
			$username = addslashes($username);
		}
		$username = safe_replace($username);
		//首先判断会员审核表
		$this->verify_db = pc_base::load_model('member_verify_model');
		if($this->verify_db->get_one(array('username'=>$username))) {
			exit('0');
		}

		$this->_init_phpsso();
		$status = $this->client->ps_checkname($username);

		if($status == -4 || $status == -1) {
			exit('0');
		} else {
			exit('1');
		}
	}

	/**
	 * 检查用户昵称
	 * @param string $nickname	昵称
	 * @return $status {0:已存在;1:成功}
	 */
	public function public_checknickname_ajax() {
		$nickname = isset($_GET['nickname']) && trim($_GET['nickname']) ? trim($_GET['nickname']) : exit('0');
		if(CHARSET != 'utf-8') {
			$nickname = iconv('utf-8', CHARSET, $nickname);
			$nickname = addslashes($nickname);
		}
		//首先判断会员审核表
		$this->verify_db = pc_base::load_model('member_verify_model');
		if($this->verify_db->get_one(array('nickname'=>$nickname))) {
			exit('0');
		}
		if(isset($_GET['userid'])) {
			$userid = intval($_GET['userid']);
			//如果是会员修改，而且NICKNAME和原来优质一致返回1，否则返回0
			$info = get_memberinfo($userid);
			if($info['nickname'] == $nickname){//未改变
				exit('1');
			}else{//已改变，判断是否已有此名
				$where = array('nickname'=>$nickname);
				$res = $this->db->get_one($where);
				if($res) {
					exit('0');
				} else {
					exit('1');
				}
			}
		} else {
			$where = array('nickname'=>$nickname);
			$res = $this->db->get_one($where);
			if($res) {
				exit('0');
			} else {
				exit('1');
			}
		}
	}

	/**
	 * 检查验证码
	 * @param string $email
	 * @return $status {0:验证失败;1:验证成功}
	 */
	public function public_checkcode_ajax(){
		$this->_session_start();
		$code = isset($_GET['code']) && trim($_GET['code']) ? trim($_GET['code']) : exit('0');
		//判断验证码
		if(isset($_SESSION['code'])){
			if ($_SESSION['code'] == strtolower($code)) {
				exit('0');
			}
		}
		echo '1';
	}

	/**
	 * 检查邮箱
	 * @param string $email
	 * @return $status {-1:email已经存在 ;-5:邮箱禁止注册;1:成功}
	 */
	public function public_checkemail_ajax() {
		$this->_init_phpsso();
		$email = isset($_GET['email']) && trim($_GET['email']) ? trim($_GET['email']) : exit(0);

		$status = $this->client->ps_checkemail($email);
		if($status == -5) {	//禁止注册
			exit('0');
		} elseif($status == -1) {	//用户名已存在，但是修改用户的时候需要判断邮箱是否是当前用户的
			if(isset($_GET['phpssouid'])) {	//修改用户传入phpssouid
				$status = $this->client->ps_get_member_info($email, 3);
				if($status) {
					$status = unserialize($status);	//接口返回序列化，进行判断
					if (isset($status['uid']) && $status['uid'] == intval($_GET['phpssouid'])) {
						exit('1');
					} else {
						exit('0');
					}
				} else {
					exit('0');
				}
			} else {
				exit('0');
			}
		} else {
			exit('1');
		}
	}

	public function public_sina_login() {
		define('WB_AKEY', pc_base::load_config('system', 'sina_akey'));
		define('WB_SKEY', pc_base::load_config('system', 'sina_skey'));
		define('WEB_CALLBACK', APP_PATH.'index.php?m=member&c=index&a=public_sina_login&callback=1');
		pc_base::load_app_class('saetv2.ex', '' ,0);
		$this->_session_start();

		if(isset($_GET['callback']) && trim($_GET['callback'])) {
			$o = new SaeTOAuthV2(WB_AKEY, WB_SKEY);
			if (isset($_REQUEST['code'])) {
				$keys = array();
				$keys['code'] = $_REQUEST['code'];
				$keys['redirect_uri'] = WEB_CALLBACK;
				try {
					$token = $o->getAccessToken('code', $keys);
				} catch (OAuthException $e) {
				}
			}
			if ($token) {
				$_SESSION['token'] = $token;
			}
			$c = new SaeTClientV2(WB_AKEY, WB_SKEY, $_SESSION['token']['access_token'] );
			$ms  = $c->home_timeline(); // done
			$uid_get = $c->get_uid();
			$uid = $uid_get['uid'];
			$me = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息
			if(CHARSET != 'utf-8') {
				$me['name'] = iconv('utf-8', CHARSET, $me['name']);
				$me['location'] = iconv('utf-8', CHARSET, $me['location']);
				$me['description'] = iconv('utf-8', CHARSET, $me['description']);
				$me['screen_name'] = iconv('utf-8', CHARSET, $me['screen_name']);
			}
			if(!empty($me['id'])) {
				//检查connect会员是否绑定，已绑定直接登录，未绑定提示注册/绑定页面
				$where = array('connectid'=>$me['id'], 'from'=>'sina');
				$r = $this->db->get_one($where);

				//connect用户已经绑定本站用户
				if(!empty($r)) {
					//读取本站用户信息，执行登录操作

					$password = $r['password'];
					$this->_init_phpsso();
					$synloginstr = $this->client->ps_member_synlogin($r['phpssouid']);
					$userid = $r['userid'];
					$groupid = $r['groupid'];
					$username = $r['username'];
					$nickname = empty($r['nickname']) ? $username : $r['nickname'];
					$this->db->update(array('lastip'=>ip(), 'lastdate'=>SYS_TIME, 'nickname'=>$me['name']), array('userid'=>$userid));

					if(!$cookietime) $get_cookietime = param::get_cookie('cookietime');
					$_cookietime = $cookietime ? intval($cookietime) : ($get_cookietime ? $get_cookietime : 0);
					$cookietime = $_cookietime ? TIME + $_cookietime : 0;

					$phpcms_auth_key = md5(pc_base::load_config('system', 'auth_key').$this->http_user_agent);
					$phpcms_auth = sys_auth($userid."\t".$password, 'ENCODE', $phpcms_auth_key);

					param::set_cookie('auth', $phpcms_auth, $cookietime);
					param::set_cookie('_userid', $userid, $cookietime);
					param::set_cookie('_username', $username, $cookietime);
					param::set_cookie('_groupid', $groupid, $cookietime);
					param::set_cookie('cookietime', $_cookietime, $cookietime);
					param::set_cookie('_nickname', $nickname, $cookietime);

					$forward = isset($_GET['forward']) && !empty($_GET['forward']) ? $_GET['forward'] : 'index.php?m=member&c=index';
					showmessage(L('login_success').$synloginstr, $forward);

				} else {
					//弹出绑定注册页面
					$_SESSION = array();
					$_SESSION['connectid'] = $me['id'];
					$_SESSION['from'] = 'sina';
					$connect_username = $me['name'];

					//加载用户模块配置
					$member_setting = getcache('member_setting');
					if(!$member_setting['allowregister']) {
						showmessage(L('deny_register'), 'index.php?m=member&c=index&a=login');
					}

					//获取用户siteid
					$siteid = isset($_REQUEST['siteid']) && trim($_REQUEST['siteid']) ? intval($_REQUEST['siteid']) : 1;
					//过滤非当前站点会员模型
					$modellist = getcache('member_model', 'commons');
					foreach($modellist as $k=>$v) {
						if($v['siteid']!=$siteid || $v['disabled']) {
							unset($modellist[$k]);
						}
					}
					if(empty($modellist)) {
						showmessage(L('site_have_no_model').L('deny_register'), HTTP_REFERER);
					}

					$modelid = 10; //设定默认值
					if(array_key_exists($modelid, $modellist)) {
						//获取会员模型表单
						require CACHE_MODEL_PATH.'member_form.class.php';
						$member_form = new member_form($modelid);
						$this->db->set_model($modelid);
						$forminfos = $forminfos_arr = $member_form->get();

						//万能字段过滤
						foreach($forminfos as $field=>$info) {
							if($info['isomnipotent']) {
								unset($forminfos[$field]);
							} else {
								if($info['formtype']=='omnipotent') {
									foreach($forminfos_arr as $_fm=>$_fm_value) {
										if($_fm_value['isomnipotent']) {
											$info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'], $info['form']);
										}
									}
									$forminfos[$field]['form'] = $info['form'];
								}
							}
						}

						$formValidator = $member_form->formValidator;
					}
					include template('member', 'connect');
				}
			} else {
				showmessage(L('login_failure'), 'index.php?m=member&c=index&a=login');
			}
		} else {
			$o = new SaeTOAuthV2(WB_AKEY, WB_SKEY);
			$aurl = $o->getAuthorizeURL(WEB_CALLBACK);
			include template('member', 'connect_sina');
		}
	}

	/**
	 * 盛大通行证登陆
	 */
	public function public_snda_login() {
		define('SNDA_AKEY', pc_base::load_config('system', 'snda_akey'));
		define('SNDA_SKEY', pc_base::load_config('system', 'snda_skey'));
		define('SNDA_CALLBACK', urlencode(APP_PATH.'index.php?m=member&c=index&a=public_snda_login&callback=1'));

		pc_base::load_app_class('OauthSDK', '' ,0);
		$this->_session_start();
		if(isset($_GET['callback']) && trim($_GET['callback'])) {

			$o = new OauthSDK(SNDA_AKEY, SNDA_SKEY, SNDA_CALLBACK);
			$code = $_REQUEST['code'];
			$accesstoken = $o->getAccessToken($code);

			if(is_numeric($accesstoken['sdid'])) {
				$userid = $accesstoken['sdid'];
			} else {
				showmessage(L('login_failure'), 'index.php?m=member&c=index&a=login');
			}

			if(!empty($userid)) {

				//检查connect会员是否绑定，已绑定直接登录，未绑定提示注册/绑定页面
				$where = array('connectid'=>$userid, 'from'=>'snda');
				$r = $this->db->get_one($where);

				//connect用户已经绑定本站用户
				if(!empty($r)) {
					//读取本站用户信息，执行登录操作
					$password = $r['password'];
					$this->_init_phpsso();
					$synloginstr = $this->client->ps_member_synlogin($r['phpssouid']);
					$userid = $r['userid'];
					$groupid = $r['groupid'];
					$username = $r['username'];
					$nickname = empty($r['nickname']) ? $username : $r['nickname'];
					$this->db->update(array('lastip'=>ip(), 'lastdate'=>SYS_TIME, 'nickname'=>$me['name']), array('userid'=>$userid));
					if(!$cookietime) $get_cookietime = param::get_cookie('cookietime');
					$_cookietime = $cookietime ? intval($cookietime) : ($get_cookietime ? $get_cookietime : 0);
					$cookietime = $_cookietime ? TIME + $_cookietime : 0;

					$phpcms_auth_key = md5(pc_base::load_config('system', 'auth_key').$this->http_user_agent);
					$phpcms_auth = sys_auth($userid."\t".$password, 'ENCODE', $phpcms_auth_key);

					param::set_cookie('auth', $phpcms_auth, $cookietime);
					param::set_cookie('_userid', $userid, $cookietime);
					param::set_cookie('_username', $username, $cookietime);
					param::set_cookie('_groupid', $groupid, $cookietime);
					param::set_cookie('cookietime', $_cookietime, $cookietime);
					param::set_cookie('_nickname', $nickname, $cookietime);
					param::set_cookie('_from', 'snda');
					$forward = isset($_GET['forward']) && !empty($_GET['forward']) ? $_GET['forward'] : 'index.php?m=member&c=index';
					showmessage(L('login_success').$synloginstr, $forward);
				} else {
					//弹出绑定注册页面
					$_SESSION = array();
					$_SESSION['connectid'] = $userid;
					$_SESSION['from'] = 'snda';
					$connect_username = $userid;
					include template('member', 'connect');
				}
			}
		} else {
			$o = new OauthSDK(SNDA_AKEY, SNDA_SKEY, SNDA_CALLBACK);
			$accesstoken = $o->getSystemToken();
			$aurl = $o->getAuthorizeURL();

			include template('member', 'connect_snda');
		}

	}


	/**
	 * QQ号码登录
	 * 该函数为QQ登录回调地址
	 */
	public function public_qq_loginnew(){
		$appid = pc_base::load_config('system', 'qq_appid');
		$appkey = pc_base::load_config('system', 'qq_appkey');
		$callback = pc_base::load_config('system', 'qq_callback');
		pc_base::load_app_class('qqapi','',0);
		$info = new qqapi($appid,$appkey,$callback);
		$this->_session_start();
		if(!isset($_GET['code'])){
			$info->redirect_to_login();
		}else{
			$code = $_GET['code'];
			$openid = $_SESSION['openid'] = $info->get_openid($code);
			if(!empty($openid)){
				$r = $this->db->get_one(array('connectid'=>$openid,'from'=>'qq'));

				if(!empty($r)){
					//QQ已存在于数据库，则直接转向登陆操作
					$password = $r['password'];
					$this->_init_phpsso();
					$synloginstr = $this->client->ps_member_synlogin($r['phpssouid']);
					$userid = $r['userid'];
					$groupid = $r['groupid'];
					$username = $r['username'];
					$nickname = empty($r['nickname']) ? $username : $r['nickname'];
					$this->db->update(array('lastip'=>ip(), 'lastdate'=>SYS_TIME, 'nickname'=>$me['name']), array('userid'=>$userid));
					if(!$cookietime) $get_cookietime = param::get_cookie('cookietime');
					$_cookietime = $cookietime ? intval($cookietime) : ($get_cookietime ? $get_cookietime : 0);
					$cookietime = $_cookietime ? TIME + $_cookietime : 0;
					$phpcms_auth_key = md5(pc_base::load_config('system', 'auth_key').$this->http_user_agent);
					$phpcms_auth = sys_auth($userid."\t".$password, 'ENCODE', $phpcms_auth_key);
					param::set_cookie('auth', $phpcms_auth, $cookietime);
					param::set_cookie('_userid', $userid, $cookietime);
					param::set_cookie('_username', $username, $cookietime);
					param::set_cookie('_groupid', $groupid, $cookietime);
					param::set_cookie('cookietime', $_cookietime, $cookietime);
					param::set_cookie('_nickname', $nickname, $cookietime);
					$forward = isset($_GET['forward']) && !empty($_GET['forward']) ? $_GET['forward'] : '/index.php?m=member&c=index';
					showmessage(L('login_success').$synloginstr, $forward);
				}else{
					//未存在于数据库中，跳去完善资料页面。页面预置用户名（QQ返回是UTF8编码，如有需要进行转码）
					$user = $info->get_user_info();
					$_SESSION['connectid'] = $openid;
					$_SESSION['from'] = 'qq';
					if(CHARSET != 'utf-8') {//转编码
						$connect_username = iconv('utf-8', CHARSET, $user['nickname']);
					} else {
						$connect_username = $user['nickname'];
					}
					include template('member', 'connect');
				}
			}
		}
	}

	/**
	 * QQ微博登录
	 */
	public function public_qq_login() {
		define('QQ_AKEY', pc_base::load_config('system', 'qq_akey'));
		define('QQ_SKEY', pc_base::load_config('system', 'qq_skey'));
		pc_base::load_app_class('qqoauth', '' ,0);
		$this->_session_start();
		if(isset($_GET['callback']) && trim($_GET['callback'])) {
			$o = new WeiboOAuth(QQ_AKEY, QQ_SKEY, $_SESSION['keys']['oauth_token'], $_SESSION['keys']['oauth_token_secret']);
			$_SESSION['last_key'] = $o->getAccessToken($_REQUEST['oauth_verifier']);

			if(!empty($_SESSION['last_key']['name'])) {
				//检查connect会员是否绑定，已绑定直接登录，未绑定提示注册/绑定页面
				$where = array('connectid'=>$_REQUEST['openid'], 'from'=>'qq');
				$r = $this->db->get_one($where);

				//connect用户已经绑定本站用户
				if(!empty($r)) {
					//读取本站用户信息，执行登录操作
					$password = $r['password'];
					$this->_init_phpsso();
					$synloginstr = $this->client->ps_member_synlogin($r['phpssouid']);
					$userid = $r['userid'];
					$groupid = $r['groupid'];
					$username = $r['username'];
					$nickname = empty($r['nickname']) ? $username : $r['nickname'];
					$this->db->update(array('lastip'=>ip(), 'lastdate'=>SYS_TIME, 'nickname'=>$me['name']), array('userid'=>$userid));
					if(!$cookietime) $get_cookietime = param::get_cookie('cookietime');
					$_cookietime = $cookietime ? intval($cookietime) : ($get_cookietime ? $get_cookietime : 0);
					$cookietime = $_cookietime ? TIME + $_cookietime : 0;

					$phpcms_auth_key = md5(pc_base::load_config('system', 'auth_key').$this->http_user_agent);
					$phpcms_auth = sys_auth($userid."\t".$password, 'ENCODE', $phpcms_auth_key);

					param::set_cookie('auth', $phpcms_auth, $cookietime);
					param::set_cookie('_userid', $userid, $cookietime);
					param::set_cookie('_username', $username, $cookietime);
					param::set_cookie('_groupid', $groupid, $cookietime);
					param::set_cookie('cookietime', $_cookietime, $cookietime);
					param::set_cookie('_nickname', $nickname, $cookietime);
					param::set_cookie('_from', 'snda');
					$forward = isset($_GET['forward']) && !empty($_GET['forward']) ? $_GET['forward'] : '/index.php?m=member&c=index';
					showmessage(L('login_success').$synloginstr, $forward);
				} else {
					//弹出绑定注册页面
					$_SESSION = array();
					$_SESSION['connectid'] = $_REQUEST['openid'];
					$_SESSION['from'] = 'qq';
					$connect_username = $_SESSION['last_key']['name'];

					//加载用户模块配置
					$member_setting = getcache('member_setting');
					if(!$member_setting['allowregister']) {
						showmessage(L('deny_register'), 'index.php?m=member&c=index&a=login');
					}

					//获取用户siteid
					$siteid = isset($_REQUEST['siteid']) && trim($_REQUEST['siteid']) ? intval($_REQUEST['siteid']) : 1;
					//过滤非当前站点会员模型
					$modellist = getcache('member_model', 'commons');
					foreach($modellist as $k=>$v) {
						if($v['siteid']!=$siteid || $v['disabled']) {
							unset($modellist[$k]);
						}
					}
					if(empty($modellist)) {
						showmessage(L('site_have_no_model').L('deny_register'), HTTP_REFERER);
					}

					$modelid = 10; //设定默认值
					if(array_key_exists($modelid, $modellist)) {
						//获取会员模型表单
						require CACHE_MODEL_PATH.'member_form.class.php';
						$member_form = new member_form($modelid);
						$this->db->set_model($modelid);
						$forminfos = $forminfos_arr = $member_form->get();

						//万能字段过滤
						foreach($forminfos as $field=>$info) {
							if($info['isomnipotent']) {
								unset($forminfos[$field]);
							} else {
								if($info['formtype']=='omnipotent') {
									foreach($forminfos_arr as $_fm=>$_fm_value) {
										if($_fm_value['isomnipotent']) {
											$info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'], $info['form']);
										}
									}
									$forminfos[$field]['form'] = $info['form'];
								}
							}
						}

						$formValidator = $member_form->formValidator;
					}
					include template('member', 'connect');
				}
			} else {
				showmessage(L('login_failure'), 'index.php?m=member&c=index&a=login');
			}
		} else {
			$oauth_callback = APP_PATH.'index.php?m=member&c=index&a=public_qq_login&callback=1';
			$oauth_nonce = md5(SYS_TIME);
			$oauth_signature_method = 'HMAC-SHA1';
			$oauth_timestamp = SYS_TIME;
			$oauth_version = '1.0';

			$url = "https://open.t.qq.com/cgi-bin/request_token?oauth_callback=$oauth_callback&oauth_consumer_key=".QQ_AKEY."&oauth_nonce=$oauth_nonce&oauth_signature=".QQ_SKEY."&oauth_signature_method=HMAC-SHA1&oauth_timestamp=$oauth_timestamp&oauth_version=$oauth_version";
			$o = new WeiboOAuth(QQ_AKEY, QQ_SKEY);

			$keys = $o->getRequestToken(array('callback'=>$oauth_callback));
			$_SESSION['keys'] = $keys;
			$aurl = $o->getAuthorizeURL($keys['oauth_token'] ,false , $oauth_callback);

			include template('member', 'connect_qq');
		}

	}

	/**
	 * 找回密码
	 * 新增加短信找回方式
	 */
	public function public_forget_password () {

		$email_config = getcache('common', 'commons');

		//SMTP MAIL 二种发送模式
		if($email_config['mail_type'] == '1'){
			if(empty($email_config['mail_user']) || empty($email_config['mail_password'])) {
				showmessage(L('email_config_empty'), HTTP_REFERER);
			}
		}
		$this->_session_start();
		$member_setting = getcache('member_setting');
		if(isset($_POST['dosubmit'])) {
			if ($_SESSION['code'] != strtolower($_POST['code'])) {
				showmessage(L('code_error'), HTTP_REFERER);
			}

			$memberinfo = $this->db->get_one(array('email'=>$_POST['email']));
			if(!empty($memberinfo['email'])) {
				$email = $memberinfo['email'];
			} else {
				showmessage(L('email_error'), HTTP_REFERER);
			}

			pc_base::load_sys_func('mail');
			$phpcms_auth_key = md5(pc_base::load_config('system', 'auth_key'));

			$code = sys_auth($memberinfo['userid']."\t".SYS_TIME, 'ENCODE', $phpcms_auth_key);

			$url = APP_PATH."index.php?m=member&c=index&a=public_forget_password&code=$code";
			$message = $member_setting['forgetpassword'];
			$message = str_replace(array('{click}','{url}'), array('<a href="'.$url.'">'.L('please_click').'</a>',$url), $message);
			//获取站点名称
			$sitelist = getcache('sitelist', 'commons');

			if(isset($sitelist[$memberinfo['siteid']]['name'])) {
				$sitename = $sitelist[$memberinfo['siteid']]['name'];
			} else {
				$sitename = 'PHPCMS_V9_MAIL';
			}
			sendmail($email, L('forgetpassword'), $message, '', '', $sitename);
			showmessage(L('operation_success'), 'index.php?m=member&c=index&a=login');
		} elseif($_GET['code']) {
			$phpcms_auth_key = md5(pc_base::load_config('system', 'auth_key'));
			$hour = date('y-m-d h', SYS_TIME);
			$code = sys_auth($_GET['code'], 'DECODE', $phpcms_auth_key);
			$code = explode("\t", $code);

			if(is_array($code) && is_numeric($code[0]) && date('y-m-d h', SYS_TIME) == date('y-m-d h', $code[1])) {
				$memberinfo = $this->db->get_one(array('userid'=>$code[0]));

				if(empty($memberinfo['phpssouid'])) {
					showmessage(L('operation_failure'), 'index.php?m=member&c=index&a=login');
				}
				$updateinfo = array();
				$password = random(8,"23456789abcdefghkmnrstwxy");
				$updateinfo['password'] = password($password, $memberinfo['encrypt']);

				$this->db->update($updateinfo, array('userid'=>$code[0]));
				if(pc_base::load_config('system', 'phpsso')) {
					//初始化phpsso
					$this->_init_phpsso();
					$this->client->ps_member_edit('', $email, '', $password, $memberinfo['phpssouid'], $memberinfo['encrypt']);
				}
				$email = $memberinfo['email'];
				//获取站点名称
				$sitelist = getcache('sitelist', 'commons');
				if(isset($sitelist[$memberinfo['siteid']]['name'])) {
					$sitename = $sitelist[$memberinfo['siteid']]['name'];
				} else {
					$sitename = 'PHPCMS_V9_MAIL';
				}
				pc_base::load_sys_func('mail');
				sendmail($email, L('forgetpassword'), "New password:".$password, '', '', $sitename);
				showmessage(L('operation_success').L('newpassword').':'.$password);

			} else {
				showmessage(L('operation_failure'), 'index.php?m=member&c=index&a=login');
			}

		} else {
			$siteid = isset($_REQUEST['siteid']) && trim($_REQUEST['siteid']) ? intval($_REQUEST['siteid']) : 1;
			$siteinfo = siteinfo($siteid);

			include template('member', 'forget_password');
		}
	}

	/**
	 *通过手机修改密码
	 *方式：用户发送HHPWD afei985#821008 至 1065788 ，PHPCMS进行转发到网站运营者指定的回调地址，在回调地址程序进行密码修改等操作,处理成功时给用户发条短信确认。
	 *phpcms 以POST方式传递相关数据到回调程序中
	 *要求：网站中会员系统，mobile做为主表字段，并且唯一（如已经有手机号码，把号码字段转为主表字段中）
	 */

	public function public_changepwd_bymobile(){
		$phone = $_REQUEST['phone'];
		$msg = $_REQUEST['msg'];
		$sms_key = $_REQUEST['sms_passwd'];
		$sms_pid = $_REQUEST['sms_pid'];
		if(empty($phone) || empty($msg) || empty($sms_key) || empty($sms_pid)){
			return false;
		}
		if(!preg_match('/^1([0-9]{9})/',$phone)) {
			return false;
		}
		//判断是否PHPCMS请求的接口
		pc_base::load_app_func('global','sms');
		pc_base::load_app_class('smsapi', 'sms', 0);
		$this->sms_setting_arr = getcache('sms');
		$siteid = $_REQUEST['siteid'] ? $_REQUEST['siteid'] : 1;
		if(!empty($this->sms_setting_arr[$siteid])) {
			$this->sms_setting = $this->sms_setting_arr[$siteid];
		} else {
			$this->sms_setting = array('userid'=>'', 'productid'=>'', 'sms_key'=>'');
		}
		if($sms_key != $this->sms_setting['sms_key'] || $sms_pid != $this->sms_setting['productid']){
			return false;
		}
		//取用户名
		$msg_array = explode("@@",$str);
		$newpwd = $msg_array[1];
		$username = $msg_array[2];
		$array = $this->db->get_one(array('mobile'=>$phone,'username'=>$username));
		if(empty($array)){
			echo 1;
		}else{
			$result = $this->db->update(array('password'=>$newpwd),array('mobile'=>$phone,'username'=>$username));
			if($result){
				//修改成功，发送短信给用户回执
				//检查短信余额
				if($this->sms_setting['sms_key']) {
					$smsinfo = $this->smsapi->get_smsinfo();
				}
				if($smsinfo['surplus'] < 1) {
					echo 1;
				}else{
					$this->smsapi = new smsapi($this->sms_setting['userid'], $this->sms_setting['productid'], $this->sms_setting['sms_key']);
					$content = '你好,'.$username.',你的新密码已经修改成功：'.$newpwd.' ,请妥善保存！';
					$return = $this->smsapi->send_sms($phone, $content, SYS_TIME, CHARSET);
					echo 1;
				}
			}
		}
	}

	/**
	 * 手机短信方式找回密码
	 */
	public function public_forget_password_mobile () {
		$step = intval($_POST['step']);
		$step = max($step,1);
		$this->_session_start();

		if(isset($_POST['dosubmit']) && $step==2) {
			//处理提交申请，以手机号为准
			if ($_SESSION['code'] != strtolower($_POST['code'])) {
				showmessage(L('code_error'), HTTP_REFERER);
			}
			$username = safe_replace($_POST['username']);

			$r = $this->db->get_one(array('username'=>$username),'userid,mobile');
			if($r['mobile']=='') {
				$_SESSION['mobile'] = '';
				$_SESSION['userid'] = '';
				$_SESSION['code'] = '';
				showmessage("该账号没有绑定手机号码，请选择其他方式找回！");
			}
			$_SESSION['mobile'] = $r['mobile'];
			$_SESSION['userid'] = $r['userid'];
			include template('member', 'forget_password_mobile');
		} elseif(isset($_POST['dosubmit']) && $step==3) {
			$sms_report_db = pc_base::load_model('sms_report_model');
			$mobile_verify = $_POST['mobile_verify'];
			$mobile = $_SESSION['mobile'];
			if($mobile){
				if(!preg_match('/^1([0-9]{10})$/',$mobile)) exit('check phone error');
				pc_base::load_app_func('global','sms');
				$posttime = SYS_TIME-600;
				$where = "`mobile`='$mobile' AND `posttime`>'$posttime'";
				$r = $sms_report_db->get_one($where,'id,id_code','id DESC');
				if($r && $r['id_code']==$mobile_verify) {
					$sms_report_db->update(array('id_code'=>''),$where);
					$userid = $_SESSION['userid'];
					$updateinfo = array();
					$password = random(8,"23456789abcdefghkmnrstwxy");
					$encrypt = random(6,"23456789abcdefghkmnrstwxyABCDEFGHKMNRSTWXY");
					$updateinfo['encrypt'] = $encrypt;
					$updateinfo['password'] = password($password, $encrypt);

					$this->db->update($updateinfo, array('userid'=>$userid));
					$rs = $this->db->get_one(array('userid'=>$userid),'phpssouid');
					if(pc_base::load_config('system', 'phpsso')) {
						//初始化phpsso
						$this->_init_phpsso();
						$this->client->ps_member_edit('', '', '', $password, $rs['phpssouid'], $encrypt);
					}
					$status = sendsms($mobile, $password, 5);
					if($status!==0) showmessage($status);
					$_SESSION['mobile'] = '';
					$_SESSION['userid'] = '';
					$_SESSION['code'] = '';
					showmessage("密码已重置成功！请查收手机",'?m=member&c=index&a=login');
				} else {
					showmessage("短信验证码错误！请重新获取！");
				}
			}else{
				showmessage("短信验证码已过期！请重新获取！");
			}
		} else {
			$siteid = isset($_REQUEST['siteid']) && trim($_REQUEST['siteid']) ? intval($_REQUEST['siteid']) : 1;
			$siteinfo = siteinfo($siteid);
			include template('member', 'forget_password_mobile');
		}
	}
	//通过用户名找回密码
	public function public_forget_password_username() {
		$step = intval($_POST['step']);
		$step = max($step,1);
		$this->_session_start();

		if(isset($_POST['dosubmit']) && $step==2) {
			//处理提交申请，以手机号为准
			if ($_SESSION['code'] != strtolower($_POST['code'])) {
				showmessage(L('code_error'), HTTP_REFERER);
			}
			$username = safe_replace($_POST['username']);

			$r = $this->db->get_one(array('username'=>$username),'userid,email');
			if($r['email']=='') {
				$_SESSION['userid'] = '';
				$_SESSION['code'] = '';
				showmessage("该账号没有绑定手机号码，请选择其他方式找回！");
			} else {
				$_SESSION['userid'] = $r['userid'];
				$_SESSION['email'] = $r['email'];
			}
			$email_arr = explode('@',$r['email']);
			include template('member', 'forget_password_username');
		} elseif(isset($_POST['dosubmit']) && $step==3) {
			$sms_report_db = pc_base::load_model('sms_report_model');
			$mobile_verify = $_POST['mobile_verify'];
			$email = $_SESSION['email'];
			if($email){
				if(!preg_match('/^([a-z0-9_]+)@([a-z0-9_]+).([a-z]{2,6})$/',$email)) exit('check email error');
				if($_SESSION['emc_times']=='' || $_SESSION['emc_times']<=0){
					showmessage("验证次数超过5次,验证码失效，请重新获取邮箱验证码！",HTTP_REFERER,3000);
				}
				$_SESSION['emc_times'] = $_SESSION['emc_times']-1;
				if($_SESSION['emc']!='' && $_POST['email_verify']==$_SESSION['emc']) {

					$userid = $_SESSION['userid'];
					$updateinfo = array();
					$password = random(8,"23456789abcdefghkmnrstwxy");
					$encrypt = random(6,"23456789abcdefghkmnrstwxyABCDEFGHKMNRSTWXY");
					$updateinfo['encrypt'] = $encrypt;
					$updateinfo['password'] = password($password, $encrypt);

					$this->db->update($updateinfo, array('userid'=>$userid));
					$rs = $this->db->get_one(array('userid'=>$userid),'phpssouid');
					if(pc_base::load_config('system', 'phpsso')) {
						//初始化phpsso
						$this->_init_phpsso();
						$this->client->ps_member_edit('', '', '', $password, $rs['phpssouid'], $encrypt);
					}
					$_SESSION['email'] = '';
					$_SESSION['userid'] = '';
					$_SESSION['emc'] = '';
					$_SESSION['code'] = '';
					pc_base::load_sys_func('mail');
					sendmail($email, '密码重置通知', "您在".date('Y-m-d H:i:s')."通过密码找回功能，重置了本站密码。");
					include template('member', 'forget_password_username');
					exit;
				} else {
					showmessage("验证码错误！请重新获取！",HTTP_REFERER,3000);
				}
			} else {
				showmessage("非法请求！");
			}
		} else {
			include template('member', 'forget_password_username');
		}
	}

	//邮箱获取验证码
	public function public_get_email_verify() {
		pc_base::load_sys_func('mail');
		$this->_session_start();
		$code = $_SESSION['emc'] = random(8,"23456789abcdefghkmnrstwxy");
		$_SESSION['emc_times']=5;
		$message = '您的验证码为：'.$code;

		sendmail($_SESSION['email'], '邮箱找回密码验证', $message);
		echo '1';
	}
    /*
     * 金币商城
     */
    public function shop(){
        $memberinfo = $this->memberinfo;
        //初始化phpsso
        $phpsso_api_url = $this->_init_phpsso();
        //获取头像数组
        $avatar = $this->client->ps_getavatar($this->memberinfo['phpssouid']);

        include template('member', 'shop');

    }
    /**
     * 用户中心功能↓↓
     */
    
    //签到奖励
    public function sign(){
        $memberinfo = $this->memberinfo;
        $member=$this->db->get_one(array('userid'=>$memberinfo['userid']));
         
        $start = mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));//当天起始时间
        $beginYesterday=mktime(0,0,0,date('m'),date('d')-1,date('Y'));//昨天起始时间
        $endYesterday=mktime(0,0,0,date('m'),date('d'),date('Y'))-1;//昨天结束时间
         
        if($member['sign_time'] >= $start){
            $data=array('static'=>0,'msg'=>'你今天已经签到过了哦');//判断当天已签到过
            die(json_encode($data));exit();
        }elseif($member['sign_time']=='0' || $member['sign_time'] < $beginYesterday ){//首次签到或者签到中断
            $updatearr = array('sign_time'=>time(), 'sign_num'=>'1','integral'=>'+=15','gold'=>'+=20');
            $data=array('static'=>1,'msg'=>'sign_success','integral'=>'10','gold'=>'15');
        }elseif($member['sign_num'] > 0 && $member['sign_time'] >= $beginYesterday && $member['sign_time'] <= $endYesterday){//连续签到
            if($member['sign_num'] > 0 && $member['sign_num'] < 6){//1-6天
                $updatearr = array('sign_time'=>time(), 'sign_num'=>'+=1','  integral'=>'+=15','gold'=>'+=20');
                $data=array('static'=>1,'msg'=>'sign_success','integral'=>'10','gold'=>'15');
            }
            if($member['sign_num'] >= 6 && $member['sign_num'] < 15){//7-15天
                $updatearr = array('sign_time'=>time(), 'sign_num'=>'+=1','integral'=>'+=20','gold'=>'+=30');
                $data=array('static'=>1,'msg'=>'sign_success','integral'=>'15','gold'=>'25');
            }
            if($member['sign_num'] >= 15 && $member['sign_num'] < 30){//16-30天
                $updatearr = array('sign_time'=>time(), 'sign_num'=>'+=1','integral'=>'+=25','gold'=>'+=40');
                $data=array('static'=>1,'msg'=>'sign_success','integral'=>'20','gold'=>'35');
            }
            if($member['sign_num'] >=30){//30天以上
                $updatearr = array('sign_time'=>time(), 'sign_num'=>'+=1','integral'=>'+=35','gold'=>'+=55');
                $data=array('static'=>1,'msg'=>'sign_success','integral'=>'30','gold'=>'50');
            }
        }
        $result=$this->db->update($updatearr, array('userid'=>$memberinfo['userid']));
        if($result==1){
            die(json_encode($data));
        }
         
    }
    
    //下载游戏送金币积分
    public function download(){
         
        $this->down_db = pc_base::load_model('download_user_model');
         
        $gameid=$_GET['gameid'];
        $memberinfo = $this->memberinfo;
        $start = mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));//当天起始时间
         
        $down=$this->down_db->get_one(array('game_id'=>$gameid,'userid'=>$memberinfo['userid']));//判断是否已下载过该游戏
        if($down){
            exit();
        }else{
            $this->down_db->insert(array('game_id'=>$gameid, 'userid'=>$memberinfo['userid'],'username'=>$memberinfo['username'],'down_time'=>time()));
            $down_count=$this->down_db->count(" userid = '".$memberinfo['userid']."' and down_time >= '$start'");//统计当天已下载几款游戏
            if($down_count<10){//每天最多领取10次奖励
                $updatearr = array('integral'=>'+=2','gold'=>'+=2');
                $result=$this->db->update($updatearr, array('userid'=>$memberinfo['userid']));
            }else{
                exit();
            }
        }
         
    }
    
    //分享网站获取奖励
    public function share_reward(){
         
        $this->share_db = pc_base::load_model('share_model');
         
        $comment_id=$_GET['id'];
        $memberinfo = $this->memberinfo;
        $start = mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));//当天起始时间
         
        $share=$this->share_db->get_one(array('comment_id'=>$comment_id,'userid'=>$memberinfo['userid']));//判断是否已分享过该文章
        if($share){
            exit();
        }else{
            $this->share_db->insert(array('comment_id'=>$comment_id, 'userid'=>$memberinfo['userid'],'username'=>$memberinfo['username'],'share_time'=>time()));
            $share_count=$this->share_db->count(" userid = '".$memberinfo['userid']."' and share_time >= '$start'");//统计当天已分享过几篇文章
            if($share_count<3){//每天最多领取3次奖励
                $updatearr = array('integral'=>'+=10','gold'=>'+=10');
                $result=$this->db->update($updatearr, array('userid'=>$memberinfo['userid']));
            }else{
                exit();
            }
        }
    }
    //我的礼包
    public function my_libao(){
        $memberinfo = $this->memberinfo;
        $member=$this->member_detail();
        $paihang=$this->paihang();
        $new=$this->message_ts();
        $page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
         
        include template('member', 'my_libao');
    }
    
    //我下载的游戏
    public function my_game(){
        $memberinfo = $this->memberinfo;
        $member=$this->member_detail();
        $paihang=$this->paihang();
        $new=$this->message_ts();
        $page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
    
        include template('member', 'my_game');
    }
    
    //我的等级
    public function my_level(){
        $memberinfo = $this->memberinfo;
        $member=$this->member_detail();
        $do=$this->do_task();
        $paihang=$this->paihang();
        $new=$this->message_ts();
        $this->cash_db = pc_base::load_model('shop_cash_model');
        $start = mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));//当天起始时间
        $cash=$this->cash_db->select("userid='".$member['userid']."' and cash_time >= '$start'",'shopid', $limit = '',$order = 'id DESC');//获取当天兑换的商品id
        foreach ($cash as $v){
            $shops[]=$v['shopid'];//二维数组转换为一维数组
        }
        $page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
    
        include template('member', 'my_level');
    }
    
    //等级说明
    public function level_explain(){
        $member=$this->member_detail();
        $paihang=$this->paihang();
        $new=$this->message_ts();
        include template('member', 'level_explain');
    }
    
    //金币商城
    public function gold_shop(){
        $member=$this->member_detail();
        $paihang=$this->paihang();
        $new=$this->message_ts();
        $this->cash_db = pc_base::load_model('shop_cash_model');
        $start = mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));//当天起始时间
        $cash=$this->cash_db->select("userid='".$member['userid']."' and cash_time >= '$start'",'shopid', $limit = '',$order = 'id DESC');//获取当天兑换的商品id
        foreach ($cash as $v){
            $shops[]=$v['shopid'];
        }
        $page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
        include template('member', 'gold_shop');
    }
    
    //我的兑换
    public function my_cash(){
        $member=$this->member_detail();
        $paihang=$this->paihang();
        $new=$this->message_ts();
        $page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
        include template('member', 'my_cash');
    }
    
    
    //金币商城兑换
    public function cash_do(){
        $shopid=$_GET['shopid'];
         
        $this->cash_db = pc_base::load_model('shop_cash_model');
        $this->shop_db = pc_base::load_model('shop_model');
         
        $memberinfo = $this->memberinfo;
        $start = mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));//当天起始时间
        $grade = include(PHPCMS_PATH.'/phpcms/modules/member/grade.php');//加载积分等级配置文件
        $info=$this->shop_db->get_one("id='$shopid' and status=99 ");
         
        if($info['num']-$info['dh_num']==0){
            $data=array('static'=>0,'msg'=>'没有了，已兑换完了！');//该商品已被兑换完
            die(json_encode($data));
        }else{
            $cash=$this->cash_db->get_one("shopid = '$shopid' and  userid = '".$memberinfo['userid']."' and cash_time >= '$start'");//判断当天是否已兑换过该商品
            if($cash){
                $data=array('static'=>0,'msg'=>'你今天已经兑换过该商品了');
                die(json_encode($data));
            }else{
                $member=$this->db->get_one(array('userid'=>$memberinfo['userid']));//获取用户信息
                foreach ($grade as $v){
                    if($member['integral']>=$v['min'] && $member['integral']<=$v['max']){
                        $level=$v['level'];//获取当前等级
                    }
                }
                if($level<$info['lv'] || $member['gold']<$info['gold_coin']){//判断所需等级和金币是否足够
                    $data=array('static'=>0,'msg'=>'兑换等级或者金币不够哦');
                    die(json_encode($data));
                }else{
                    $this->cash_db->insert(array('shopid'=>$shopid, 'shopname'=>$info['title'],'userid'=>$memberinfo['userid'],'username'=>$memberinfo['username'],'email'=>$member['email'],'cash_time'=>time()));
                    $updatearr = array('dh_num'=>'+=1');//已兑换数量+1
                    $updatearr2 = array('gold'=>'-='.$info['gold_coin']);//扣除用户兑换所需金币
                    $this->db->update($updatearr2, array('userid'=>$memberinfo['userid']));//更新用户金币
                    $result=$this->shop_db->update($updatearr, array('id'=>$shopid));//更新商品兑换数量
                    $data=array('static'=>1,'msg'=>'兑换成功');
                    die(json_encode($data));
                }
                 
            }
        }
         
         
    }
    
    //用户等级详细信息
    public function member_detail(){
         
        $memberinfo = $this->memberinfo;
        $this->detail_db = pc_base::load_model('member_detail_model');
        $member=$this->db->get_one(array('userid'=>$memberinfo['userid']));//获取用户信息
        $grade = include(PHPCMS_PATH.'/phpcms/modules/member/grade.php');//加载等级配置文件
         
        $member_detail=$this->detail_db->get_one(array('userid'=>$memberinfo['userid']));
         
        if($member_detail!=''){
            $i=6;
            foreach($member_detail as $key =>$getoutvisnull)
            {
                if(empty($getoutvisnull))
                {
                    $i--;
                }
            }
        }else{
            $i=0;
        }
        $member['wanzd']=ceil(($i/5)*100);//资料完整度
        $sign_num=$grade['sign_num'];
         
        foreach ($grade as $v){
            if($member['integral']>=$v['min'] && $member['integral']<=$v['max']){
                $member['level']=$v['level'];
                $member['level_name']=$v['name'];
                $member['max']=$v['max']+1;
                $member['c']=$member['integral']-$v['min'];//
            }
        }
        if($member['level']==0){
            $member['need_point']=100;//升级所需积分
        }else{
            $member['need_point']=($member['level']+1)*100;//升级所需积分
        }
        $member['percent']=ceil(($member['c']/$member['need_point'])*100);//计算升级还需多少积分百分比
        $beginYesterday=mktime(0,0,0,date('m'),date('d')-1,date('Y'));//昨天起始时间
         
        if($member['sign_time']=='0' || $member['sign_time'] < $beginYesterday ){
            $member['sign_num_next']=1;//签到中断或者首次签到
        }else{
            $member['sign_num_next']=$member['sign_num']+1;//加上当天签到后的总共签到天数
        }
        foreach ($sign_num as $s){
            if($member['sign_num_next'] >= $s['min_day'] && $member['sign_num_next'] < $s['max_day']){
                $member['gold_next']=$s['gold'];//明日签到获得的金币
                $member['point_next']=$s['integral'];//明日签到获得的积分
            }
        }
        $start = mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));//当天起始时间
        if($member['sign_time']>=$start){
            $member['is_sign']=1;//当天已签到
        }else{
            $member['is_sign']=0;
        }
        //初始化phpsso
        $phpsso_api_url = $this->_init_phpsso();
        //获取头像数组
        $avatar = $this->client->ps_getavatar($this->memberinfo['phpssouid']);
         
        $grouplist = getcache('grouplist');
        $memberinfo['groupname'] = $grouplist[$memberinfo[groupid]]['name'];
         
        if($avatar){
            $member['img']=$avatar['180'];
        }
         
        return $member;
         
    }
    
    //签到排行榜
    public function paihang(){
        $memberinfo = $this->memberinfo;
        $grade = include(PHPCMS_PATH.'/phpcms/modules/member/grade.php');//加载等级配置文件
         
        $panghang=$this->db->select("",'*', $limit = '10',$order = 'sign_num DESC');//查询签到天数前十名的用户
         
        foreach ($grade as $v){
            for ($i=0;$i<count($panghang);$i++){
                if($panghang[$i]['integral']>=$v['min'] && $panghang[$i]['integral']<=$v['max']){
                    $panghang[$i]['level_name']=$v['name'];//根据用户积分匹配用户等级名称
                }
            }
        }
        $ph=$this->db->count("sign_num > (SELECT sign_num  FROM 93636_member WHERE userid='".$memberinfo['userid']."') ");
        $panghang['my_ph']=$ph+1;//我的签到排行
        return $panghang;
         
    }
    
    //系统消息
    public function message(){
        include template('member', 'my_message');
    }
    
    //公告
    public function notice(){
        $member=$this->member_detail();
        $paihang=$this->paihang();
        $new=$this->message_ts();
        $this->notice_db = pc_base::load_model('announce_model');
        $where="passed=1 ";
        $page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
        $pagesize=10;//每页显示条数
        $offset = ($page - 1) * $pagesize;
        $total = $this->notice_db->count($where);//总记录数
        $infos = $this->notice_db->select($where, '*', $offset.','.$pagesize,'aid DESC');//
        $pagenum=ceil($total/$pagesize);//总页数
        $next=$page+1;//下一页
    
        $pages=$page.'/'.$pagenum;
        if($page>1){
            $previous=$page-1;
            $pages.=' <a href="http://www.93636.com/index.php?m=member&c=index&a=notice&page='.$previous.'">上一页</a>';
        }
        if($pagenum>1 && $page < $pagenum){
            $pages.=' <a href="http://www.93636.com/index.php?m=member&c=index&a=notice&page='.$next.'">下一页</a>';
        }
        include template('member', 'notice');
    }
    
    //私信
    public function private_letter(){
        $memberinfo = $this->memberinfo;
        $member=$this->member_detail();
        $paihang=$this->paihang();
        $new=$this->message_ts();
        $this->message_db = pc_base::load_model('message_model');
        $where="send_to_id='".$memberinfo['username']."' and status !=3";
        $page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
        $pagesize=10;//每页显示条数
        $offset = ($page - 1) * $pagesize;
        $total = $this->message_db->count($where);//总记录数
        $infos = $this->message_db->select($where, '*', $offset.','.$pagesize,'messageid DESC');
        $pagenum=ceil($total/$pagesize);//总页数
        $next=$page+1;//下一页
         
        $pages=$page.'/'.$pagenum;
        if($page>1){
            $previous=$page-1;
            $pages.=' <a href="http://www.93636.com/index.php?m=member&c=index&a=private_letter&page='.$previous.'">上一页</a>';
        }
        if($pagenum>1 && $page < $pagenum){
            $pages.=' <a href="http://www.93636.com/index.php?m=member&c=index&a=private_letter&page='.$next.'">下一页</a>';
        }
        include template('member', 'private_letter');
    }
    
    //修改私信状态为已读---点击弹窗
    //statud:1.未读；2.已读;3.删除
    public function message_read(){
        $messageid=$_GET['messageid'];
        $this->message_db = pc_base::load_model('message_model');
        $updatearr = array('status'=>'2');
        $result=$this->message_db->update($updatearr, array('messageid'=>$messageid));//
    }
    
    //用户中心删除私信
    public function message_del(){
        $messageid=$_POST['messageid'];//消息id
        $newstr = rtrim($messageid, ",");
        /* $message = explode(',',$messageid);
         array_pop($message); */
         
        $this->message_db = pc_base::load_model('message_model');
        $updatearr = array('status'=>'3');
        $result=$this->message_db->update($updatearr, "messageid in ($newstr)");//
         
    }
    
    //私信全部标记为已读
    public function read(){
        $memberinfo = $this->memberinfo;
        $this->message_db = pc_base::load_model('message_model');
        $updatearr = array('status'=>'2');
        $result=$this->message_db->update($updatearr, array('send_to_id'=>$memberinfo['username'],'status'=>'1'));//将当前用户的所有消息标记为已读状态
    }
    
    //公告全部标记为已读
    public function read_announce(){
        $memberinfo = $this->memberinfo;
        $this->notice_db = pc_base::load_model('announce_model');
        $messageid=$_POST['messageid'];
        if($messageid==''){
            exit();
        }
        $newstr = rtrim($messageid, ",");
        $notice=$this->notice_db->get_one(array('aid'=>$messageid));
        $notice_id = explode(',',$notice['read_user']);//分割字符串
        if(empty($notice['read_user'])){//已读用户为空
            $result=$this->notice_db->update("read_user='".$memberinfo['userid']."'", "aid in ($newstr)");
        }else{
            $result=$this->notice_db->update("read_user=concat(read_user,',".$memberinfo['userid']."')", "aid in ($newstr)");//将当前用户的所有公告标记为已读状态
        }
         
    }
    //修改公告状态为已读---点击弹窗
    public function notice_read(){
        $messageid=$_GET['messageid'];
        $memberinfo = $this->memberinfo;
        $this->notice_db = pc_base::load_model('announce_model');
        $notice=$this->notice_db->get_one(array('aid'=>$messageid));
        $notice_id = explode(',',$notice['read_user']);
        if(empty($notice['read_user'])){//已读用户为空
            $result=$this->notice_db->update("read_user='".$memberinfo['userid']."'", "aid in ($messageid)");//
        }elseif(in_array($memberinfo['userid'],$notice_id)){
            exit();
        }else{
            $result=$this->notice_db->update("read_user=concat(read_user,',".$memberinfo['userid']."')", "aid in ($messageid)");//
        }
    }
    
    //判断当天已完成哪些任务
    public function do_task(){
        $memberinfo = $this->memberinfo;
        $member=$this->db->get_one(array('userid'=>$memberinfo['userid']));
    
        $start = mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));//当天起始时间
        $startdate=strtotime(date("Y-m-d",time())) ;
        $enddate=$startdate+86399;
        if($member['sign_time'] >= $start){
            $do['sign']=1;//已签到
        }
        $this->down_db = pc_base::load_model('download_user_model');
        $down_count=$this->down_db->count(" userid = '".$memberinfo['userid']."' and down_time >= '$start'");//统计当天已下载几款游戏
        if($down_count >= 10){
            $do['down']=1;//下载游戏任务已完成
        }
        $this->share_db = pc_base::load_model('share_model');
        $share_count=$this->share_db->count(" userid = '".$memberinfo['userid']."' and share_time >= '$start'");//统计当天已分享过几篇文章
        if($share_count>=3){
            $do['share']=1;//分享任务已完成
        }
        // 	    $this->comment_data_db = pc_base::load_model('comment_data_model');
        // 	    $comment=$this->comment_data_db->count("userid = '".$memberinfo['userid']."' and creat_at >= '$startdate' and creat_at <= '$enddate' and status=1 ");
        // 	    if($comment>=2){
        // 	        $do['pinglun']=1;//评论任务已完成
        // 	    }
         
        return $do;
    }
    
    //未读消息提示
    public function message_ts(){
        $memberinfo = $this->memberinfo;
        $this->notice_db = pc_base::load_model('announce_model');
        $this->message_db = pc_base::load_model('message_model');
         
        $where="send_to_id='".$memberinfo['username']."' and status =1";
        $total_m = $this->message_db->count($where);//未读私信记录数
        $total_n = $this->notice_db->count("read_user NOT LIKE '%,".$memberinfo['userid'].",%'");//未读私信记录数
         
        if($total_n>0 || $total_m>0){
            $new=1;
        }else{
            $new=0;
        }
        return $new;
    }
    
    public function avtar(){
        //初始化phpsso
        $phpsso_api_url = $this->_init_phpsso();
        //获取头像数组
        $avatar = $this->client->ps_getavatar($this->memberinfo['phpssouid']);
        if($avatar){
            $img=$avatar['180'];
            $data=array('static'=>1,'msg'=>$img);
        }else{
            $data=array('static'=>0,'msg'=>'no');
        }
        die(json_encode($data));
    }



	/**
	 * 查找帐号发送请求
	 */

	public function mb_search(){
		$this->_session_start();
		$str = "ss223SSM84LX5894opwxDxXAdmxeLaZVYww2009";
		$time=strtotime("+1 week 3 days 7 hours 5 seconds");
		$sign=md5(md5($str)."22dDKEZA".$time);
		$this->mb_db = pc_base::load_model('mb_model');
		$user_id = $_SESSION['userid'];
		if(!empty($user_id)){
			$sql = "select DISTINCT  username as a from 93636_mb WHERE `user_id` = $user_id";
			$this->mb_db->query($sql);
			$list = $this->mb_db->fetch_array();
			$str='';
			foreach($list as $v){
				$a[]=$v['a'];
			}
			$str = implode('_',$a);
			$url = "http://admin.93636.com/admin.php/Api/Appapi/mb_ye/sign/".$sign."/str/".$str;
			echo $url;
			$data = file_get_contents("$url");
			echo $data;

		}else{
			exit(0);
		}






	}
}
?>