<?php 
defined('IN_ADMIN') or exit('No permission resources.');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title>phpcms V9 - 后台管理中心</title>
<link href="/statics/css/reset.css" rel="stylesheet" type="text/css" />
<link href="/statics/css/zh-cn-system.css" rel="stylesheet" type="text/css" />
<link href="/statics/css/table_form.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="/statics/js/jquery.min.js"></script>
</head>
<body>

<div class="subnav">
    <div class="content-menu ib-a blue line-x">
        <a href='?m=help&c=gift&a=init&pc_hash=<?php echo $pc_hash; ?>' class="on"><em>全部礼包</em></a><span>|</span><a href='?m=help&c=gift&a=init&pc_hash=<?php echo $pc_hash; ?>&last=1'><em>未过期</em></a><span>|</span><a href='?m=help&c=gift&a=init&pc_hash=<?php echo $pc_hash; ?>&last=2'><em>已过期</em></a>   
		<form action="" method="get" />
        <input type="hidden" value="help" name="m">
        <input type="hidden" value="gift" name="c">
        <input type="hidden" value="init" name="a">
        <em>游戏名</em>
        <input name="q" type="text" value="<?php if(isset($q)) echo $q;?>" class="input-text" />
        <input type="submit" name="search" class="button" value="<?php echo L('search');?>" />   
        </form>
    </div>
</div>

<style type="text/css">
html{_overflow-y:scroll}
</style>

<div class="pad-lr-10">
    <div class="table-list">
        <table width="100%" cellspacing="0">
	       <thead>
		       <tr>
			       <th align="left" width="20"><input type="checkbox" value="" id="check_box" onclick="selectall('nodeid[]');"></th>
			       <th align="left">ID</th>
			       <th align="left" width="400">礼包名称</th>
			       <th align="left">过期时间</th>
			       <th align="left">剩余个数</th>
			       <th align="left">已领个数</th>
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
		           <td align='left'><?php echo $row[title]; ?></td>
		           <td align='left'><?php echo $row[endtime]; ?></td>
		           <td align='left'><?php echo $row[on]; ?></td>
		           <td align='left'><?php echo $row[out]; ?></td>
		           <td align='center'><a href="?m=help&c=gift&a=detail&pc_hash=<?php echo $pc_hash; ?>&id=<?php echo $row[id]; ?>">查看详情</a></td>
	           </tr>
	        <?php 
            }
	        ?>
            </tbody>
        </table>
        
        <div id="pages"><?php echo $pages;?></div>
    </div>
</div>
</body>
</html>