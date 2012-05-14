<div data-role="page" id="page_near" data-theme="f" class="type-interior"> 
	<div data-role="header" data-position="fixed" data-theme="f">
		<h1><?php echo $user_data['display_name'];?></h1>
		<a href="<?php echo base_url("mobile/c_page/post_weibo");?>" rel="external">发状态</a>
		<a href=<?php echo base_url("mobile/c_page/");?> rel="external" data-role="button" data-icon="refresh" data-iconpos="notext"></a>
		<div data-id="foo1" data-position="fixed">
			<div data-role="navbar">
				<ul>
					<li><a href="<?=base_url('mobile/c_page/page_private')?>"  data-transition="fade" data-theme="b">蜗居</a></li>
					<li><a href="<?=base_url('mobile/c_page/page_near')?>" class="ui-btn-active ui-state-persist" data-theme="b" data-transition="fade">周边</a></li>
					<li><a href="<?=base_url('mobile/c_page')?>" data-transition="fade" data-theme="b">蜗语</a></li>
					<li><a href="<?=base_url('mobile/c_page/page_friend')?>"  data-transition="fade" data-theme="b">蜗广场</a></li>
					
				</ul>
			</div><!-- /navbar -->
		</div><!-- /footer -->   
	</div><!-- /header --> 
 
	<div data-role="content"> 
		<div class="content-primary">	
			<ul data-role="listview" data-filter="true" data-filter-placeholder="查看周边">
				<?php if(isset($user_data['near_user_data'])):?>
				<?php foreach($user_data['near_user_data'] as $value):?>
				<li id="<?='user_'.$value['user_id'];?>">
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