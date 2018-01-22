<?php 
	
	/**
	 * 模板风格列表
	 * @param integer $siteid 站点ID，获取单个站点可使用的模板风格列表
	 * @param integer $disable 是否显示停用的{1:是,0:否}
	 */
	function template_list($siteid = '', $disable = 0) {
		$list = glob(PC_PATH.'templates'.DIRECTORY_SEPARATOR.'*', GLOB_ONLYDIR);
		$arr = $template = array();
		if ($siteid) {
			$site = pc_base::load_app_class('sites','admin');
			$info = $site->get_by_id($siteid);
			if($info['template']) $template = explode(',', $info['template']);
		}
		foreach ($list as $key=>$v) {
			$dirname = basename($v);
			if ($siteid && !in_array($dirname, $template)) continue;
			if (file_exists($v.DIRECTORY_SEPARATOR.'config.php')) {
				$arr[$key] = include $v.DIRECTORY_SEPARATOR.'config.php';
				if (!$disable && isset($arr[$key]['disable']) && $arr[$key]['disable'] == 1) {
					unset($arr[$key]);
					continue;
				}
			} else {
				$arr[$key]['name'] = $dirname;
			}
			$arr[$key]['dirname']=$dirname;
		}
		return $arr;
	}
	/**
	 * 设置config文件
	 * @param $config 配属信息
	 * @param $filename 要配置的文件名称
	 */
	function set_config($config, $filename="system") {
		$configfile = CACHE_PATH.'configs'.DIRECTORY_SEPARATOR.$filename.'.php';
		if(!is_writable($configfile)) showmessage('Please chmod '.$configfile.' to 0777 !');
		$pattern = $replacement = array();
		foreach($config as $k=>$v) {
			if(in_array($k,array('js_path','css_path','img_path','attachment_stat','admin_log','gzip','errorlog','phpsso','phpsso_appid','phpsso_api_url','phpsso_auth_key','phpsso_version','connect_enable', 'upload_url','sina_akey', 'sina_skey', 'snda_enable', 'snda_status', 'snda_akey', 'snda_skey', 'qq_akey', 'qq_skey','qq_appid','qq_appkey','qq_callback','admin_url'))) {
				$v = trim($v);
				$configs[$k] = $v;
				$pattern[$k] = "/'".$k."'\s*=>\s*([']?)[^']*([']?)(\s*),/is";
	        	$replacement[$k] = "'".$k."' => \${1}".$v."\${2}\${3},";					
			}
		}
		$str = file_get_contents($configfile);
		$str = preg_replace($pattern, $replacement, $str);
		return pc_base::load_config('system','lock_ex') ? file_put_contents($configfile, $str, LOCK_EX) : file_put_contents($configfile, $str);		
	}
	
	/**
	 * 获取系统信息
	 */
	function get_sysinfo() {
		$sys_info['os']             = PHP_OS;
		$sys_info['zlib']           = function_exists('gzclose');//zlib
		$sys_info['safe_mode']      = (boolean) ini_get('safe_mode');//safe_mode = Off
		$sys_info['safe_mode_gid']  = (boolean) ini_get('safe_mode_gid');//safe_mode_gid = Off
		$sys_info['timezone']       = function_exists("date_default_timezone_get") ? date_default_timezone_get() : L('no_setting');
		$sys_info['socket']         = function_exists('fsockopen') ;
		$sys_info['web_server']     = strpos($_SERVER['SERVER_SOFTWARE'], 'PHP')===false ? $_SERVER['SERVER_SOFTWARE'].'PHP/'.phpversion() : $_SERVER['SERVER_SOFTWARE'];
		$sys_info['phpv']           = phpversion();	
		$sys_info['fileupload']     = @ini_get('file_uploads') ? ini_get('upload_max_filesize') :'unknown';
		return $sys_info;
	}

	/**
	 * 检查目录可写性
	 * @param $dir 目录路径
	 */
	function dir_writeable($dir) {
		$writeable = 0;
		if(is_dir($dir)) {  
	        if($fp = @fopen("$dir/chkdir.test", 'w')) {
	            @fclose($fp);      
	            @unlink("$dir/chkdir.test"); 
	            $writeable = 1;
	        } else {
	            $writeable = 0; 
	        } 
		}
		return $writeable;
	}
	
	/**
	 * 返回错误日志大小，单位MB
	 */
	function errorlog_size() {
		$logfile = CACHE_PATH.'error_log.php';
		if(file_exists($logfile)) {
			return $logsize = pc_base::load_config('system','errorlog') ? round(filesize($logfile) / 1048576 * 100) / 100 : 0;
		} 
		return 0;
	}

    function category(){
        return array(
            //九游栏目转换
            '1' => '33',//休闲益智
            '2' => '39',//赛车竞速
            '3' => '35',//角色扮演
            '4' => '37',//策略游戏
            '5' => '37',//冒险
            '6' => '38',//动作游戏
            '7' => '36',//模拟精英
            '8' => '34',//体育竞技
            '9' => '32',//射击游戏
            '10' => '42',//棋牌
            '11' => '41',//格斗
            '12' => '33',//休闲益智
            '13' => '41',//回合-》格斗
            '14' => '35',//即使-》角色扮演
            '18' => '39',//赛车
            '19' => '33',//其他
            '25' => '36',//养成
            /*
            '35' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
            '36' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
            '37' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
            '38' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
            '39' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
            '40' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
            '41' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
            '48' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
            '49' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
            '50' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
            '51' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
            '53' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
            '54' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
            '55' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
            '56' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
            '57' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
            */
        );
    }
