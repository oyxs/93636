<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');

?>
<body>
<a href="/index.php?m=admin&c=index&a=game_api&type=1&pc_hash=<?php echo $_SESSION['pc_hash'];?>">【查看未编辑游戏】</a>
<a href="/index.php?m=admin&c=index&a=game_api&type=0&pc_hash=<?php echo $_SESSION['pc_hash'];?>">【查看所有游戏】</a>
<br><br>

<div class="table-list">
    <table width="100%">
        <thead>
        <tr>
            <th width="40">ID</th>
            <th width="70">游戏名称</th>
            <th width="118">对应游戏ID</th>
            <th width="72">管理操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $s=>$r):?>
            <tr class="con">
                <td align='center' ><?php echo $r['id']?></td>
                <td align='center' ><?php echo $r['name']?></td>
                <td align='center'><input type="text" value="<?php echo $r['game_id']?>" name="game_id"  aria-valuemax=""/> </td>
                <td align='center' ><input type="button" class="save" value="更改"  data-type="<?php echo $r['id']?>"/> </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
<script>
    $(function(){
       $(".con .save").click(function(){
          var id=$(this).attr("data-type");
           var game_id =$(this).parent().prev().find("input").val();
            $.ajax({
               type: 'post',
                data: {game_id: game_id,id:id},
                url: '/index.php?m=admin&c=index&a=game_api&pc_hash=<?php echo $_SESSION['pc_hash'];?>',
               dataType: 'json',
               success: function(data) {
                   if (data.static == "1") {
                       alert("修改成功");
                   } else {
                       alert("修改失败");
                   }
               }
           })
       }) ;
    });
</script>

</body>