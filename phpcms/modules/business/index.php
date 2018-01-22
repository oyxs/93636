<?php
defined('IN_PHPCMS') or exit('No permission resources.');
//模型缓存路径
pc_base::load_sys_class('FileUpload');

class index {
    private $db;
    function __construct() {
        $this->db = pc_base::load_model('admin_model');
        $this->new_db = pc_base::load_model('news_model');
        session_start();
    }

    //首页
	public function init() {//文章列表页
		if(!empty($_SESSION['business_login'])){
            $username=$_SESSION['business_username'];
            $page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
            $pagesize = 10;
            $offset = $pagesize*($page-1);
            $where="catid in (2,29,30,27) && username='$username'";
            $data=$this->new_db->select($where, '*',$offset.','.$pagesize,"id desc");
            $totalnums=$this->new_db->count($where);
            $totalnums = isset($totalnums) ? $totalnums : 0;
            $pages = pages($totalnums, $page, $pagesize);
            include template('business','index');
        }else{
            showmessage(L('请重新登录'),'?m=business&c=index&a=login');
        }
	}
	
	public function login(){//登录页
       if(isset($_POST['submit'])){
            $username = isset($_POST['username']) ? trim($_POST['username']) : showmessage(L('nameerror'),HTTP_REFERER);
            $r = $this->db->get_one(array('username'=>$username));
           if(!$r) showmessage(L('user_not_exist'),'?m=business&c=index&a=login');
           $password=md5(md5(trim($_POST['password'])).$r['encrypt']);
           if($r['password']==$password){
               $_SESSION['business_login']=1;
               $_SESSION['business_username']=$username;
			   $_SESSION['userid']=$username;
               showmessage(L('登录成功'),'?m=business&c=index&a=init');
           }else{
               showmessage(L('密码错误'),'?m=business&c=index&a=login');
           }
       }
        include template('business','login');
	}
    public function del(){//删除文章
        if(empty($_SESSION['business_login'])){
            showmessage(L('未登录请从新登录'),'?m=business&c=index&a=login');
        }
        $id=$_GET['id']?intval($_GET['id']):"";

        $result=$this->new_db->del_content($id);
        $result=$this->new_db->del_content_data($id);
        $result==true?showmessage(L('删除成功'),'?m=business&c=index&a=init'):showmessage(L('删除失败'),'?m=business&c=index&a=init');

    }

