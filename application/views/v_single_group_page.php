<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse"
				data-target=".nav-collapse"> <span class="icon-bar"></span> <span
				class="icon-bar"></span> <span class="icon-bar"></span>
			</a> <a class="brand" href="<?php echo base_url("c_wlinke"); ?>">蜗临客</a>
			<div class="nav-collapse">
				<ul class="nav">
					<li class="active"><a href="<?php echo base_url(); ?>">首页</a></li>
				</ul>
				<ul class="nav pull-right">
					<li class="dropdown"><a href="#" class="dropdown-toggle"
						data-toggle="dropdown">账号<b class="caret"></b></a>
						<ul class="dropdown-menu">
							<!-- <li><a href="#">设置</a></li> -->
							<!-- <li class="divider"></li> -->
							<li><a href="<?php echo base_url('c_login/login_out');?>">登出</a></li>
						</ul></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="span2">
			<div class="row">
				<div class="span2">
					<a href="#upload_avatar" data-toggle="modal" rel="tooltip"
						title="修改头像"><img name="name" width="70"
						src="<?php echo $user_data['user_avatar'];?>"></a>
					<div class="modal fade" id="upload_avatar">
						<div class="modal-header">
							<a class="close" data-dismiss="modal">×</a>
							<h3>上传头像</h3>
							<form enctype="multipart/form-data" action="<?php echo base_url("c_page_weibo/upload_avatar") ?>" method="post"> 
								<input type="file" name="userfile" size="20" />
								<input type="hidden" name="user_id" value="<?php echo $user_data['user_id']?>" />
								<p>上传文件必须小于1MB</p>
								<p id="preview_look">文件预览</p>
								<input type="submit" value="上传" />
							</form>
						</div>
					</div>
					<h2><?php echo $user_data['display_name'];?></h2>
				</div>
				<div class="span2">
					<h4>
						状态数：<span class="badge"><?php echo $user_data['weibo_count']?></span>
					</h4>
					<h4>
						好友数：<span class="badge"><?php echo $user_data['friend_count']?></span>
					</h4>
				</div>
				<div class="span2">
					<hr size="1" style="border: 1px solid #bbb;">
				</div>
			</div>
			<div>
				<ul class="nav nav-pills nav-stacked" >
					<li class="nav-header">
					   已加入群组
					</li>
					<?php 
						foreach($user_data['group_datas'] as $key=>$value){
							if($value['group_id']==$user_data['this_group_data']['group_id']){
								echo '<li class="active"><a href="'.base_url("c_single_group_page/group/".$value['group_id'])
								.'">'.$value['group_name'].'</a></li>';
							}else{
								echo '<li><a href="'.base_url("c_single_group_page/group/".$value['group_id'])
								.'">'.$value['group_name'].'</a></li>';
							}
						}
					?>
				</ul>
			</div>
		</div>

		<div class="span7">
			<h2>有什么事情想告诉大家？</h2>
			<form action="<?php echo base_url('c_page_weibo/post_weibo');?>"
				method="post" style="text-align: right;" class="form">
				<textarea style="width: 98%" class="input-xlarge" id="weibo_content"
					name="weibo_content" rows="3"></textarea>
				<button type="submit" class="btn">发布</button>
			</form>
			<h3>群组成员 (<?php echo $user_data['this_group_data']['member_count']?>)</h3>
			<?php foreach($user_data['this_group_data']['group_members'] as $key=>$value):?>
				<div class="span1"  style="text-align:center">
					<img width="100%" src="<?php echo $value['user_avatar'];?>" /> 
					<a><?php echo $value['display_name'];?></a>
				</div>
			<?php endforeach;?>
		</div>
		<div class="span3">
			
		</div>
	</div>
	<hr size="1" style="border: 1px solid #bbb;">
	<footer>
		<p>&copy; Wlinke 2012</p>
	</footer>
</div>
