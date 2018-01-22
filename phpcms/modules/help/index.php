<?php
defined('IN_PHPCMS') or exit('No permission resources.');

class index {
	function __construct() {
		$this->libao_model = pc_base::load_model('libao_model');
        $this->gift_model = pc_base::load_model('gift_model');
        $this->game_model = pc_base::load_model('game_model');
        $this->member_model =pc_base::load_model('member_model');
	}

	private function _session_start() {
		$session_storage = 'session_'.pc_base::load_config('system','session_storage');
		pc_base::load_sys_class($session_storage);
	}


    /*
     * 判断登录状态
     */
    public function login_check(){
		$this->_session_start();
        $username=$_SESSION['username']?$_SESSION['username']:'';//param::get_cookie('_username');
        $member= $this->member_model->get_one(array('username'=>$username),'groupid');
        if(!empty($username)&&$member['groupid']==2){
            $data=array('static'=>1,'msg'=>$username);
            die(json_encode($data));
        }else{
            $data=array('static'=>0,'msg'=>'not_login');
            die(json_encode($data));
        }
    }

	/*
 	* maxip判断登录
 	*/
	public function ip_login_api(){
		header('Content-type: application/json');

		$jsoncallback = htmlspecialchars($_REQUEST ['jsoncallback']);
		$this->_session_start();
		$username = $_SESSION['username'];
		$userid = $_SESSION['userid'];
		$img=get_memberavatar($userid,1,45);
		if(!empty($username)){
			$data=array('static'=>1,'username'=>$username,'userid'=>$userid,'img'=>$img);
			echo $jsoncallback . "(" . json_encode($data) . ")";
			exit();
		}else{
			$data=array('static'=>0);
			echo $jsoncallback . "(" . json_encode($data) . ")";
			exit();

		}
	}


    /*
     * 新的领取礼包
     */
    public function get_code(){
		$this->_session_start();
        if(!empty($_POST['username'])&&intval($_POST['id'])){
            $id=intval($_POST['id']);
			if($_SESSION['username']!=$_POST['username']){
                unset($_SESSION['username']);
				$data=array('static'=>0,'msg'=>'账号未登录或超时，请重新登录。');
				die(json_encode($data));
			}
            $username=$_SESSION['username'];//$_POST['username'];
            $gift=$this->gift_model;
            $result=$gift->select("gift_id=$id  and username='$username'",'*', $limit = '0,1', $order = 'id DESC');
            if($result){
                $data=array('static'=>0,'msg'=>'你已领取过该礼包');
                die(json_encode($data));
            }else{
                $info=$gift->select("gift_id=$id and status=0 ",'*', $limit = '0,1', $order = 'id DESC');
				if(empty($info)){
					$data=array('static'=>0,'msg'=>'没有了，已领取完！');
					die(json_encode($data));
				}
                $code=$info[0]['code'];
                $u_id=$info[0]['id'];
				$get_ip=ip();
				$get_date2=strtotime(date('Y-m-d'));//$get_date-86400;
				$total1=$gift->count("username='$username' and status=1 and gettime>$get_date2");
				$total2=$gift->count("gettime>$get_date2 and status=1 and getip='$get_ip'");
				if($total1>5 || $total2>5){
					$data=array('static'=>0,'msg'=>'一个用户当天最多只能领取5个礼包，请勿非法操作！');
					die(json_encode($data));
				}
                $status=$gift->update(array('status'=>1,'username'=>$username,'gettime'=>time(),'getip'=>$get_ip),"id=$u_id");
				if($status){
				$data=array('static'=>1,'msg'=>$code);
				}else{
					$data=array('static'=>0,'msg'=>'领取失败！');
				}

                die(json_encode($data));
            }
        }

    }
	
/**
 * 微信礼包兑换方法
 * Enter description here ...
 * @param unknown_type $fahaoid 
 * @param unknown_type $keyword
 * @param unknown_type $fromUsername 用户微信ID
 */
function cardNumberWX($fahaoid,$keyword,$fromUsername){ 
	$this->_session_start();
	if ($fahaoid&&$keyword)
	{
		$gift=$this->gift_model;
		$libao=$this->libao_model;
		$time=time();
		 /**
		  *  判断礼包是否存在
		  * Enter description here ...
		  * @var unknown_type
		  */
     	$timesql=$libao->select("id='$fahaoid' and status='99'",'starttime,endtime,title,gamename', $limit = '0,1', $order = 'id DESC');
		if($timesql){
		   	$starttime=strtotime($timesql[0]['starttime']);
			$endtime=strtotime($timesql[0]['endtime']);
			/**
			  *  判断发号是否结束
			  * Enter description here ...
			  * @var unknown_type
			  */
			if($starttime>$time || $endtime<$time)
			{
					return  "notime" ;
			}
			$newstemp_r=$gift->select("gift_id='$fahaoid' and status = '0'",'id,code', $limit = '0,1', $order = 'id DESC');
		    
			//判断礼包是否存在
		    if($newstemp_r){
		    	
	//	    	//判断时间是否已经超时
	//	    	if($time>endcarddate){
	//	    		return "time_n";
	//	    	}
		    	$mnum=$gift->count("gift_id='$fahaoid' and status=1 and OpenID='$fromUsername'");
		    	//判断当前用户是否已经领取了这个礼包
				if($mnum>0){
		    		return "user_n";
		    	}
		    	//更新礼包
		    	$code_id=$newstemp_r[0]['id'];
		    	$update = $gift->update(array('status'=>1,'OpenID'=>$fromUsername,'gettime'=>time()),"id=$code_id"); 
				if($update)
				{
					$data=array('title'=>$timesql[0]['title'],'code'=>$newstemp_r[0]['code'],'id'=>$code_id);
					return $data;
	 				// return  $newstemp_r[0]['code'];
	 			}else{
	 				 return  "hasgot";
				}
			}else{
				$data=array('gamename'=>$timesql[0]['gamename'],'msg'=>'not');
				return $data;
			}
		}else {
			return "hasused";
		}
	}else{
		return "hasused";
	}
	
}
	
	
	/*
	 * 礼包剩余
	 */
	public function last() {
	    $id = intval($_GET["id"]);
	    $where = array("gift_id"=>$id,"status"=>0);
	    $code = $this->gift_model->count($where,"code");
	    echo $code;
	}
	
