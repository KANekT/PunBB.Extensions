/**
 * vote_post 
 * 
 * @author hcs
 * @copyright (C) 2011 hcs vote_post extension for PunBB
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package vote_post
 */

var vote_post = {
	response : {},
	url : '',

	updateTips: function(t) {
		$(".validateTips").html(t).addClass("ui-state-highlight");
		setTimeout(function() {
				$(".validateTips").removeClass("ui-state-highlight", 1500);
			},500
		);
	},

    send_data: function() {

		$.ajax({
			url: vote_post.url,
			type: "POST",
			cache: false,
			data: {csrf_token : vote_post.response.csrf_token, form_sent : 1, req_message : $("#vote_form_reason").val()},
			dataType: "json",
			timeout: 3000000,
			
			success:function(data){
				if (data.error != undefined)
				{
					vote_post.updateTips(data.error);
					$("#vote_form_reason").addClass("ui-state-error");
					return;
				}
				
				if (data.message != undefined)
				{
					$("#vote_form").dialog("close");
					alert(data.message);
					return;
				}
				
				if (data.destination_url != undefined)
				{
					window.location = data.destination_url;
					return;
				}
				window.location.reload(true);
				return;
			},
			
			error: function(){
				alert('error!');
				window.location = vote_post.url;
			}
			
		});
	},
	show_form: function(data) {
		$("#vote_form_description").html(data.description);
		$("#vote_form").dialog({
			height: "auto",
			width: 350,
			title : data.title,
			show: "fade",
			hide: "fade",
			resizable: false,
			modal: true,
			buttons: [{
				text: data.submit,
				click: function() {
					vote_post.send_data();
				}
			}],
			close: function() {
				$(".validateTips").empty();
				$("#vote_form_reason").val("").removeClass("ui-state-error");
			}				
		});			
	},
	init:function(){
		$(document).ready(function(){
			$("#brd-wrap").append('<div id="vote_form" style="display:none;pading:0 0;"><p id="vote_form_description"></p><p class="validateTips"></p><textarea style="width:97%;height:118px" id="vote_form_reason" /></div><div id="vote_error" style="display:none;pading:0 0;"><p></p></div>');

			$(".vote_info_link").click(function(){
				vote_post.url = $(this).attr("href");
				$.ajax({
					url: vote_post.url,
					type: "GET",
					cache: false,
					dataType: "json",
					timeout: 3000,
					
					success:function(data){
						if (data.code = -1 && data.message != undefined)
						{
							$("#vote_error").dialog({resizable: false});
							$("#vote_error p").html(data.message);
							return;
						}
						vote_post.response = data;
						vote_post.show_form(data);
					},
					
					error: function(){
						window.location = vote_post.url;
					}
					
				});		
				return false;
			});
		});		
	}
}
vote_post.init();