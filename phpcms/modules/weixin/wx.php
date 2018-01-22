<?php
define('PHPCMS_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR);
require_once PHPCMS_PATH.'/phpcms/modules/content/index.php';
define("TOKEN", "Hsei23_dwu1Jsnzd220251264dwHmx_S");
$wechatObj = new wx();
if($_GET['sc']){ 
  $wechatObj->getSC();
}

$a = $_GET['fun'];
// $code=$_GET['CODE'];
// $state=$_GET['state'];
/**
 * 刷新菜单
 */
if($a == 'reload_menu'){

    /* 设置缓存限制为 “private” */

    session_cache_limiter('private');
    $cache_limiter = session_cache_limiter();

    /* 设置缓存过期时间为1 分钟 */
    session_cache_expire(1);
    $cache_expire = session_cache_expire();

    /* 开始会话 */

    session_start();
    $_SESSION['flag']=1;
    
}
if($_SESSION['flag']==1){
    $wechatObj->app_menu();
    session_destroy();
    //echo '{"f":1}';
}

$wechatObj->responseMsg();

//$wechatObj->valid();


class wx
{

    public $appid = "wx1a975a230a9ed1ea";
    public $appsecret = "dd0831a09d21d60a45c6bfa13a9f65bb";


    // public function valid()
    // {
    //     $echoStr = $_GET["echostr"];

    //     //valid signature , option
    //     if($this->checkSignature()){
    //         echo $echoStr;
    //         exit;
    //     }
    // }


    //获得凭证接口
    //返回数组，access_token 和  time 有效期
    public function access_token() {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appid}&secret={$this->appsecret}";
        $cont = file_get_contents($url);
        return json_decode($cont, 1);
    }


    public function responseMsg()
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        $wx_get=new index();

        //extract post data
        if (!empty($postStr)){
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                libxml_disable_entity_loader(true);
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();
                $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            <FuncFlag>0</FuncFlag>
                            </xml>"; 
                $ev = $postObj->Event;
                if ($ev == "subscribe")
                {
                    $gz_text='{ "content":"欢迎关注温香暖玉，么么哒~\r\n热门小说 \n 《墙外的春天》：&lt;a href=\"https://m.baozoukanshu.com/content/2084/5/1692/45291/0.html\"&gt;继续阅读&lt;/a&gt;\r\n主编推荐\n《流氓神针》：&lt;a href=\"https://m.baozoukanshu.com/content/1150/1/1692/45301/0.html\"&gt;进入阅读&lt;/a&gt;\n《出轨的女人》：&lt;a href=\"https://m.baozoukanshu.com/content/950/1/1692/45303/0.html\"&gt;进入阅读&lt;/a&gt;\n《乡村小医师》：&lt;a href=\"https://m.baozoukanshu.com/content/2198/1/1692/45304/0.html\"&gt;进入阅读&lt;/a&gt;\n《超级保安》：&lt;a href=\"https://m.baozoukanshu.com/content/160/1/1692/45306/0.html\"&gt;进入阅读&lt;/a&gt; \n《向来缘浅，奈何情深》：&lt;a href=\"https://m.baozoukanshu.com/content/2344/1/1692/45300/0.html\"&gt;进入阅读&lt;/a&gt;" }';
                    $msgInfo = json_decode($gz_text, true);
                    
                    $info=$msgInfo['content'];
                    $textTpl = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[text]]></MsgType>
                                <Content>$info</Content>
                                </xml>";    
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time);
                    echo $resultStr;
                }
         
                if ($ev == "CLICK")//菜单点击推送事件
                {
                    $content = $postObj->EventKey; // 获取key

                    switch ($content)
                    {
                        case "V1001_hz":
                            $textTpl = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[image]]></MsgType>
                                <Image>
                                <MediaId><![CDATA[2czRG2T2lmGadHlRT3q0z-0uJuQE7mAcTQ8NTHQL0eQ]]></MediaId>
                                </Image>
                                </xml>";    
                            break; 
                        case "V1001_sh":
                            $textTpl = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[image]]></MsgType>
                                <Image>
                                <MediaId><![CDATA[2czRG2T2lmGadHlRT3q0z-0uJuQE7mAcTQ8NTHQL0eQ]]></MediaId>
                                </Image>
                                </xml>";
                            break;                      
                        default : 
                            //$content = "商务合作、转载授权等事宜请联系QQ：1179460747，备注“商务合作”。";
                            break;
                    }
                    
                    

                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time);
                    echo $resultStr;
                }

                $msg_type=$postObj->MsgType;//回复类型
                    if(!empty( $keyword ))
                    {
                        $msgType = "text";
                        $result=$wx_get->Keyowrd_Url($keyword);
                        if($result == 'no'){ 
                            $result=$wx_get->Story_Url($keyword);
                                if($result=="no"){
                                    $gz_text='{ "content":"请输入正确的小说名字，不要有错别字哦！\r\n 【热门小说】:&lt;a href=\"https://m.baozoukanshu.com/index-238-687.html\"&gt;点这里&lt;/a&gt;\r\n【热门漫画】&lt;a href=\"https://c6978.weipinwan.com/referrals/index/34749\"&gt;点这里&lt;/a&gt; \r\n【历史推送】：&lt;a href=\"https://mp.weixin.qq.com/mp/profile_ext?action=home&amp;__biz=MzIxODM4MDczMA==&amp;scene=124#wechat_redirect\"&gt;点这里&lt;/a&gt;" }';
                                    $msgInfo = json_decode($gz_text, true);
                                        
                                    $info=$msgInfo['content'];
                                    $contentStr =  $info;
                                    $textTpl = "<xml>
                                                <ToUserName><![CDATA[%s]]></ToUserName>
                                                <FromUserName><![CDATA[%s]]></FromUserName>
                                                <CreateTime>%s</CreateTime>
                                                <MsgType><![CDATA[text]]></MsgType>
                                                <Content>$contentStr</Content>
                                                </xml>";
                                }else{

                                    $gz_text='{ "content":"【'.$result['title'].'】\n网盘地址：'.$result['url_2'].'"}';
                                    $msgInfo = json_decode($gz_text, true);
                                            
                                    $info=$msgInfo['content'];
                                    $contentStr =  $info;
                                    $textTpl = "<xml>
                                                <ToUserName><![CDATA[%s]]></ToUserName>
                                                <FromUserName><![CDATA[%s]]></FromUserName>
                                                <CreateTime>%s</CreateTime>
                                                <MsgType><![CDATA[text]]></MsgType>
                                                <Content>$contentStr</Content>
                                                </xml>";
                                }
                            
                    }else {
                        $thumb = $result['thumb'];
                        $title = $result['title'];
                        $url = $result['url'];
                        // $gz_text='{ "content":"'.$result['content'].' "}';
                        // $msgInfo = json_decode($gz_text, true);
                                    
                        // $info=$msgInfo['content'];
                        $contentStr =  $result['content'];
                        switch ($result['type']) {
                            // case '2':
                            //     $textTpl = "<xml>
                            //                 <ToUserName>< ![CDATA[%s] ]></ToUserName>
                            //                 <FromUserName>< ![CDATA[%s] ]></FromUserName>
                            //                 <CreateTime>%s</CreateTime>
                            //                 <MsgType>< ![CDATA[image] ]></MsgType>
                            //                 <Image>
                            //                 <MediaId>< ![CDATA[$thumb] ]></MediaId>
                            //                 </Image>
                            //                 </xml>";
                            //     break;
                            case '3':
                                $textTpl = "<xml>
                                            <ToUserName><![CDATA[%s]]></ToUserName>
                                            <FromUserName><![CDATA[%s]]></FromUserName>
                                            <CreateTime>%s</CreateTime>
                                            <MsgType><![CDATA[news]]></MsgType>
                                            <ArticleCount>1</ArticleCount>
                                            <Articles>
                                            <item>
                                            <Title>< ![CDATA[$title] ]></Title> 
                                            <Description><![CDATA[$contentStr]]></Description>
                                            <PicUrl><![CDATA[$thumb]]></PicUrl>
                                            <Url><![CDATA[$url]]></Url>
                                            </item>
                                            </Articles></xml>";
                                break;
                            default:
                                $textTpl = "<xml>
                                            <ToUserName><![CDATA[%s]]></ToUserName>
                                            <FromUserName><![CDATA[%s]]></FromUserName>
                                            <CreateTime>%s</CreateTime>
                                            <MsgType><![CDATA[text]]></MsgType>
                                            <Content>$contentStr</Content>
                                            </xml>";
                                break;
                        }

                        
                        
                    }
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $resultStr;
                }else{
                    echo "Input something...";
                }
          
        }else {
            echo "";
            exit;
        }
    }

    /**
     * 创建自定义菜单
     * Enter description here ...
     */
    function app_menu() {
        //$this->delete_menu();
        $data=$this->getDateJson();
        $data = preg_replace("#\\\u([0-9a-f]{4})#ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '\\1'))", $data);//json中文转换
        $access_token = $this -> access_token();
        $ch = curl_init('https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $access_token['access_token']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data)));
        $data = curl_exec($ch);
        $datajson=json_decode($data,true);
        if($datajson['errmsg']=='ok'){
            echo '刷新成功';exit();
        }else{
            echo '失败';exit();
        }
    }
    /**
     * 删除菜单
     * Enter description here ...
     */
    function  delete_menu(){
        $access_token = $this -> access_token();
        $ch = curl_init('https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=' . $access_token['access_token']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
    }
    
    /**
     * 自定义菜单
     * Enter description here ...
     */
    private function getDateJson(){
        $wx_get=new index();
        $arr2=array();
        $arr31=array();
        $arr32=array();
        $arr33=array();
        $menusql=$wx_get->get_menu();
        $i=0;$j=0;$j1=0;$j2=0;$j3=0;$lan1=0;$lan2=0;$lan3=0;
        foreach($menusql as $item){
            if($item['lanwei'] == 1){
                $lan1++;
            }
            if($item['lanwei'] == 2){
                $lan2++;
            }
            if($item['lanwei'] == 3){
                $lan3++;
            }
                
        }
        foreach ($menusql as $row){
            $i++;
            $name=$row['name'];
            $type=$row['type'];
            $url=$row['url'];
            $key=$row['munukey'];
            if($row['lanwei']==1 ){//判断拦1
                if($row['level']==2){
                    if($row['type'] == 'click'){
                        $arr31[$j1]=array("type"=>$type, "name"=>$name, "key"=>$key);$j1++;
                    }else{
                        $arr31[$j1]=array("type"=>$type, "name"=>$name, "url"=>$url);$j1++;
                    }
                    
                }
                if($lan1 >= 2){
                    if ($row['level']==1){
                        $arr2[$j]=array( "name"=>$name, "sub_button"=>$arr31);
                        $j++;
                    }
                }else{
                    if ($row['level']==1){
                        if($row['type'] == 'click'){
                            $arr2[$j]=array("type"=>$type, "name"=>$name, "key"=>$key);
                        }else{
                            $arr2[$j]=array("type"=>$type, "name"=>$name, "url"=>$url);
                        }
                        //$arr2[$j]=array("type"=>$type, "name"=>$name, "url"=>$url);
                        $j++;
                    }
                }
                
            }
            if($row['lanwei']==2 ){//判断拦2
                if($row['level']==2){
                    if($row['type'] == 'click'){
                        $arr32[$j2]=array("type"=>$type, "name"=>$name, "key"=>$key);$j2++;
                    }else{
                        $arr32[$j2]=array("type"=>$type, "name"=>$name, "url"=>$url);$j2++;
                    }
                    
                }
                if($lan2 >= 2){
                    if ($row['level']==1){
                        $arr2[$j]=array( "name"=>$name, "sub_button"=>$arr32);
                        $j++;
                    }
                }else{
                    if ($row['level']==1){
                        if($row['type'] == 'click'){
                            $arr2[$j]=array("type"=>$type, "name"=>$name, "key"=>$key);
                        }else{
                            $arr2[$j]=array("type"=>$type, "name"=>$name, "url"=>$url);
                        }
                        
                        $j++;
                    }
                }
                
            }
            if($row['lanwei']==3){//判断拦3
                if($row['level']==2){
                    if($row['type'] == 'click'){
                        $arr33[$j3]=array("type"=>$type, "name"=>$name, "key"=>$key);$j3++;
                    }else{
                        $arr33[$j3]=array("type"=>$type, "name"=>$name, "url"=>$url);$j3++;
                    }
                    
                }
                if($lan3 >= 2){
                    if ($row['level']==1){
                        $arr2[$j]=array( "name"=>$name, "sub_button"=>$arr33);
                        $j++;
                    }
                }else{
                    if ($row['level']==1){
                        if($row['type'] == 'click'){
                            $arr2[$j]=array("type"=>$type, "name"=>$name, "key"=>$key);
                        }else{
                            $arr2[$j]=array("type"=>$type, "name"=>$name, "url"=>$url);
                        }
                        
                        $j++;
                    }
                }
                
            }

        }
        $arr1=array("button"=>$arr2);

        return json_encode($arr1);

        
    }
    /**
    **素材接口
    */
    public function getSC() {
               
        $access_token = $this -> access_token();
        $data='{
           "type":"image",
           "offset":0,
           "count":20
        }';
        $ch = curl_init('https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=' . $access_token['access_token']);  
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");  
                curl_setopt($ch, CURLOPT_POST, 1 );
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $datajson = curl_exec ($ch);
                $result=json_decode($datajson,true);
        print_r($result);exit;
        
    }

    private function checkSignature()
    {
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
                
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}

?>