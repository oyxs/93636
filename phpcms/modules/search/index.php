<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_sys_class('form','',0);
pc_base::load_sys_class('format','',0);
class index {
	function __construct() {
		$this->db = pc_base::load_model('search_model');
		$this->content_db = pc_base::load_model('content_model');
        $this->gift_db = pc_base::load_model('gift_model');
	}

	public function searchall(){//搜索总页面
		//获取siteid
		$siteid = isset($_REQUEST['siteid']) && trim($_REQUEST['siteid']) ? intval($_REQUEST['siteid']) : 1;
		$siteids = getcache('category_content','commons');
		$CATEGORYS = getcache('category_content_'.$siteid,'commons');
		$SEO = seo($siteid);

		$game=$this->db = pc_base::load_model('game_model');
		$content=$this->db = pc_base::load_model('news_model');
		$libao=$this->db = pc_base::load_model('libao_model');
		if(isset($_GET['q'])) {
			if(trim($_GET['q'])=='') {
				if($_SERVER["HTTP_HOST"]=="m.93636.com" || $_SERVER["HTTP_HOST"]=="x.mm2358.com"){
					header('Location: /index.php?m=search&c=index&a=restart_search');exit;
				}
				else if($_SERVER["HTTP_HOST"]=="mnq.93636.com"){
					header('Location: /index.php?m=search&c=index&a=restart_search');exit;
				}else{
					header('Location: '.APP_PATH.'index.php?m=search&c=index&a=restart_search');exit;
				}
			}
			$typeid = empty($_GET['typeid']) ? 0 : intval($_GET['typeid']);

			$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
			$pagesize = 15;
			$offset = $pagesize*($page-1);


			$q = safe_replace(trim($_GET['q']));
			$q = new_html_special_chars(strip_tags($q));
			$q = str_replace('%', '', $q);	//过滤'%'，用户全文搜索
			$search_q = $q;	//搜索原内容

			if($_SERVER["HTTP_HOST"]=="m.93636.com" || $_SERVER["HTTP_HOST"]=="x.mm2358.com"){
				$litenumber = 3;
				$number = 4;
			}elseif($_SERVER["HTTP_HOST"]=="www.93636.com"){
				$litenumber = 1;
				$number = 2;
			}
			$data2=$game->select("`title` = '$q' and status=99 ", '*','0,1');
			$whereyx="`title` like '%$q%' and title !='$q' and is_show=1";
			if(count($data2)){
				$data1=$game->select($whereyx, '*','0,'.$litenumber);
			}else{
				$data1=$game->select($whereyx, '*','0,'.$number);
			}
			$datayx=array_merge($data2,$data1);
//			$whereyx="title like '%$q%'and game_id<>'' and is_show=1 and status=99";
//			$datayx=$game->select($whereyx, '*','0,2');
			$totalnumsyx=$game->count($whereyx);
			$wherewz="`status`=99 and `title` like '%$q%'";
			$datawz=$content->select($wherewz, '*','0,'.$number,'id DESC');
			$totalnumswz=$content->count($wherewz);
			$wherelb="`title` like '%$q%'";
			$sql="select * from 93636_libao LEFT JOIN 93636_libao_data ON 93636_libao.id=93636_libao_data.id  WHERE `title` like '%$q%' ORDER BY  93636_libao.id DESC limit 0,$number  ";
			$result=$libao->query($sql);
			while($row=mysql_fetch_array($result)){
				$datalb[]=$row;
			}
			$totalnumslb=$libao->count($wherelb);
//			$totalnums = isset($totalnums) ? $totalnums : 0;

//			$pages = pages($totalnums, $page, $pagesize);
			if($_SERVER["HTTP_HOST"]=="m.93636.com" || $_SERVER["HTTP_HOST"]=="x.mm2358.com"){
				include	template('search','wap_searchall');
			}
			else if($_SERVER["HTTP_HOST"]=="mnq.93636.com"){
				include	template('search','search_mnq');
			}
			else{
				include	template('search','search_all');
			}

		}else {
			if($_SERVER["HTTP_HOST"]=="m.93636.com" || $_SERVER["HTTP_HOST"]=="x.mm2358.com"){
				include	template('search','wap_search_index');
			}
			else if($_SERVER["HTTP_HOST"]=="mnq.93636.com"){
				include	template('search','search_mnq');
			}
			else{
				include	template('search','search_all');
			}
		}

	}



