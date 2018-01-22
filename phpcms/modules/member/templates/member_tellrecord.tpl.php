<?php defined('IN_ADMIN') or exit('No permission resources.');?>
<?php include $this->admin_tpl('header', 'admin');?>
<div class="pad-lr-10">
	<div class="table-list">
		<div class="bk10"></div>
		<form name="myform" id="myform" action="?m=member&c=member_tellrecord&a=delete" method="post" onsubmit="check();return false;">
			<table width="100%" cellspacing="0">
				<thead>
				<tr>
					<th align="left" width="30px"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>
					<th align="left">ID</th>
					<th>手机号</th>
					<th align="left">时间</th>
					<th align="left">验证码</th>
					<th align="left">ip</th>

<!--					<th>--><?php //echo L('operation')?><!--</th>-->
				</tr>
				</thead>
				<tbody>
				<?php
				foreach($rs as $k=>$v) {
					?>
					<tr>
						<td align="left"><input type="checkbox" value="<?php echo $v['id']?>" name="id[]" ></td>
						<td align="left"><?php echo $v['id']?></td>
						<td align="center"><?php echo $v['tell']?></th>
						<td align="left"><?php echo date("Y-m-d H :i:s",$v['time']);?></td>
						<td align="left"><?php echo $v['code']?></td>
						<td align="left"><?php echo $v['ip']?></td>
<!--						<td align="left">--><?php //echo $v['pay_status'] ? L('icon_unlock'):L('icon_locked') ?><!--</td>-->
					<!--	<td align="center">
						 <a href="javascript:edit(<?php /*echo $v['modelid']*/?>, '<?php /*echo $v['name']*/?>')"><?php /*echo L('edit')*/?></a> | <a href="javascript:delete(<?php /*echo $v['id']*/?>, '<?php /*echo $v['name']*/?>')">删除</a>
						</td>-->
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>

			<!--<div class="btn"><label for="check_box"><?php echo L('select_all')?>/<?php echo L('cancel')?></label> <input type="submit" class="button" name="dosubmit" value="<?php echo L('delete')?>" onclick="return confirm('<?php echo L('sure_delete')?>')"/>
				<input type="submit" class="button" name="dosubmit" onclick="document.myform.action='?m=member&c=member_MB93636&a=sort'" value="<?php echo L('sort')?>"/>-->
			</div>
			<div id="pages"><?php echo $pages?></div>
	</div>
</div>
</form>
<div id="PC__contentHeight" style="display:none">160</div>

<script language="JavaScript">
	<!--
	function edit(id, name) {
		window.top.art.dialog({id:'edit'}).close();
		window.top.art.dialog({title:'<?php echo L('edit').L('member_model')?>《'+name+'》',id:'edit',iframe:'?m=member&c=member_model&a=edit&modelid='+id,width:'700',height:'500'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;d.document.getElementById('dosubmit').click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
	}

	function move(id, name) {
		window.top.art.dialog({id:'move'}).close();
		window.top.art.dialog({title:'<?php echo L('move')?>《'+name+'》',id:'move',iframe:'?m=member&c=member_model&a=move&modelid='+id,width:'700',height:'500'}, function(){var d = window.top.art.dialog({id:'move'}).data.iframe;d.$('#dosubmit').click();return false;}, function(){window.top.art.dialog({id:'move'}).close()});
	}

	function check() {
		if(myform.action == '?m=member&c=member_model&a=delete') {
			var ids='';
			$("input[name='modelid[]']:checked").each(function(i, n){
				ids += $(n).val() + ',';
			});
			if(ids=='') {
				window.top.art.dialog({content:'<?php echo L('plsease_select').L('member_model')?>',lock:true,width:'200',height:'50',time:1.5},function(){});
				return false;
			}
		}
		myform.submit();
	}

	//修改菜单地址栏
	function _M(menuid) {
		$.get("?m=admin&c=index&a=public_current_pos&menuid="+menuid, function(data){
			parent.$("#current_pos").html(data);
		});
	}

	//-->
</script>
</body>
</html>