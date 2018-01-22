<?php
/**
 * 输出xml头部信息
 */
function wmlHeader() {
	echo "<?xml version=\"1.0\" encoding=\"".CHARSET."\"?>\n";
}
/**
 * 解析分类url路径
 */
function list_url($typeid) {
    return WAP_SITEURL."&amp;a=lists&amp;typeid=$typeid";
}
function my_list_url($typeid) {
	$mydata = array(
		"10"=>"/games/",      // 移动端typeid对应栏目的URL
		"4"=>"/youxilibao/",
        "43"=>"/zhuanqu/",
        "29"=>"/h5/",
        "3"=>"/youxizixun/",
        "2"=>"/youxipingce/",
        "5"=>"/video/",
        "9"=>"/chanyenews/",
        "24"=>"/yuletupian/",
        "31"=>"/h5/h5xxyz/",
        "32"=>"/h5/h5jsby/",
        "33"=>"/h5/h5scyx/",
        "34"=>"/h5/h5clyx/",
        "30"=>"/h5/h5fxsj/",
        "21"=>"/video/glvideo/",
        "22"=>"/video/pcvideo/",
        "23"=>"/video/xcvideo/",
        "25"=>"/yuletupian/meinvtupian/",
        "26"=>"/yuletupian/youxijietu/",
        "27"=>"/yuletupian/youxibizhi/",
        "28"=>"/yuletupian/qitatupian/",
		"1"=>"/youxigonglue/",
		"35"=>"/jyzjsy/",
		"36"=>"/jyzjsy/picture/",
		"37"=>"/jyzjsy/gonglue/",
		"38"=>"/jyzjsy/mpgl/",
		"39"=>"/jyzjsy/xsgl/",
		"40"=>"/jyzjsy/jjgl/",
		"41"=>"/jyzjsy/news/",
		"42"=>"/jyzjsy/libao/",
		"109"=>"/jyzjsy/video/",
		"46"=>"/xjqxz/",
		"47"=>"/xjqxz/news/",
		"48"=>"/xjqxz/viedo/",
		"49"=>"/xjqxz/picture/",
		"50"=>"/xjqxz/libao/",
		"51"=>"/xjqxz/lsgl/",
		"52"=>"/xjqxz/zbgl/",
		"53"=>"/xjqxz/zygl/",
		"54"=>"/xjqxz/gonglue/",
		"55"=>"/shenmuol/",
		"56"=>"/shenmuol/gonglue/",
		"57"=>"/shenmuol/zygl/",
		"58"=>"/shenmuol/gkgl/",
		"59"=>"/shenmuol/zbgl/",
		"62"=>"/shenmuol/news/",
		"60"=>"/shenmuol/libao/",
		"61"=>"/shenmuol/picture/",
		"63"=>"/shenmuol/video/",
		"64"=>"/baimaojihua/",
		"65"=>"/baimaojihua/gonglue/",
		"66"=>"/baimaojihua/jsgl/",
		"67"=>"/baimaojihua/gkgl/",
		"68"=>"/baimaojihua/czgl/",
		"69"=>"/baimaojihua/viedo/",
		"70"=>"/baimaojihua/news/",
		"71"=>"/baimaojihua/libao/",
		"72"=>"/baimaojihua/picture/",
		"73"=>"/tiantianchuanqi/",
		"74"=>"/tiantianchuanqi/gonglue/",
		"75"=>"/tiantianchuanqi/zygl/",
		"76"=>"/tiantianchuanqi/hbgl/",
		"77"=>"/tiantianchuanqi/zrgl/",
		"80"=>"/tiantianchuanqi/news/",
		"78"=>"/tiantianchuanqi/libao/",
		"79"=>"/tiantianchuanqi/picture/",
		"81"=>"/tiantianchuanqi/viedo/",
		"82"=>"/buliangren/",
		"90"=>"/buliangren/viedo/",
		"89"=>"/buliangren/picture/",
		"88"=>"/buliangren/libao/",
		"87"=>"/buliangren/news/",
		"86"=>"/buliangren/zrgl/",
		"85"=>"/buliangren/xsgl/",
		"84"=>"/buliangren/xkgl/",
		"83"=>"/buliangren/gonglue/",
		"91"=>"/huaqiangu/",
		"92"=>"/huaqiangu/gonglue/",
		"93"=>"huaqiangu/zygl/",
		"94"=>"/huaqiangu/xsgl/",
		"95"=>"/huaqiangu/fbgl/",
		"96"=>"/huaqiangu/news/",
		"97"=>"/huaqiangu/libao/",
		"98"=>"/huaqiangu/picture/",
		"99"=>"/huaqiangu/viedo/",
		"100"=>"/dahuaxiyou/",
		"107"=>"/dahuaxiyou/wenda/",
		"106"=>"/dahuaxiyou/jietu/",
		"105"=>"/dahuaxiyou/viedo/",
		"101"=>"/dahuaxiyou/gonglue/",
		"102"=>"/dahuaxiyou/libao/",
		"103"=>"/dahuaxiyou/xinwen/",
		"110"=>"/ysqth/",
		"118"=>"/ysqth/picture/",
		"117"=>"/ysqth/yxgl/",
		"116"=>"/ysqth/zqhb/",
		"115"=>"/ysqth/zrgl/",
		"114"=>"/ysqth/video/",
		"113"=>"/ysqth/news/",
		"112"=>"/ysqth/libao/",
		"111"=>"/ysqth/gonglue/",
		);
	return $mydata[$typeid];
   // return "/lists/$typeid";
}