    public function restart_search(){//新的搜索

        //获取siteid
        $siteid = isset($_REQUEST['siteid']) && trim($_REQUEST['siteid']) ? intval($_REQUEST['siteid']) : 1;
        $siteids = getcache('category_content','commons');
        $CATEGORYS = getcache('category_content_'.$siteid,'commons');
        $SEO = seo($siteid);

        $game=$this->db = pc_base::load_model('game_model');
        $content=$this->db = pc_base::load_model('news_model');
        $libao=$this->db = pc_base::load_model('libao_model');
        if(isset($_GET['q'])) {
            if(trim($_GET['q'])=='') {
                if($_SERVER["HTTP_HOST"]=="m.93636.com" || $_SERVER["HTTP_HOST"]=="x.mm2358.com"){
                    header('Location: /index.php?m=search&c=index&a=restart_search');exit;
                }
                else if($_SERVER["HTTP_HOST"]=="mnq.93636.com"){
                    header('Location: /index.php?m=search&c=index&a=restart_search');exit;
                }else{
                    header('Location: '.APP_PATH.'index.php?m=search&c=index&a=restart_search');exit;
                }
            }
            $typeid = empty($_GET['typeid']) ? 0 : intval($_GET['typeid']);

            $page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
            $pagesize = 15;
            $offset = $pagesize*($page-1);


            $q = safe_replace(trim($_GET['q']));
            $q = new_html_special_chars(strip_tags($q));
            $q = str_replace('%', '', $q);	//过滤'%'，用户全文搜索
            $search_q = $q;	//搜索原内容

            //$data='';
			$str='手游';
            if($typeid==1){//搜索游戏库
				if(strpos($q,$str)){
                    $q=str_replace('手游','',$q);
                }
                $data2=$game->select("`title` = '$q'", '*','0,1');
                $where="`title` like '%$q%' && `title` !='$q' && `is_show`=1";
				
                $data1=$game->select($where, '*',$offset.','.$pagesize);
                $data=array_merge($data2,$data1);
				$totalnums=$game->count($where);

            }elseif($typeid==2){//搜索文章
                $where="`status`=99 and `title` like '%$q%'";
                $data=$content->select($where, '*',$offset.','.$pagesize,'id DESC');
                $totalnums=$content->count($where);
            }
			elseif($typeid==4){//搜索文章
                $where="`status`=99 and `title` like '%$q%'";
                $data=$content->select($where, '*',$offset.','.$pagesize,'id DESC');
                $totalnums=$content->count($where);
            }
            elseif($typeid==3){//搜索礼包
                $where="`title` like '%$q%'";
                $sql="select * from 93636_libao LEFT JOIN 93636_libao_data ON 93636_libao.id=93636_libao_data.id  WHERE `title` like '%$q%' ORDER BY  93636_libao.id DESC limit $offset,$pagesize  ";
                $result=$libao->query($sql);
                while($row=mysql_fetch_array($result)){
                    $data[]=$row;
                }
                $totalnums=$libao->count($where);
            }
            $totalnums = isset($totalnums) ? $totalnums : 0;

            $pages = pages($totalnums, $page, $pagesize);
            if($_SERVER["HTTP_HOST"]=="m.93636.com" || $_SERVER["HTTP_HOST"]=="x.mm2358.com"){
                include	template('search','wap_search');
            }
            else if($_SERVER["HTTP_HOST"]=="mnq.93636.com"){
                include	template('search','search_mnq');
            }
            else{
                include	template('search','list');
            }

        }else {
            if($_SERVER["HTTP_HOST"]=="m.93636.com" || $_SERVER["HTTP_HOST"]=="x.mm2358.com"){
                include	template('search','wap_search_index');
            }
            else if($_SERVER["HTTP_HOST"]=="mnq.93636.com"){
                include	template('search','search_mnq');
            }
            else{
                include	template('search','list');
            }
        }

    }

    /*
    * 游戏礼包列表
    */
    public function game_gift(){
        $siteid = isset($_REQUEST['siteid']) && trim($_REQUEST['siteid']) ? intval($_REQUEST['siteid']) : 1;
        $SEO = seo($siteid);
        $libao=$this->db = pc_base::load_model('gift_model');
        $page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
        $pagesize = 15;
        $offset = $pagesize*($page-1);
        //$where = '', $data = '*', $limit = '', $order = '', $group = '', $key=''
        $libao->select();
        include	template('search','game_gift');
    }



