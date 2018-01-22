<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_sys_class('format', '', 0);
class index {
	function __construct() {		
		$this->db = pc_base::load_model('content_model');
        $this->api_db = pc_base::load_model('game_api_model');
		$this->siteid = isset($_GET['siteid']) && (intval($_GET['siteid']) > 0) ? intval(trim($_GET['siteid'])) : (param::get_cookie('siteid') ? param::get_cookie('siteid') : 1);
		param::set_cookie('siteid',$this->siteid);	
		$this->wap_site = getcache('wap_site','wap');
		$this->types = getcache('wap_type','wap');
		$this->wap = $this->wap_site[$this->siteid];
		define('WAP_SITEURL', $this->wap['domain'] ? $this->wap['domain'].'index.php?' : APP_PATH.'index.php?m=wap&siteid='.$this->siteid);
		if($this->wap['status']!=1) exit(L('wap_close_status'));
    }
	
	//展示首页
	public function init() {
		$WAP = $this->wap;
		$TYPE = $this->types;
		$WAP_SETTING = string2array($WAP['setting']);
		$GLOBALS['siteid'] = $siteid = max($this->siteid,1);
		$CATEGORYS = getcache('category_content_'.$siteid,'commons');
		$template = $WAP_SETTING['index_template'] ? $WAP_SETTING['index_template'] : 'index';
		if($_SERVER['HTTP_HOST']=='mnq.93636.com'){
			include template('wap', 'index_mnq');
		}
		else{
			include template('wap', $template);
		}
	}
	
