<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');?>
<script type="text/javascript"> 
<!--
	$(function(){
		$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'200',height:'50'}, function(){this.close();$(obj).focus();})}});
		$("#modelid").formValidator({onshow:"<?php echo L('select_model');?>",onfocus:"<?php echo L('select_model');?>",oncorrect:"<?php echo L('input_right');?>"}).inputValidator({min:1,onerror:"<?php echo L('select_model');?>"}).defaultPassed();
		$("#catname").formValidator({onshow:"<?php echo L('input_catname');?>",onfocus:"<?php echo L('input_catname');?>",oncorrect:"<?php echo L('input_right');?>"}).inputValidator({min:1,onerror:"<?php echo L('input_catname');?>"}).defaultPassed();
		$("#catdir").formValidator({onshow:"<?php echo L('input_dirname');?>",onfocus:"<?php echo L('input_dirname');?>"}).regexValidator({regexp:"^([a-zA-Z0-9、-]|[_]){0,30}$",onerror:"<?php echo L('enter_the_correct_catname');?>"}).inputValidator({min:1,onerror:"<?php echo L('input_dirname');?>"}).ajaxValidator({type : "get",url : "",data :"m=admin&c=category&a=public_check_catdir&old_dir=<?php echo $catdir;?>",datatype : "html",cached:false,getdata:{parentid:'parentid'},async:'false',success : function(data){	if( data == "1" ){return true;}else{return false;}},buttons: $("#dosubmit"),onerror : "<?php echo L('catname_have_exists');?>",onwait : "<?php echo L('connecting');?>"}).defaultPassed();
		$("#url").formValidator({onshow:" ",onfocus:"<?php echo L('domain_name_format');?>",tipcss:{width:'300px'},empty:true}).inputValidator({onerror:"<?php echo L('domain_name_format');?>"}).regexValidator({regexp:"http:\/\/(.+)\/$",onerror:"<?php echo L('domain_end_string');?>"});
		$("#template_list").formValidator({onshow:"<?php echo L('template_setting');?>",onfocus:"<?php echo L('template_setting');?>",oncorrect:"<?php echo L('input_right');?>"}).inputValidator({min:1,onerror:"<?php echo L('template_setting');?>"}).defaultPassed();
	})
//-->
</script>

<form name="myform" id="myform" action="?m=weixin&c=reply&a=resource_add" method="post">
<div class="pad-10">
<div class="col-tab">
<div id="div_setting_1" class="contentList pad-10">

