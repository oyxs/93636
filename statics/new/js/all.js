$(function (){
    var nickName = $.cookie("nickname");
	//alert(nickName);
    if (nickName == undefined || $.trim(nickName).length <= 0) {//没有登录
            $("#divLogin").show();
            $("#divInfo").hide();
     } else {
            $("#divLogin").hide();
            $("#divInfo").show();
            //再给name赋值
/*		document.querySelector("#user").innerHTML = $.cookie.get("nickname"); */
     }
});
   
   
   
 $(".logout").click(function(){
      delCookie();
  });

 //删除cookie，等点击退出的时候调用方法
 function delCookie() {
        $.cookie('nickname', '', { expires: -1 });
        //直接显示登陆框
        $("#divLogin").show();
        $("#divInfo").hide();
 }
 