    //展示列表页
	public function lists() {
	    $parentids = array();
		$WAP = $this->wap;			
		$TYPE = $this->types;
		$WAP_SETTING = string2array($WAP['setting']);
		$GLOBALS['siteid'] = $siteid = max($this->siteid,1);
		$typeid = intval($_GET['typeid']);		
		if(!$typeid) exit(L('parameter_error'));					
		$catid = $this->types[$typeid]['cat'];	
		$siteids = getcache('category_content','commons');
		$siteid = $siteids[$catid];
		$CATEGORYS = getcache('category_content_'.$siteid,'commons');
		
		$titles = include(PHPCMS_PATH.'/phpcms/modules/wap/wap_title.php');	
        $tit_le = $titles[$typeid];

		if(!isset($CATEGORYS[$catid])) exit(L('parameter_error'));
		$CAT = $CATEGORYS[$catid];
		$siteid = $GLOBALS['siteid'] = $CAT['siteid'];
		extract($CAT);	
		foreach($TYPE as $_t) $parentids[] = $_t['parentid'];
			
		$template = ($TYPE[$typeid]['parentid']==0 && in_array($typeid,array_unique($parentids))) ? $WAP_SETTING['category_template'] : $WAP_SETTING['list_template'];
		$mydata = require PHPCMS_PATH.'/phpcms/modules/wap/help.php';
		foreach ($mydata as $ks=>$v){
			if(strpos($ks,",$typeid,")!==false){
				$template = $v;
				break;
    		}
		}		
				
		$MODEL = getcache('model','commons');
		$modelid = $CAT['modelid'];
		$tablename = $this->db->table_name = $this->db->db_tablepre.$MODEL[$modelid]['tablename'];
		$total = $this->db->count(array('status'=>'99','catid'=>$catid));
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$pagesize = $WAP_SETTING['listnum'] ? intval($WAP_SETTING['listnum']) : 20 ;
		$offset = ($page - 1) * $pagesize;

		$list = $this->db->select(array('status'=>'99','catid'=>$catid), '*', $offset.','.$pagesize,'inputtime DESC');
		
		//构造wap url规则
		define('URLRULE', '/index.php?m=wap&c=index&a=lists&typeid={$typeid}~/index.php?m=wap&c=index&a=lists&typeid={$typeid}&page={$page}');
		$GLOBALS['URL_ARRAY'] = array('typeid'=>$typeid);
		
		$pages = wpa_pages($total, $page, $pagesize);
		if($_SERVER['HTTP_HOST']=='mnq.93636.com' && $typeid==10){
			include template('wap', 'game_mnq');
		}
		else if($_SERVER['HTTP_HOST']=='mnq.93636.com' && $typeid==119){
			include template('wap', 'heji_mnq');
		}
		else if($_SERVER['HTTP_HOST']=='mnq.93636.com' && $typeid==120){
			include template('wap', 'heji_mnq');
		}
		else if($_SERVER['HTTP_HOST']=='mnq.93636.com' && $typeid==121){
			include template('wap', 'heji_mnq');
		}
		else if($_SERVER['HTTP_HOST']=='mnq.93636.com' && $typeid==122){
			include template('wap', 'heji_mnq');
		}
		else if($_SERVER['HTTP_HOST']=='mnq.93636.com' && $typeid==123){
			include template('wap', 'heji_mnq');
		}
		else if($_SERVER['HTTP_HOST']=='mnq.93636.com' && $typeid==124){
			include template('wap', 'heji_mnq');
		}
		else if($_SERVER['HTTP_HOST']=='mnq.93636.com' && $typeid==125){
			include template('wap', 'heji_mnq');
		}
		else if($_SERVER['HTTP_HOST']=='m.93636.com' && $typeid==120){
			$typename="腾讯手游";
		    include template('wap', 'game_collection_list');//移动站游戏合集列表页
		}
		else if($_SERVER['HTTP_HOST']=='m.93636.com' && $typeid==121){
			 $typename="网易手游";
		    include template('wap', 'game_collection_list');//移动站游戏合集列表页
		}
		else if($_SERVER['HTTP_HOST']=='m.93636.com' && $typeid==122){
			$typename="三国题材";
		    include template('wap', 'game_collection_list');//移动站游戏合集列表页
		}
		else if($_SERVER['HTTP_HOST']=='m.93636.com' && $typeid==123){
			 $typename="西游题材";
		    include template('wap', 'game_collection_list');//移动站游戏合集列表页
		}
		else if($_SERVER['HTTP_HOST']=='m.93636.com' && $typeid==124){
			$typename="小说改编";
		    include template('wap', 'game_collection_list');//移动站游戏合集列表页
		}
		else if($_SERVER['HTTP_HOST']=='m.93636.com' && $typeid==125){
			 $typename="回合制";
		    include template('wap', 'game_collection_list');//移动站游戏合集列表页
		}
		else if($_SERVER['HTTP_HOST']=='m.93636.com' && $typeid==126){
		    $typename="女生必玩";
		    include template('wap', 'game_collection_list');//移动站游戏合集列表页
		}
		else if($_SERVER['HTTP_HOST']=='m.93636.com' && $typeid==127){
		    $typename="经典单机";
		    include template('wap', 'game_collection_list');//移动站游戏合集列表页
		}
		else{
			if($typeid==119){
		        include template('wap', 'game_collection');//移动站游戏合集首页
		    }else{
		        include template('wap', $template);
		    }
		}
	}	
	
