<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');?>
<form name="searchform" action="" method="get" >
<input type="hidden" value="weixin" name="m">
<input type="hidden" value="reply" name="c">
<input type="hidden" value="new_search_reply" name="a">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
        <tr>
        <td>
        <div class="explain-col">
                
                <?php echo '更新时间';?>：
                <?php echo form::date('updatetime',$_GET['updatetime'],0,0,'false');?>- &nbsp;<?php echo form::date('end_time',$_GET['end_time'],0,0,'false');?>
                               
                <select name="searchtype">
                    <option value='0' <?php if($_GET['searchtype']==0) echo 'selected';?>><?php echo '关键词';?></option>
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
            <th width="150">关键字</th>
            <th align='center' width="250">回复文本</th>
            <th align='center' width="100">图片地址</th>
            <th align='center' width="100">图文标题</th>
            <th align='center' width="150">图文链接</th>
            <th align='center' width="80">回复类型</th>
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
        <td align='center'><?php echo $r['keyword'];?></td>
        <td align='center'><?php echo $r['content'];?></td>
        <td align='center'><?php echo $r['thumb'];?></td>
        <td align='center'><?php echo $r['title'];?></td> 
        <td align='center'><?php echo $r['url'];?></td> 
        <td align='center'><?php echo str_replace(array("1","2","3"),array("纯文本模式","图片模式","图文模式"),trim($r['type'],","));?></td>
        <td align='center'><?php echo $r['author'];?></td>
        <td align='center'><?php echo format::date($r['updatetime'],1);?></td>
        <td align='center'><a href="?m=weixin&c=reply&a=reply_edit&id=<?php echo $r['id']?>"><?php echo L('edit');?></a> | <a href="javascript:confirmurl('?m=weixin&c=reply&a=reply_delete&id=<?php echo $r['id']?>','确认要删除 『 <?php echo $r['keyword']?> 』 吗？')">删除</a></td>
    </tr>
     <?php }
    }
    ?>
    </tbody>
    </table>

    <div class="btn">
	<input type="hidden" name="pc_hash" value="<?php echo $_SESSION['pc_hash'];?>" /></div>  </div>
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
