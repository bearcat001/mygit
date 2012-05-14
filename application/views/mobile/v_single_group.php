<div data-role="page" id='single_group' class="type-interior"> 
 
	<div data-role="header" data-position="fixed"  data-theme="f">
		<h1><?php echo $group['group_name'];?></h1>
		<a data-rel="back" data-icon="arrow-l">返回</a>
	</div><!-- /header --> 
 	
	<div data-role="content"> 
		<div class="content-primary">	
			<div class="ui-grid-a">
				<div class="ui-block-a" >
					
				</div>
				<div class="ui-block-b" >
					<h3><?php echo $group['group_name']?></h3>
				</div>
			</div>
			<hr/>
			<div>
				<ul data-role="listview" data-inset="true">
					<li>
						<lable>群组介绍：<?php echo $group['group_destription'];?></lable>
					</li>
					<li>
						<lable>成员数：<?php echo $group['member_count'];?></lable>
					</li>
					<li>
						<lable>创建时间：<?php echo date("Y-m-d G:i",$group['create_time']+8*60*60);?></lable>
					</li>
				</ul>
				<hr/>
			</div>
		</div><!--/content-primary -->		
	</div>
</div><!-- /page --> 