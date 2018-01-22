<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');?>
<form name="searchform" action="" method="get" >
<input type="hidden" value="weixin" name="m">
<input type="hidden" value="reply" name="c">
<input type="hidden" value="new_search_resource" name="a">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
        <tr>
        <td>
        <div class="explain-col">
                
                <?php echo '更新时间';?>：
                <?php echo form::date('updatetime',$_GET['updatetime'],0,0,'false');?>- &nbsp;<?php echo form::date('end_time',$_GET['end_time'],0,0,'false');?>
                <?php echo '栏目';?>：
                <select name="searchtype2">
                    <option value='0' <?php if($_GET['searchtype2']==0) echo 'selected';?>><?php echo '全部';?></option>
                    <option value='1' <?php if($_GET['searchtype2']==1) echo 'selected';?>><?php echo '热门小说';?></option>
                    <option value='2' <?php if($_GET['searchtype2']==2) echo 'selected';?>><?php echo '言情小说';?></option>
                    <option value='3' <?php if($_GET['searchtype2']==3) echo 'selected';?>><?php echo '玄幻小说';?></option>
                    <option value='4' <?php if($_GET['searchtype2']==4) echo 'selected';?>><?php echo '推荐小说';?></option>
                </select>     
                <?php echo '小说类型';?>：
                <select name="searchtype3">
                    <option value='0' <?php if($_GET['searchtype3']==0) echo 'selected';?>><?php echo '全部';?></option>
                    <option value='1' <?php if($_GET['searchtype3']==1) echo 'selected';?>><?php echo '玄幻';?></option>
                    <option value='2' <?php if($_GET['searchtype3']==2) echo 'selected';?>><?php echo '奇幻';?></option>
                    <option value='3' <?php if($_GET['searchtype3']==3) echo 'selected';?>><?php echo '武侠';?></option>
                    <option value='4' <?php if($_GET['searchtype3']==4) echo 'selected';?>><?php echo '仙侠';?></option>
                    <option value='5' <?php if($_GET['searchtype3']==5) echo 'selected';?>><?php echo '都市';?></option>
                    <option value='6' <?php if($_GET['searchtype3']==6) echo 'selected';?>><?php echo '校园';?></option>
                    <option value='7' <?php if($_GET['searchtype3']==7) echo 'selected';?>><?php echo '言情';?></option>
                    <option value='8' <?php if($_GET['searchtype3']==8) echo 'selected';?>><?php echo '同人';?></option>
                    <option value='9' <?php if($_GET['searchtype3']==9) echo 'selected';?>><?php echo '灵异';?></option>
                    <option value='10' <?php if($_GET['searchtype3']==10) echo 'selected';?>><?php echo '科幻';?></option>
                    <option value='11' <?php if($_GET['searchtype3']==11) echo 'selected';?>><?php echo '军师';?></option>
                    <option value='12' <?php if($_GET['searchtype3']==12) echo 'selected';?>><?php echo '游戏';?></option>
                    <option value='13' <?php if($_GET['searchtype3']==13) echo 'selected';?>><?php echo '历史';?></option>
                    <option value='14' <?php if($_GET['searchtype3']==14) echo 'selected';?>><?php echo '文学';?></option>
                    <option value='15' <?php if($_GET['searchtype3']==15) echo 'selected';?>><?php echo '其他';?></option>
                </select>           
                <select name="searchtype">
                    <option value='0' <?php if($_GET['searchtype']==0) echo 'selected';?>><?php echo '标题';?></option>
                    <option value='1' <?php if($_GET['searchtype']==1) echo 'selected';?>><?php echo 'ID';?></option>
                </select>
                
                <input name="keyword" type="text" value="<?php if(isset($keyword)) echo $keyword;?>" class="input-text" />
                <input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
    </div>
        </td>
        </tr>
    </tbody>
</table>
</form>
<form name="myform" action="?m=admin&c=category&a=listorder" method="post">
<div class="pad_10">
<div class="bk10"></div>
<div class="table-list">
    <table width="100%" cellspacing="0" >
        <thead>
            <tr>
            <th width="30">ID</th>
            <th width="150">标题</th>
            <th align='center' width="80">栏目</th>
            <th align='center' width="100">作者</th>
            <th align='center' width="300">网盘地址</th> 
            <th align='center' width="80">密码</th>
            <th align='center' width="80">小说类型</th>
            <th align='center' width="80">编辑者</th>
            <th align='center' width="120">时间</th>
			<th ><?php echo L('operations_manage');?></th>
            </tr>
        </thead>
    <tbody>
    <?php
    if(is_array($datas)) {
        foreach ($datas as $r) {
            
    ?>
        <tr>
        <td align='center' ><?php echo $r['id'];?></td>
        <td align='center'><?php echo $r['title'];?></td>
        <td align='center'><?php echo str_replace(array("1","2","3","4"),array("热门小说","言情小说","玄幻小说","推荐小说"),trim($r['catname'],","));?></td>
        <td align='center'><?php echo $r['author'];?></td>
        <td align='center'><?php echo $r['wp_url'];?></td> 
        <td align='center'><?php echo $r['pwd'];?></td> 
        <td align='center'><?php echo str_replace(array("10","11","12","13","14","15","1","2","3","4","5","6","7","8","9"),array("科幻","军事","游戏","历史","文学","其他","玄幻","奇幻","武侠","仙侠","都市","校园","言情","同人","灵异"),trim($r['type'],","));?></td>
        <td align='center'><?php echo $r['create_user'];?></td>
        <td align='center'><?php echo format::date($r['create_time'],1);?></td>
        <td align='center'><a href="?m=weixin&c=reply&a=resource_edit&id=<?php echo $r['id']?>"><?php echo L('edit');?></a> | <a href="javascript:confirmurl('?m=weixin&c=reply&a=resource_delete&id=<?php echo $r['id']?>','确认要删除 『 <?php echo $r['title']?> 』 吗？')">删除</a></td>
    </tr>
     <?php }
    }
    ?>
    </tbody>
    </table>

    <div class="btn">
	<input type="hidden" name="pc_hash" value="<?php echo $_SESSION['pc_hash'];?>" />
	<input type="submit" class="button" name="dosubmit" value="<?php echo L('listorder')?>" /></div>  </div>
</div>
</div>
<div id="pages"><?php echo $pages?></div>
</form>
<script language="JavaScript">
<!--
	window.top.$('#display_center_id').css('display','none');
//-->
</script>
</body>
</html>
