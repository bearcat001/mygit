<div data-role="page" class="type-interior" id="register" data-theme="f">

	<div data-role="header" data-theme="f">
		<h1>蜗临客</h1>
		<a data-rel="back" data-icon="arrow-l">返回</a>
	</div>
	<!-- /header -->

	<div data-role="content">
		
		<div class="content-secondary">
			<form action="<?=base_url("mobile/c_register/register_submit"); ?>"
				method="post" data-ajax="false">
				<ul data-role="listview">
					<li data-role="list-divider">注册</li>
					<li>邮箱</li>
					<li><input id="register_email" name="register_email" type="email"
						value="" placeholder="请输入您的邮箱" /></li>
					<li>密码（6位及以上数字或字母组合）</li>
					<li><input id="register_password" name="register_password"type="password" value="" placeholder="请输入您的密码" /></li>
					<li>真实姓名</li>
					<li><input id="register_real_name" name="register_real_name" type="text"
						value="" placeholder="请输入您的姓名" /></li>
					<li>蓝牙地址（12位字母数字组合，不区分大小写）</li>
					<li><input id="register_bluetooth_mac" name="register_bluetooth_mac"
						type="text" value="" placeholder="请输入您的蓝牙地址" /> <input
						type="submit" value="提交" /></li>
				</ul>
			</form>
		</div>
		
		<div class="content-primary">
			<h1>如何获得蓝牙地址？</h1>
			<ul data-role="listview">
				<li data-role="list-divider">iOS系统</li>
				<li>如iphone</li>
				<li>设置-&gt;通用-&gt;关于本机-&gt;蓝牙</li>
				<li data-role="list-divider">Android系统</li>
				<li>如HTC、Moto、三星、魅族、小米、索爱、华为、中兴</li>
				<li>menu-&gt;设置-&gt;关于手机-&gt;硬件信息(状态信息)-&gt;蓝牙地址(需先打开蓝牙)</li>
				<li data-role="list-divider">Symbian系统</li>
				<li>如诺基亚</li>
				<li>输入*#2820# ，即可查看蓝牙地址</li>
				<li data-role="list-divider">MTK系统</li>
				<li>联想、天语、康佳、海尔、万利达</li>
				<li>打开蓝牙，查看高级选项，就可见蓝牙地址</li>
			</ul>
		</div>
	</div>
	<div data-role="footer" class="footer-docs" data-theme="c">
			<p>&copy; 2011-2012 WlinkE</p>
	</div>	
</div>
<!-- /page -->
