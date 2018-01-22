<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);


class gift extends admin{
    private $libao_model;
	private	$gift_model;
 
    public function __construct(){
        $this->libao_model = pc_base::load_model('libao_model');
        $this->gift_model = pc_base::load_model('gift_model');
        $pc_hash = isset($_GET["pc_hash"])?$_GET["pc_hash"]:$_POST["pc_hash"];
    }
    
    public function init(){
        $where = "";
        $last = isset($_GET["last"])?intval($_GET["last"]):0;
		//游戏名搜索礼包6.22
        $q = safe_replace(trim($_GET['q']));
        $q = new_html_special_chars(strip_tags($q));
        $q = str_replace('%', '', $q);	//过滤'%'，用户全文搜索
		
        if($last==1){
            $where = "endtime>'".date("Y-m-d")."'";
        }
        if($last==2){
            $where = "endtime<'".date("Y-m-d")."'";
        }
        //游戏名搜索礼包6.22
        if($q){
            $where = "`gamename` like '%$q%'";
        }
        $page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
        $pagesize = 15;
        $offset = ($page-1)*$pagesize;
        
        $datasql = $this->libao_model->select($where, 'id,title,endtime', $offset.','.$pagesize,'id desc');      
        $data = array();
        foreach ($datasql as $one){
            $gift_id = $one["id"];
            
			$one["on"] = $this->gift_model->count("gift_id=$gift_id and status=0");
            $one["out"] = $this->gift_model->count(array("gift_id"=>$gift_id,"status"=>1));
            array_push($data, $one);
        }
        $total = $this->libao_model->count($where);
        $pages = pages($total, $page, $pagesize);
        include $this->admin_tpl("gift_list");
    }
    
    public function detail(){
        $gift_id = intval($_GET["id"]);
        $gift_type = intval(isset($_GET["types"])?$_GET["types"]:2);
        $where = array("gift_id"=>$gift_id);
        if($gift_type==1 || $gift_type==0){
            $where['status'] = $gift_type;
        }

        
        $page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
        $pagesize = 15;
        $offset = ($page-1)*$pagesize;
        
        
        $data = $this->gift_model->select($where, '*',$offset.','.$pagesize);
        $total = $this->gift_model->count($where);
        $pages = pages($total, $page, $pagesize);
        
        include $this->admin_tpl("gift_list_detail");
    }
    
    public function add(){
        $gift_id = intval($_POST["gift_id"]);
        $data = $_POST["data"];
        $data = explode("\r\n",$data);
        
        foreach ($data as $row){
            if(!empty($row)){
                $this->gift_model->insert(array("gift_id"=>$gift_id,"code"=>$row));
            }
        }
        
        header("location:?m=help&c=gift&a=detail&pc_hash=$pc_hash&id=$gift_id");
    }
    
    public function addfile(){
        $gift_id = intval($_POST["gift_id"]);
        if($_FILES["file"]["error"] > 0){
            echo "Error: " . $_FILES["file"]["error"] . "<br />";exit;
        }else{
            $data = file_get_contents($_FILES["file"]["tmp_name"]);
            $data = explode("\n",$data);

            foreach ($data as $row){
                if(!empty($row)){
                    $this->gift_model->insert(array("gift_id"=>$gift_id,"code"=>$row));
                }
            }
            header("location:?m=help&c=gift&a=detail&pc_hash=$pc_hash&id=$gift_id");
        }
    }
    
    public function del(){
        $id = intval($_GET["id"]);
        $return = $this->gift_model->delete("id=$id");
        if($return===false){
            echo "false";
        }else{
            echo "true";
        }
    }
    
    public function delAll(){
        $idstring = $_GET["ids"];
        $ids = explode("|", $idstring);
        $stingId = implode(",", $ids);
        $where = "id in ($stingId)";
        $return = $this->gift_model->delete($where);
        if($return===false){
            echo "false";
        }else{
            echo "true";
        }
    }

    public function delete_gift(){
        $id = intval($_GET["id"]);
        $return = $this->gift_model->delete("gift_id=$id");
        if($return===false){
            showmessage(L('删除失败'));
        }else{
            showmessage(L('删除成功'));
        }
    }
}