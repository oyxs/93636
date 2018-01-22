<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_sys_class('model', '', 0);
class game_model extends model {
    function __construct() {
        $this->db_config = pc_base::load_config('database');
        $this->db_setting = 'default';
        $this->table_name = 'game';
        parent::__construct();
    }

    public function add_game1($data){//九游插入主表
        $this->table_name = '93636_game';
        $id=$this->insert($data,1,false);
        return $id;

    }
    public function add_game_data($game_data){//九游插入附表
        $this->table_name = '93636_game_data';
        $id=$this->insert($game_data,1,false);
        return $id;

    }

    public function sel_game_id($game_id){//查询gameid
        $this->table_name = '93636_game';
        $where = array('game_id' => $game_id);
        $result = $this->get_one($where, 'id');
        return $result;

    }
    public function sel_anlink($anlink){
        $this->table_name = '93636_game';
        //$where = "anlink like '%$anlink%'";
        $where = array('anlink'=>$anlink);
        $sel = $this->get_one($where, 'id');
        return $sel;
    }
    public function sel_title($title){
        $this->table_name = '93636_game';
        //$where = "title like '%$title%'";
        $where= array('title'=>$title);
        $data=$this->get_one($where, 'id');
        return $data;

    }

}