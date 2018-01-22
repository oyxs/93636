<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');

?>
<body xmlns="http://www.w3.org/1999/html">

<tr>
    <form action="" method="post" />
    <th><h3>添加游戏</h3></th>
    <th><input type="text" size="30" name="game_name" /> </th>
    <th><input type="submit"  value="添加游戏" name="submit" /> </th>
    </form>
</tr>
</body>