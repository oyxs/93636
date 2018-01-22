<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/11/3
 * Time: 17:21
 */


class downcount
{
    public function downcount(){
        if($_POST['keyss'] == 1){
            echo $_POST['more'];
        }




//        include $this->admin_tpl('member_downcount');
    }
    public function ajax_count(){


//        $userid = $_SESSION['userid'];
//        $admin_username = param::get_cookie('admin_username');
//        $roles = getcache('role','commons');
//        $rolename = $roles[$_SESSION['roleid']];
//        $site = pc_base::load_app_class('sites');
//        $sitelist = $site->get_list($_SESSION['roleid']);
//        $currentsite = $this->get_siteinfo(param::get_cookie('siteid'));
//        /*管理员收藏栏*/
//        $adminpanel = $this->panel_db->select(array('userid'=>$userid), "*",20 , 'datetime');
//        $site_model = param::get_cookie('site_model');
//        include $this->admin_tpl('index');
    }


}