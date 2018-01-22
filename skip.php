<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<body>
<script type="text/javascript">
    var u="<?php echo $_GET['u'];?>";
    function is_weixin(){
        var ua = navigator.userAgent.toLowerCase();
        if(ua.match(/MicroMessenger/i)=="micromessenger") {
            return 1;
        } else {
            return 0;
        }
    }

if(is_weixin()){
    window.location.href='http://www.93636.com/reminder.php?u='+u;
}
else{
    location.href=u;
}
</script>
</body>
</html>



