<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/11/3
 * Time: 17:21
 */

defined('IN_PHPCMS') or exit('No permission resources.');
//模型缓存路径
define('CACHE_MODEL_PATH',CACHE_PATH.'caches_model'.DIRECTORY_SEPARATOR.'caches_data'.DIRECTORY_SEPARATOR);

pc_base::load_app_class('admin', 'admin', 0);
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);
pc_base::load_app_func('util', 'content');
class mb93636 extends admin
{
    private $db;

    function __construct() {
        parent::__construct();
        $this->db = pc_base::load_model('mb_model');

    }


    /**
     * 93636充值记录
     */
    public function index(){

        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        $type = isset($_GET['type']) ? $_GET['type'] : '';
        $start_time = isset($_GET['start_time']) ? $_GET['start_time'] : '';
        $end_time = isset($_GET['end_time']) ? $_GET['end_time'] : date('Y-m-d', SYS_TIME);
        if (isset($_GET['search'])) {
            $where_start_time = strtotime($start_time) ? strtotime($start_time) : 0;
            $where_end_time = strtotime($end_time) + 86400;
            //开始时间大于结束时间，置换变量
            if($where_start_time > $where_end_time) {
                $tmp = $where_start_time;
                $where_start_time = $where_end_time;
                $where_end_time = $tmp;
                $tmptime = $start_time;

                $start_time = $end_time;
                $end_time = $tmptime;

                unset($tmp, $tmptime);
            }
            $where=' 1=1';
            if($keyword) {
                if ($type == '1') {
                    $where .= " and `username` LIKE '$keyword%'";
                } elseif($type == '2') {
                    $where .= " and `game_name` = '$keyword'";
                }elseif($type == '4') {
                    if($keyword=="支付宝"){
                        $where .= " and `type` = '1'";
                    }elseif($keyword=="银联"){
                        $where .= " and `type` = '2'";
                    }elseif($keyword=="微信"){
                        $where .= " and `type` = '3'";
                    }else{
                        $where .= " and `type` = '1'";
                    }
                }else {
                    $where .= " and `username` like '$keyword%'";
                }
            }
            if($start_time){
                $where .= " and FROM_UNIXTIME(create_time,'%Y-%m-%d') >= '$start_time'";
//                $where .= " and create_time >=".strtotime($start_time);

            }
            if($end_time){
                $where .= " and FROM_UNIXTIME(create_time,'%Y-%m-%d') <= '$end_time'";
//                $where .= " and create_time <=".strtotime($end_time);
            }
        }else{
            $where='';
        }

        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
//        $pagesize = 22;
//        $offset = $pagesize*($page-1);
//        $sql="select * from 93636_mb LEFT JOIN 93636_member ON 93636_mb.user_id=93636_member.userid  WHERE '1=1'"."$where"." ORDER BY  93636_mb.id DESC limit $offset,$pagesize  ";
//        $result=$this->db->query($sql);
//        while($row=mysql_fetch_array($result)){
//            $rs[]=$row;
//        }

        $rs = $this->db->listinfo($where, 'id DESC', $page, 22);
        $pages = $this->db->pages;
        $member = $this->db = pc_base::load_model('member_model');
        if($rs){
            foreach($rs as $k=>$v){
                $userid = $rs[$k]['user_id'];
                $wheres = array('userid'=>$userid);
                $tell = $member->get_one($wheres);
                $rs[$k]['tell'] = $tell['tell'];
            }
        }
        include $this->admin_tpl('member_mb93636');
    }
    /**
     * 短信发送记录
     */
    public function tellrecord(){
        $alidayu = $this->db = pc_base::load_model('alidayu_model');
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $where = '1=1';
        $rs = $alidayu->listinfo($where,'id DESC', $page, 22);
        $pages = $alidayu->pages;
        include $this->admin_tpl('member_tellrecord');

    }



}