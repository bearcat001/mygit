<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse"
				data-target=".nav-collapse"> <span class="icon-bar"></span> <span
				class="icon-bar"></span> <span class="icon-bar"></span>
			</a> <a class="brand" href="<?=base_url("c_wlinke"); ?>">蜗临客</a>
			<div class="nav-collapse">
				<ul class="nav">
					<li class="active"><a href="<?=base_url("c_page_weibo"); ?>">首页</a></li>
				</ul>
				<ul class="nav pull-right">
					<li class="dropdown"><a href="#" class="dropdown-toggle"
						data-toggle="dropdown">账号<b class="caret"></b></a>
						<ul class="dropdown-menu">
							<!-- <li><a href="#">设置</a></li> -->
							<!-- <li class="divider"></li> -->
							<li><a href="<?=base_url('c_login/login_out');?>">登出</a></li>
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
							<form enctype="multipart/form-data" action="<?=base_url("c_page_weibo/upload_avatar") ?>" method="post"> 
								<input type="file" name="userfile" size="20" />
								<input type="hidden" name="user_id" value="<?=$user_data['user_id']?>" />
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
				<ul class="nav nav-pills nav-stacked">
					<li><a href="<?=base_url("c_page_weibo"); ?>"><i class="icon-home"></i>首页</a></li>
					<li class="active"><a href="<?=base_url("c_page_group"); ?>"><i
							class="icon-heart"></i>群组</a></li>
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
			<div class="tabbable">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#1" data-toggle="tab">全部群组</a></li>
					<li><a href="#2" data-toggle="tab">已入群组</a></li>
				</ul>
				<div class="tab-content">
				    <div class="tab-pane active" id="1">
				      <div class="row">
						<?php if(isset($user_data['group_datas'])):?>
							<?php foreach($user_data['group_datas'] as $key=>$value):?>
								<div class="well span3">
									<div class="row">
										<div class="span2"><a href="<?=base_url("c_single_group_page/group/".$value['group_id']);?>"> <?=$value['group_name']; ?></a></div>
										<div class="span1">
											<?php if(!$value['is_in']):?>
												<div class="span1">
													<button class="btn btn-mini" type="submit">加入</button>
												</div>
											<?php else:?>
												<div class="span1">
													<a class="btn btn-mini disabled">已加入</a>
												</div>
											<?php endif;?>
										</div>
										<div class="span3">
											<?=$value['group_destription']; ?>
										</div>
										<div class="span3">
											<br>
										</div>
										<div class="span1" style="text-align:center">
											<p>群号: <a><?=$value['group_id']; ?></a></p>
										</div>
										<div class="span1" style="text-align:center">
											<p>类型: <a><?=$value['group_category'];?></a></p>
										</div>
										<div class="span1" style="text-align:center">
											<p>成员: <a><?=$value['member_count'];?></a></p>
										</div>
									</div>
								</div>	
							<?php endforeach;?>
					    <?php else:?>
						    <div class="span2 alert alert-info">还没有群组</div>
						<?php endif;?>
					</div>
			    </div>
			    <div class="tab-pane" id="2">
			      <div class="row">
						<?php if(isset($user_data['group_datas'])):?>
							<?php foreach($user_data['group_datas'] as $key=>$value):?>
								<?php if($value['is_in']):?>
								<div class="well span3">
									<div class="row">
										<div class="span2"><a href="<?=base_url("c_single_group_page/group/".$value['group_id']);?>"> <?=$value['group_name']; ?></a></div>
										<div class="span1">
											<?php if(!$value['is_in']):?>
												<div class="span1">
													<button class="btn btn-mini" type="submit">加入</button>
												</div>
											<?php else:?>
												<div class="span1">
													<a class="btn btn-mini disabled">已加入</a>
												</div>
											<?php endif;?>
										</div>
										<div class="span3">
											<?=$value['group_destription']; ?>
										</div>
										<div class="span3">
											<br>
										</div>
										<div class="span1" style="text-align:center">
											<p>群号: <a><?=$value['group_id']; ?></a></p>
										</div>
										<div class="span1" style="text-align:center">
											<p>类型: <a><?=$value['group_category'];?></a></p>
										</div>
										<div class="span1" style="text-align:center">
											<p>成员: <a><?=$value['member_count'];?></a></p>
										</div>
									</div>
								</div>
								<?php endif;?>	
							<?php endforeach;?>
					    <?php else:?>
						    <div class="span2 alert alert-info">还没有群组</div>
						<?php endif;?>
					</div>
			    </div>
			  </div>
			</div>
		</div>
		<div class="span3">
			<h3>周边接触过谁呢？</h3>
			<div class="row">
				<?php if(isset($user_data['search_user_datas'])):?>
					<?php foreach($user_data['search_user_datas'] as $key=>$value):?>
						<?php if($value['type']=="user"&&$value['display_name']):?>
							<div class="span3">
					<div class="row">
						<div class="span1">
							<img width="50" src="<?=$value['user_avatar'];?>" />
						</div>
						<div class="span2">
							<div class="row">
								<div class="span2">
									<a href="javascript:void(0);"><?=$value['display_name'];?></a>
								</div>
								<div class="span2">
									<h6><?=timespan($value['last_search_time'],now()).'前';?></h6>
								</div>
								<div class="span2">
									<span class="label"><?='第'.$value['search_count'].'次接触'; ?></span>
								</div>
							</div>
						</div>
					</div>
					<hr size="1" style="border: 1px solid #bbb;">
				</div>
						<?php endif;?>
					<?php endforeach;?>
				<?php else:?>
					<div class="span2 alert alert-info">还没有接触过别的用户</div>
				<?php endif;?>
			</div>
			
		</div>
	</div>
	<hr size="1" style="border: 1px solid #bbb;">
	<footer>
		<p>&copy; Wlinke 2012</p>
	</footer>
</div>
