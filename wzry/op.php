<?php

$id = intval($_GET['id']);
switch ($id)
{
case 1:
  $url = "http://wechat.battlesteed.cn/OW/index.act?OWID=7&proxUrl=aHR0cDovL3d3dy5vd3podXNob3UuY29tL3N0cmVldC5waHA=";
  break;
case 2:
  $url = "http://wechat.battlesteed.cn/OW/index.act?OWID=7&proxUrl=aHR0cDovL3d3dy5vd3podXNob3UuY29tL2FiaWxpdHkucGhw";
  break;
case 3:
  $url = "http://wechat.battlesteed.cn/OW/index.act?OWID=7&proxUrl=aHR0cDovL3d3dy5vd3podXNob3UuY29tL3dvcmQucGhw";
  break;
case 4:
 $url = "http://wechat.battlesteed.cn/OW/index.act?OWID=7&proxUrl=aHR0cDovL3d3dy5vd3podXNob3UuY29tL2ZhY2UucGhw";
 break;
case 5:
 $url = "http://wechat.battlesteed.cn/OW/index.act?OWID=7&proxUrl=aHR0cDovL3d3dy5vd3podXNob3UuY29tL2NhcnJ5LnBocA==";
case 6:
 $url = "http://wechat.battlesteed.cn/OW/index.act?OWID=7&type=2";
 break;
case 7:
 $url = "http://www.dabiaoge.me/pvp/tool/fw/18183.html";
 break;
case 8:
 $url = "http://www.dabiaoge.me/pvp/tool/pvp18183.html";
 break;
}
 
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
    <meta name="MobileOptimized" content="620"/>
    <meta name="format-detection" content="telephone=no" />
    <title>王者荣耀攻略</title>
</head>
<body>

<style type="text/css">
	*{margin: 0;padding: 0}
    img{max-width: 100%;}
    .bg-ad{width: 100%;height: 2rem;position:fixed;left:0;bottom:0;display:none;}
    .bg-ad a{width: 100%;height: 100%;display: block}
    .bg-ad a img{width: 100%;height: 100%;display: block}
</style>
<iframe src="<?php  echo $url;?>" id="myiframe" frameborder="0" width="100%" ></iframe>
<div style="width:100%;height:1.78rem;background-color:#1E283B;display:none;"></div>
<div class="bg-ad clearfix">
    <a href="http://www.93636.com/" class="bg-ad-l" title="电子竞技" target="_blank" rel="nofollow">
        <img src="http://www.93636.com/statics/new_style/img/dj.png" alt="电子竞技" class="img-scale">
    </a>
</div>
</body>
<script src="http://www.93636.com/statics/new_style/js/lib/jquery-1.8.3.min.js"></script>
<script type="text/javascript" language="javascript">
    window.onload=function(){
        if ( document.body.clientHeight <= window.screen.height) {
            document.body.clientHeight = window.screen.height
        } else if(document.documentElement.scrollHeight <= document.documentElement.clientHeight) {
            bodyTag = $('body')[0];
            bodyTag.style.height = document.documentElement.clientWidth / screen.width * screen.height + 'px';
        }
        setTimeout(function() {
            window.scrollTo(0, 1)
        }, 0);
    };
    /*  隐藏地址栏 end */
    /* 初始化单位  rem */
    (function (doc, win) {
        var docEl = doc.documentElement,
                resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
                recalc = function () {
                    var clientWidth = docEl.clientWidth;
                    if (!clientWidth) return;
                    docEl.style.fontSize = 20 * (clientWidth / 320) + 'px';
                };

        if (!doc.addEventListener) return;
        win.addEventListener(resizeEvt, recalc, false);
        doc.addEventListener('DOMContentLoaded', recalc, false);
    })(document, window);

    var ifm= document.getElementById("myiframe");
    ifm.height=document.documentElement.clientHeight;

</script>
</html>
