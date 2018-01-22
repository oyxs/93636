<?php 
defined('IN_PHPCMS') or exit('No permission resources.'); 
$session_storage = 'session_'.pc_base::load_config('system','session_storage');
pc_base::load_sys_class($session_storage);
pc_base::load_app_class('foreground','member');
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);
pc_base::load_app_func('global');

class deposit extends foreground {
	private $pay_db,$member_db,$account_db;
	function __construct() {
		if (!module_exists(ROUTE_M)) showmessage(L('module_not_exists')); 
		parent::__construct();
		$this->pay_db = pc_base::load_model('pay_payment_model');
		$this->account_db = pc_base::load_model('pay_account_model');
		$this->_username = param::get_cookie('_username');
		$this->_userid = intval(param::get_cookie('_userid'));
		$this->handle = pc_base::load_app_class('pay_deposit');
	}

	public function init() {
		pc_base::load_app_class('pay_factory','',0);
		$where = '';
		$page = $_GET['page'] ? intval($_GET['page']) : '1';
		$where = "AND `userid` = '$this->_userid'";
		$start = $end = $status = '';
		if($_GET['dosubmit']){
			$start_addtime = $_GET['info']['start_addtime'];
			$end_addtime = $_GET['info']['end_addtime'];
			$status = safe_replace($_GET['info']['status']);
			if($start_addtime && $end_addtime) {
				$start = strtotime($start_addtime.' 00:00:00');
				$end = strtotime($end_addtime.' 23:59:59');
				$where .= "AND `addtime` >= '$start' AND  `addtime` <= '$end'";				
			}
			if($status) $where .= "AND `status` LIKE '%$status%' ";			
		}
		if($where) $where = substr($where, 3);
		$infos = $this->account_db->listinfo($where, 'addtime DESC', $page, '15');
		if (is_array($infos) && !empty($infos)) {
			foreach($infos as $key=>$info) {
				if($info['status']=='unpay' && $info['pay_id']!= 0 && $info['pay_id']) {
					$payment = $this->handle->get_payment($info['pay_id']);
					$cfg = unserialize_config($payment['config']);
					$pay_name = ucwords($payment['pay_code']);
					
					$pay_fee = pay_fee($info['money'],$payment['pay_fee'],$payment['pay_method']);
					$logistics_fee = $info['logistics_fee'];
					$discount = $info['discount'];			
					// calculate amount
					$info['price'] = $info['money'] + $pay_fee + $logistics_fee + $discount;			
					// add order info
					$order_info['id']	= $info['trade_sn'];
					$order_info['quantity']	= $info['quantity'];
					$order_info['buyer_email']	= $info['email'];
					$order_info['order_time']	= $info['addtime'];
					
					//add product info
					$product_info['name'] = $info['contactname'];
					$product_info['body'] = $info['usernote'];
					$product_info['price'] = $info['price'];
					
					//add set_customerinfo
					$customerinfo['telephone'] = $info['telephone'];
					if($payment['is_online'] === '1') {
						$payment_handler = new pay_factory($pay_name, $cfg);		
						$payment_handler->set_productinfo($product_info)->set_orderinfo($order_info)->set_customerinfo($customer_info);
						$infos[$key]['pay_btn'] = $payment_handler->get_code('value="'.L('pay_btn').'" class="pay-btn"');					
					}
					
				} else {
					$infos[$key]['pay_btn'] = '';
				}
			}
		}
		foreach(L('select') as $key=>$value) {
			$trade_status[$key] = $value;
		}
		$pages = $this->account_db->pages;
		include template('pay', 'pay_list');		
	}
	
	public function pay() {	
		$memberinfo = $this->memberinfo;
		$pay_types = $this->handle->get_paytype();
		$trade_sn = create_sn();
		param::set_cookie('trade_sn',$trade_sn);
		$show_validator = 1;
		include template('pay', 'deposit');
	}


	/*
	 * 新购买米币
	 */
	public function pay_mb(){
		if(!empty($_GET['msg'])){
			$show=trim(new_htmlentities($_GET['msg']));//支付成功条件
		}
		$this->mb_game = pc_base::load_model('mb_game_model');
		$game = $this->mb_game->select();
		$pay_name=!empty($_SESSION['username'])?$_SESSION['username']:showmessage("请登录后再进行充值", "http://www.93636.com/index.php?m=member&c=index&a=login");
		//$pay_name=$_SESSION['username'];
		$number=date("ymdhis");//订单号
		$trade_name='93636米币充值';//商品名称
		$trade_description='游戏平台米币充值';//商品描述
		include template('pay', 'pay_mb');
	}

