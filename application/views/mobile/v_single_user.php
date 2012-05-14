<div data-role="page" id="page_user" class="type-interior"> 
	<div data-role="header" data-position="fixed"  data-theme="f">
		<h1><?php echo $feed['display_name'];?></h1>
		<a data-rel="back" data-icon="arrow-l">返回</a>
	</div><!-- /header --> 
 
	<div data-role="content"> 
		<div class="content-primary">	
			<div class="ui-grid-a">
				<div class="ui-block-a" >
					<img name="name" height="80" src="<?php echo $feed['user_avatar'];?>">
				</div>
				<div class="ui-block-b" >
					<h3><?php echo $feed['display_name']?></h3>
				</div>
			</div>
			<hr/>
			<div>
				<ul data-role="listview" data-inset="true">
					<li>
						<lable for="private_email">上次活动时间：<?php echo date("Y-m-d G:i",$feed['last_activity']+8*60*60);?></lable>
					</li>
					<li>
						<lable for="private_email">好友数：<?php echo $feed['friend_count'];?></lable>
					</li>
					<li>
						<lable for="private_email">微博数：<?php echo $feed['weibo_count'];?></lable>
					</li>
				</ul>
			</div>
			<hr/>
			<div>
				<ul data-role="listview" data-inset="true">
					<li style="text-align:center;">
						<a href="<?=base_url('');?>" style="text-align:center;">加为好友</a>
					</li>
				</ul>
			</div>
		</div><!--/content-primary -->		
	</div>
</div><!-- /page --> 