function category_type(){//游戏原始类别
    return array(
        //九游栏目转换
        '1' => '休闲',//休闲益智
        '2' => '竞速',//赛车竞速
        '3' => '角色',//角色扮演
        '4' => '策略',//策略游戏
        '5' => '冒险',//冒险
        '6' => '动作',//动作游戏
        '7' => '模拟',//模拟精英
        '8' => '体育',//体育竞技
        '9' => '射击',//射击游戏
        '10' => '棋牌',//棋牌
        '11' => '格斗',//格斗
        '12' => '益智',//休闲益智
        '13' => '回合',//回合-》格斗
        '14' => '即时',//即使-》角色扮演
        '18' => '赛车',//赛车
        '19' => '其他',//其他
        '25' => '养成',//养成
        /*
        '35' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
        '36' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
        '37' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
        '38' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
        '39' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
        '40' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
        '41' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
        '48' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
        '49' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
        '50' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
        '51' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
        '53' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
        '54' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
        '55' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
        '56' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
        '57' => 'http://player.juhe.gcvideo.cn/player.php/vid/',
        */
    );
}

    function status(){
        return array(
            //运营状态
            '1' => '删档内测',
            '2' => '公测',
            '3' => '运营',
            '4' => '下线',
            '5' => '封测',
            '9' => '即将公测',
            '10' => '不删档内测',
            '11' => '删档内测结束',
            '12' => '即将内测',
            '13' => '停运',
            '14' => '预告',
            '15' => '压测',
            '16' => '即将封测',
            '17' => '停服',
            '18' => '不删档内测结束',
            '19' => '压测结束',
            '20' => '封测结束',
            '21' => '即将压测',
            '22' => '开放性内测',
            '23' => '二次内测',
            '24' => '破解',
            '25' => '免费',
            '26' => '收费',
            '27' => '首发',
            '28' => '即将删档封测',
            '29' => '即将不删档封测',
            '30' => '删档封测',
            '31' => '不删档封测',
            '32' => '删档封测结束',
            '33' => '不删档封测结束',


        );
    }
    function platform(){//平台类型
        return array(
            '1' => 'sb',
            '2' => '57',
            '3' => '58',
            '4' => '59',
            '5' => 'wp',
            '6' => 'H5',
            '7' => 'Wap',
            '8' => 'Web',
            '9'=>'Flash'
        );

    }
    function plate(){//平台类型
     return array(
         '2' => '0',
        '3' => '1',
        '4' => '1',
     );

}

    function game_type(){//游戏类型 单机网游
        return array(
            '7' => '',
            '8' => '',
            '9' => '',
            '10' => '',
            '11' => '',
            '12' => '',
            '13' => '',
            '14' => '',
            '15'=>'1',
            '16'=>'',
            '17'=>'0',
            '18'=>'0',
            '19'=>'',
        );

    }

?>