    /**
	 * 关键词搜索
	 */
	public function init() {
		//获取siteid
		$siteid = isset($_REQUEST['siteid']) && trim($_REQUEST['siteid']) ? intval($_REQUEST['siteid']) : 1;
		$SEO = seo($siteid);

		//搜索配置
		$search_setting = getcache('search');
		$setting = $search_setting[$siteid];

		$search_model = getcache('search_model_'.$siteid);
		$type_module = getcache('type_module_'.$siteid);

		if(isset($_GET['q'])) {
			if(trim($_GET['q'])=='') {
				if($_SERVER["HTTP_HOST"]=="m.93636.com" || $_SERVER["HTTP_HOST"]=="x.mm2358.com"){
					header('Location: /index.php?m=search');exit;
				}
				else if($_SERVER["HTTP_HOST"]=="mnq.93636.com"){
					header('Location: /index.php?m=search');exit;
				}else{
					header('Location: '.APP_PATH.'index.php?m=search');exit;
				}
			}
			$typeid = empty($_GET['typeid']) ? 48 : intval($_GET['typeid']);
			$time = empty($_GET['time']) || !in_array($_GET['time'],array('all','day','month','year','week')) ? 'all' : trim($_GET['time']);
			$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
			$pagesize = 10;
			$q = safe_replace(trim($_GET['q']));
			$q = new_html_special_chars(strip_tags($q));
			$q = str_replace('%', '', $q);	//过滤'%'，用户全文搜索
			$search_q = $q;	//搜索原内容
			
			//按时间搜索
			if($time == 'day') {
				$search_time = SYS_TIME - 86400;
				$sql_time = ' AND adddate > '.$search_time;
			} elseif($time == 'week') {
				$search_time = SYS_TIME - 604800;
				$sql_time = ' AND adddate > '.$search_time;
			} elseif($time == 'month') {
				$search_time = SYS_TIME - 2592000;
				$sql_time = ' AND adddate > '.$search_time;
			} elseif($time == 'year') {
				$search_time = SYS_TIME - 31536000;
				$sql_time = ' AND adddate > '.$search_time;
			} else {
				$search_time = 0;
				$sql_time = '';
			}
			if($page==1 && !$setting['sphinxenable']) {
				//精确搜索
				$commend = $this->db->get_one("`typeid` = '$typeid' $sql_time AND `data` like '%$q%'");
			} else {
				$commend = '';
			}
			//如果开启sphinx
			if($setting['sphinxenable']) {
				$sphinx = pc_base::load_app_class('search_interface', '', 0);
				$sphinx = new search_interface();
				
				$offset = $pagesize*($page-1);
				$res = $sphinx->search($q, array($siteid), array($typeid), array($search_time, SYS_TIME), $offset, $pagesize, '@weight desc');
				
				$totalnums = $res['total'];
				//如果结果不为空
				if(!empty($res['matches'])) {
					$result = $res['matches'];
				}
			} else {
				pc_base::load_sys_class('segment', '', 0);
				$segment = new segment();
				//分词结果
				$segment_q = $segment->get_keyword($segment->split_result($q));
				//如果分词结果为空
				if(!empty($segment_q)) {
					$sql = "`siteid`= '$siteid' AND `typeid` = '$typeid' $sql_time AND MATCH (`data`) AGAINST ('$segment_q' IN BOOLEAN MODE)";
				} else {
					$sql = "`siteid`= '$siteid' AND `typeid` = '$typeid' $sql_time AND `data` like '%$q%'";
				}
				$result = $this->db->listinfo($sql, 'searchid DESC', $page, 10);
			}

			//如果开启相关搜索功能
			if($setting['relationenble']) {
				//如果关键词长度在8-16之间，保存关键词作为relation search
				$this->keyword_db = pc_base::load_model('search_keyword_model');

				if(strlen($q) < 17 && strlen($q) > 5 && !empty($segment_q)) {
					$res = $this->keyword_db->get_one(array('keyword'=>$q));
					if($res) {
						//关键词搜索数+1
						//$this->keyword_db->update(array('searchnums'=>'+=1'), array('keyword'=>$q));
					} else {
						//关键词转换为拼音
						pc_base::load_sys_func('iconv');
						$pinyin = gbk_to_pinyin($q);
						if(is_array($pinyin)) {
							$pinyin = implode('', $pinyin);
						}
						$this->keyword_db->insert(array('keyword'=>$q, 'searchnums'=>1, 'data'=>$segment_q, 'pinyin'=>$pinyin));
					}
				}
				//相关搜索
				if(!empty($segment_q)) {
					$relation_q = str_replace(' ', '%', $segment_q);
				} else {
					$relation_q = $q;
				}
				$relation = $this->keyword_db->select("MATCH (`data`) AGAINST ('%$relation_q%' IN BOOLEAN MODE)", '*', 10, 'searchnums DESC');
			}
				
			//如果结果不为空
			  if(!empty($result) || !empty($commend['id'])) {
				//开启sphinx后文章id取法不同
				if($setting['sphinxenable']) {
					foreach($result as $_v) {
						$sids[] = $_v['attrs']['id'];
					}
				} else {
					foreach($result as $_v) {
						$sids[] = $_v['id'];
					}
				}

				if(!empty($commend['id'])) {
					$sids[] = $commend['id'];
				}
				$sids = array_unique($sids);

				$where = to_sqls($sids, '', 'id');
				//获取模型id
				$model_type_cache = getcache('type_model_'.$siteid,'search');
				$model_type_cache = array_flip($model_type_cache);
				$modelid = $model_type_cache[$typeid];

				//是否读取其他模块接口
				if($modelid) {
					$this->content_db->set_model($modelid);
					
					/**
					 * 如果表名为空，则为黄页模型
					 */
					if(empty($this->content_db->model_tablename)) {
						$this->content_db = pc_base::load_model('yp_content_model');
						$this->content_db->set_model($modelid);

					}

					if($setting['sphinxenable']) {
						$data = $this->content_db->listinfo($where, 'id DESC', 1, $pagesize);
						$pages = pages($totalnums, $page, $pagesize);
					} else {
						$data = $this->content_db->select($where, '*');
						$pages = $this->db->pages;
						$totalnums = $this->db->number;
					}
				
					//如果分词结果为空
					if(!empty($segment_q)) {
						$replace = explode(' ', $segment_q);
						foreach($replace as $replace_arr_v) {
							$replace_arr[] =  '<font color=red>'.$replace_arr_v.'</font>';
						}
						foreach($data as $_k=>$_v) {
							$data[$_k]['title'] = str_replace($replace, $replace_arr, $_v['title']);
							$data[$_k]['description'] = str_replace($replace, $replace_arr, $_v['description']);
						}
					} else {
						foreach($data as $_k=>$_v) {
							$data[$_k]['title'] = str_replace($q, '<font color=red>'.$q.'</font>', $_v['title']);
							$data[$_k]['description'] = str_replace($q, '<font color=red>'.$q.'</font>', $_v['description']);
						}
					}
				} else {
					//读取专辑搜索接口
					$special_api = pc_base::load_app_class('search_api', 'special');
 					$data = $special_api->get_search_data($sids);
					$totalnums = count($data);
				}
			}
			$execute_time = execute_time();
			$pages = isset($pages) ? $pages : '';
			$totalnums = isset($totalnums) ? $totalnums : 0;
			$data = isset($data) ? $data : '';
			
			if($_SERVER["HTTP_HOST"]=="m.93636.com" || $_SERVER["HTTP_HOST"]=="x.mm2358.com"){
				include	template('search','wap_search');
			}
			else if($_SERVER["HTTP_HOST"]=="mnq.93636.com"){
				include	template('search','search_mnq');
				}
			else{
				include	template('search','list');
			}
		} else {
			if($_SERVER["HTTP_HOST"]=="m.93636.com" || $_SERVER["HTTP_HOST"]=="x.mm2358.com"){
				include	template('search','wap_search_index');
			}
			else if($_SERVER["HTTP_HOST"]=="mnq.93636.com"){
				include	template('search','search_mnq');
				}
			else{
				include	template('search','list');
			}
		}
	}
	
