<?php 
defined('IN_ADMIN') or exit('No permission resources.');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title>后台管理中心</title>
<link href="/statics/css/reset.css" rel="stylesheet" type="text/css" />
<link href="/statics/css/zh-cn-system.css" rel="stylesheet" type="text/css" />
<link href="/statics/css/table_form.css" rel="stylesheet" type="text/css" />
<link href="/statics/css/dialog.css" rel="stylesheet" type="text/css" />

<script language="javascript" type="text/javascript" src="/statics/js/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="/statics/js/admin_common.js"></script>
<style type="text/css">
html{_overflow-y:scroll}
fieldset{
    width:40%;
	margin: 10px 0px 0px 20px;
	float: left;	
}

.table_form tbody td{
	border-bottom: 0px;
}

.pad-lr-10{
	margin-top: 10px;
}

</style>
</head>
<body>
<div class="clear">
    <fieldset>
    	<legend><a href="javascript:void(0)" onclick="$(this).parent().parent().children('table').toggle()">直接添加激活码</a></legend>
    	<form action="?m=help&c=gift&a=add&pc_hash=<?php echo $pc_hash; ?>" method="post">
    	<input type="hidden" name="gift_id" value="<?php echo $gift_id; ?>">
        	<table width="100%" class="table_form" style="display: table;">
        		<tbody>
        		<tr>	
        			<td width="280"><textarea rows="8" cols="30" name="data"></textarea></td>
        			<td>
        			     <input type="submit" class="button" value="直接添加" />
        			</td>
        		</tr>
        		</tbody>
        	</table>
    	</form>
    </fieldset>
    
    <fieldset>
    	<legend><a href="javascript:void(0)" onclick="$(this).parent().parent().children('table').toggle()">文件上传激活码</a></legend>
    	<form action="?m=help&c=gift&a=addfile&pc_hash=<?php echo $pc_hash; ?>" method="post" enctype="multipart/form-data">
    	<input type="hidden" name="gift_id" value="<?php echo $gift_id; ?>">
    	<table width="100%" class="table_form" style="display: table;">
    		<tbody>
    		<tr>	
    			<td>
    			     <input type="file" name="file" class="input-text" value="">
    			     <input type="submit" class="button" value="上传" />
    			</td>
    		</tr>
    		</tbody>
    	</table>
    	</form>
    </fieldset>
</div>

<div class="pad-lr-10">
     <div class="table-list">
          <table width="100%" cellspacing="0">
               <thead>
        	       <tr>
        		       <th align="left" width="20"><input type="checkbox" value="" id="check_box" onclick="selectall('ids[]');"></th>
        		       <th align="left">ID</th>
        		       <th align="left" width="400">激活码</th>
        		       <th align="left">领取状态</th>
        		       <th align="left">领取人</th>
					   <th align="left">领取时间</th>
					   <th align="left">领取ip</th>
        		       <th align="center">操作</th>
        	      </tr>
               </thead>
               <tbody>
               <?php 
               foreach ($data as $row){
               ?>
                   <tr>
        	           <td align="left"><input class="inputcheckbox " name="ids[]" value="<?php echo $row[id]; ?>" type="checkbox"></td>
        	           <td align='left'><?php echo $row[id]; ?></td>
        	           <td align='left'><?php echo $row[code]; ?></td>
        	           <td align='left'><?php if(intval($row[status])==1){echo "已领取";}else{echo "未领取";} ?></td>
        	           <td align='left'><?php echo $row[username]; ?></td>
					   <td align='left'><?php echo $row[gettime]==0? '': date('Y-m-d H:i:s',$row[gettime]); ?></td>
					   <td align='left'><?php echo empty($row[getip]) || $row[getip]==0?'':$row[getip]; ?></td>
        	           <td align='center'><a href="javascript:;" onclick="del(<?php echo $row[id]; ?>)">删除</a></td>
                   </tr>
               <?php 
               }
               ?>
               </tbody>
          </table>
            
          <div class="btn">
                <label for="check_box">全选/取消</label>
			  <input type="button" class="button" name="dosubmit" onclick="dels()" value="删除"/>
        <input type="button" class="button" name="dosubmit" onclick="confirmurl('?m=help&c=gift&a=delete_gift&pc_hash=&id=<?php echo $gift_id; ?>','确定要全部删除吗？')" value="全部删除"/>
			  <input type="button" class="button" name="dosubmit" onclick="location.href='http://www.93636.com/index.php?m=help&c=gift&a=detail&pc_hash=&id=<?php echo $gift_id; ?>&types=1'" value="已领取"/>
			  <input type="button" class="button" name="dosubmit" onclick="location.href='http://www.93636.com/index.php?m=help&c=gift&a=detail&pc_hash=&id=<?php echo $gift_id; ?>&types=0'" value="未领取"/>
			  <input type="button" class="button" name="dosubmit" onclick="location.href='http://www.93636.com/index.php?m=help&c=gift&a=detail&pc_hash=&id=<?php echo $gift_id; ?>&types=2'" value="全部"/>
          </div>
        
          <div id="pages"><?php echo $pages;?></div>
      </div>
</div>

<script type="text/javascript">
var pc_hash = '<?php echo $_SESSION['pc_hash'];?>';
function selectall(name) {
	if ($("#check_box").attr("checked")=='checked') {
		$("input[name='"+name+"']").each(function() {
  			$(this).attr("checked","checked");
			
		});
	} else {
		$("input[name='"+name+"']").each(function() {
  			$(this).removeAttr("checked");
		});
	}
}
           
function del(id){
	$.get("?m=help&c=gift&a=del&pc_hash=<?php echo $pc_hash; ?>&id="+id,
		function(data){
		  if(data=="true"){
			  location.reload();
		  }else{
			  alert("删除失败");
		  }
		}
	);
}

function dels(){
	var ids = "";
	$("input[name='ids[]']").each(function() {
		if($(this).attr("checked")=="checked"){
			if(ids==""){
				ids = $(this).val();
			}else{
				ids = ids+"|"+$(this).val();
			}
		}
	});
	$.get("?m=help&c=gift&a=delAll&pc_hash=<?php echo $pc_hash; ?>&ids="+ids,
		function(data){
		  if(data=="true"){
			 location.reload();
		  }else{
			 alert("删除失败");
		  }
		}
	);
};
	
</script>
</body>
</html>