function bigimg_url($url,$w='') {
	return WAP_SITEURL.'&amp;a=big_image&amp;url='.base64_encode($url).'&amp;w='.$w;
}
/**
 * 解析内容url路径
 * $catid 栏目id
 * $typeid wap分类id
 * $id 文章id
 */
function show_url($catid, $id, $typeid='') {
	global $WAP;
	if($typeid=='') {
		$types = getcache('wap_type','wap');
		foreach ($types as $type) {
			if($type['cat']==$catid) {
				$typeid = $type['typeid'];
				break;
			}
		}
	}
    return WAP_SITEURL."&amp;a=show&amp;catid=$catid&amp;typeid=$typeid&amp;id=$id";
}

function my_show_url($catid, $id, $typeid=''){
	global $WAP;
	if($typeid=='') {
		$types = getcache('wap_type','wap');
		foreach ($types as $type) {
			if($type['cat']==$catid) {
				$typeid = $type['typeid'];
				break;
			}
		}
	}
$mydata = array(
               	"5"=>"/games/",      // 栏目id（catid）对应的url
			    "33"=>"/games/",
			   	"34"=>"/games/",   
			   	"35"=>"/games/",
			   	"32"=>"/games/",
			   	"36"=>"/games/",
			   	"37"=>"/games/",
			   	"38"=>"/games/",
			   	"39"=>"/games/",
			   	"41"=>"/games/",
			   	"42"=>"/games/",
				"31"=>"/youxilibao/",
				"118"=>"/zhuanqu/",
				"6"=>"/h5/",
				"30"=>"/youxizixun/",
				"29"=>"/youxipingce/",
				"17"=>"/video/",
				"27"=>"/chanyenews/",
				"12"=>"/yuletupian/",
				"7"=>"/h5/",
				"8"=>"/h5/",
				"9"=>"/h5/",
				"10"=>"/h5/",
				"11"=>"/h5/",
				"18"=>"/video/",
				"19"=>"/video/",
				"20"=>"/video/",
				"13"=>"/yuletupian/",
				"25"=>"/yuletupian/",
				"26"=>"/yuletupian/",
				"24"=>"/yuletupian/",
				"2"=>"/youxigonglue/",
				"154"=>"/jyzjsy/",
				"160"=>"/jyzjsy/",
				"155"=>"/jyzjsy/",
				"156"=>"/jyzjsy/",
				"157"=>"/jyzjsy/",
				"158"=>"/jyzjsy/",
				"161"=>"/jyzjsy/",
				"159"=>"/jyzjsy/",
				"171"=>"/jyzjsy/",
				"128"=>"/xjqxz/",
				"153"=>"/xjqxz/",
				"133"=>"/xjqxz/",
				"132"=>"/xjqxz/",
				"130"=>"/xjqxz/",
				"151"=>"/xjqxz/",
				"150"=>"/xjqxz/",
				"149"=>"/xjqxz/",
				"152"=>"/xjqxz/",
				"173"=>"/shenmuol/",
				"174"=>"/shenmuol/",
				"175"=>"/shenmuol/",
				"176"=>"/shenmuol/",
				"177"=>"/shenmuol/",
				"178"=>"/shenmuol/",
				"179"=>"/shenmuol/",
				"180"=>"/shenmuol/",
				"181"=>"/shenmuol/",
				"162"=>"/baimaojihua/",
				"163"=>"/baimaojihua/",
				"164"=>"/baimaojihua/",
				"165"=>"/baimaojihua/",
				"166"=>"/baimaojihua/",
				"167"=>"/baimaojihua/",
				"168"=>"/baimaojihua/",
				"169"=>"/baimaojihua/",
				"170"=>"/baimaojihua/",
				"182"=>"/tiantianchuanqi/",
				"183"=>"/tiantianchuanqi/",
				"184"=>"/tiantianchuanqi/",
				"185"=>"/tiantianchuanqi/",
				"186"=>"/tiantianchuanqi/",
				"187"=>"/tiantianchuanqi/",
				"188"=>"/tiantianchuanqi/",
				"189"=>"/tiantianchuanqi/",
				"190"=>"/tiantianchuanqi/",
				"191"=>"/buliangren/",
				"199"=>"/buliangren/",
				"198"=>"/buliangren/",
				"197"=>"/buliangren/",
				"196"=>"/buliangren/",
				"195"=>"/buliangren/",
				"194"=>"/buliangren/",
				"193"=>"/buliangren/",
				"192"=>"/buliangren/",
				"200"=>"/huaqiangu/",
				"201"=>"/huaqiangu/",
				"202"=>"huaqiangu/",
				"203"=>"/huaqiangu/",
				"204"=>"/huaqiangu/",
				"205"=>"/huaqiangu/",
				"206"=>"/huaqiangu/",
				"207"=>"/huaqiangu/",
				"208"=>"/huaqiangu/",
				"119"=>"/dahuaxiyou/",
				"141"=>"/dahuaxiyou/",
				"125"=>"/dahuaxiyou/",
				"124"=>"/dahuaxiyou/",
				"120"=>"/dahuaxiyou/",
				"121"=>"/dahuaxiyou/",
				"122"=>"/dahuaxiyou/",
				"123"=>"/dahuaxiyou/",
				"209"=>"/ysqth/",
				"210"=>"/ysqth/",
				"211"=>"/ysqth/",
				"212"=>"/ysqth/",
				"213"=>"/ysqth/",
				"214"=>"/ysqth/",
				"215"=>"/ysqth/",
				"216"=>"/ysqth/",
				"217"=>"/ysqth/",
				);
			$mydata2 = array(13,18,19,20,24,25,26,120,121,122,123,124,125,141,152,149,150,151,130,132,133,153,155,156,157,158,159,160,161,171,163,164,165,166,167,168,169,170,174,175,176,177,179,180,178,181,183,184,185,186,187,188,189,190,192,193,194,195,196,197,198,199,201,202,203,204,205,206,207,208,210,211,212,213,214,215,216,217,);	
			$mydata2_len=sizeof($mydata2);
	for($i=0;$i<$mydata2_len;$i++){
		if(intval($mydata2[$i])==$catid){
				return $mydata[$catid].$catid.'9'.$id.'.html';
			}
		}
	return $mydata[$catid].$id.'.html';
	//return $mydata[$catid].$catid.	'9'.$id;
    //return "/show/$typeid/$catid/$id";
}