    //游戏库接口
    public function games(){

		$SEO["title"] = "2016好玩的手机网游下载筛选_手机单机游戏破解版下载中心";

        $catid = isset($_GET['catid']) ? intval($_GET['catid']) : 5;
        $siteids = getcache('category_content','commons');
        $siteid = $siteids[$catid];
        $CATEGORYS = getcache('category_content_'.$siteid,'commons');
        $singleornet = isset($_GET['network']) ? intval($_GET['network']) : 9;
        $network = $singleornet;
        $plate = isset($_GET['plate']) ? intval($_GET['plate']) : 9;
		$anlink = isset($_GET['anlink']) ? intval($_GET['anlink']) : true;
        $libao = isset($_GET['libao']) ? intval($_GET['libao']) : 0;
        $flag  =  isset($_GET['flag']) ? intval($_GET['flag']) : 0;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        if($_SERVER["HTTP_HOST"]=="m.93636.com" || $_SERVER["HTTP_HOST"]=="x.mm2358.com"){
			 $seos = include(PHPCMS_PATH.'/phpcms/modules/search/wap_game.php');
			}
		else{
			 $seos = include(PHPCMS_PATH.'/phpcms/modules/search/games.php');
			}	
        $SEO = $seos[$network."_".$plate."_".$catid];
        if($flag==1){
            $order="recommend DESC";
        }else{
            $order="id DESC";
        }
        $where = "1=1 and is_show=1";
        if($singleornet!=9){
            $where = $where." and singleornet=$singleornet";
        }
	if($plate!=9&&$plate!=3){
            $where = $where." and plate=$plate";
        }
        if($libao!=0){
	        $where = $where." and libaoURL!=''";
        }

		if($anlink!=null){
	        $where = $where." and anlink!=''";
        }
		if($_SERVER["HTTP_HOST"]=="m.93636.com" || $_SERVER["HTTP_HOST"]=="x.mm2358.com" && $network==0){
			 include template('search','game_wap_danji');
			}
		else if($_SERVER["HTTP_HOST"]=="m.93636.com" || $_SERVER["HTTP_HOST"]=="x.mm2358.com" && $network==1){
			include template('search','game_wap_wangyou');
			}
		else if($_SERVER["HTTP_HOST"]=="mnq.93636.com" && $network==0){
		include template('search','mnq_danji');
		}
		else if($_SERVER["HTTP_HOST"]=="mnq.93636.com" && $network==1){
		include template('search','mnq_wangyou');
		}
		else{
        		include	template('search','games');
			}
    }
    
