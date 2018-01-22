<?php
set_time_limit(0);
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);

class jiuyou extends admin{
    private $db;
    function __construct() {
        $this->db = pc_base::load_model('game_model');
		$this->game_db = pc_base::load_model('game_api_model');
        parent::__construct();
    }
    public function index()
    {
		$pageNum=1;//同步页数
       while(true){
            $url = "http://interface.9game.cn/datasync/getdata";//九游数据地址
            $caller = "um_miguan001_149188";//caller
            $signKey = "56d0000a91cecf12e85cd180bfdf349c";
            $day=date('Ymd',strtotime("-4 days")).'000000';
            // $dateFrom ="20000101000000";//开始时间     //第一次上线20000101000000    date('Ymd',strtotime("-10 days")).'000000';//后期
            $dateFrom =$day;
            $dateTo = date(Ymd) . '000001';//结束时间
            $syncEntity = 'game,platform,package';//需要同步的信息列表  game: 游戏信息  platform：游戏平台信息  package：游戏包信息  event：大事件信息
            $syncField = 'game.id,game.name,game.categoryId,game.typeId,game.keywords,
        platform.gameId,platform.score,platform.platform,platform.logoImageUrl,platform.frontImageUrl,platform.screenshotImageUrls,platform.instruction,platform.operationStatusId,platform.size,platform.description,platform.version,platform.gameId,platform.versionUpdateDesc,platform.dataPkgInstallDesc,platform.operationTypeId,platform.uploadTime,platform.modifyTime,platform.language,platform.operationStatusId,
        package.downUrl,package.fileSize';//获取字段
            $syncType = 1;//同步方式  1：时间范围同步“游戏”信息， 2：按照gameId列表查询“游戏”信息   3：查询“排行榜”信息
            $pageSize = 100;//每页数量
            $str = $caller . 'dateFrom=' . $dateFrom . 'dateTo=' . $dateTo . 'pageNum=' . $pageNum . 'pageSize=' . $pageSize . 'syncEntity=' . $syncEntity . 'syncField=' . $syncField . 'syncType=' . $syncType . $signKey;
            $sign = md5($str);
			
            $id = date(ymdhis);
            $arr = array(
                'id' => $id,
                'client' => array(
                    'caller' => $caller,
                    'ex' => null
                ),
                'data' => array(
                    'syncEntity' => $syncEntity,
                    'syncField' => $syncField,
                    'syncType' => $syncType,
                    'dateFrom' => $dateFrom,
                    'dateTo' => $dateTo,
                    'pageSize' => $pageSize,
                    'pageNum' => $pageNum
                ),
                'sign' => $sign,
                'encrypt' => 'base64'

            );

            $sign_data = json_encode($arr);
			
            $return = http_post($url, $sign_data);
            if ($return[0] == 200) {//接收数据
                $arr = json_decode($return[1], true);
                $game_info = base64_decode($arr['data']);
                $game_info = json_decode($game_info, true);
				
                if (!count($game_info['list'])) {
					exit("获取游戏结束");
                }
                $preg_game = array();
				if(is_array($game_info)){
                foreach ($game_info['list'] as $v) {
					$games[] = $v;
					}
				}else{
					exit("获取游戏结束");
				}
				if(is_array($games)){
				foreach($games as $v){
						if($v['deleted']!=1){
							$preg_game[]=$v;
						}
					}
					}else{
						exit("获取游戏结束");
					}
            }else{
				exit("获取游戏结束");
            }
			
            $category = category();//分类转换
            $status = status();//运营状态
            $platform = platform();//平台类型
            $game_type = game_type();//游戏类别 单机网游
            $category_type = category_type();//游戏类别文字
            $plate = plate();
            foreach ($preg_game as $key => $val) {//转换mysql字段
                $result_game_id=$this->db->sel_game_id($val['id']);//查询game_id是否存在
                $result_title=$this->db->sel_title($val['name']);
                if (!empty($result_game_id)||!empty($result_title)) {
                    !empty($result_game_id)==true?$where = array('id' => $result_game_id['id']):$where = array('id' => $result_title['id']);
                    $up_sql = array('game_id' => $val['id'], 'version' => $val['platforms'][0]['version'], 'state' => $status[$val['platforms'][0]['operationStatusId']],'anlink'=>$val['packages'][0]['downUrl']);
                    $this->table_name = '93636_game';
                    $this->db->update($up_sql, $where);
                } else{
                    $sql_data = array(
                        'catid' => $category[$val['categoryId']],//栏目
                        'typeid' => $platform[$val['platforms'][0]['platformId']],//类别
                        'title' => $val['name'],//游戏名
                        //'style'=>'',
                        //'thumb' => $val['platforms'][0]['logoImageUrl'],//缩略图
                        'keywords' => $val['keywords'],//关键词
                        //'description' => str_replace('\'','',$val['platforms'][0]['description']),//摘要
                        'posids' => 0,//推荐位
                        //'url'=>'',
                        'listorder' => 0,
                        'status' => 1,//状态
                        'sysadd' => 1,
                        'islink' => 0,
                        'username' => 'rainbow',
                        'inputtime' => time(),//发布时间
                        'updatetime' => time(),//更新时间
                        //'tag' => $val['keywords'],
                        'type' => $category_type[$val['categoryId']],//游戏类型
                        'downloadtimes' => rand(110, 1000),//下载次数
                        'plate' => $plate[$val['platforms'][0]['platformId']],//游戏平台
                        'singleornet' => $game_type[$val['typeId']],//单机网游
                        'language' => $val['platforms'][0]['language'],//语言
                        'version' => $val['platforms'][0]['version'],//版本
                        'size' => number_format($val['packages'][0]['fileSize'] / 1024 / 1024, 2, '.', '') . "MB",//文件大小
                        'state' => $status[$val['platforms'][0]['operationStatusId']],//运营状态
                        'recommend' => round($val['platforms'][0]['score']),//推荐指数
                        //'bigtu' => $val['platforms'][0]['frontImageUrl'],//内页大图内页大图
                        'anlink' => $val['packages'][0]['downUrl'],//安卓下载地址
                        //'twcode'=>'',//安卓二维码
                        //'applelink'=>$val['packages'][0]['downUrl'],//苹果下载链接
                        //'appleerw'=>'',//苹果二维码
                        'hengshu' => 2,//横竖
                        //'seotitle' => $val['name'],//SEO标题
                        'gamename' => $val['name'],//游戏名称
                        'good' => 0,
                        'bad' => 0,
                        //'libaoURL'=>'',
                        //'nopicture`'=>'',
                        //'pclink'=>$val['packages'][0]['downUrl'],
                        'game_id' => $val['id']
                    );
                    $this->table_name = '93636_game';
                    $game_id = $this->db->add_game1($sql_data);
                    $game_data = array(
                        'id' => $game_id,//ID
                        'content' => str_replace('\'','',$val['platforms'][0]['description']),//内容
                        'readpoint' => 0,//阅读数
                        'groupids_view' => '',//阅读权限
                        'paginationtype' => 0,// 分页方式
                        'maxcharperpage' => 10000,//分页字符数
                        'template' => '',//模板
                        'paytype' => 0,//支付类别
                        'allow_comment' => 1,// 是否允许评论
                        'relation' => '',//相关文章
                        //'pictures'=>$pictures,//游戏截图
                        'QQQ' => '',
                    );
                    $this->table_name = '93636_game_data';
                    $this->db->add_game_data($game_data);
                    echo "游戏不存在添加游戏" . $val['name'] . "<br/>";
                }
            }
           $pageNum++;
        }
        include $this->admin_tpl('jiuyou_index');
    }
	/*
     * 360api
     */
    public function api(){
        header("Content-type:text/html;charset=utf-8");
        set_time_limit(0);
        $api_id=$this->game_db->select($where = '', $data = 'api_id') ;
        foreach($api_id as $v){
            $w_id[] = $v['api_id'];
        }
        $start=0;
        $num=300;
        while(true){
            $url="http://api.np.mobilem.360.cn/app/cpsgames?from=lm_241329&start=$start&num=$num&starttime=0&endtime=0";
            $str="endtime=0&from=lm_241329&num=$num&start=$start&starttime=097d619bd547be9ca34650c509ad4daab";
            $sign=md5($str);
            $get_url=$url."&sign=".$sign;
            $data=file_get_contents($get_url);
            $array=json_decode($data,true);
            $start=$start+$num;
            $items=$array['items'];
            if(empty($items)){
                break;
            }else{
                foreach($items as $v){
                    if(in_array($v['id'],$w_id)){
                        $data_up=array(
                            'downloadUrl'=>$v['downloadUrl']
                         );
                        $where=array('api_id'=>$v['id']);
                        $this->game_db->update($data_up, $where = $where);

                    }else{
                        $data_insert=array(
                            'name'=>$v['name'],
                            'api_id'=>$v['id'],
                            'downloadUrl'=>$v['downloadUrl']
                        );
                        $return_insert_id=$this->game_db->insert($data_insert, $return_insert_id = true);
                    }

                }
            }
        }
    }

    /*
     * CDN接口
     */
    public function cdn_api(){
        if(isset($_POST['sub'])){
            $p_data=$_POST['p_data'];
            $type = $_POST['type'];
            $url="http://push.dnion.com/cdnUrlPush.do";
            $post_data=array(
                'captcha'=>"436bd4c3",
                'type'=>$type,
                'url'=>$p_data,
                'decoude'=>'y'
            );
            $parm_string = http_build_query($post_data,"&");
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $parm_string);
            curl_exec($ch);
            curl_close($ch);
        }
        include $this->admin_tpl('cdn_api');

    }




}