<table width="100%" class="table_form ">
		<tr>
            <th>标题：</th>
            <td><input type="text" name="info[title]" id="title" class="input-text" value="<?php echo $title;?>"></td>
        </tr>
        <tr>
            <th>所属栏目：</th>
            <td><input type='radio' name='info[catname]' value='1' id="catname" checked> <?php echo '热门小说';?>&nbsp;&nbsp;&nbsp;&nbsp;
	  			<input type='radio' name='info[catname]' value='2' id="catname"> <?php echo '言情小说';?>&nbsp;&nbsp;&nbsp;&nbsp;
	  			<input type='radio' name='info[catname]' value='3' id="catname"> <?php echo '玄幻小说';?>&nbsp;&nbsp;&nbsp;&nbsp;
	  			<input type='radio' name='info[catname]' value='4' id="catname"> <?php echo '推荐小说';?></td>
        </tr>
        <tr>
            <th>作者：</th>
            <td><input type="text" name="info[author]" id="author" style="width:200px;" class="input-text" value="<?php echo $author;?>"></td>
        </tr>
        <tr>
            <script type="text/javascript" src="<?php echo JS_PATH?>content_addtop.js"></script>
            <script type="text/javascript" src="<?php echo JS_PATH?>swfupload/swf2ckeditor.js"></script>
            <th>封面图片：</th>
            <td><?php echo form::images('info[thumb]', 'image', $thumb, 'content');?></td>
        </tr>
        <tr>
            <th>网盘地址：</th>
            <td><input type="text" name="info[wp_url]" id="wp_url" style="width:400px;" class="input-text" value="<?php echo $wp_url;?>"></td>
        </tr>
        <tr>
            <th>密码：</th>
            <td><input type="textarea" name="info[pwd]" id="pwd" class="input-text" value="<?php echo $pwd;?>"></td>
        </tr>
        <tr>
            <th>类型：</th>
            <td><input type='radio' name='info[type]' value='1' id="type" checked> <?php echo '玄幻';?>&nbsp;&nbsp;
	  			<input type='radio' name='info[type]' value='2' id="type"> <?php echo '奇幻';?>&nbsp;&nbsp;
	  			<input type='radio' name='info[type]' value='3' id="type"> <?php echo '武侠';?>&nbsp;&nbsp;
	  			<input type='radio' name='info[type]' value='4' id="type"> <?php echo '仙侠';?>&nbsp;&nbsp;
	  			<input type='radio' name='info[type]' value='5' id="type"> <?php echo '都市';?>&nbsp;&nbsp;
	  			<input type='radio' name='info[type]' value='6' id="type"> <?php echo '校园';?>&nbsp;&nbsp;
	  			<input type='radio' name='info[type]' value='7' id="type"> <?php echo '言情';?>&nbsp;&nbsp;
	  			<input type='radio' name='info[type]' value='8' id="type"> <?php echo '同人';?>&nbsp;&nbsp;
	  			<input type='radio' name='info[type]' value='9' id="type"> <?php echo '灵异';?>&nbsp;&nbsp;
	  			<input type='radio' name='info[type]' value='10' id="type"> <?php echo '科幻';?>&nbsp;&nbsp;
	  			<input type='radio' name='info[type]' value='11' id="type"> <?php echo '军事';?>&nbsp;&nbsp;
	  			<input type='radio' name='info[type]' value='12' id="type"> <?php echo '游戏';?>&nbsp;&nbsp;
	  			<input type='radio' name='info[type]' value='13' id="type"> <?php echo '历史';?>&nbsp;&nbsp;
	  			<input type='radio' name='info[type]' value='14' id="type"> <?php echo '文学';?>&nbsp;&nbsp;
	  			<input type='radio' name='info[type]' value='15' id="type"> <?php echo '其他';?></td>
        </tr>
        <tr>
            <th>描述：</th>
            <td><textarea  name="info[descrition]" id="descrition" class="input-text" value="<?php echo $descrition;?>"  style="width:200px;height:80px;"><?php echo $descrition;?></textarea></td>
        </tr>
        

</table>

</div>
 <div class="bk15"></div>
	<input name="id" type="hidden" value="<?php echo $id;?>">
    <input name="dosubmit" type="submit" value="<?php echo L('submit')?>" class="button">

</form>
</div>

</div>
<!--table_form_off-->
</div>

<script language="JavaScript">
<!--
	window.top.$('#display_center_id').css('display','none');
	$(function(){
		var url = $('#url').val();
		if(!url.match(/^http:\/\//)) $('#url').val('');
	})
	function SwapTab(name,cls_show,cls_hide,cnt,cur){
		for(i=1;i<=cnt;i++){
			if(i==cur){
				 $('#div_'+name+'_'+i).show();
				 $('#tab_'+name+'_'+i).attr('class',cls_show);
			}else{
				 $('#div_'+name+'_'+i).hide();
				 $('#tab_'+name+'_'+i).attr('class',cls_hide);
			}
		}
	}
	function load_file_list(id) {
		if(id=='') return false;
		$.getJSON('?m=admin&c=category&a=public_tpl_file_list&style='+id+'&catid=<?php echo $catid?>', function(data){$('#category_template').html(data.category_template);$('#list_template').html(data.list_template);$('#show_template').html(data.show_template);});
	}
	<?php if(isset($setting['template_list']) && !empty($setting['template_list'])) echo "load_file_list('".$setting['template_list']."')"?>
//-->
</script>