    //游戏库接口
    public function libao(){
	    $catid = isset($_GET['catid']) ? intval($_GET['catid']) : 5;
        $singleornet = isset($_GET['network']) ? intval($_GET['network']) : 9;
        $network = $singleornet;
        $plate = isset($_GET['plate']) ? intval($_GET['plate']) : 9;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        
        $libao = 1;
	    $where = "libaoURL!=''";
	    
	    $SEO["title"] = "93636手游礼包发放平台,礼包大全";
	    $SEO["keyword"] = "手游礼包发放平台,礼包大全";
	    $SEO["description"] = "93636手游网手游礼包发放平台国内最大的手机网游相关礼包发放，其中包括新手卡，激活码，特权礼包等等。";
        if($_SERVER["HTTP_HOST"]=="m.93636.com" || $_SERVER["HTTP_HOST"]=="x.mm2358.com"){
			include	template('search','game_libao');
			}
		else{
        	include	template('search','games');
		}
    }
    /*
     * 礼包列表页
     */
    public function libao_list(){
		
        $posid = isset($_GET['posid']) ? intval($_GET['posid']):99;
        $flag  = isset($_GET['flag']) ?  intval($_GET['flag']):0;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		if($_SERVER["HTTP_HOST"]=="m.93636.com" || $_SERVER["HTTP_HOST"]=="x.mm2358.com"){
			 $seos = include(PHPCMS_PATH.'/phpcms/modules/search/wap_game.php');
			}
		else{
			 $seos = include(PHPCMS_PATH.'/phpcms/modules/search/libao_list.php');
			}
	  
		
       $SEO = $seos[$posid."-".$flag];
		if($flag==0){
			$order="listorder DESC";
		}else{
			$order="id ASC";
		}
        
		if($_SERVER["HTTP_HOST"]=="m.93636.com" || $_SERVER["HTTP_HOST"]=="x.mm2358.com"){
            include	template('search','game_libao');
        }
        else{
            include	template('search','libao_list');
        }

    }

	public function public_get_suggest_keyword() {
		$url = $_GET['url'].'&q='.$_GET['q'];
		$trust_url = array('c8430fcf851e85818b546addf5bc4dd3');
		$urm_md5 = md5($url);
		if (!in_array($urm_md5, $trust_url)) exit;
		
		$res = @file_get_contents($url);
		if(CHARSET != 'gbk') {
			$res = iconv('gbk', CHARSET, $res);
		}
		echo $res;
	}
	
