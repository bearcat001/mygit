<div data-role="page" id="page_private" class="type-interior"> 
	<div data-role="header" data-position="fixed" data-theme="f">
		<h1><?php echo $user_data['display_name'];?></h1>
		<a href="<?php echo base_url("mobile/c_page/post_weibo");?>" rel="external">发状态</a>
		<a href=<?php echo base_url();?> rel="external" data-role="button" data-icon="refresh" data-iconpos="notext"></a>
		<div data-id="foo1" data-position="fixed">
			<div data-role="navbar">
				<ul>
					<li><a href="<?=base_url('mobile/c_page/page_private')?>"data-theme="b" class="ui-btn-active ui-state-persist" data-transition="fade">蜗居</a></li>
					<li><a href="<?=base_url('mobile/c_page/page_near')?>" data-theme="b" data-transition="fade">周边</a></li>
					<li><a href="<?=base_url('mobile/c_page')?>" data-theme="b" data-transition="fade">蜗语</a></li>
					<li><a href="<?=base_url('mobile/c_page/page_place')?>" data-theme="b" data-transition="fade">蜗广场</a></li>
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
					<li>
						<lable for="private_email">好友数：<?php echo $user_data['friend_count'];?></lable>
					</li>
					<li>
						<lable for="private_email">微博数：<?php echo $user_data['weibo_count'];?></lable>
					</li>
				</ul>
			</div>
			<hr/>
			<div>
				<ul data-role="listview" data-inset="true">
					<li style="text-align:center;">
						<a href="<?=base_url('mobile/c_login/login_out');?>" style="text-align:center;">注销登陆</a>
					</li>
					
				</ul>
			</div>
			
		</div><!--/content-primary -->		
	</div>
</div><!-- /page --> 