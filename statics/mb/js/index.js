;(function (window, undefined) {
    //ie
    if ($.browser.msie && ($.browser.version == "7.0")) {
        String.prototype.trim = function () {
            return this.replace(/^\s |\s $/g, '');
        };
    }
    //focus
    $('input').focus(function () {
        $(this).addClass("focus");
    }).blur(function () {
        $(this).removeClass("focus");
    });
    //tab
    tab("#v-tab", "#t-container");
    //hover
    $('.dropdown-link').hover(function () {
            var $this = $(this);
            var _kind = $this.parents(".control-group").attr("data-kind");
            $this.find(".dropdown").stop().slideDown(200).find("li").on("click", function () {
                _kind = $(this).attr("type");
                $this.find(".link-txt").html("<i></i>" + $(this).text().trim());
                $this.parents(".control-group").attr("data-kind", _kind);
            });

        },
        function () {
            $(this).find(".dropdown").stop().slideUp(200);
        });

    //line
    $("#choose-list").find("li").hover(function () {
        var _left = $(this).position().left;
        $(".underline").css('left', _left - 4 + "px")
    }, function () {
        $(".underline").css('left', $(this).parent().find("li.act").position().left - 4 + "px")
    });


    //choose-game-box
    $(".choose-game-box input").focus(function () {
        $(".mchoose-box").slideDown(200);
        var _text = '';
        $("#choose-content").find("li").on("click", function () {
            _text = $(this).find("a").text().trim();
            $(".choose-game-box input").val(_text);
            $(".mchoose-box").slideUp(200);
            return false;
        })
    });
    //
    $(document).on("click", function (e) {
        var target = $(e.target);
        if (target.closest(".choose-game-box").length == 0) {
            $(".mchoose-box").slideUp(200);
        }
    });

    //充值选择
    $(".controls-option").find(".channel-radio").on("click", function () {
        $(this).addClass("active").siblings().removeClass("active");
        if ($(this).parents(".control-group").hasClass("pay-count-box")) {
            var pay_num = $(this).attr("data-num");
            $("#pay-count").val(pay_num);
            $(".pay-fee").text(pay_num * 10);
        } else if ($(this).parents(".control-group").hasClass("pay-method-box")) {
            var _method = $(this).attr("data-kind");
            $(".pay-method-box").attr("data-method", _method);
            $("#pay_type").val(_method);
            if (_method == 1) {
                $('#pay_form').attr('action', 'http://www.93636.com/alipay/alipayapi.php');
            } else if (_method == 2) {
                $('#pay_form').attr('action', 'http://www.93636.com/UnionPay/demo/api_01_gateway/Form_6_2_FrontConsume.php');
            } else if (_method == 3) {
                $('#pay_form').attr('action', 'http://www.93636.com/payInterface_native/');
            }
        }

    });
    //充值米币
    $("#pay-count").change(function () {
        var pay_num = $("#pay-count").val();
        $(".pay-fee").text(pay_num * 10);
    });

    //弹窗
    $(function () {
        var msg = $("#msg").attr("show-id");
        if (msg == "succer") {
            $(".v-dialog,.mask").show();
            $("#v-tab").find("li").removeClass("active").eq(1).addClass("active");
            $("#t-container").find(".v-tab-content").removeClass("cur").hide().eq(1).addClass("cur").show();
        }
    });

    //
   $(".submit-box a").on("click", function () {
        //$(".v-dialog,.mask").show();
        var game_name = $('input[name=game_name]').val();
        var username = $('input[name=username]').val();
        var username_check = $('input[name=username_check]').val();
        var type = $('input[name=type]').val();
        var pay = $('input[name=pay]').val();
        var number = $('input[name=number]').val();
        var trade_name = $('input[name=trade_name]').val();
        var trade_description = $('input[name=trade_description]').val();
        var dosubmit = $('input[name=dosubmit]').val();
        if (game_name == '' || username == '' || username_check == '' || type == ''  || pay == '' || dosubmit == '' || trade_name == '' || trade_description == '' || number == '') {
            alert('您输入的信息不完整');
            exit();
        }


        $.ajax({
            type: "post",
            data: {username: username},
            url: "http://admin.93636.com/admin.php/Api/Appapi/member.html?jsoncallback=?",
            dataType: "jsonp",
            jsonp: "callback",
            jsonpCallback: "success_jsonpCallback",
            success: function (data) {
                if (data.static == "1") {
                    $.ajax({
                        type: 'post',
                        data: {
                            game_name: game_name,
                            username: username,
                            username_check: username_check,
                            type: type,
                            pay: pay,
                            dosubmit: dosubmit,
                            number: number,
                            trade_description: trade_description,
                            trade_name: trade_name
                        },
                        url: '/index.php?m=pay&c=deposit&a=indent',
                        dataType: 'json',
                        success: function (data) {
                            if (data.static == 1) {
                                $("#pay_form").submit();
                            } else {
                                alert(data.msg);
                            }
                        }
                    });
                } else {
                    alert(data.msg);
                }
            },
            error: function () {
                alert('fail');
            }
        });

    });
    $(".mask,.close").click(function () {
        $(".mask,.v-dialog").hide();
        return false;
    });
    //表单验证
    comfirmCheck($("input[name='username']"), $("input[name='username_check']"));


    //支付记录
    $("#v-tab").find("li").eq(1).click(function () {
        $.ajax({
            type: 'post',
            data: {},
            url: '/index.php?m=pay&c=deposit&a=pay_select',
            dataType: 'json',
            success: function (data) {
               zfAjax(data);
               loadMore($(".mod-czjl"));
            }
        });


    });

    $("#cz-submit").on("click", function () {
        var dosubmit = 1;
        var _parent=$(this).parents(".v-content-bd");
        var pay_time = _parent.find(".date-time").attr("data-kind");
        var pay_status = _parent.find(".order-status").attr("data-kind");
        var number = _parent.find("input").val();
        $.ajax({
            type: 'post',
            data: {dosubmit: dosubmit, pay_time: pay_time, pay_status: pay_status, number: number},
            url: '/index.php?m=pay&c=deposit&a=pay_select',
            dataType: 'json',
            success: function (data) {
                zfAjax(data);
                loadMore($(".mod-czjl"));
            }
        });
    });

    //消费记录
     $("#v-tab").find("li").eq(2).click(function () {
          $.post("/index.php?m=pay&c=deposit&a=get_game_username",{},function(result){
            if(result.static==1){
                var _msg=result.msg;
                $.ajax({
                type: 'post',
                data: {msg:_msg},
                url: 'http://admin.93636.com/admin.php/Api/Appapi/pay.html?jsoncallback=?',
                dataType : "jsonp",
                jsonp: "callback",
                jsonpCallback:"success_jsonpCallback",
                success: function (data) {
                    xfAjax(data);
                    loadMore($(".mod-xfjl"));
                },
                error: function () {
                alert('fail');
            }
        });
            }
         },"json");
    });

      $("#xf-submit").on("click", function () {
        var dosubmit = 1;
        var _parent=$(this).parents(".v-content-bd");
        var pay_time = _parent.find(".date-time").attr("data-kind");
        var pay_status = _parent.find(".order-status").attr("data-kind");
        var number = _parent.find("input").val();
         $.post("/index.php?m=pay&c=deposit&a=get_game_username",{},function(result){
            if(result.static==1){
                var _msg=result.msg;
                $.ajax({
                type: 'post',
                data: {msg:_msg,dosubmit: dosubmit, pay_time: pay_time, pay_status: pay_status, number: number},
                url: 'http://admin.93636.com/admin.php/Api/Appapi/pay.html?jsoncallback=?',
                dataType : "jsonp",
                jsonp: "callback",
                jsonpCallback:"success_jsonpCallback",
                success: function (data) {
                    xfAjax(data);
                    loadMore($(".mod-xfjl"));
                },
                error: function () {
                alert('fail');
            }
             });
            }
         },"json");
    });

})(window);