    //展示内容页
	public function show() {
		$WAP = $this->wap;
		$WAP_SETTING = string2array($WAP['setting']);
		$TYPE = $this->types;
		$GLOBALS['siteid'] = $siteid = max($this->siteid,1);
		$typeid = $type_tmp = intval($_GET['typeid']);	
		$catid = intval($_GET['catid']);
		$id = intval($_GET['id']);
		if(!$catid || !$id) exit(L('parameter_error'));
		$siteids = getcache('category_content','commons');
		$siteid = $siteids[$catid];
		$CATEGORYS = getcache('category_content_'.$siteid,'commons');
		$page = intval($_GET['page']);
		$page = max($page,1);
		if(!isset($CATEGORYS[$catid]) || $CATEGORYS[$catid]['type']!=0) exit(L('information_does_not_exist','','content'));
		$this->category = $CAT = $CATEGORYS[$catid];
		$this->category_setting = $CAT['setting'] = string2array($this->category['setting']);
		$siteid = $GLOBALS['siteid'] = $CAT['siteid'];
		
		$MODEL = getcache('model','commons');
		$modelid = $CAT['modelid'];
		
		$tablename = $this->db->table_name = $this->db->db_tablepre.$MODEL[$modelid]['tablename'];
		$r = $this->db->get_one(array('id'=>$id));
        if($_GET['v']=='360'){
            $api_data=$this->api_db->get_one(array('game_id'=>$id),'downloadUrl');
            if(!empty($api_data)){
                $down_url=$api_data['downloadUrl'];
            }
        }elseif($_GET['v']=='gf'&&!empty($r['guanlink'])){
            $down_url=$r['guanlink'];
        }
        elseif($_GET['v']!='360'&&$_GET['v']!='gf'&&!empty($r['qmlink'])){
            $down_url=$r['qmlink'];
        }else{
            $down_url=$r['anlink'];
        }
		$gamename=$r['gamename'];//游戏名字
		if(!$r || $r['status'] != 99) showmessage(L('info_does_not_exists'),'blank');
		
		//上一页
		$previous_page = $this->db->get_one("`catid` = '$catid' AND `id`<'$id' AND `status`=99",'*','id DESC');
		//下一页
		$next_page = $this->db->get_one("`catid`= '$catid' AND `id`>'$id' AND `status`=99");
		if(empty($previous_page)) {
			$previous_page = array('title'=>L('这已经是第一篇内容了'), 'thumb'=>IMG_PATH.'nopic_small.gif', 'url'=>'javascript:alert(\''.L('这已经是第一篇内容了').'\');');
		}
		if(empty($next_page)) {
			$next_page = array('title'=>L('这已经是最后一篇内容了'), 'thumb'=>IMG_PATH.'nopic_small.gif', 'url'=>'javascript:alert(\''.L('这已经是最后一篇内容了').'\');');
		}
		
		$this->db->table_name = $tablename.'_data';
		$r2 = $this->db->get_one(array('id'=>$id));
		$rs = $r2 ? array_merge($r,$r2) : $r;

		//再次重新赋值，以数据库为准
		$catid = $CATEGORYS[$r['catid']]['catid'];
		$modelid = $CATEGORYS[$catid]['modelid'];
		
		require_once CACHE_MODEL_PATH.'content_output.class.php';
		$content_output = new content_output($modelid,$catid,$CATEGORYS);
		$data = $content_output->get($rs);
		extract($data);
		$mydata = array(
			"2"=>"1",  //第一个数字是栏目真实id，第二个数字是栏目typeid
			"29"=>"2",
			"30"=>"3",
			"31"=>"4",
			"17"=>"5",
			"18"=>"21",
			"19"=>"22",
			"20"=>"23",
			"27"=>"9",
			"12"=>"24",
			"13"=>"25",
			"25"=>"26",
			"26"=>"27",
			"24"=>"28",
			"6"=>"29",
			"7"=>"31",
			"8"=>"32",
			"9"=>"33",
			"10"=>"34",
			"11"=>"30",
			"5"=>"10",
			"32"=>"44",
			"33"=>"20",
			"34"=>"19",
			"35"=>"18",
			"36"=>"17",
			"37"=>"16",
			"38"=>"15",
			"39"=>"14",
			"41"=>"13",
			"42"=>"45",
			"118"=>"43",
			"119"=>"100",
			"120"=>"101",
			"121"=>"102",
			"122"=>"103",
			"123"=>"104",
			"124"=>"105",
			"125"=>"106",
			"141"=>"107",
			"128"=>"46",
			"152"=>"54",
			"149"=>"53",
			"150"=>"52",
			"151"=>"51",
			"130"=>"50",
			"132"=>"49",
			"133"=>"48",
			"153"=>"47",
			"154"=>"35",
			"155"=>"37",
			"156"=>"38",
			"157"=>"39",
			"158"=>"40",
			"159"=>"42",
			"160"=>"36",
			"161"=>"41",
			"171"=>"109",
			"162"=>"64",
			"163"=>"65",
			"164"=>"66",
			"165"=>"67",
			"166"=>"68",
			"167"=>"69",
			"168"=>"70",
			"169"=>"71",
			"170"=>"72",
			"173"=>"55",
			"174"=>"56",
			"175"=>"57",
			"176"=>"58",
			"177"=>"59",
			"179"=>"60",
			"180"=>"61",
			"178"=>"62",
			"181"=>"63",
			"182"=>"73",
			"183"=>"74",
			"184"=>"75",
			"185"=>"76",
			"186"=>"77",
			"188"=>"78",
			"189"=>"79",
			"187"=>"80",
			"190"=>"81",
			"191"=>"82",
			"192"=>"83",
			"193"=>"84",
			"194"=>"85",
			"195"=>"86",
			"196"=>"87",
			"197"=>"88",
			"198"=>"89",
			"199"=>"90",
			"200"=>"91",
			"201"=>"92",
			"202"=>"93",
			"203"=>"94",
			"204"=>"95",
			"205"=>"96",
			"206"=>"97",
			"207"=>"98",
			"208"=>"99",
			"209"=>"110",
			"210"=>"111",
			"211"=>"117",
			"212"=>"115",
			"213"=>"116",
			"214"=>"113",
			"215"=>"112",
			"216"=>"118",
			"217"=>"114",
			);
		$typeid = intval($mydata[$catid]);
		if($typeid==0){
			$typeid = $type_tmp;
		}


	    if(strpos($content, '[/page]')!==false) {
			$content = preg_replace("|\[page\](.*)\[/page\]|U", '', $content);
		} elseif (strpos($content, '[page]')!==false) {
			$content = str_replace('[page]', '', $content);
		}

		//根据设置字节数对文章加入分页标记
		if($maxcharperpage < 10) $maxcharperpage = $WAP_SETTING['c_num'];
		$contentpage = pc_base::load_app_class('contentpage','content');
		$content = $contentpage->get_data($content,$maxcharperpage);
		$isshow = 1;
		if($pictureurls) {
			$pictureurl = pic_pages($pictureurls);
			$isshow = 0;			
			//进行图片分页处理		
			$PIC_POS = strpos($pictureurl, '[page]');
			if($PIC_POS !== false) {
				$this->url = pc_base::load_app_class('wap_url', 'wap');
				$pictureurls = array_filter(explode('[page]', $pictureurl));
				$pagenumber = count($pictureurls);
				if (strpos($pictureurl, '[/page]')!==false && ($CONTENT_POS<7)) {
					$pagenumber--;
				}
				for($i=1; $i<=$pagenumber; $i++) {
					$pageurls[$i] = $this->url->show($id, $i, $catid, $typeid);
				}
				$END_POS = strpos($pictureurl, '[/page]');
				if($END_POS !== false) {
					if(preg_match_all("|\[page\](.*)\[/page\]|U", $pictureurl, $m, PREG_PATTERN_ORDER)) {
						foreach($m[1] as $k=>$v) {
							$p = $k+1;
							$titles[$p]['title'] = strip_tags($v);
							$titles[$p]['url'] = $pageurls[$p][0];
						}
					}
				}
				
				//当不存在 [/page]时，则使用下面分页
				$pages = content_pages($pagenumber,$page, $pageurls, 0);
				//判断[page]出现的位置是否在第一位 
				if($CONTENT_POS<7) {
					$pictureurl = $pictureurls[$page];
				} else {
					if ($page==1 && !empty($titles)) {
						$pictureurl = $title.'[/page]'.$pictureurls[$page-1];
					} else {
						$pictureurl = $pictureurls[$page-1];
					}
				}		
			}			
		}
		
		//进行自动分页处理		
		$CONTENT_POS = strpos($content, '[page]');
		if($CONTENT_POS !== false) {
			$this->url = pc_base::load_app_class('wap_url', 'wap');
			$contents = array_filter(explode('[page]', $content));
			$pagenumber = count($contents);
			if (strpos($content, '[/page]')!==false && ($CONTENT_POS<7)) {
				$pagenumber--;
			}
			for($i=1; $i<=$pagenumber; $i++) {
				$pageurls[$i] = $this->url->show($id, $i, $catid, $typeid);
			}
			$END_POS = strpos($content, '[/page]');
			if($END_POS !== false) {
				if(preg_match_all("|\[page\](.*)\[/page\]|U", $content, $m, PREG_PATTERN_ORDER)) {
					foreach($m[1] as $k=>$v) {
						$p = $k+1;
						$titles[$p]['title'] = strip_tags($v);
						$titles[$p]['url'] = $pageurls[$p][0];
					}
				}
			}
			
			//当不存在 [/page]时，则使用下面分页
			$pages = content_pages($pagenumber,$page, $pageurls);
			//判断[page]出现的位置是否在第一位 
			if($CONTENT_POS<7) {
				$content = $contents[$page];
			} else {
				if ($page==1 && !empty($titles)) {
					$content = $title.'[/page]'.$contents[$page-1];
				} else {
					$content = $contents[$page-1];
				}
			}
			if($_GET['remains']=='true') {
		        $content = $pages ='';
		        for($i=$page;$i<=$pagenumber;$i++) {
		            $content .=$contents[$i-1];
		        }
	    	}			
		}
				
		$content = content_strip(wml_strip($content));	
		$template = $WAP_SETTING['show_template'] ? $WAP_SETTING['show_template'] : 'show';
		
		$mydata = require PHPCMS_PATH.'/phpcms/modules/wap/help_show.php';
		foreach ($mydata as $ks=>$v){
			if(strpos($ks,",$typeid,")!==false){
				$template = $v;
				break;
    		}
		}


		if($_SERVER['HTTP_HOST']=='mnq.93636.com'){
			include template('wap', 'show_mnq');
		}
		else{			
			include template('wap', $template);
		}
	}
	