	/**
	 * 提示搜索接口
	 * TODO 暂时未启用，用的是google的接口
	 */
	public function public_suggest_search() {
		//关键词转换为拼音
		pc_base::load_sys_func('iconv');
		$pinyin = gbk_to_pinyin($q);
		if(is_array($pinyin)) {
			$pinyin = implode('', $pinyin);
		}
		$this->keyword_db = pc_base::load_model('search_keyword_model');
		$suggest = $this->keyword_db->select("pinyin like '$pinyin%'", '*', 10, 'searchnums DESC');
		
		foreach($suggest as $v) {
			echo $v['keyword']."\n";
		}
    }

    /**
    **微信礼包列表搜索
    **
    */
    public function wx_search(){

        //获取siteid
        $siteid = isset($_REQUEST['siteid']) && trim($_REQUEST['siteid']) ? intval($_REQUEST['siteid']) : 1;
        $siteids = getcache('category_content','commons');
        $CATEGORYS = getcache('category_content_'.$siteid,'commons');
        $SEO = seo($siteid);
        $time=time();

        $libao=$this->db = pc_base::load_model('libao_model');
        $gift = $this->db = pc_base::load_model('gift_model');
        if(isset($_GET['q'])) {
            // if(trim($_GET['q'])=='') {
            //     header('Location: '.APP_PATH.'index.php?m=search&c=index&a=wx_search');exit;
            // }
            $typeid = empty($_GET['typeid']) ? 0 : intval($_GET['typeid']);

            $page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
            $pagesize = 10;
            $offset = $pagesize*($page-1);


            $q = safe_replace(trim($_GET['q']));
            $q = new_html_special_chars(strip_tags($q));
            $q = str_replace('%', '', $q);	//过滤'%'，用户全文搜索
            $search_q = $q;	//搜索原内容

            if(trim($_GET['q'])!=''){
            	//$where=" status='99' and `gamename` like '%$q%' and starttime <= '".$time."'  and endtime >= '".$time."'" ;
            	$where=" status='99' and `gamename` like '%$q%'" ;
            }else{
            	// $where=" status='99' and starttime <= '".$time."'  and endtime >= '".$time."'" ;
            	$where=" status='99'" ;
            }
            //$sql="select * from 93636_libao LEFT JOIN 93636_libao_data ON 93636_libao.id=93636_libao_data.id  WHERE `title` like '%$q%' ORDER BY  93636_libao.id DESC limit $offset,$pagesize  ";
            //$result=$libao->query($sql);
            $result=$libao->select($where, '*',$offset.','.$pagesize,'id DESC');
            $totalnums=$libao->count($where);
            $i = 0;
            $j = 0; 
            foreach ($result as  $v) {
            	if($time <= strtotime("+1 month",strtotime($v['endtime'])) && strtotime($v['endtime']) < $time){
            		$result[$j]['is_expire']=1;
            	}
            	else if($time > strtotime("+1 month",strtotime($v['endtime'])) && strtotime($v['endtime']) < $time){
            		array_splice($result,$j,1); 
            		$j--;
            	}
            	$j++; 
            }
            foreach ($result as $v) {
            	$is_null=$gift->count("gift_id = ".$v['id'] ." and status=0");//判断礼包是否被领完
            	$result[$i]['is_null']=$is_null;
            	$i++; 
            }
            if($page > 1){
	            $pagex=ceil($totalnums/$pagesize);
	            if($page<=$pagex){
	                $result[0]['page']=$page+1;
	            }
	            if($pagex>0){
	                $result[0]['sum']=$pagex;
	            }else if($pagex==0){
	                $result[0]['sum']=$pagex;
	            }
	            
	            $result= json_encode($result);
	            echo  $result;exit();
	        }

            $pages = pages($totalnums, $page, $pagesize);
            include	template('search','wx_search');

        }else {
            include	template('search','wx_search');
        }

    }

    /*
    **礼包淘号详情页
    **
    */
    public function TH_show(){
    	$libao=$this->db = pc_base::load_model('libao_model');
    	$gift = $this->db = pc_base::load_model('gift_model');
    	$id = empty($_GET['id']) ? 0 : intval($_GET['id']);
    	$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
    	$pagesize = 12;
        $offset = $pagesize*($page-1);
 		
 		$result=$libao->select("id= '".$id."' and status=99", '*','0,1','id DESC');
 		$data=$gift->select("gift_id = '".$id."'", 'code',$offset.','.$pagesize,'id DESC');
 		$totalnums=$gift->count("gift_id = '".$id."'");
 		if($page > 1){
	            $pagex=ceil($totalnums/$pagesize);
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
        include	template('search','taohao_show');
    }

    
}
?>