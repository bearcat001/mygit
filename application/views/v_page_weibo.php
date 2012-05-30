<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse"
				data-target=".nav-collapse"> <span class="icon-bar"></span> <span
				class="icon-bar"></span> <span class="icon-bar"></span>
			</a> <a class="brand" href="<?php echo base_url(); ?>">蜗临客</a>
			<div class="nav-collapse">
				<ul class="nav">
					<li class="active"><a href="<?php echo base_url("c_page_weibo"); ?>">首页</a></li>
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
					<li class="active"><a href="<?php echo base_url("c_page_weibo"); ?>"><i class="icon-home"></i>首页</a></li>
					<li><a href="<?php echo base_url("c_page_group"); ?>"><i class="icon-heart"></i>群组</a></li>
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
					<li class="active"><a href="#1" data-toggle="tab">全部</a></li>
					<li><a href="#2" data-toggle="tab">好友</a></li>
				</ul>
			</div>
			<div class="row">
				<?php if(isset($user_data['feeds'])):?>
					<?php foreach($user_data['feeds'] as $key=>$value):?>
						<!-- 微博头像 -->
				<div class="span1">
					<img width="50" src="<?php echo $value['user_avatar'];?>" />
				</div>
				<!-- 微博内容 -->
				<div class="span6">
					<div class="row">
						<!-- 博主名字及内容 -->
						<div class="span6">
							<a href="javascript:void(0);"><?php echo $value['display_name'];?></a>:<?php echo $value['feed_content'];?>
								</div>
						<div class="span6"></div>
						<div class="span6">
							<br>
						</div>
						<!-- 查看是否来自转发 -->
								<?php if(isset($value['source_feed'])):?>
									<div class="span5 well">
							<div class="row">
								<div class="span5">
									<a href="javascript:void(0);"><?php echo $value['source_feed']['display_name'];?></a>:<?php echo $value['source_feed']['feed_content'];?>
											</div>
								<div class="span5">
									<br>
								</div>
								<div class="span5">
									<div style="float: left;">
										<a href="javascript:void(0);"><?php echo timespan($value['source_feed']['create_time'],now());?>前</a>
									</div>
									<div style="float: right;">
										<a data-toggle="modal"
											href="#transpond_<?php echo $value['feed_id'];?>_<?php echo $value['source_feed']['feed_id'];?>">转发(<?php echo $value['source_feed']['transpond_count']?>)</a>
										| <a href="javascript:void(0);" data-toggle="collapse"
											data-target="#comment_<?php echo $value['feed_id'];?>_<?php echo $value['source_feed']['feed_id'];?>">评论(<?php echo $value['source_feed']['comment_count'];?>)</a>
									</div>
								</div>
								<!-- 评论对话框 -->
								<div class="span6">
									<div
										id="comment_<?php echo $value['feed_id'];?>_<?php echo $value['source_feed']['feed_id'];?>"
										class="collapse">暂时不能评论哦~！</div>
								</div>
							</div>
						</div>
						<!-- 转发对话框 -->
						<div class="modal fade"
							id="transpond_<?php echo $value['feed_id'];?>_<?php echo $value['source_feed']['feed_id'];?>">
							<div class="modal-header">
								<a class="close" data-dismiss="modal">×</a>
								<h3>转发微博</h3>
							</div>
							<div class="modal-body">
								<form
									action="<?php echo base_url('c_page_weibo/transpond_weibo_submit');?>"
									method="post">
									<textarea style="width: 98%" class="input-xlarge"
										name="transpond_weibo_content" rows="3"><?php echo '//'.$value['display_name'].':'.$value['feed_content']?></textarea>
									<input type="hidden" name="transpond_weibo_id"
										value="<?php echo $value['source_feed']['feed_id'];?>" />
									<div style="text-align: right;">
										<button type="submit" class="btn">转发</button>
									</div>
								</form>
							</div>
						</div>
								<?php endif;?>
								<!-- 微博操作 -->
						<div class="span6">
							<div style="float: left;">
								<a href="javascript:void(0);"><?php echo timespan($value['create_time'],now());?>前</a>
							</div>
							<div style="float: right;">
									<?php if(isset($value['source_feed'])):?>
										<a data-toggle="modal"
									href="#transpond_<?php echo $value['feed_id'];?>_<?php echo $value['source_feed']['feed_id'];?>">转发(<?php echo $value['transpond_count'];?>)</a> |
									<?php else:?>
										<a data-toggle="modal"
									href="#transpond<?php echo $value['feed_id'];?>">转发(<?php echo $value['transpond_count'];?>)</a> |
									<?php endif;?>	
										<a href="javascript:void(0);" data-toggle="collapse"
									data-target="#comment<?php echo $value['feed_id'];?>">评论(<?php echo $value['comment_count'];?>)</a>
							</div>
						</div>
						<!-- 评论对话框 -->
						<div class="span6">
							<div id="comment<?php echo $value['feed_id'];?>" class="collapse">暂时不能评论哦~！</div>
						</div>
						<!-- 转发对话框 -->
								<?php if(isset($value['source_feed'])):?>
									<div class="modal fade"
							id="transpond_<?php echo $value['feed_id'];?>_<?php echo $value['source_feed']['feed_id'];?>">
								<?php else:?>
									<div class="modal fade"
								id="transpond<?php echo $value['feed_id'];?>">
								<?php endif;?>
									<div class="modal-header">
									<a class="close" data-dismiss="modal">×</a>
									<h3>转发微博</h3>
								</div>
								<div class="modal-body">
									<form
										action="<?php echo base_url('c_page_weibo/transpond_weibo_submit');?>"
										method="post">
										<textarea style="width: 98%" class="input-xlarge"
											name="transpond_weibo_content" rows="3" placeholder="转发微博"></textarea>
										<input type="hidden" name="transpond_weibo_id"
											value="<?php echo $value['feed_id'];?>" />
										<div style="text-align: right;">
											<button type="submit" class="btn">转发</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					<div class="span7">
						<hr size="1" style="border: 1px solid #bbb;">
					</div>
					<div id="feed_id_<?php echo $value['feed_id'];?>"
						style="visibility: hidden; display: none;"><?php echo $value['feed_id'];?></div>
					<?php endforeach;?>
				<?php endif;?>
				<div id="get_old_feed" class="span7" style="text-align: center">
						<button id="get_old_feed_submit" data-loading-text="读取中……"
							class="btn" style="width: 100%">读取更多</button>
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
								<img width="50" src="<?php echo $value['user_avatar'];?>" />
							</div>
							<div class="span2">
								<div class="row">
									<div class="span2">
										<a href="javascript:void(0);"><?php echo $value['display_name'];?></a>
									</div>
									<div class="span2">
										<h6><?php echo timespan($value['last_search_time'],now()).'前';?></h6>
									</div>
									<div class="span2">
										<span class="label"><?php echo '第'.$value['search_count'].'次接触'; ?></span>
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
				<h3>群组</h3>
				<div class="row">
				<?php if(isset($user_data['group_datas'])):?>
					<?php foreach($user_data['group_datas'] as $key=>$value):?>
							<div class="span3">
						<div class="row">
							<form action="<?php echo base_url("c_page_weibo/join_group_submit")?>"
								method="post">
								<div class="span2">
									<a href="javascript:void(0);" rel="tooltip"
										title="<?php echo $value['group_destription'];?>"><?php echo $value['group_name'];?></a>
								</div>
								<div class="span2">
									成员数：<span class="badge"><?php echo $value['member_count'];?></span>
								</div>
								<input type="hidden" name="join_group_id"
									value="<?php echo $value['group_id'];?>" />
										<?php if(!$value['is_in']):?>
											<div class="span1">
									<button class="btn btn-mini" type="submit">加入</button>
								</div>
										<?php else:?>
											<div class="span1">
									<a class="btn btn-mini disabled">已加入</a>
								</div>
										<?php endif;?>
									</form>
						</div>
					</div>
					<?php endforeach;?>
			    <?php else:?>
				    <div class="span2 alert alert-info">还没有群组</div>
				<?php endif;?>
			</div>
			</div>
		</div>
		<hr size="1" style="border: 1px solid #bbb;">
		<footer>
			<p>&copy; Wlinke 2012</p>
		</footer>
	</div>
</div>