	//提交评论
	function comment() {
		$WAP = $this->wap;
		$TYPE = $this->types;		
		if($_POST['dosumbit']) {
			$comment = pc_base::load_app_class('comment','comment');
			pc_base::load_app_func('global','comment');
			$username = $this->wap['sitename'].L('phpcms_friends');
			$userid = param::get_cookie('_userid');		
			$catid = intval($_POST['catid']);		
			$typeid = intval($_POST['typeid']);		
			$contentid = intval($_POST['id']);		
			$msg = trim($_POST['msg']);
			$commentid = remove_xss(safe_replace(trim($_POST['commentid'])));
			$title = $_POST['title'];
			$url = $_POST['url'];	
			
			//通过API接口调用数据的标题、URL地址
			if (!$data = get_comment_api($commentid)) {
				exit(L('parameter_error'));
			} else {
				$title = $data['title'];
				$url = $data['url'];
				unset($data);
			} 		
			$data = array('userid'=>$userid, 'username'=>$username, 'content'=>$msg);
			$comment->add($commentid, $this->siteid, $data, $id, $title, $url);
			echo '<script type="text/javaScript" src="'.JS_PATH.'jquery.min.js"></script><script language="JavaScript" src="'.JS_PATH.'admin_common.js"></script>';
			echo L('wap_guestbook').'<br/><a href="'.show_url($catid,$contentid,$typeid).'">'.L('wap_goback').'</a><script language=javascript>setTimeout("redirect(\''.HTTP_REFERER.'\');",3000);</script>';
		}
	}
	