/**
 * 当前路径 
 * 返回指定分类路径层级
 * @param $typeid 分类id
 * @param $symbol 分类间隔符
 */
function wap_pos($typeid, $symbol=' > '){
	$type_arr = array();
	$type_arr = getcache('wap_type','wap');
	if(!isset($type_arr[$typeid])) return '';
	$pos = '';
	if($type_arr[$typeid]['parentid']!=0) {
		$pos = '<a href="'.list_url($type_arr[$typeid]['parentid']).'">'.$type_arr[$type_arr[$typeid]['parentid']]['typename'].'</a>'.$symbol;
	}
	$pos .= '<a href="'.list_url($typeid).'">'.$type_arr[$typeid]['typename'].'</a>'.$symbol;
	return $pos;
}
	/*获取当前栏目typeid 的栏目名称*/
function wap_typename($typeid){
		$type_arr = array();
		$type_arr = getcache('wap_type','wap');
		
		return $type_arr[$typeid]['typename'];
	}
	/*获取当前栏目的catid*/
function wap_catid($typeid){
	$type_arr = array();
	$type_arr = getcache('wap_type','wap');
	
	return $type_arr[$typeid]['cat'];
	}

	
function my_wap_pos($typeid, $symbol=' > '){
	$type_arr = array();
	$type_arr = getcache('wap_type','wap');
	if(!isset($type_arr[$typeid])) return '';
	$pos = '';
	if($type_arr[$typeid]['parentid']!=0) {
		$pos = '<a href="'.mynewurl(list_url($type_arr[$typeid]['parentid'])).'">'.$type_arr[$type_arr[$typeid]['parentid']]['typename'].'</a>'.$symbol;
	}
	$pos .= '<a href="'.mynewurl(list_url($typeid)).'">'.$type_arr[$typeid]['typename'].'</a>'.$symbol;
	
	return $pos;
}

