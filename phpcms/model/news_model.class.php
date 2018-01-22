<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_sys_class('model', '', 0);
class news_model extends model {
    function __construct() {
        $this->db_config = pc_base::load_config('database');
        $this->db_setting = 'default';
        $this->table_name = 'news';
        parent::__construct();
    }

    public function add_content_data($info_data){
        $this->table_name = '93636_news_data';
        $id=$this->insert($info_data,true,false);
        return $id;
    }
    public function sel_content_data($id){
        $this->table_name = '93636_news_data';
        $where = array('id' => $id);
        $result = $this->get_one($where, 'content');
        return $result;
    }
    public function up_content_data($data,$id){
        $this->table_name = '93636_news_data';
        $where = array('id' => $id);
        $result =$this->update($data,$where);
        return $result;

    }
    public function up_content($data,$id){
        $this->table_name = '93636_news';
        $where = array('id' => $id);
        $result =$this->update($data,$where);
        return $result;
    }
    public function del_content($id){
        $this->table_name = '93636_news';
        $result= $this->delete("id=$id");
        return $result;
    }
    public function del_content_data($id){
        $this->table_name = '93636_news_data';
        $result= $this->delete("id=$id");
        return $result;
    }
}
