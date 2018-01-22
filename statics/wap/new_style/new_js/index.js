$(document).ready(function() {
    $(".navlist").click(function() {
        if ($('body').css('overflow') == 'hidden') {
            $('body').css('overflow', 'auto');
        }
        else {
            $('body').css('overflow', 'hidden');
        }
        $(".sidebar").toggle();
        if ($(this).hasClass("cur")) {
            $(this).removeClass("cur");
        } else {
            $(this).addClass("cur");
        }
    });
    $(".side-kong").click(function() {
        if ($('body').css('overflow') == 'hidden') {
            $('body').css('overflow', 'auto');
        }
        else {
            $('body').css('overflow', 'hidden');
        }
        $(".sidebar").hide();
    });

});