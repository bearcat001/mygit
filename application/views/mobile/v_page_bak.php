<script type="text/javascript">
	 $(document).ready(function() {
		
	 	$("#weibo_submit").bind("click",function () { 
		 	if($('#weibo_content').val()!=""){
		 		$.mobile.pageLoading();
		 		$.post("<?php echo base_url("mobile/c_wlinke/wlinke_ajax/");?>", 
			 		 	{
			 		 	action: "post_weibo",
			 		 	token: "<?php echo $user_data['token'];?>",
			 		 	weibo_content: $('#weibo_content').val()
			 		 	},
						function(data){
			 		 		if(data.substr(0,2)=="no"){}
			 		 		else{
								$.mobile.changePage("#post_success_dialog");
								$('#weibo_content').html("");
							}		
					});
 		 		$.mobile.pageLoading(true); 
		 	}
	 	}); 
	 	$("#get_old_public_weibo").bind("click",function () { 
	 		$.mobile.pageLoading();
	 		$.post("<?php echo base_url("mobile/c_wlinke/wlinke_ajax/");?>", 
		 		 	{
		 		 	action: "get_public_weibo",
		 		 	token: "<?php echo $user_data['token'];?>",
		 		 	filter: "old",
		 		 	last_feed_id: $("#public_list_view li:last-child").attr("id").substr(12)
		 		 	},
					function(data){
						if(data.substr(0,2)=="no"){}
						else{
			 		 		$("#public_list_view").append(data);
			 		 		$('#public_list_view').listview('refresh');		
						}
				});
	 		$.mobile.pageLoading(true); 
	 	});
	 	$("#get_new_public_weibo").bind("click",function () { 
	 		$.mobile.pageLoading();
	 		$.post("<?php echo base_url("mobile/c_wlinke/wlinke_ajax/");?>", 
		 		 	{
		 		 	action: "get_public_weibo",
		 		 	token: "<?php echo $user_data['token'];?>",
		 		 	filter: "new",
		 		 	last_feed_id: $("#public_list_view li:nth-child(3)").attr("id").substr(12)
		 		 	},
					function(data){
						if(data.substr(0,2)=="no"){}
						else{
			 		 		$("#top_of_new_public").after(data);
			 		 		$('#public_list_view').listview('refresh');		
						}
				});
	 		$.mobile.pageLoading(true); 
	 	}); 
	});
</script>
<div data-role="page" id="page_weibo"> 
	<div data-role="header" data-position="fixed">
		<h1><?php echo $user_data['display_name'];?></h1>
		<a href="#post_weibo" rel="external">发状态</a>
		<a href=<?php echo base_url();?> rel="external" data-role="button" data-icon="refresh" data-iconpos="notext"></a>
		<div data-role="navbar">
				<ul>
					<li><a href="#page_private" data-transition="fade">个人</a></li>
					<li><a href="#page_weibo" class="ui-btn-active ui-state-persist" data-transition="fade">微博</a></li>
					<li><a href="#page_friend" data-transition="fade">好友</a></li>
					<li><a href="#page_group" data-transition="fade">群组</a></li>
				</ul>
			</div><!-- /navbar -->
	</div><!-- /header --> 
 
	<div data-role="content"> 
		<ul data-role="listview" id="public_list_view" data-theme="c">
			<li>
				<img alt="个人头像" src=<?php echo $user_data['user_avatar'];?> />
				<h1><?=$user_data['display_name'];?></h1>
			</li>
			<li data-role="list-divider" data-theme="c"  id="top_of_new_public"><button id="get_new_public_weibo">查看最新</button></li>
			<?php if(isset($user_data['feeds'])):?>
			<?php foreach($user_data['feeds'] as $feed):?>
			<li data-role="list-divider" data-theme="c" id="<?='public_feed_'.$feed['feed_id'];?>">
				<div class="ui-grid-a">
					<div class="ui-block-a" style="width:20%;">
						<img height="36" src=<?php echo $feed['user_avatar'];?>>
					</div>
					<div class="ui-block-b" style="width:80%;">
						<?php echo $feed['display_name'];?>
						<div style="text-align:right;"><p><?php echo timespan($feed['create_time'],now()).'前';?></p></div>
					</div>
					<div class="ui-block-a" style="width:20%;">
					</div>
					<div class="ui-block-b" style="width:80%;">
						<?php echo $feed['feed_content'];?>
					</div>
					<div class="ui-block-a" style="width:20%;">
					</div>
					<div class="ui-block-b" style="width:80%;text-align:right;">
						<br/>
						<div><p><?php echo "转发：  ".$feed['transpond_count'];?> <?php echo "评论 ：".$feed['comment_count'];?></p></div>
					</div>
				</div>
			</li>
			<?php endforeach;?>
			<?php endif;?>
		</ul>
		<ul data-role="listview"><li data-role="list-divider" data-theme="c" ><button id="get_old_public_weibo">查看更早</button></li></ul>
	</div>
</div><!-- /page --> 