	/*
	 * 礼包剩余
	 */
	public function lasts() {
	    //$id = intval($_GET["id"]);
	    $id = isset($_GET["id"])?$_GET["id"]:'';
	    if($id==''){
		   $sql = "select count(id) as tol,gift_id from 93636_gift where status=0 group by gift_id";
	    }else{
		   $ids = explode(",", $id);
		   foreach($ids as $k=>$mv){
			  $ids[$k] = intval($mv);
		   }
		   $id = implode(",", $ids);
		   $sql = "select count(id) as tol,gift_id from 93636_gift where status=0 and gift_id in ($id) group by gift_id";
	    }
	    	    	    
	    $result = $this->gift_model->query($sql);
	    $data = array();
	    while($row = mysql_fetch_assoc($result)){
		    array_push($data, $row);
	    }
	    echo json_encode($data);
	}
	/*
	 * 获取有的标题，缩略图，游戏下载链接
	 */
	public function gethree() {
		$sql = "select id,title,thumb,anlink,applelink from 93636_game where anlink!='' group by id";
	    $result = $this->game_model->query($sql);
	    $data = array();
	    while($row = mysql_fetch_assoc($result)){
		    array_push($data, $row);
	    }
	    echo json_encode($data);
	}
	
	/*
	 * 顶踩查询接口
	 */
	public function getUpDown(){
	    $id = intval($_GET["id"]);
	    $where = array("id"=>$id);
	    $data = $this->game_model->get_one($where,"good,bad");
	    echo json_encode($data);
	}
	
	/*
	 * 顶
	 */
	public function up(){
	    $id = intval($_GET["id"]);
	    
		if(isset($_COOKIE["updown"])){
		    $libao = $_COOKIE["updown"];
		    $ids = explode("|",$libao);
		    if(in_array($_GET["id"], $ids)){
			    echo "已经评分过";exit;
		    }else{
				$libao = $libao."|".$id;
			    setcookie("updown", $libao, time()+3600*24);
		    }
	    }else{
		    setcookie("updown", $id, time()+3600*24);
	    }

	    $where = array("id"=>$id);
	    $data = $this->game_model->get_one($where,"good");
	    $this->game_model->update(array("good"=>intval($data["good"])+1),$where);
	    echo intval($data["good"])+1;
	}
	
