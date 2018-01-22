<?php
defined('IN_PHPCMS') or exit('No permission resources.');
//模型缓存路径
define('CACHE_MODEL_PATH',CACHE_PATH.'caches_model'.DIRECTORY_SEPARATOR.'caches_data'.DIRECTORY_SEPARATOR);
pc_base::load_app_func('util','content');
pc_base::load_app_class('admin','admin',0);
pc_base::load_sys_class('form', '', 0);
pc_base::load_sys_class('format', '', 0);
class reply extends admin{
	public function __construct() {
		//parent::__construct();
		$this->reply = pc_base::load_model('wx_reply_model');
		$this->resource = pc_base::load_model('wx_resource_model');

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
	**后台微信关键词回复列表
	**
	*/
	public function wx_reply(){
		$datas = $this->reply->listinfo('','id desc',$_GET['page']);
		$pages = $this->reply->pages;
		include $this->admin_tpl('reply');
	}
	/*
	**后台微信关键词回复添加
	**
	*/
	public function reply_add() {
		if(isset($_POST['dosubmit'])) {

			$_POST['info']['updatetime']=time();
			$_POST['info']['author']=param::get_cookie('admin_username');
			$this->reply->insert($_POST['info']);
			
			//结束
			showmessage(L('add_success'),'?m=weixin&c=reply&a=wx_reply');
		} else {
			
			include $this->admin_tpl('reply_add');
		}
	}
	/*
	**后台微信关键词删除
	**
	*/
	public function reply_delete() {
		$_GET['id'] = intval($_GET['id']);
		$del=$this->reply->delete(array('id'=>$_GET['id']));
		showmessage(L('operation_success'),HTTP_REFERER);
	}
	/*
	**后台微信关键词修改
	**
	*/
	public function reply_edit() {
		if(isset($_POST['dosubmit'])) {
			$id = intval($_POST['id']);
			//print_r($_POST['info']);exit;
			$_POST['info']['updatetime']=time();
			$_POST['info']['author']=param::get_cookie('admin_username');
			$this->reply->update($_POST['info'],array('id'=>$id));
			
			//结束语言文件修改
			showmessage(L('operation_success'),'?m=weixin&c=reply&a=wx_reply');
		} else {
			$datas=$this->reply->get_one("`id` = '".$_GET['id']."'",'keyword,type,url,id,thumb,title,content','id DESC');
			include $this->admin_tpl('reply_edit');
		}
	}

	/***
	 * 后台搜索
	 */
	public function new_search_reply(){
	    $this->siteid = $this->get_siteid();
	    //搜索框
	    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
	    $updatetime = isset($_GET['updatetime']) ? $_GET['updatetime'] : '';
	    $end_time = $_GET['end_time'];
	    if(empty($end_time)){
	    	$end_time = date('Y-m-d H:i:s', SYS_TIME);
	    }
	    
	    if (isset($_GET['search'])) { 
			$where = '1=1 ';
			//搜索
			
			if(isset($_GET['updatetime']) && $_GET['updatetime']) {
				$updatetime = strtotime($_GET['updatetime']);
				$where .= " AND `updatetime` > '$updatetime'";
			}
			if($end_time) {
				$end_time = strtotime($end_time);
				$where .= " AND `updatetime` < '$end_time'";
			}
			if($updatetime>$end_time) showmessage(L('starttime_than_endtime'));
			if(isset($_GET['keyword']) && !empty($_GET['keyword'])) {
				$searchtype = intval($_GET['searchtype']);
				if($searchtype == 0 ) {
					$keyword = strip_tags(trim($keyword));
					$where .= " AND `keyword` like '%$keyword%'";
				} elseif($searchtype==1) {
					$keyword = intval($_GET['keyword']);
					$where .= " AND `id`='$keyword'";
				}
			}

             	$datas = $this->reply->listinfo($where,'id desc',$_GET['page']);
             	$pages = $this->reply->pages;

	    }
	     
	     
	    include $this->admin_tpl('reply');
	}
	/*
	**后台微信资源列表
	**
	*/
	public function wx_resource(){
		$datas = $this->resource->listinfo('','id desc',$_GET['page']);
		$pages = $this->resource->pages;
		include $this->admin_tpl('resource');
	}
	/*
	**后台微信资源添加
	**
	*/
	public function resource_add() {
		if(isset($_POST['dosubmit'])) {

			$_POST['info']['create_time']=time();
			$_POST['info']['create_user']=param::get_cookie('admin_username');
			$this->resource->insert($_POST['info']);
			
			//结束
			showmessage(L('add_success'),'?m=weixin&c=reply&a=wx_resource');
		} else {
			
			include $this->admin_tpl('resource_add');
		}
	}
	/*
	**后台微信资源删除
	**
	*/
	public function resource_delete() {
		$_GET['id'] = intval($_GET['id']);
		$del=$this->resource->delete(array('id'=>$_GET['id']));
		showmessage(L('operation_success'),HTTP_REFERER);
	}
	/*
	**后台微信资源修改
	**
	*/
	public function resource_edit() {
		if(isset($_POST['dosubmit'])) {
			$id = intval($_POST['id']);
			//print_r($_POST['info']);exit;
			$_POST['info']['create_time']=time();
			$_POST['info']['create_user']=param::get_cookie('admin_username');
			$this->resource->update($_POST['info'],array('id'=>$id));
			
			//结束语言文件修改
			showmessage(L('operation_success'),'?m=weixin&c=reply&a=wx_resource');
		} else {
			$datas=$this->resource->get_one("`id` = '".$_GET['id']."'",'id,title,catname,author,wp_url,pwd,thumb,descrition,type','id DESC');
			include $this->admin_tpl('resource_edit');
		}
	}

	/***
	 * 后台资源搜索
	 */
	public function new_search_resource(){
	    $this->siteid = $this->get_siteid();
	    //搜索框
	    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
	    $updatetime = isset($_GET['updatetime']) ? $_GET['updatetime'] : '';
	    $end_time = $_GET['end_time'];
	    if(empty($end_time)){
	    	$end_time = date('Y-m-d H:i:s', SYS_TIME);
	    }
	    
	    if (isset($_GET['search'])) {
			$where = '1=1 ';
			//搜索
			
			if(isset($_GET['updatetime']) && $_GET['updatetime']) {
				$updatetime = strtotime($_GET['updatetime']);
				$where .= " AND `create_time` > '$updatetime'";
			}
			if($end_time) {
				$end_time = strtotime($end_time);
				$where .= " AND `create_time` < '$end_time'";
			}
			if($updatetime>$end_time) showmessage(L('starttime_than_endtime'));
			if(isset($_GET['keyword']) && !empty($_GET['keyword'])) {
				$searchtype = intval($_GET['searchtype']);
				if($searchtype == 0 ) {
					$keyword = strip_tags(trim($keyword));
					$where .= " AND `title` like '%$keyword%'";
				} elseif($searchtype==1) {
					$keyword = intval($_GET['keyword']);
					$where .= " AND `id`='$keyword'";
				}
			}
			$searchtype2 = intval($_GET['searchtype2']);
			if(isset($searchtype2) && !empty($searchtype2) && $searchtype2 > 0) {
				$where .= " AND `catname`='$searchtype2'";
			}
			$searchtype3 = intval($_GET['searchtype3']);
			if(isset($searchtype3) && !empty($searchtype3) && $searchtype3 > 0) {
				$where .= " AND `type`='$searchtype3'";
			}
			
             	$datas = $this->resource->listinfo($where,'id desc',$_GET['page']);
             	$pages = $this->resource->pages;

	    }
	     
	     
	    include $this->admin_tpl('resource');
	}
}
?>