<div data-role="page" id="page_friend"> 
	<div data-role="header" data-position="fixed">
		<h1><?php echo $user_data['display_name'];?></h1>
		<a href="#post_weibo" rel="external">发状态</a>
		<a href=<?php echo base_url();?> rel="external" data-role="button" data-icon="refresh" data-iconpos="notext"></a>
		<div data-id="foo1" data-position="fixed">
			<div data-role="navbar">
				<ul>
					<li><a href="#page_private" data-transition="fade">个人</a></li>
					<li><a href="#page_weibo" data-transition="fade">微博</a></li>
					<li><a href="#page_friend" class="ui-btn-active ui-state-persist" data-transition="fade">好友</a></li>
					<li><a href="#page_group" data-transition="fade">群组</a></li>
				</ul>
			</div><!-- /navbar -->
		</div><!-- /footer -->   
	</div><!-- /header --> 
 
	<div data-role="content"> 
		<div class="content-primary">	
			<ul data-role="listview" data-filter="true">
				<?php if(isset($user_data['all_user_datas'])):?>
				<?php foreach($user_data['all_user_datas'] as $value):?>
				<li>
					<a href="#">
						<img alt="个人头像" src=<?php echo $value['user_avatar'];?> />
						<h2><?=$value['display_name']?></h2>
						<p><?php 
						if(isset($value['latest_update']))
							echo $value['latest_update'];
						?></p>
					</a>
				</li>
				<?php endforeach;?>
				<?php endif;?>
			</ul>
		</div><!--/content-primary -->		
	</div>
</div><!-- /page --> 

<div data-role="page" id="page_group"> 
	<div data-role="header" data-position="fixed">
		<h1><?php echo $user_data['display_name'];?></h1>
		<a href="#post_weibo" rel="external">发状态</a>
		<a href=<?php echo base_url();?> rel="external" data-role="button" data-icon="refresh" data-iconpos="notext"></a>
		<div data-id="foo1" data-position="fixed">
			<div data-role="navbar">
				<ul>
					<li><a href="#page_private" data-transition="fade">个人</a></li>
					<li><a href="#page_weibo" data-transition="fade">微博</a></li>
					<li><a href="#page_friend" data-transition="fade">好友</a></li>
					<li><a href="#page_group"  class="ui-btn-active ui-state-persist" data-transition="fade">群组</a></li>
				</ul>
			</div><!-- /navbar -->
		</div><!-- /footer -->   
	</div><!-- /header --> 
 
	<div data-role="content"> 
		<div class="content-primary">	
			<ul data-role="listview" data-filter="true">
				<?php if(isset($user_data['group_data'])):?>
				<?php foreach($user_data['group_data'] as $value):?>
				<li>
					<a href="#">
						<h2><?=$value['group_name']?></h2>
						<p><?=$value['group_destription']?></p>
					</a>
				</li>
				<?php endforeach;?>
				<?php endif;?>
			</ul>
		</div><!--/content-primary -->		
	</div>
</div><!-- /page --> 

<div data-role="page" id="page_private"> 
	<div data-role="header" data-position="fixed">
		<h1><?php echo $user_data['display_name'];?></h1>
		<a href="#post_weibo" rel="external">发状态</a>
		<a href=<?php echo base_url();?> rel="external" data-role="button" data-icon="refresh" data-iconpos="notext"></a>
		<div data-id="foo1" data-position="fixed">
			<div data-role="navbar">
				<ul>
					<li><a href="#page_private"  class="ui-btn-active ui-state-persist" data-transition="fade">个人</a></li>
					<li><a href="#page_weibo" data-transition="fade">微博</a></li>
					<li><a href="#page_friend" data-transition="fade">好友</a></li>
					<li><a href="#page_group"  data-transition="fade">群组</a></li>
				</ul>
			</div><!-- /navbar -->
		</div><!-- /footer -->   
	</div><!-- /header --> 
 
	<div data-role="content"> 
		<div class="content-primary">	
			<div class="ui-grid-a">
				<div class="ui-block-a" >
					<img name="name" height="80" src="<?php echo $user_data['user_avatar'];?>">
				</div>
				<div class="ui-block-b" >
					<h3><?php echo $user_data['display_name']?></h3>
				</div>
			</div>
			<hr/>
			<div>
				<ul data-role="listview" data-inset="true">
					<li>
						<lable for="private_email">登录名：<?php echo $user_data['email'];?></lable>
					</li>
					<li>
						<lable for="private_email">上次活动时间：<?php echo date("Y-m-d G:i",$user_data['last_activity']+8*60*60);?></lable>
					</li>
				</ul>
			</div>
			
		</div><!--/content-primary -->		
	</div>
</div><!-- /page --> 


<div data-role="page" id='post_weibo'> 
 
	<div data-role="header" data-position="fixed" >
		<h1>发状态</h1>
		<a data-rel="back" data-icon="arrow-l">返回</a>
	</div><!-- /header --> 
 	
	<div data-role="content"> 
		<form>
			<textarea rows="15" name="weibo_content" id="weibo_content" placeholder="请输入微博内容" style="width:99%; "></textarea>
		</form>
		<button id="weibo_submit">发布</button>
	</div>
</div><!-- /page --> 

<div data-role="page" id="post_success_dialog" data-theme="a"> 
	<div data-role="header">
		<h1>发表成功</h1>
	</div>

	<div data-role="content">
	    <h1>发表成功</h1>
		<h3>是否继续发微博？</h3>
		<a data-role="button" data-rel="back">是</a>  
		<a href="#page_weibo" data-role="button">否</a>       
	</div>
</div><!-- /page --> 
