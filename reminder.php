<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<head>
<style>
    body{ margin: 0 auto; padding: 0; height: 100%; width: 100%; }
    .bannrt { background: #333;  min-height: 100%; min-width: 480px; width:100%; margin: 0 auto;}
    .bannrt img { display: block; width: 100%;}
    .bannrt .close { width:100px; height:100px; padding-top:15px; padding-left:15px;}
    .bannrt .close_ioc { background:url(/statics/images/close.png) no-repeat 100% 100%; min-width:59px;  min-height:57px; display:block; float:left;}
</style>
    <script src="/statics/js/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(".close").click(function() {
                $(".bannrt").hide();
            });
        });
    </script>
</head>
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
if(!is_weixin()){
    window.location.href=u;
}
</script>
<div class="bannrt">
    <div class="close"><a href="avascript:void(0);" class="close_ioc"></a></div>
    <img src="/statics/images/bt.png" alt="">
</div>
</body>
</html>



