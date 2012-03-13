$(document).ready(function(){
    $(".thanks_info_link").click(function(e){
        e.stopPropagation();

        $.ajax({
            url: $(this).attr("href"),
            type: "GET",
            cache: false,
            data: {},
            dataType: "json",
            timeout: 3000,

            success:function(data){
                if (data.error != undefined)
                {
                    habr.updateTips(data.error);
                    $("#habr_form_reason").addClass("ui-state-error");
                    return;
                }

                if (data.message != undefined)
                {
                    var stick = $('<span class="sig-line"><!-- --></span><div class=\"thanks_sig\"><span class=\"thanks_sig_head\">'+data.message+'</span></div>');
                    $("#p"+data.pid+"").next().append(stick);
                    return;
                }
                return;
            },

            error: function(){
                alert('error!');
                window.location = habr.url;
            }

        });
        $(".thanks_info_link").hide();
        return false;
    });
});