/**
 * 获取子分类
 */
function subtype($parentid = NULL, $siteid = '') {
	if (empty($siteid)) $siteid = $GLOBALS['siteid'];
	$types = getcache('wap_type','wap');
	foreach($types as $id=>$type) {
		if($type['siteid'] == $siteid && ($parentid === NULL || $type['parentid'] == $parentid)) {
			$subtype[$id] = $type;;
		}		
	}
	return $subtype;
}
function fen_title($typeid) {
		//$types = getcache('category_content_'.$siteid,'content');
		$types2 = getcache('wap_type','wap');
		$types=$types2[$typeid]['cat'];
		return $types;

	}
/**
 * 分页函数
 * 
 * @param $num 信息总数
 * @param $curr_page 当前分页
 * @param $perpage 每页显示数
 * @param $urlrule URL规则
 * @param $array 需要传递的数组，用于增加额外的方法
 * @return 分页
 */
/*
function wpa_pages($num, $curr_page, $perpage = 20, $urlrule = '', $array = array(),$setpages = 10) {
	if(defined('URLRULE')) {
		$urlrule = URLRULE;
		$array = $GLOBALS['URL_ARRAY'];
	} elseif($urlrule == '') {
		$urlrule = url_par('page={$page}');
	}
	$multipage = '';
	if($num > $perpage) {
		$page = $setpages+1;
		$offset = ceil($setpages/2-1);
		$pages = ceil($num / $perpage);
		if (defined('IN_ADMIN') && !defined('PAGES')) define('PAGES', $pages);
		$from = $curr_page - $offset;
		$to = $curr_page + $offset;
		$more = 0;
		if($page >= $pages) {
			$from = 2;
			$to = $pages-1;
		} else {
			if($from <= 1) {
				$to = $page-1;
				$from = 2;
			}  elseif($to >= $pages) { 
				$from = $pages-($page-2);  
				$to = $pages-1;  
			}
			$more = 1;
		} 
		$multipage .= $curr_page.'/'.$pages;
		if($curr_page>0) {
			$multipage .= ' <a href="'.pageurl($urlrule, $curr_page-1, $array).'">'.L('previous').'</a>';
		}
		if($curr_page==$pages) {
			$multipage .= ' <a href="'.pageurl($urlrule, $curr_page, $array).'">'.L('next').'</a>';
		} else {
			$multipage .= ' <a href="'.pageurl($urlrule, $curr_page+1, $array).'">'.L('next').'</a>';
		}
		
	}
	return $multipage;
}
*/

/**
 * 分页函数
 * 
 * @param $num 信息总数
 * @param $curr_page 当前分页
 * @param $perpage 每页显示数
 * @param $urlrule URL规则
 * @param $array 需要传递的数组，用于增加额外的方法
 * @return 分页
 */
function wpa_pages($num, $curr_page, $perpage = 20, $urlrule = '', $array = array(),$setpages = 10) {
	if(defined('URLRULE')) {
		$urlrule = URLRULE;
		$array = $GLOBALS['URL_ARRAY'];
	} elseif($urlrule == '') {
		$urlrule = url_par('page={$page}');
	}
	$multipage = '';
	if($num > $perpage) {
		$page = $setpages+1;
		$offset = ceil($setpages/2-1);
		$pages = ceil($num / $perpage);
		if (defined('IN_ADMIN') && !defined('PAGES')) define('PAGES', $pages);
		$from = $curr_page - $offset;
		$to = $curr_page + $offset;
		$more = 0;
		if($page >= $pages) {
			$from = 2;
			$to = $pages-1;
		} else {
			if($from <= 1) {
				$to = $page-1;
				$from = 2;
			}  elseif($to >= $pages) { 
				$from = $pages-($page-2);  
				$to = $pages-1;  
			}
			$more = 1;
		} 
		$multipage .= $curr_page.'/'.$pages;
		if($curr_page>0) {
			$multipage .= ' <a href="'.my_pageurl($urlrule, $curr_page-1, $array).'">'.L('previous').'</a>';
		}
		if($curr_page==$pages) {
			$multipage .= ' <a href="'.my_pageurl($urlrule, $curr_page, $array).'">'.L('next').'</a>';
		} else {
			$multipage .= ' <a href="'.my_pageurl($urlrule, $curr_page+1, $array).'">'.L('next').'</a>';
		}
		
	}
	return $multipage;
}

