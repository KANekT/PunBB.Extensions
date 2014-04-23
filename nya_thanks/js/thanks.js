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
                    var mess = $('<span class="sig-line"><!-- --></span><div class=\"thanks_sig\"><span class=\"thanks_error\">'+data.error+'</span></div>');
                    $("#p"+data.pid).next().append(mess);
                    return;
                }

                if (data.message != undefined)
                {
                    var mess = $('<span class="sig-line"><!-- --></span><div class=\"thanks_sig\"><span class=\"thanks_mess\">'+data.message+'</span></div>');
                    $("#p"+data.pid).next().append(mess);
                    var usid = parseInt($("#thp"+data.pid).text()) + 1;
                    $(".thu"+data.uid).text(usid);
                    $(".thanks_info_link.thl"+data.pid).hide();
                    return;
                }
                return;
            }
        });
        return false;
    });
});