	public function add_content(){
        if(empty($_SESSION['business_login'])){
            showmessage(L('未登录请从新登录'),'?m=business&c=index&a=login');
        }
            if(isset($_POST['sub'])) {
			$static=1;
            $info=array(
                    'title'=>trim($_POST['info']['title']),
                    'catid'=>intval($_POST['info']['catid']),
                    'tags'=>trim($_POST['info']['tags']),
                    'username'=>$_SESSION['business_username'],
                    'keywords'=>trim($_POST['info']['keywords']),
                    'description'=>trim($_POST['info']['description']),
                    'inputtime'=>strtotime($_POST['info']['inputtime']),
                    'status'=>1,
                );
                if(!empty($_FILES['file']['tmp_name'])) {//缩略图
                    $up = new fileupload;
                    //设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
                    $up->set("path", "./uploadfile/content/");
                    $up->set("maxsize", 2000000);
                    $up->set("allowtype", array("gif", "png", "jpg", "jpeg"));
                    $up->set("israndname", true);
                    $path = "http://www.93636.com/uploadfile/content/";
                    //使用对象中的upload方法， 就可以上传文件， 方法需要传一个上传表单的名子 pic, 如果成功返回true, 失败返回false
                    if ($up->upload("file")) {
                     $info['thumb'] = $path . $up->getFileName();
                    } else {
                    print_r($up->getErrorMsg());
                        }
                }
				if(!empty($_FILES['nopicture']['tmp_name'])) {//不规则图
                    $up = new fileupload;
                    //设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
                    $up->set("path", "./uploadfile/content/");
                    $up->set("maxsize", 2000000);
                    $up->set("allowtype", array("gif", "png", "jpg", "jpeg"));
                    $up->set("israndname", true);
                    $path = "http://www.93636.com/uploadfile/content/";
                    //使用对象中的upload方法， 就可以上传文件， 方法需要传一个上传表单的名子 pic, 如果成功返回true, 失败返回false
                    if ($up->upload("nopicture")) {
                     $info['nopicture'] = $path . $up->getFileName();
                    } else {

                    print_r($up->getErrorMsg());
                        }
                }
                $id=$this->new_db->insert($info,true);
                $info_data=array(
                    'content'=>$_POST['info']['content'],
                    'id'=>$id
                );
                $result=$this->new_db->add_content_data($info_data);
                $id==true?showmessage(L('发布成功'),'/index.php?m=business&c=index&a=init'):showmessage(L('发布失败'),'/index.php?m=business&c=index&a=init');
            }else{
				$static=0;
			}
        include template('business', 'article');
    }
    public function up_content(){//文章编辑
        if(empty($_SESSION['business_login'])){
            showmessage(L('未登录请从新登录'),'?m=business&c=index&a=login');
        }
        $id=intval($_GET['id']);
        //if(empty($id))showmessage(L('非法操作'),'?m=business&c=index&a=login');
        $info=$this->new_db->get_one("id=$id");
        $info['inputtime']=date("Y-m-d",$info['inputtime']);
        $info_data=$this->new_db->sel_content_data($id);
        $info['content']= $info_data['content'];
        if($_POST['sub']) {
            $info=array(
                'title'=>trim($_POST['info']['title']),
                'catid'=>intval($_POST['info']['catid']),
                'tags'=>trim($_POST['info']['tags']),
                'username'=>$_SESSION['business_username'],
                'keywords'=>trim($_POST['info']['keywords']),
                'description'=>trim($_POST['info']['description']),
                'inputtime'=>strtotime($_POST['info']['inputtime']),
                'status'=>1,
            );
            if(!empty($_FILES['file']['tmp_name'])){
            $up = new fileupload;
            //设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
            $up -> set("path", "./uploadfile/content/");
            $up -> set("maxsize", 2000000);
            $up -> set("allowtype", array("gif", "png", "jpg","jpeg"));
            $up -> set("israndname", true);
            $path="http://www.93636.com/uploadfile/content/";
            //使用对象中的upload方法， 就可以上传文件， 方法需要传一个上传表单的名子 pic, 如果成功返回true, 失败返回false
            if($up -> upload("file")) {
               $info['thumb']=$path.$up->getFileName();
               } else {
              print_r($up->getErrorMsg());
             }
            }
			if(!empty($_FILES['nopicture']['tmp_name'])){
            $up = new fileupload;
            //设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
            $up -> set("path", "./uploadfile/content/");
            $up -> set("maxsize", 2000000);
            $up -> set("allowtype", array("gif", "png", "jpg","jpeg"));
            $up -> set("israndname", true);
            $path="http://www.93636.com/uploadfile/content/";
            //使用对象中的upload方法， 就可以上传文件， 方法需要传一个上传表单的名子 pic, 如果成功返回true, 失败返回false
            if($up -> upload("nopicture")) {
               $info['nopicture']=$path.$up->getFileName();
               } else {
              print_r($up->getErrorMsg());
             }
            }
            $id=intval($_POST['info']['id']);


            $this->new_db->up_content($info,$id);
            $info_data=array(
                'content'=>$_POST['info']['content'],
                'id'=>$id
            );
            $result=$this->new_db->up_content_data($info_data,$id);
            $result==true?showmessage(L('修改成功'),'/index.php?m=business&c=index&a=init'):showmessage(L('修改失败'),'/index.php?m=business&c=index&a=init');
        }


        include template('business', 'up_article');

    }
	public function login_out(){//退出登录
		unset($_SESSION['business_login']);
		unset($_SESSION['business_username']);
		showmessage(L('注销成功'),'/index.php?m=business&c=index&a=login');
	}
	
	}
?>