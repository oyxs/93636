<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');

?>
<body>
<form action="/index.php?m=admin&c=index&a=new_search_list" method="post" id="search"/>
<li>标题</li>
    <input type="text" size="50" value="<?php echo $q ?>" name="q"/>
<select name="typeid" style="width: 300px;">
    <option value="1">游戏</option>
    <option value="2">文章</option>
	<option value="3">礼包</option>
	
</select>
    <input type="submit" value="搜索"/>
</form>
<br><br>

    <div class="table-list">
        <table width="100%">
            <thead>
            <tr>


                <th width="40">ID</th>
                <th>标题</th>
                <th width="40">点击量</th>
                <th width="70">发布人</th>
                <th width="118">更新时间</th>
                <th width="72">管理操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data as $s=>$r):?>

            <tr>
                <td align='center' ><?php echo $r['id']?></td>
                <td>
                    <a href="<?php echo $r['url']; ?>" target="_blank"><span style="" ><?php echo $r['title'];?></span></a>
                </td>
                <td align='center' title="今日点击：0&#10;昨日点击：&#10;本周点击：0&#10;本月点击：0"><?php echo $r['downloadtimes']?></td>
                <td align='center'>
                    <?php echo $r['username']?></td>
                <td align='center'><?php echo date("Y-m-d h:i:s",$r['updatetime'])?></td>
			    
                <td align='center'>
				<?php if($typeid!=3) echo "<!--";?>
				<a href="javascript:;" onclick="javascript:openwinx('?m=help&c=gift&a=detail&id=<?php echo $r['id']?>','')">详情</a> |
				<?php if($typeid!=3) echo "-->";?>
				<a href="javascript:;" onclick="javascript:openwinx('?m=content&c=content&a=edit&catid=<?php echo $r['catid']?>&id=<?php echo $r['id']?>','')">修改</a> </td>
			
            </tr>
            <?php endforeach;?>



            </tbody>
        </table>


</body>