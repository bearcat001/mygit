<div data-role="page" class="type-interior" id="login" data-theme="f"> 
 
	<div data-role="header" data-theme="f" >
		<h1>蜗临客</h1>
	</div><!-- /header --> 
 
	<div data-role="content" > 
		<div class="content-secondary" >
		    <form action="<?=base_url("mobile/c_login/login_submit"); ?>" method="post" data-ajax="false" data-theme="f">
			    <ul data-role="listview">
				    <li data-role="list-divider">登陆</li>
				    <li>邮箱</li>
					<li><input id="login_email" name="login_email" type="email" value="" placeholder="请输入您的邮箱"/></li>
					<li>密码</li>
					<li><input id="login_password" name="login_password" type="password" value="" placeholder="请输入您的密码"/></li>
					<li>
			    	<div class="ui-grid-a">
			    		<div class="ui-block-a"><input type="submit" value="提交"/></div>
						<div class="ui-block-b"> <a href="<?=base_url("mobile/c_register"); ?>" data-role="button">注册</a> </div>
		   		 	</div>
		   		 	</li>
		   		 	<li data-role="list-divider">最近注册用户</li>
		   		 	<?php foreach($latest_user_data as $user_data):?>
		   		 	<?php
			   		 	echo "<li data-theme='c'>";
						echo "<img src=".$user_data['user_avatar']." />";
						echo "<h3>".$user_data['display_name']."</h3>";
						echo "<span class='ui-li-count'>".timespan($user_data['create_time'],now()).'前'."</span>";
						echo "</li>";
					?>
		   		 	<?php endforeach;?>
		   		 	
	   		 	</ul>
	   		 </form>
		</div>
		
		<div class="content-primary" style="text-align:center;">
		   <h1>WlinkE</h1>
		   <p>WlinkE（蜗临客）平台，以近距离无线通信技术为基础，为用户（以经常进行群体活动的人群为主）打造以个人为中心、基于真实近距离发现和交互的服务，帮助用户掌握身边人和物的状态及内在信息，实现近距离人与人、人与物交互。</p>
		   <img src="<?=base_url("images/login_im.png")?>" />
		</div>
	</div> 
	<div data-role="footer" class="footer-docs" data-theme="c">
			<p>&copy; 2011-2012 WlinkE</p>
	</div>	
</div><!-- /page --> 

