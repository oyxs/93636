<?php
defined('IN_PHPCMS') or exit('No permission resources.');
//模型缓存路径
define('CACHE_MODEL_PATH',CACHE_PATH.'caches_model'.DIRECTORY_SEPARATOR.'caches_data'.DIRECTORY_SEPARATOR);
pc_base::load_app_func('util','content');
pc_base::load_app_class('admin','admin',0);
pc_base::load_sys_class('form', '', 0);
pc_base::load_sys_class('format', '', 0);
class menu extends admin{
	public function __construct() {
		//parent::__construct();
		$this->db = pc_base::load_model('wx_menu_model');

		//权限判断
		// if(isset($_GET['catid']) && $_SESSION['roleid'] != 1 && ROUTE_A !='pass' && strpos(ROUTE_A,'public_')===false) {
		// 	$catid = intval($_GET['catid']);
		// 	$this->priv_db = pc_base::load_model('category_priv_model');
		// 	$action = $this->categorys[$catid]['type']==0 ? ROUTE_A : 'init';
		// 	$priv_datas = $this->priv_db->get_one(array('catid'=>$catid,'is_admin'=>1,'action'=>$action));
		// 	if(!$priv_datas) showmessage(L('permission_to_operate'),'blank');
		// }
	}
	/*
	**后台微信菜单列表
	**
	*/
	public function wx_menu(){
		include $this->admin_tpl('menu');
	}
	/**
	**菜单列表数据json
	**
	*/
	public function wx_menu_list(){


		$type = $_POST['type'];
		$newtime=$_POST['newtime'];
		$updatetime=$_POST['updatetime'];

		$sql="1=1 ";
		if($type){
			$sql=$sql."and type='$type'";
		}
		if ($newtime){
			$sql=$sql."and '". $newtime."' <= FROM_UNIXTIME(updatetime,'%Y-%m-%d %H:%i:%s') ";

		}
		 if ($updatetime){
			$sql=$sql."and FROM_UNIXTIME(updatetime,'%Y-%m-%d %H:%i:%s') <= '". $updatetime."'";
		}
		$count=$this->db->count($sql);
		$sql = $this->db->select($sql, '*','','id DESC');
		$rows=array();
		$col=array();
		$i=0;

		foreach ($sql as $r) {
			if ($r['type']=='view'){
				$r['typeName']="跳转URL";
			}
			if ($r['type']=='click'){
				$r['typeName']="点击推事件";
			}
			
			if ($r['lanwei']=='1'){
				$r['lanwei2']="第一栏";
			}
			if ($r['lanwei']=='2'){
				$r['lanwei2']="第二栏";
			}
			if ($r['lanwei']=='3'){
				$r['lanwei2']="第三栏";
			}
			$r['newtime'] = date("Y-m-d",$r['newtime']);
			$r['updatetime'] = date("Y-m-d",$r['updatetime']);
			$col[$i]=array("id" => $r['id'],"name" => $r['name'], "type" => $r['type'], "listorder" => $r['listorder'], "url" =>$r['url'],  "munukey" =>$r['munukey'],"typeName"=>$r['typeName'],"lanwei"=>$r['lanwei'], "lanwei2"=>$r['lanwei2']
			,"level" =>$r['level'],"username" =>$r['username'],"newtime" =>$r['newtime'], "starttime" =>$r['newtime'], "updatetime" =>$r['updatetime'],"endtime" =>$r['updatetime']);
			 $i++;
			
		}
		 echo '{"total":'.$count.',"rows":' . json_encode($col) . '}';
	}
	/**
	**添加、修改菜单
	**
	*/
	public function save_menu(){
		$id=$_POST['id'];
		$name = $_POST['name'];
		$type = $_POST['type'];
		$key = $_POST['munukey'];
		$url= $_POST['url'];
		$listorder= $_POST['listorder'];
		$lanwei=$_POST['lanwei'];
		$level = $_POST['level'];
		if(!$name){
			echo  '1';
			exit();
		}
		if(!$level){
			echo  '6';exit();
		}
		if($level == 2){
			if(!$type){
			echo  '2';exit();
			}
		}
		
		if($type == 'view'){
			if(!$url){
				echo  '4';exit();
			}
		}
		if($type == 'click'){
			if(!$key){
				echo  '3';exit();
			}
		}
		
		if(!$lanwei){
			echo  '5';exit();
		}

		$data=array("info"  => array("name" => $name,"type" => $type,"listorder" => $listorder,"url" => $url,"munukey" => $key,"lanwei" => $lanwei,"level" => $level,"username" => param::get_cookie('admin_username')));
		if($id){
			$data['info']['updatetime']=time();
			$this->db->update($data['info'],array('id'=>$id));
		}else{
			$data['info']['newtime']=time();
			$this->db->insert($data['info']);
		}
		echo  'OK';
	}
	/**
	**删除菜单
	**
	*/
	public function remove_menu(){
		$id=$_POST['ids'];
		if($id){
			$del=$this->db->delete(array('id'=>$id));
			if ($del){
				echo 'remove ok';
			}else {
				echo 'remove false';
			}
		}else{
			echo 'remove false';
		}
		
	}
}
?>