	/*
     * 踩	
	 */
	public function down(){
	    $id = intval($_GET["id"]);
	    	    
	    if(isset($_COOKIE["updown"])){
		    $libao = $_COOKIE["updown"];
		    $ids = explode("|",$libao);
		    if(in_array($_GET["id"], $ids)){
			    echo "已经评分过";exit;
		    }else{
				$libao = $libao."|".$id;
			    setcookie("updown", $libao, time()+3600*24);
		    }
	    }else{
		    setcookie("updown", $id, time()+3600*24);
	    }
	    
	    $where = array("id"=>$id);
	    $data = $this->game_model->get_one($where,"bad");
	    $this->game_model->update(array("bad"=>intval($data["bad"])+1),$where);
	    echo intval($data["bad"])+1;
	}
	
	
	public function myfav(){
	    $phpcms_auth = param::get_cookie('auth');	    
	    $game_id = isset($_GET["game_id"])?intval($_GET["game_id"]):0;
	    if ($phpcms_auth && $game_id!=0){
	        $userid = param::get_cookie('_userid'); 
	        $myfav = pc_base::load_model('myfav_model');
	        $data = array("game_id"=>$game_id,"user_id"=>$userid);
	        $result = $myfav->insert($data,false,true);
	        if($result){
	           exit('0'); 
	        }
	    }
	    exit('1');
	}
	
	public function mydown(){
	    $phpcms_auth = param::get_cookie('auth');
	    $game_id = isset($_GET["game_id"])?intval($_GET["game_id"]):0;
	    if ($phpcms_auth && $game_id!=0){
	        $userid = param::get_cookie('_userid');
	        $mydown = pc_base::load_model('mydown_model');
	        
	        $data = array("game_id"=>$game_id,"user_id"=>$userid);
	        $result = $mydown->insert($data,false,true);
	        if($result){
	            exit('0');
	        }
	    }
	    exit('1');
	}
	/**
	**微信礼包自动回复
	**
	*/
	public function getPackXml($libaoname){
		$this->_session_start();
		$time=date('y-m-d',time());
		if ($libaoname)
		{
			$libao=$this->libao_model;
			$timesql=$libao->select("gamename='$libaoname' and starttime <= '".$time."' and endtime >= '".$time."' ",'id,title,gamename', $limit = '0,1', $order = 'id DESC');
			if($timesql){
				$data=array('title'=> trim($timesql[0]['title']),'gamename'=> trim($timesql[0]['gamename']),'code'=>$timesql[0]['id'].'#');
				return $data;
				//return $timesql[0]['id'].'#';
			}else {
				$data=array('title'=>$timesql[0]['title'],'gamename'=> trim($timesql[0]['gamename']),'code'=>'no');
				return $data;
				//return 'no';
			}
		}
	}
	/**
	**微信游戏自动回复
	**
	*/
	public function getGameXml($gamename){
		$this->_session_start();
		if ($gamename)
		{
			$game=$this->game_model;
			$timesql=$game->select("title='$gamename' ",'id,url', $limit = '0,1', $order = 'id DESC');
			if($timesql){
				return $timesql[0]['url'];
			}else {
				return 'no';
			}
		}
	}
	//判断礼包是否领取完
	public function is_null(){
		$this->_session_start();
		$id=intval($_POST['id']);
		$gift=$this->gift_model;
		$info=$gift->select("gift_id=$id and status=0 ",'id', $limit = '0,1', $order = 'id DESC');
		if(empty($info)){
			$data=array('static'=>0,'msg'=>'没有了，已领取完！');
		}else{
			$data=array('static'=>1,'msg'=>'success！');
		}
		die(json_encode($data));
	}
	//判断当天是否已对该用户推送消息
	public function is_send_ts($faid,$fromUsername){
		$this->_session_start();
		
		$gift=$this->gift_model;
		$info=$gift->select("OpenID='$fromUsername' and is_send=1 ",'id', $limit = '0,1', $order = 'id DESC');
		if(empty($info)){
			$update = $gift->update(array('is_send'=>1),array('id'=>$faid,'OpenID'=>$fromUsername,'status'=>1)); 
			return 'no';
		}else{
			return 'yse';
		}
	}
    //获取礼包剩余百分比数量
	public function libaonullp(){
		$id = $_POST['id'];
		$number = $_POST['number'];
		if(empty($id)||empty($number)){
			echo json_encode(0);
		}
		$game_db = pc_base::load_model('gift_model');
		$where="gift_id=".$id ." and status=0";
		$result=$game_db->count($where);
		if($result){
			$data=ceil(($result/$number)*100);
			echo json_encode($data);
		}else{
			echo json_encode(0);
		}

	}