	//评论列表页
	function comment_list() {
		$WAP = $this->wap;
		$TYPE = $this->types;		
		$comment = pc_base::load_app_class('comment','comment');
		pc_base::load_app_func('global','comment');	
		$typeid  = intval($_GET['typeid']);	
		$GLOBALS['siteid'] = max($this->siteid,1);
		$commentid = isset($_GET['commentid']) && trim(addslashes(urldecode($_GET['commentid']))) ? trim(addslashes(urldecode($_GET['commentid']))) : exit(L('illegal_parameters'));
		if(!preg_match("/^[a-z0-9_\-]+$/i",$commentid)) exit(L('illegal_parameters'));
		list($modules, $contentid, $siteid) = decode_commentid($commentid);	
		list($module, $catid) = explode('_', $modules);
		$comment_setting_db = pc_base::load_model('comment_setting_model');
		$setting = $comment_setting_db->get_one(array('siteid'=>$this->siteid));	
		
		//通过API接口调用数据的标题、URL地址
		if (!$data = get_comment_api($commentid)) {
			exit(L('illegal_parameters'));
		} else {
			$title = $data['title'];
			$url = $data['url'];
			unset($data);
		}
		include template('wap', 'comment_list');
	}
	
	//导航页
	function maps() {
		$WAP = $this->wap;
		$TYPE = $this->types;
		$WAP_SETTING = string2array($WAP['setting']);	
		$GLOBALS['siteid'] = max($this->siteid,1);	
		include template('wap', 'maps');
	}
	