	/*
	 * 新订单查询
	 */
	public function pay_select(){
		$last_date = strtotime("-1 day");
		$last_week = strtotime("-1 week");
		$last_mom =strtotime("-1 month");
		$this->mb_db = pc_base::load_model('mb_model');
		if(!isset($_POST['dosubmit'])){//默认
		$user_id=$_SESSION['userid'];
			$data=$this->mb_db->select(array('user_id'=>$user_id),'create_time,number,type,mb,pay,pay_status','','id desc');
		die(json_encode($data));
		}elseif(isset($_POST['dosubmit'])){//搜索
			$pay_time = !empty($_POST['pay_time'])?intval($_POST['pay_time']):'';
			$pay_status = isset($_POST['pay_status'])?intval($_POST['pay_status']):'';
			$number=!empty($_POST['number'])?trim(new_htmlentities($_POST['number'])):'';

			$pay_time_array=array(//查询时间数组
					'1'=>'28832400',
					'2'=>$last_date,
					'3'=>$last_week,
					'4'=>$last_mom
			);
			$where = "create_time>$pay_time_array[$pay_time]";

			if($pay_status!=2){
				$where=$where."&&pay_status=$pay_status";
			};
			if(!empty($number)){
				$where=$where."&&number=$number";
			};

			$data=$this->mb_db->select($where,'create_time,number,type,mb,pay,pay_status','','id desc');
			die(json_encode($data));

		}

	}


	/**
	 * 用户充值前订单提交接口
	 */
	public function indent(){
		if($_POST['dosubmit']==1){
			$user_id=$_SESSION['userid'];//会员userid
			$member_name = $_SESSION['username'];//会员名
			$type=intval($_POST['type']);//支付方式
			$pay = intval($_POST['pay']);
			$number=trim(new_htmlentities($_POST['number']));//订单号
			$game_name=trim(new_htmlentities($_POST['game_name']));//充值游戏
			$trade_name=trim(new_htmlentities($_POST['trade_name']));//商品名称
			$trade_description=trim(new_htmlentities($_POST['trade_description']));//商品描述
			$username= trim(new_htmlentities($_POST['username']));
			$username_check= trim(new_htmlentities($_POST['username_check']));
			if($username!=$username_check){
				$data= array('static'=>0,'msg'=>'用户名输入不一致');
				die(json_encode($data));
			}

			if(empty($user_id)||empty($type)||empty($pay)||empty($number)||empty($game_name)||empty($trade_name)||empty($trade_description)||empty($username)){
				$data= array('static'=>0,'msg'=>'参数错误');
				die(json_encode($data));
			}
			$this->mb_db = pc_base::load_model('mb_model');
			$this->mb_rebate= pc_base::load_model('mb_rebate_model');
			$this->mb_rebate_log= pc_base::load_model('mb_rebate_log_model');
			$this->mb_rebate_log= pc_base::load_model('mb_rebate_log_model');
			$rebate = $this->mb_rebate->get_one(array('id'=>1));//获取返利设置表

			$arr=unserialize($rebate['rebate']);
			$now = time();

			if($now>=$rebate['start_time']&&$now<=$rebate['end_time']&&$rebate['static']==1){//符合返利条件 返利
				foreach($arr as $v){
					if($pay>=$v['m']){
						$rebate_v = $v['val']*10;
						break;
					}
				}

			}

			$info=array(
					'user_id'=>$user_id,
					'member_name'=>$member_name,
					'type'=>$type,
					'pay'=>$pay,
					'number'=>$number,
					'game_name'=>$game_name,
					'trade_name'=>$trade_name,
					'trade_description'=>$trade_description,
					'create_time'=>time(),
					'pay_status'=>0,
					'username'=>$username,
					'mb'=>$pay*10+$rebate_v,

			);
			$result=$this->mb_db->insert($info);//插入米币表

			if($result){
				$data=array('static'=>1,'msg'=>'订单成功');
				die(json_encode($data));
			}


		}

	}

	/**
	 * 获取93636会员下的游戏用户名
	 */
	public function get_game_username(){
		$username = $_SESSION['username'];//会员名
		if(empty($username)){
			$data =array('static'=>0,'msg'=>'登录超时');
			die(json_encode($data));
		}
		$this->mb_db = pc_base::load_model('mb_model');
		$sql = "select distinct username from `93636_mb` WHERE member_name= '$username'";
		$this->mb_db->query($sql);
		$list = $this->mb_db->fetch_array();
		$str='';
		foreach($list as $key=>$v){
			$str =$str.','. $v['username'];
		}
		$str = substr($str,1);
		$data= array('static'=>1,'msg'=>$str);
		die(json_encode($data));
	}


