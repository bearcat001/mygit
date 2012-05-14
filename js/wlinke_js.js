var wlinke_ajax_url="http://localhost/wlinke/c_ajax";
$(document).ready(
	function() {
		 $("#login_submitttt").bind(
				 "click",
				 function () { 
					 if($('#login_email').val()!=""){
				 		$.post(
				 			wlinke_ajax_url, 
				 		 	{
				 		 	action: "userLogin",
				 		 	login_email: $('#login_email').val(),
				 		 	login_password: $('#login_password').val()
				 		 	},
							function(data){
				 		 		if(data=="yes")
				 		 			alert("yes");
				 		 		else
				 		 			alert("账号或密码错误！");
				 		 	}
				 		 );
				 	}
			}
		);
		 $("#get_old_feed_submit").bind(
				 "click",
				 function () { 
					var btn = $("#get_old_feed_submit");
		 		 	btn.button('loading');
			 		 $.post(
			 		 	wlinke_ajax_url, 
			 		 	{
			 		 	action: "get_public_weibo",
			 		 	filter: "old",
			 		 	last_feed_id: $("#get_old_feed").prev().html()
			 		 	},
						function(data){
			 		 		btn.button('reset');
			 		 		if(data!="no")
			 		 			$("#get_old_feed").before(data);
			 		 		else
			 		 			alert("没有更多内容啦！");
			 		 	}
			 		 );
			 		}
		 );
	}
);
