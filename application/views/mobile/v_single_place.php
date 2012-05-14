<div data-role="page" id='single_place' class="type-interior"> 
 
	<div data-role="header" data-position="fixed" data-theme="f" >
		<h1><?php echo $place['place_name'];?></h1>
		<a data-rel="back" data-icon="arrow-l">返回</a>
	</div><!-- /header --> 
 	
	<div data-role="content"> 
		<div class="content-primary">	
			<div class="ui-grid-a">
				<div class="ui-block-a" >
					
				</div>
				<div class="ui-block-b" >
					<h3><?php echo $place['place_name']?></h3>
				</div>
			</div>
			<hr/>
			<div>
				<ul data-role="listview" data-inset="true">
					<li>
						<lable>群组介绍：<?php echo $place['place_destription'];?></lable>
					</li>
					<li>
						<lable>成员数：<?php echo $place['member_count'];?></lable>
					</li>
					<li>
						<lable>群组类别：<?php echo $place['place_category'];?></lable>
					</li>
					<li>
						<lable>创建时间：<?php echo date("Y-m-d G:i",$place['create_time']+8*60*60);?></lable>
					</li>
				</ul>
				<hr/>
				<ul data-role="listview" data-filter="true" data-filter-placeholder="当前在此聚点的蜗友">
					<?php if(isset($place['place_recent_member'])):?>
					<?php foreach($place['place_recent_member'] as $value):?>
					<li id="<?='user_'.$value['user_id'];?>">
						<a href=<?php echo base_url("mobile/c_page/single_user/".str_replace(array('+','/','='),array('-','_',''),base64_encode(serialize($value))));?> style="text-decoration: none;">
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
			</div>
		</div><!--/content-primary -->		
	</div>
</div><!-- /page --> 