/**
 * 返回分页路径
 *
 * @param $urlrule 分页规则
 * @param $page 当前页
 * @param $array 需要传递的数组，用于增加额外的方法
 * @return 完整的URL路径
 */
function my_pageurl($urlrule, $page, $array = array()) {
	$urlrule = '/lists/{$typeid}~/lists/{$typeid}/{$page}';
    if(strpos($urlrule, '~')) {
        $urlrules = explode('~', $urlrule);
        $urlrule = $page < 2 ? $urlrules[0] : $urlrules[1];
    }
    $findme = array('{$page}');
    $replaceme = array($page);
    if (is_array($array)) foreach ($array as $k=>$v) {
        $findme[] = '{$'.$k.'}';
        $replaceme[] = $v;
    }
    $url = str_replace($findme, $replaceme, $urlrule);
    $url = str_replace(array('http://','//','~'), array('~','/','http://'), $url);
    return $url;
}

/**
 * 过滤内容为wml格式
 */
function wml_strip($string) {
    $string = str_replace(array('&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;', '&'), array(' ', '&', '"', "'", '“', '”', '—', '{<}', '{>}', '·', '…', '&amp;'), $string);
	return str_replace(array('{<}', '{>}'), array('&lt;', '&gt;'), $string);
}

/**
 * 内容中图片替换
 */
function content_strip($content,$ishow=1) {
    
   $content = preg_replace('/<img[^>]*src=[\'"]?([^>\'"\s]*)[\'"]?[^>]*>/ie', "wap_img('$1',$ishow)", $content);
      
   //匹配替换过的图片
      
   $content = strip_tags($content,'<b><br><img><p><div><a>');
   return $content;
}

/**
 * 图片过滤替换
 */
function wap_img($url,$ishow) {
	$wap_site = getcache('wap_site','wap');
	$wap_setting = string2array($wap_site[$GLOBALS['siteid']]['setting']);
	$show_big = bigimg_url($url);
	if($ishow==1) $show_tips = '<br><a href="'.$show_big.'">浏览大图</a>';
	return '<img src="'.thumb($url,$wap_setting['thumb_w'],$wap_setting['thumb_h']).'">'.$show_tips;
}

function strip_selected_tags($text) {
    $tags = array('em','font','h1','h2','h3','h4','h5','h6','hr','i','ins','li','ol','p','pre','small','span','strike','strong','sub','sup','table','tbody','td','tfoot','th','thead','tr','tt','u','div','span');
    $args = func_get_args();
    $text = array_shift($args);
    $tags = func_num_args() > 2 ? array_diff($args,array($text)) : (array)$tags;
    foreach ($tags as $tag){
        if( preg_match_all( '/<'.$tag.'[^>]*>([^<]*)<\/'.$tag.'>/iu', $text, $found) ){
            $text = str_replace($found[0],$found[1],$text);
        }
    }
    return $text;
}

/**
 * 生成文章分页方法
 */

