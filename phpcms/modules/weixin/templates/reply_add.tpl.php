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

<form name="myform" id="myform" action="?m=weixin&c=reply&a=reply_add" method="post">
<div class="pad-10">
<div class="col-tab">
<div id="div_setting_1" class="contentList pad-10">

<table width="100%" class="table_form ">
		<tr>
            <th>关键字：</th>
            <td><input type="text" name="info[keyword]" id="keyword" class="input-text" value="<?php echo $keyword;?>"></td>
        </tr>
        <tr>
            <th>回复文本：</th>
            <td><textarea  style="width:600px;height:200px" name="info[content]" id="content" class="input-text" ><?php echo $content;?></textarea></td>
        </tr>
        <tr>
            <th>图文标题：</th>
            <td><input type="textarea" name="info[title]" id="title" class="input-text" value="<?php echo $title;?>"></td>
        </tr>
        <tr>
            <script type="text/javascript" src="<?php echo JS_PATH?>content_addtop.js"></script>
            <script type="text/javascript" src="<?php echo JS_PATH?>swfupload/swf2ckeditor.js"></script>
            <th>图片：</th>
            <td><?php echo form::images('info[thumb]', 'image', $thumb, 'content');?></td>
        </tr>
        <tr>
            <th>图文链接：</th>
            <td><input type="text" name="info[url]" id="url" style="width:400px;" class="input-text" value="<?php echo $url;?>"></td>
        </tr>
        <tr>
            <th>回复类型：</th>
            <td><input type='radio' name='info[type]' value='1' id="type" checked> <?php echo '纯文本模式';?>&nbsp;&nbsp;&nbsp;&nbsp;
	  			<input type='radio' name='info[type]' value='2' id="type"> <?php echo '图片模式';?>&nbsp;&nbsp;&nbsp;&nbsp;
	  			<input type='radio' name='info[type]' value='3' id="type"> <?php echo '图文模式';?></td>
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