<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
class ptb extends admin {
    function __construct() {
        $this->db = pc_base::load_model('mb_game_model');
        $this->rebate_db = pc_base::load_model('mb_rebate_model');
        parent::__construct();
    }

    /**
     * 联运游戏添加
     */
    public function game_add(){

        if($_POST['submit']){
            $data['game_name'] = htmlspecialchars($_POST['game_name']);
            $result = $this->db->insert($data);
            $result == true ?showmessage("添加成功"):showmessage("添加失败");
        }
        include $this->admin_tpl('game_add');
    }

    /**
     * 米币返利
     */
    public function mb_rebate(){
        if($_POST['submit']){
            $start_time = strtotime($_POST['start_time']);
            $end_time = strtotime("$_POST[end_time] 23:59:59");

            $count = intval($_POST['count']);
            $arr = array(
                'rebate1'=>array('m'=>intval($_POST['m1']),val=>$_POST['v1']),
                'rebate2'=>array('m'=>intval($_POST['m2']),val=>$_POST['v2']),
                'rebate3'=>array('m'=>intval($_POST['m3']),val=>$_POST['v3']),
                'rebate4'=>array('m'=>intval($_POST['m4']),val=>$_POST['v4']),
                'rebate5'=>array('m'=>intval($_POST['m5']),val=>$_POST['v5']),

            );
            $static = intval($_POST['static']);
            $rebate=serialize($arr);

            $data=array(
                'start_time'=>$start_time,
                'end_time' =>$end_time,
                'rebate'=>$rebate,
                'count'=>$count,
                'static'=>$static
            );

            $result = $this->rebate_db->update($data,array('id'=>1));
            $result == true ?showmessage("返利活动设置成功"):showmessage("返利活动设置失败");


        }
        $old_data = $this->rebate_db->get_one(array('id'=>1));
        $old_arr = unserialize($old_data['rebate']);

        include $this->admin_tpl('mb_rebate');

    }


}
?>