function content_pages($num, $curr_page,$pageurls,$showremain = 1) {
	$multipage = '';
	$page = 11;
	$offset = 4;
	$pages = $num;
	$from = $curr_page - $offset;
	$to = $curr_page + $offset;
	$more = 0;
	if($page >= $pages) {
		$from = 2;
		$to = $pages-1;
	} else {
		if($from <= 1) {
			$to = $page-1;
			$from = 2;
		} elseif($to >= $pages) {
			$from = $pages-($page-2);
			$to = $pages-1;
		}
		$more = 1;
	}
	$multipage .='('.$curr_page.'/'.$num.')';
	if($curr_page>0) {
		$perpage = $curr_page == 1 ? 1 : $curr_page-1;
		$multipage .= '<a class="a1" href="'.$pageurls[$perpage][1].'">'.L('previous').'</a>';
	}
	
	if($curr_page<$pages) {
		if($curr_page<$pages-5 && $more) {
			$multipage .= ' <a id="nextpage" class="a1" href="'.$pageurls[$curr_page+1][1].'">'.L('next').'</a>';
		} else {
			$multipage .= ' <a id="nextpage" class="a1" href="'.$pageurls[$curr_page+1][1].'">'.L('next').'</a>';
		}
	} elseif($curr_page==$pages) {
		$multipage .= ' <a id="nextpage" class="a1" href="'.$pageurls[$curr_page][1].'">'.L('next').'</a>';
	}
	if($showremain) $multipage .="| <a href='".$pageurls[$curr_page][1]."&remains=true'>剩余全文</a>";
	return $multipage;
}

/**
 * 多图分页处理
 */

function pic_pages($array) {
	if(!is_array($array) || empty($array)) return false;
	foreach ($array as $k=>$p) {
		$photo_arr[$k]='<img src="'.$p['url'].'"><br>'.$p['alt'];
	}
	$photo_page = @implode('[page]', $photo_arr);
	$photo_page =content_strip(wml_strip($photo_page),0);
	return $photo_page;
}

/**
 * 获取热词
 */
function hotword() {
	$site = getcache('wap_site','wap');
	$setting = string2array($site[$GLOBALS['siteid']]['setting']);
	$hotword = $setting['hotwords'];
	$hotword_arr = explode("\n", $hotword);
	if(is_array($hotword_arr) && count($hotword_arr) > 0) {
		foreach($hotword_arr as $_k) {
			$v = explode("|",$_k);
			$hotword_string .= '<a href="'.$v[1].'">'.$v[0].'</a>&nbsp';
		}		
	}
	return $hotword_string;
}

