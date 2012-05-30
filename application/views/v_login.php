
<div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="<?php echo base_url(); ?>">蜗临客</a>
          <div class="nav-collapse">
            <ul class="nav">
              <li class="active"><a href="<?php echo base_url("c_login"); ?>">登陆</a></li>
              <li><a href="<?php echo base_url("c_register"); ?>">注册</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

<div class="container">
	<div class="row">
		<div class="span3">
			<div class="well">
				<form action="<?php echo base_url("c_login/login_submit");?>" method="post">
					<h2>用户登陆</h2>
					<label>账号</label> 
					<input id="login_email" name="login_email" style="width: 98%" type="email" placeholder="请输入账号"> 
					<label>密码</label> 
					<input id="login_password" name="login_password" style="width: 98%" type="password" placeholder="请输入密码"> 
					<label class="checkbox"> 
						<input type="checkbox">记住我
					</label>
					<?php if(isset($error)):?>
					<div class="alert alert-error" >
					<?php echo $error;?>
					</div>
					<?php endif;?>
					<div style="text-align:right;">
						 <button type="submit" class="btn">登陆</button>
						<a href="<?php echo base_url("c_register"); ?>" class="btn">注册</a>
					</div>
				</form>
			</div>
          	<div>
          		<h2>最新注册</h2>
	          	<ul class=" thumbnails">
	          	  <?php if(isset($latest_user_data)):?>
				  <?php foreach($latest_user_data as $user_data):?>
			   		 	<?php
			   		 		echo '<li class="span1"><div class="thumbnail">';
							echo "<img width='50' src=".$user_data['user_avatar']." />";
							echo '<h5 style="text-align:center;">'.$user_data['display_name']."</h5>";
							echo "</div></li>";				
						?>
				  <?php endforeach;?>
				  <?php endif;?>
				</ul>
			</div>
		</div>
		<!--/span-->
		<div class="span9">
			<div class="well">
				<h1>Wlinke（蜗邻客）</h1>
				<div class="row">
					<div class="span3">
						<img width="200" src="<?php echo base_url("images/login_im.png")?>" />
						<hr>
						<button class="btn btn-large btn-success disabled" style="width:98%">Android客户端下载</button>
						<p>即将开放</p>
						<button class="btn btn-large btn-success disabled" style="width:98%">iPhone客户端下载</button>
						<p>即将开放</p>
					</div>
				 	<div class="span5">
					   <p><h3>在大学校园里，你是否也遇到过这样的问题？</h3></p>
					   <ul>
					       <li><p  style="line-height: 1.5;"><font size="3">和许多同学一起上课，却从来没有说过话？甚至都不知道ta的名字？</font></p></li>
					       <li><p  style="line-height: 1.5;"><font size="3">在大学校园里，总是有那么多熟悉的陌生人，大家经常擦肩而过，却从来没有打破沉默？</font></p></li>
					       <li><p style="line-height: 1.5;"><font size="3">在大学找不到志同道合的同学？</font></p></li>
					       <li><p style="line-height: 1.5;"><font size="3">身边总有很多活动不知情？</font></p></li>
					   </ul>
					   <p><h3>Wlinke希望通过近距离感知来帮你解决：</h3></p>
					   <ul>
					       <li><p style="line-height: 1.5;"><font size="3">了解身边同学们的个人信息</font></p></li>
					       <li><p style="line-height: 1.5;"><font size="3">了解身边的同学们的兴趣爱好</font></p></li>
					       <li><p style="line-height: 1.5;"><font size="3">了解身边的一些活动</font></p></li>
					   </ul>
						<p style="line-height: 1.5;"><span style="margin-left: 2em;"></span><font size="3"><i>Wlinke</i>
					   通过近距离发现技术（如蓝牙）帮华电师生掌握身边的信息，若您希望体验如上功能，只要注册Wlinke并且开启手机蓝牙即可，当然不开启蓝牙您的信息是绝对安全的</font></p>
					</div>
					
				</div>
			</div>
			<!--/row-->
		</div>
		<!--/span-->
		
	</div>
	<!--/row-->

	<hr size="1" style="border: 1px solid #bbb;">
	<footer>
		<p>&copy; Wlinke 2012</p>
	</footer>
</div>
<!--/.fluid-container-->