function tab(t_nav, container) {
    $(container).children().hide();
    $(container).children(".cur").show();
    $(t_nav).find("li").click(function () {
        $(this).addClass("active").siblings().removeClass("active");
        $(container).children().eq(parseInt($(this).index())).addClass("cur").fadeIn(300).siblings().removeClass("cur").hide();
        return false;
    })
}
//表单验证
function comfirmCheck(s1, s2) {
    var _val2;
    s2.bind('input propertychange', function () {
        _val2 = $(this).val();
        if (_val2 == "") {
            $(".confirm-account-box .tip-txt").removeClass().addClass("tip-txt").html("<i>*</i>在填写充值用户名时请仔细填写并确认，以免充值错误");
        } else if (s1.val() != _val2 && _val2 != "") {
            $(".confirm-account-box .tip-txt").removeClass("yes").addClass("err").html("<i></i>账号不一致");
        } else if (s1.val() == _val2 && _val2 != "") {
            $(".confirm-account-box .tip-txt").removeClass("err").addClass("yes").html("<i></i>");
        }
    });


}
function zfAjax(_data) {
    var _html = '<li class="th clearfix">' +
        '<span class="td1">时间</span>' +
        '<span class="td2">订单号</span>' +
        '<span class="td3">充值方式</span>' +
        '<span class="td4">米币数量</span>' +
        '<span class="td5">实付金额</span>' +
        '<span class="td6">订单状态</span>' +
        '</li>';
    $.each(_data, function (i, v) {
        var date_time = new Date(parseInt(v.create_time) * 1000).toLocaleString().match("\\d{4}[^\\d]\\d{1,2}[^\\d]\\d{1,2}").toString().replace(/(\d{4})[^\\d](\d{1,2})[^\\d](\d{1,2})/,"$1-$2-$3");
        var _type = v.type;
        if(_type==1){
            _type="支付宝";
        }else if(_type==2){
            _type="银联";
        }else{
            _type="微信";
        }
        var _status = v.pay_status == 1 ? "已成交" : "未成交";
        _html += '<li class="clearfix">' +
            '<span class="td1">' + date_time + '</span>' +
            '<span class="td2">' + v.number + '</span>' +
            '<span class="td3">' + _type + '</span>' +
            '<span class="td4 ">' + v.mb + '</span>' +
            '<span class="td5">' + v.pay + '</span>' +
            '<span class="td6">' + _status + '</span>' +
            '</li>'
    });
    $("#czjl-ul").empty().append(_html).find("li").each(function () {
        if (!($(this).hasClass("th")) && $(this).find(".td6").text() == "已成交") {
            $(this).find(".td6").addClass("suc");
        } else {
            $(this).find(".td6").addClass("err");
        }
    })
}
function xfAjax(_data) {
    var _html = '<li class="th clearfix">' +
        '<span class="td1">时间</span>' +
        '<span class="td2">订单号</span>' +
        '<span class="td3">消费类别</span>' +
        '<span class="td4">商品名称</span>' +
        '<span class="td5">米币数量</span>' +
        '<span class="td6">消费状态</span>' +
        '</li>';
    $.each(_data, function (i, v) {
        var date_time = new Date(parseInt(v.create_time) * 1000).toLocaleString().match("\\d{4}[^\\d]\\d{1,2}[^\\d]\\d{1,2}").toString().replace(/(\d{4})[^\\d](\d{1,2})[^\\d](\d{1,2})/,"$1-$2-$3");
        var _status = v.status;
        if(_status==1){
            _status="待处理";
        }else if(_status==2){
            _status="已成功";
        }else{
            _status="失败";
        }
        _html += '<li class="clearfix">' +
            '<span class="td1">' + date_time + '</span>' +
            '<span class="td2">' + v.order_id + '</span>' +
            '<span class="td3">游戏充值</span>' +
            '<span class="td4 ">' + v.app_id + '</span>' +
            '<span class="td5">' + v.ptb_cnt + '</span>' +
            '<span class="td6">' + _status + '</span>' +
            '</li>'
    });
    $("#xfjl-ul").empty().append(_html).find("li").each(function () {
        if (!($(this).hasClass("th")) && $(this).find(".td6").text() == "已成功") {
            $(this).find(".td6").addClass("suc");
        } else if (!($(this).hasClass("th")) && $(this).find(".td6").text() == "失败"){
            $(this).find(".td6").addClass("err");
        }else{
             $(this).find(".td6").addClass("wait");
        }
    })
}
//点击加载更多
function loadMore(_obj){
     _obj.find(".load-more").hide()
     if (_obj.find(".table-list .m-table").height() >= 452) {
        _obj.find(".table-list .m-table").css({"max-height":"452px",
            "height":"452px"});
        _obj.find(".load-more").show().on("click", function () {
        _obj.find(".table-list .m-table").css({"max-height":"903px","height":"903px"});
          $(this).hide();
     })
  }
}