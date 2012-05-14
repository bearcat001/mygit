<div data-role="page" id="page_place" class="type-interior"> 
	<div data-role="header" data-position="fixed" data-theme="f">
		<h1><?php echo $user_data['display_name'];?></h1>
		<a href="<?php echo base_url("mobile/c_page/add_place");?>" rel="external">创建聚点</a>
		<a href=<?php echo base_url();?> rel="external" data-role="button" data-icon="refresh" data-iconpos="notext"></a>
		<div data-id="foo1" data-position="fixed">
			<div data-role="navbar">
				<ul>
					<li><a href="<?=base_url('mobile/c_page/page_private')?>" data-theme="b" data-transition="fade">蜗居</a></li>
					<li><a href="<?=base_url('mobile/c_page/page_near')?>" data-theme="b" data-transition="fade">周边</a></li>
					<li><a href="<?=base_url('mobile/c_page')?>" data-theme="b" data-transition="fade">蜗语</a></li>
					<li><a href="<?=base_url('mobile/c_page/page_place')?>" data-theme="b" class="ui-btn-active ui-state-persist" data-transition="fade">蜗广场</a></li>
				</ul>
			</div><!-- /navbar -->
		</div><!-- /footer -->   
	</div><!-- /header --> 
 
	<div data-role="content"> 
		<div class="content-primary">	
			<ul data-role="listview" data-filter="true" data-filter-placeholder="查看聚点">
				<?php if(isset($user_data['place_data'])):?>
				<?php foreach($user_data['place_data'] as $value):?>
				<li id="<?='place_'.$value['place_id'];?>">
					<a href=<?php echo base_url("mobile/c_page/single_place/".str_replace(array('+','/','='),array('-','_',''),base64_encode(serialize($value))));?> style="text-decoration: none;">
						<h2><?=$value['place_name']?></h2>
						<p><?=$value['place_destription']?></p>
					</a>
				</li>
				<?php endforeach;?>
				<?php endif;?>
			</ul>
		</div><!--/content-primary -->
		<div class="content-secondary">
			<div data-role="collapsible" data-collapsed="true" data-theme="b" data-content-theme="d">
					<h3>选项</h3>
					<ul data-role="listview" data-theme="c" data-dividertheme="b">
						<li><a href="<?=base_url('mobile/c_page/page_friend')?>">蜗友</a></li>
						<li><a href="<?=base_url('mobile/c_page/page_group')?>">蜗群</a></li>	
						<li data-theme="e"><a href="<?=base_url('mobile/c_page/page_place')?>">蜗聚点</a></li>
					</ul>
			</div>
		</div>				
	</div>
</div><!-- /page --> 