	/*
	**微信语音素材
	**
	*/
	public function WX_Voice($array){
		$sc = pc_base::load_model('wx_sc_model');
		$array=$array['item'];
		foreach ($array as $key => $v) {
			$data = array("name"=>$v['name'],"media_id"=>$v['media_id']);
			$timesql=$sc->select("media_id='".$v['media_id']."' ",'id', $limit = '', $order = 'id DESC');
			if(empty($timesql)){
				$result = $sc->insert($data,false,true);
			}
			
		}
		 
	}

	public function get_Voice($gamename){
		if ($gamename)
		{
			$sc = pc_base::load_model('wx_sc_model');
			$gamename=$gamename.'.mp3';
			$timesql=$sc->select("name='$gamename' ",'id,media_id', $limit = '0,1', $order = 'id DESC');
			if($timesql){
				return $timesql[0]['media_id'];
			}else {
				return 'no';
			}
		}
		

	}

	//获取微信用户统计接口
	public function getusersummary($data=array(),$data2=array()){
		if($data['errmsg'] != '' || $data2['errmsg'] !=''){
			echo $data['errmsg'];exit;
		}
		$list=$data['list'];
		$list2=$data2['list'];
		foreach ($list as $k =>$v) {
			foreach ($list2  as $key => $value) {
				if($v['ref_date'] == $value['ref_date']){
					$list[$k]['cumulate_user'] = $value['cumulate_user'];
					$list[$k]['site_id'] = '3';
				}
			}
		}
		$json=json_encode($list);
		
		if($json == 'null' || $json == null){
			$json = 'no_more';
		}
    	$data =['data'=>$json];
		$ch = curl_init('http://mp-admin-api.ucretui.com/wxusers');  
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");  
        curl_setopt($ch, CURLOPT_POST, 1 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $datajson = curl_exec ($ch);
        echo $datajson;
	}
	//获取微信消息接口
	public function getupstreammsg($data=array()){
		if($data['errmsg'] != ''){
			echo $data['errmsg'];exit;
		}
		$list=$data['list'];
		$data=array();
		foreach ($list as $k =>$v) {
			$date = $v['ref_date'];
			if(array_key_exists($date,$data))
			{
			    $data[$date]['msg_user'] += $v['msg_user'];
			    $data[$date]['msg_count'] += $v['msg_count'];
			}else{
				$data[$v['ref_date']]['user_source']=$v['user_source'];
				$data[$v['ref_date']]['ref_date']=$v['ref_date'];
				$data[$v['ref_date']]['msg_user']=$v['msg_user'];
				$data[$v['ref_date']]['msg_count']=$v['msg_count'];
				$data[$v['ref_date']]['site_id']='3';
			}
		}
		$json=json_encode(array_values($data));
		if($json == 'null' || $json == null){
			$json = 'no_more';
		}
		
    	$data =['data'=>$json];
		$ch = curl_init('http://mp-admin-api.ucretui.com/wxmsgs');  
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");  
        curl_setopt($ch, CURLOPT_POST, 1 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $datajson = curl_exec ($ch);
        echo $datajson;
	}
/**
	**获取菜单列表数据
	**
	*/
	public function get_menu(){
		$this->db = pc_base::load_model('wx_menu_model');
		$sql = $this->db->select('', '*','','lanwei asc,level desc,listorder desc');
		return $sql;
	}
}