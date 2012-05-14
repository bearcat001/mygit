
<div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">蜗临客</a>
           <div class="nav-collapse">
            <ul class="nav">
              <li><a href="<?=base_url("c_wlinke"); ?>">登陆</a></li>
              <li class="active"><a href="<?=base_url("c_register"); ?>">注册</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

<div class="container">
	<div class="row">
		<div class="span4">
			<div class="well">
				<form action="<?=base_url("c_register/register_submit"); ?>" method="post">
					<h2>用户注册</h2>
					<label>邮箱</label> 
					<input name="register_email" style="width: 98%" type="email" placeholder="请输入您的邮箱"> 
					<label>密码（6位及以上数字或字母组合）</label> 
					<input name="register_password" style="width: 98%" type="password" placeholder="请输入您的密码">
					<label>确认密码（6位及以上数字或字母组合）</label> 
					<input name="register_password_again" style="width: 98%" type="password" placeholder="请再次输入您的密码">  
					<label>真实姓名（方便与周边好友交互）</label> 
					<input name="register_real_name" style="width: 98%" type="text" placeholder="请输入您的姓名"> 
					<label>手机蓝牙地址（更好的发现周边好友）<h6>如002669095c16,不区分大小写</h6></label> 
					<input name="register_bluetooth_mac" style="width: 98%" type="text" placeholder="请输入您的蓝牙地址"> 
					<?php if(isset($error)):?>
					<div class="alert alert-error">
					<?php echo $error;?>
					</div>
					<?php endif;?>
					<input type="submit" style="width: 100%" class="btn" value="注册"/>
				</form>
			</div>
		</div>
		<!--/span-->
		<div class="span8">
		<div class="well">
			<h1>如何获得自己手机的蓝牙地址？</h1>
			<hr>
			<table class="table table-bordered">
		        <thead>
		          <tr>
		            <th></th>
		            <th>手机系统</th>
		            <th>手机型号</th>
		            <th>蓝牙获得方式</th>
		          </tr>
		        </thead>
		        <tbody>
		          <tr>
		            <td>1</td>
		            <td>iOS</td>
		            <td>iphone1、2、3、3GS、4、4S</td>
		            <td>设置-&gt;通用-&gt;关于本机-&gt;蓝牙</td>
		          </tr>
		          <tr>
		            <td>2</td>
		            <td>Android</td>
		            <td>HTC、三星、小米、华为、中兴</td>
		            <td><b>需要先打开蓝牙才可以查看地址</b><br>
		            	menu-&gt;设置-&gt;无线和网络-&gt;蓝牙设置-&gt;打开蓝牙<br>
		            	menu-&gt;设置-&gt;关于本机-&gt;状态消息-&gt;蓝牙地址
		            </td>
		          </tr>
		          <tr>
		            <td>3</td>
		            <td>Symbian</td>
		            <td>诺基亚</td>
		            <td>输入*#2820# ，即可查看蓝牙地址</td>
		          </tr>
		          <tr>
		            <td>4</td>
		            <td>MTK</td>
		            <td>联想、天语、康佳等</td>
		            <td>打开蓝牙，查看高级选项，就可见蓝牙地址</td>
		          </tr>
		        </tbody>
		     </table>
		     <hr>
		     <h1>蓝牙耗电嘛？</h1>
		     <p><span style="margin-left: 2em;"></span>
		     	关于大家比较关心的蓝牙耗电问题，小蜗也比较担心，于是做了一个详细的电量测试，事实大于理论嘛~
		     	</p>
		     <p><span style="margin-left: 2em;"></span>
		     	小蜗自从“蜗临客”上线以来一直都开着蓝牙，时不时还搜索下周围的同学们，下面的数据是平常一天的用电量，
		     	看起来蓝牙的耗电量还是比较小的，如果大家也做过这样的测试，不妨联系小蜗，贴出来给大家看呦~
		     	</p>
		     <div>
		     		<img width="180" src="<?php echo base_url("images/bluetooth1.png");?>" />
		     		<img width="180" src="<?php echo base_url("images/bluetooth2.png");?>" />
		     		<img width="180" src="<?php echo base_url("images/bluetooth3.png");?>" />
		     </div>
		     </div>
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
