<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');

?>
<body xmlns="http://www.w3.org/1999/html">
<form action="/index.php?m=admin&c=ptb&a=mb_rebate" method="post" >
<table  width="500" height="100">
    <tr>
        <link rel="stylesheet" type="text/css" href="http://www.93636.com/statics/js/calendar/jscal2.css"/>
        <link rel="stylesheet" type="text/css" href="http://www.93636.com/statics/js/calendar/border-radius.css"/>
        <link rel="stylesheet" type="text/css" href="http://www.93636.com/statics/js/calendar/win2k.css"/>
        <script type="text/javascript" src="http://www.93636.com/statics/js/calendar/calendar.js"></script>
        <script type="text/javascript" src="http://www.93636.com/statics/js/calendar/lang/en.js"></script>
        开始时间：<input type="text" name="start_time" id="start_time" value="<?php echo date('Y-m-d',$old_data['start_time']);?>" size="10" class="date" readonly>&nbsp;<script type="text/javascript">
            Calendar.setup({
                weekNumbers: false,
                inputField : "start_time",
                trigger    : "start_time",
                dateFormat: "%Y-%m-%d",
                showTime: false,
                minuteStep: 1,
                onSelect   : function() {this.hide();}
            });
        </script>- &nbsp;
        结束时间：<input type="text" name="end_time" id="end_time" value="<?php echo date('Y-m-d',$old_data['end_time']);?>" size="10" class="date" readonly>&nbsp;<script type="text/javascript">
            Calendar.setup({
                weekNumbers: false,
                inputField : "end_time",
                trigger    : "end_time",
                dateFormat: "%Y-%m-%d",
                showTime: false,
                minuteStep: 1,
                onSelect   : function() {this.hide();}
            });
        </script>

    </tr>

    <tr>
        <td>返利门槛：</td>
        <td><input type="text"  name="m1" value="<?php echo $old_arr['rebate1']['m']; ?>"/> </td>
        <td>返利金额：</td>
        <td><input type="text" name="v1" value="<?php echo $old_arr['rebate1']['val']; ?>"/> </td>
    </tr>
    <tr>
        <td>返利门槛：</td>
        <td><input type="text" name="m2" value="<?php echo $old_arr['rebate2']['m']?>" /> </td>
        <td>返利金额：</td>
        <td><input type="text" name="v2" value="<?php echo $old_arr['rebate2']['val']; ?>" /> </td>
    </tr>
    <tr>
        <td>返利门槛：</td>
        <td><input type="text" name="m3" value="<?php echo $old_arr['rebate3']['m'];?>" /> </td>
        <td>返利金额：</td>
        <td><input type="text" name="v3" value="<?php echo $old_arr['rebate3']['val']; ?>" /> </td>
    </tr>
    <tr>
        <td>返利门槛：</td>
        <td><input type="text" name="m4" value=" <?php echo $old_arr['rebate4']['m'];?>"/> </td>
        <td>返利金额：</td>
        <td><input type="text" name="v4" value="<?php echo $old_arr['rebate4']['val']; ?>"/> </td>
    </tr>

    <tr>
        <td>返利门槛：</td>
        <td><input type="text" name="m5" value=" <?php echo $old_arr['rebate5']['m']?> "/> </td>
        <td>返利金额：</td>
        <td><input type="text" name="v5" value="<?php echo $old_arr['rebate5']['val']; ?>"/> </td>
    </tr>
    <tr>
        <td>生效次数：</td>
        <td><input type="text" value="<?php echo $old_data['count'];?>" name="count" /> </td>
    </tr>
    <tr>
        <td>状态：</td>
        <td>关闭：<input type="radio" <?php if($old_data['static']==0) echo "checked='checked'"?> name="static" value="0" /> 开启：<input type="radio"  <?php if($old_data['static']==1) echo "checked='checked'"?> name="static" value="1" /></td>
    </tr>

    <tr>
        <td><input type="submit" name="submit"  value="提交" /></td>
    </tr>
</form>
</table>
</body>