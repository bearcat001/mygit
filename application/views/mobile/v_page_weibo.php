<script type="text/javascript">
	 $(document).ready(function() {
	 	$("#get_old_public_weibo").bind("click",function () { 
	 		$.mobile.pageLoading();
	 		$.post("<?php echo base_url("mobile/c_ajax/");?>", 
		 		 	{
		 		 	action: "get_public_weibo",
		 		 	token: "<?php echo $user_data['token'];?>",
		 		 	filter: "old",
		 		 	last_feed_id: $("#public_list_view li").eq(-2).attr("id").substr(12)
		 		 	},
					function(data){
						if(data.substr(0,2)=="no"){}
						else{
							$("#public_list_view li").eq(-1).before(data);
			 		 		$('#public_list_view').listview('refresh');		
						}
				});
	 		$.mobile.pageLoading(true); 
	 	});
	});
</script>
<div data-role="page" id="page_weibo" class="type-interior" data-theme="f"> 
	<div data-role="header" data-position="fixed" data-theme="f">
		<h1><?php echo $user_data['display_name'];?></h1>
		<a href="<?php echo base_url("mobile/c_page/post_weibo");?>" rel="external">发状态</a>
		<a href=<?php echo base_url();?> rel="external" data-role="button" data-icon="refresh" data-iconpos="notext"></a>
		<div data-role="navbar">
			<ul>
				<li><a href="<?=base_url('mobile/c_page/page_private')?>" data-transition="fade" data-theme="b">蜗居</a></li>
				<li><a href="<?=base_url('mobile/c_page/page_near')?>" data-transition="fade" data-theme="b">周边</a></li>
				<li><a href="<?=base_url('mobile/c_page')?>" class="ui-btn-active ui-state-persist" data-transition="fade" data-theme="b">蜗语</a></li>
				<li><a href="<?=base_url('mobile/c_page/page_friend')?>" data-transition="fade" data-theme="b">蜗广场</a></li>
			</ul>
		</div><!-- /navbar -->
	</div><!-- /header --> 
 
	<div data-role="content"> 
		<div class="content-primary">
		<ul data-role="listview" id="public_list_view" data-theme="c" data-dividertheme="c">
			<?php if(isset($user_data['feeds'])):?>
			<?php foreach($user_data['feeds'] as $feed):?>
			<li data-role="list-divider" data-theme="c" id="<?='public_feed_'.$feed['feed_id'];?>">
				<div class="ui-grid-a">
					<div class="ui-block-a" style="width:20%;">
						<img height="36" src=<?php echo $feed['user_avatar'];?>>
					</div>
					<div class="ui-block-b" style="width:80%;">
						<a href=<?php echo base_url("mobile/c_page/single_user/".str_replace(array('+','/','='),array('-','_',''),base64_encode(serialize($feed))));?> style="text-decoration: none;"><?php echo $feed['display_name'];?></a>
						<div style="text-align:right;"><p><?php echo timespan($feed['create_time'],now()).'前';?></p></div>
					</div>
					<div class="ui-block-a" style="width:20%;">
					</div>
					<div class="ui-block-b" style="width:80%;">
						<a href=<?php echo base_url("mobile/c_page/single_weibo/".str_replace(array('+','/','='),array('-','_',''),base64_encode(serialize($feed))));?> style="text-decoration: none;"><?php echo $feed['feed_content'];?></a>
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
			<li data-role="list-divider" data-theme="c"><a id="get_old_public_weibo">查看更早</a></li>
		</ul>
		</div>
		<div class="content-secondary">
			<div data-role="collapsible" data-collapsed="true" data-theme="b" data-content-theme="d">
					<h3>选项</h3>
					<ul data-role="listview" data-theme="c" data-dividertheme="b">
						
						<li><a href="../../docs/about/intro.html">Intro to jQuery Mobile</a></li>
						<li><a href="../../docs/about/getting-started.html">Quick start guide</a></li>	
						<li><a href="../../docs/about/features.html">Features</a></li>
						<li><a href="../../docs/about/accessibility.html">Accessibility</a></li>
						<li data-theme="e"><a href="../../docs/about/platforms.html">Supported platforms</a></li>
					</ul>
			</div>
		</div>		
	</div>
</div><!-- /page --> 