	//展示大图
	function big_image() {
		$WAP = $this->wap;
		$TYPE = $this->types;
		$WAP_SETTING = string2array($WAP['setting']);
		$GLOBALS['siteid'] = max($this->siteid,1);		
		$url=base64_decode(trim($_GET['url']));
		$url = str_replace(array('"',"'",'(',')',' '),'',$url);
		if(!preg_match('/(jpg|png|gif|bmp)$/i',fileext($url))) exit('img src error');
		$width = $_GET['w'] ?  trim(intval($_GET['w'])) : 320 ;
		$new_url = thumb($url,$width,0);
		include template('wap', 'big_image');
	}
	function category(){
	$category = getcache('category_content_'.$siteid,'commons');
	return $category;
	}

	/**
	 * 微信H5
	 */
	public function wxListH5(){
		$catid = intval($_GET['catid']);
		$this->H5game =  pc_base::load_model('H5game_model');
		$flag_data = $this->H5game->select(array('catid'=>$catid,'posids'=>1));
		if(!empty($catid)){
			$total = $this->H5game->count(array('catid'=>$catid));//10
			$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;//2
			$pagesize  = 10;
			$offset = ($page - 1) * $pagesize;
			$data = $this->H5game->select(array('catid'=>$catid,'status'=>99),"*", $offset.','.$pagesize,'clicks DESC');
			if($page > 1){
				$pagex=ceil($total/$pagesize);
				if($page<=$pagex){
					$data[0]['page']=$page+1;
				}
				if($pagex>0){
					$data[0]['sum']=$pagex;
				}else if($pagex==0){
					$data[0]['sum']=$pagex;
				}
				$data= json_encode($data);
				echo  $data;exit();
			}


		}
		include template('wap', 'wxListH5');
	}

	/**
	 * 搞笑图列表
	 */
	public function list_weixin_img(){

		$catid = intval($_GET['catid']);
		if(!empty($catid)){
			$this->weixin_img =  pc_base::load_model('weixin_img_model');
			$total = $this->weixin_img->count(array('catid'=>$catid));
			$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
			$pagesize =8 ;
			$offset = ($page - 1) * $pagesize;
			$data = $this->weixin_img->select(array('catid'=>$catid,'status'=>99),"*", $offset.','.$pagesize,'id DESC');
			foreach($data as $key =>$v){
				$data[$key]['updatetime'] = date("Y-m-d",$v['updatetime']);
			}
			if($page > 1){
				$pagex=ceil($total/$pagesize);
				if($page<=$pagex){
					$data[0]['page']=$page+1;
				}
				if($pagex>0){
					$data[0]['sum']=$pagex;
				}else if($pagex==0){
					$data[0]['sum']=$pagex;
				}
				$data= json_encode($data);
				echo  $data;exit();
			}
		}
		include	template('wap','list_weixin_img');
	}

	public function show_weixin_img(){
		$id = intval($_GET['id']);
		$catid = intval($_GET['catid']);
		if(!empty($id)){
			$this->weixin_img =  pc_base::load_model('weixin_img_model');
			//上一篇
			$last_sql  ="select 93636_weixin_img.id from 93636_weixin_img LEFT JOIN 93636_weixin_img_data ON 93636_weixin_img.id=93636_weixin_img_data.id  WHERE 93636_weixin_img.id<$id && 93636_weixin_img.catid = $catid ORDER BY 93636_weixin_img.id DESC limit 1 ";
			$last_result=$this->weixin_img->query($last_sql);
			$last_data = $row=mysql_fetch_assoc($last_result);

			//当前
			$sql="select * from 93636_weixin_img LEFT JOIN 93636_weixin_img_data ON 93636_weixin_img.id=93636_weixin_img_data.id  WHERE 93636_weixin_img.id=$id && 93636_weixin_img.catid = $catid  limit 1";
			$result=$this->weixin_img->query($sql);
			$data = $row=mysql_fetch_assoc($result);


			//下一篇
			$next_sql  ="select 93636_weixin_img.id from 93636_weixin_img LEFT JOIN 93636_weixin_img_data ON 93636_weixin_img.id=93636_weixin_img_data.id  WHERE 93636_weixin_img.id>$id && 93636_weixin_img.catid = $catid ORDER BY 93636_weixin_img.id ASC limit 1";
			$next_result=$this->weixin_img->query($next_sql);
			$next_data = $row=mysql_fetch_assoc($next_result);
		}
		include	template('wap','show_weixin_img');
	}


}


?>