	/*
	 * 充值方式支付
	 */
	public function pay_recharge() {
		if(isset($_POST['dosubmit'])) {
			$code = isset($_POST['code']) && trim($_POST['code']) ? trim($_POST['code']) : showmessage(L('input_code'), HTTP_REFERER);
			if ($_SESSION['code'] != strtolower($code)) {
					showmessage(L('code_error'), HTTP_REFERER);
			}
			$pay_id = $_POST['pay_type'];
			if(!$pay_id) showmessage(L('illegal_pay_method'));
			$_POST['info']['name'] = safe_replace($_POST['info']['name']);
			$payment = $this->handle->get_payment($pay_id);
			$cfg = unserialize_config($payment['config']);
			$pay_name = ucwords($payment['pay_code']);
			if(!param::get_cookie('trade_sn')) {showmessage(L('illegal_creat_sn'));}
			
			$trade_sn	= param::get_cookie('trade_sn');
			if(preg_match('![^a-zA-Z0-9/+=]!', $trade_sn)) showmessage(L('illegal_creat_sn'));

			$usernote = $_POST['info']['usernote'] ? $_POST['info']['name'].'['.$trade_sn.']'.'-'.new_html_special_chars(trim($_POST['info']['usernote'])) : $_POST['info']['name'].'['.$trade_sn.']';
			
			$surplus = array(
					'userid'      => $this->_userid,
					'username'    => $this->_username,
					'money'       => trim(floatval($_POST['info']['price'])),
					'quantity'    => $_POST['quantity'] ? trim(intval($_POST['quantity'])) : 1,
					'telephone'   => preg_match('/[^0-9\-]+/', $_POST['info']['telephone']) ? '' : trim($_POST['info']['telephone']),
					'contactname' => $_POST['info']['name'] ? trim($_POST['info']['name']).L('recharge') : $this->_username.L('recharge'),
					'email'       => is_email($_POST['info']['email']) ? trim($_POST['info']['email']) : '',
					'addtime'	  => SYS_TIME,
					'ip'		  => ip(),
					'pay_type'	  => 'recharge',
					'pay_id'      => $payment['pay_id'],		
					'payment'     => trim($payment['pay_name']),
					'ispay'		  => '1',
					'usernote'    => $usernote,
					'trade_sn'	  => $trade_sn,
			);
			
			$recordid = $this->handle->set_record($surplus);
			
			$factory_info = $this->handle->get_record($recordid);
			if(!$factory_info) showmessage(L('order_closed_or_finish'));
			$pay_fee = pay_fee($factory_info['money'],$payment['pay_fee'],$payment['pay_method']);
			$logistics_fee = $factory_info['logistics_fee'];
			$discount = $factory_info['discount'];
			
			// calculate amount
			$factory_info['price'] = $factory_info['money'] + $pay_fee + $logistics_fee + $discount;
			
			// add order info
			$order_info['id']	= $factory_info['trade_sn'];
			$order_info['quantity']	= $factory_info['quantity'];
			$order_info['buyer_email']	= $factory_info['email'];
			$order_info['order_time']	= $factory_info['addtime'];
			
			//add product info
			$product_info['name'] = $factory_info['contactname'];
			$product_info['body'] = $factory_info['usernote'];
			$product_info['price'] = $factory_info['price'];
			
			//add set_customerinfo
			$customerinfo['telephone'] = $factory_info['telephone'];
			if($payment['is_online'] === '1') {
				pc_base::load_app_class('pay_factory','',0);
				$payment_handler = new pay_factory($pay_name, $cfg);
				$payment_handler->set_productinfo($product_info)->set_orderinfo($order_info)->set_customerinfo($customer_info);
				$code = $payment_handler->get_code('value="'.L('confirm_pay').'" class="button"');	
			} else {
				$this->account_db->update(array('status'=>'waitting','pay_type'=>'offline'),array('id'=>$recordid));
				$code = '<div class="point">'.L('pay_tip').'</div>';
			}
		}
		include template('pay', 'payment_cofirm');		
	}	
	
	public function public_checkcode() {
		$code = $_GET['code'];
		if($_SESSION['code'] != strtolower($code)) {
			exit('0');
		} else {
			exit('1');
		}
	}
}
?>