function mynewurl($url){
	$url = str_replace("&amp;", "&", $url);
	$path = parse_url($url);
	parse_str($path['query'],$data);
	$newurl = "";
	
	if(isset($data["a"])){
		if($data["a"]=="lists"){
			$mydata = array(
		"10"=>"/games/",      // 移动端typeid对应栏目的URL
		"4"=>"/youxilibao/",
        "43"=>"/zhuanqu/",
        "29"=>"/h5/",
        "3"=>"/youxizixun/",
        "2"=>"/youxipingce/",
        "5"=>"/video/",
        "9"=>"/chanyenews/",
        "24"=>"/yuletupian/",
        "31"=>"/h5/h5xxyz/",
        "32"=>"/h5/h5jsby/",
        "33"=>"/h5/h5scyx/",
        "34"=>"/h5/h5clyx/",
        "30"=>"/h5/h5fxsj/",
        "21"=>"/video/glvideo/",
        "22"=>"/video/pcvideo/",
        "23"=>"/video/xcvideo/",
        "25"=>"/yuletupian/meinvtupian/",
        "26"=>"/yuletupian/youxijietu/",
        "27"=>"/yuletupian/youxibizhi/",
        "28"=>"/yuletupian/qitatupian/",
		"1"=>"/youxigonglue/",
		"35"=>"/jyzjsy/",
		"36"=>"/jyzjsy/picture/",
		"37"=>"/jyzjsy/gonglue/",
		"38"=>"/jyzjsy/mpgl/",
		"39"=>"/jyzjsy/xsgl/",
		"40"=>"/jyzjsy/jjgl/",
		"41"=>"/jyzjsy/news/",
		"42"=>"/jyzjsy/libao/",
		"109"=>"/jyzjsy/video/",
		"46"=>"/xjqxz/",
		"47"=>"/xjqxz/news/",
		"48"=>"/xjqxz/viedo/",
		"49"=>"/xjqxz/picture/",
		"50"=>"/xjqxz/libao/",
		"51"=>"/xjqxz/lsgl/",
		"52"=>"/xjqxz/zbgl/",
		"53"=>"/xjqxz/zygl/",
		"54"=>"/xjqxz/gonglue/",
		"55"=>"/shenmuol/",
		"56"=>"/shenmuol/gonglue/",
		"57"=>"/shenmuol/zygl/",
		"58"=>"/shenmuol/gkgl/",
		"59"=>"/shenmuol/zbgl/",
		"62"=>"/shenmuol/news/",
		"60"=>"/shenmuol/libao/",
		"61"=>"/shenmuol/picture/",
		"63"=>"/shenmuol/video/",
		"64"=>"/baimaojihua/",
		"65"=>"/baimaojihua/gonglue/",
		"66"=>"/baimaojihua/jsgl/",
		"67"=>"/baimaojihua/gkgl/",
		"68"=>"/baimaojihua/czgl/",
		"69"=>"/baimaojihua/viedo/",
		"70"=>"/baimaojihua/news/",
		"71"=>"/baimaojihua/libao/",
		"72"=>"/baimaojihua/picture/",
		"73"=>"/tiantianchuanqi/",
		"74"=>"/tiantianchuanqi/gonglue/",
		"75"=>"/tiantianchuanqi/zygl/",
		"76"=>"/tiantianchuanqi/hbgl/",
		"77"=>"/tiantianchuanqi/zrgl/",
		"80"=>"/tiantianchuanqi/news/",
		"78"=>"/tiantianchuanqi/libao/",
		"79"=>"/tiantianchuanqi/picture/",
		"81"=>"/tiantianchuanqi/viedo/",
		"82"=>"/buliangren/",
		"90"=>"/buliangren/viedo/",
		"89"=>"/buliangren/picture/",
		"88"=>"/buliangren/libao/",
		"87"=>"/buliangren/news/",
		"86"=>"/buliangren/zrgl/",
		"85"=>"/buliangren/xsgl/",
		"84"=>"/buliangren/xkgl/",
		"83"=>"/buliangren/gonglue/",
		"91"=>"/huaqiangu/",
		"92"=>"/huaqiangu/gonglue/",
		"93"=>"huaqiangu/zygl/",
		"94"=>"/huaqiangu/xsgl/",
		"95"=>"/huaqiangu/fbgl/",
		"96"=>"/huaqiangu/news/",
		"97"=>"/huaqiangu/libao/",
		"98"=>"/huaqiangu/picture/",
		"99"=>"/huaqiangu/viedo/",
		"100"=>"/dahuaxiyou/",
		"107"=>"/dahuaxiyou/wenda/",
		"106"=>"/dahuaxiyou/jietu/",
		"105"=>"/dahuaxiyou/viedo/",
		"101"=>"/dahuaxiyou/gonglue/",
		"102"=>"/dahuaxiyou/libao/",
		"103"=>"/dahuaxiyou/xinwen/",
		"110"=>"/ysqth/",
		"118"=>"/ysqth/picture/",
		"117"=>"/ysqth/yxgl/",
		"116"=>"/ysqth/zqhb/",
		"115"=>"/ysqth/zrgl/",
		"114"=>"/ysqth/video/",
		"113"=>"/ysqth/news/",
		"112"=>"/ysqth/libao/",
		"111"=>"/ysqth/gonglue/",
		);
			if(isset($data["typeid"])){
				return $mydata[$data["typeid"]];
			}
		}
		
		if($data["a"]=="show"){
			$mydata = array(
              	"5"=>"/games/", 
			   	"33"=>"/games/",
			   	"34"=>"/games/",     // 栏目id（catid）对应的url
			   	"35"=>"/games/",
			   	"32"=>"/games/",
			   	"36"=>"/games/",
			   	"37"=>"/games/",
			   	"38"=>"/games/",
			   	"39"=>"/games/",
			   	"41"=>"/games/",
			   	"42"=>"/games/",
				"31"=>"/youxilibao/",
				"118"=>"/zhuanqu/",
				"6"=>"/h5/",
				"30"=>"/youxizixun/",
				"29"=>"/youxipingce/",
				"17"=>"/video/",
				"27"=>"/chanyenews/",
				"12"=>"/yuletupian/",
				"7"=>"/h5/",
				"8"=>"/h5/",
				"9"=>"/h5/",
				"10"=>"/h5/",
				"11"=>"/h5/",
				"18"=>"/video/",
				"19"=>"/video/",
				"20"=>"/video/",
				"13"=>"/yuletupian/",
				"25"=>"/yuletupian/",
				"26"=>"/yuletupian/",
				"24"=>"/yuletupian/",
				"2"=>"/youxigonglue/",
				"154"=>"/jyzjsy/",
				"160"=>"/jyzjsy/",
				"155"=>"/jyzjsy/",
				"156"=>"/jyzjsy/",
				"157"=>"/jyzjsy/",
				"158"=>"/jyzjsy/",
				"161"=>"/jyzjsy/",
				"159"=>"/jyzjsy/",
				"171"=>"/jyzjsy/",
				"128"=>"/xjqxz/",
				"153"=>"/xjqxz/",
				"133"=>"/xjqxz/",
				"132"=>"/xjqxz/",
				"130"=>"/xjqxz/",
				"151"=>"/xjqxz/",
				"150"=>"/xjqxz/",
				"149"=>"/xjqxz/",
				"152"=>"/xjqxz/",
				"173"=>"/shenmuol/",
				"174"=>"/shenmuol/",
				"175"=>"/shenmuol/",
				"176"=>"/shenmuol/",
				"177"=>"/shenmuol/",
				"178"=>"/shenmuol/",
				"179"=>"/shenmuol/",
				"180"=>"/shenmuol/",
				"181"=>"/shenmuol/",
				"162"=>"/baimaojihua/",
				"163"=>"/baimaojihua/",
				"164"=>"/baimaojihua/",
				"165"=>"/baimaojihua/",
				"166"=>"/baimaojihua/",
				"167"=>"/baimaojihua/",
				"168"=>"/baimaojihua/",
				"169"=>"/baimaojihua/",
				"170"=>"/baimaojihua/",
				"182"=>"/tiantianchuanqi/",
				"183"=>"/tiantianchuanqi/",
				"184"=>"/tiantianchuanqi/",
				"185"=>"/tiantianchuanqi/",
				"186"=>"/tiantianchuanqi/",
				"187"=>"/tiantianchuanqi/",
				"188"=>"/tiantianchuanqi/",
				"189"=>"/tiantianchuanqi/",
				"190"=>"/tiantianchuanqi/",
				"191"=>"/buliangren/",
				"199"=>"/buliangren/",
				"198"=>"/buliangren/",
				"197"=>"/buliangren/",
				"196"=>"/buliangren/",
				"195"=>"/buliangren/",
				"194"=>"/buliangren/",
				"193"=>"/buliangren/",
				"192"=>"/buliangren/",
				"200"=>"/huaqiangu/",
				"201"=>"/huaqiangu/",
				"202"=>"huaqiangu/",
				"203"=>"/huaqiangu/",
				"204"=>"/huaqiangu/",
				"205"=>"/huaqiangu/",
				"206"=>"/huaqiangu/",
				"207"=>"/huaqiangu/",
				"208"=>"/huaqiangu/",
				"119"=>"/dahuaxiyou/",
				"141"=>"/dahuaxiyou/",
				"125"=>"/dahuaxiyou/",
				"124"=>"/dahuaxiyou/",
				"120"=>"/dahuaxiyou/",
				"121"=>"/dahuaxiyou/",
				"122"=>"/dahuaxiyou/",
				"123"=>"/dahuaxiyou/",
				"209"=>"/ysqth/",
				"210"=>"/ysqth/",
				"211"=>"/ysqth/",
				"212"=>"/ysqth/",
				"213"=>"/ysqth/",
				"214"=>"/ysqth/",
				"215"=>"/ysqth/",
				"216"=>"/ysqth/",
				"217"=>"/ysqth/",
				);
			if(isset($data["catid"]) && isset($data["id"])){
					$mydata2 = array(13,18,19,20,24,25,26,120,121,122,123,124,125,141,152,149,150,151,130,132,133,153,155,156,157,158,159,160,161,171,163,164,165,166,167,168,169,170,174,175,176,177,179,180,178,181,183,184,185,186,187,188,189,190,192,193,194,195,196,197,198,199,201,202,203,204,205,206,207,208,210,211,212,213,214,215,216,217,);				
					$mydata2_len=sizeof($mydata2);
					for($i=0;$i<$mydata2_len;$i++){
						if(intval($mydata2[$i])==$data["catid"]){
								return $mydata[$data["catid"]].$data["catid"].'9'.$data["id"].'.html';
							}
						}
				return $mydata[$data["catid"]].$data["id"].'.html';
			}
		}
	}
/*
	if(isset($data["a"])){
    	$newurl=$newurl."/".$data["a"];
	}
	if(isset($data["typeid"])){
    	$newurl=$newurl."/".$data["typeid"];
	}
	if(isset($data["catid"])){
    	$newurl=$newurl."/".$data["catid"];
	}
	if(isset($data["id"])){
    	$newurl=$newurl."/".$data["id"];
	}
	return $newurl;
*/
 }
?>