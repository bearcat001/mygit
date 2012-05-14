<div data-role="page" id="add_group"> 
 
	<div data-role="header" data-position="fixed"  data-theme="f">
		<h1>蜗临客</h1>
		<a data-rel="back" data-icon="arrow-l">返回</a>
	</div><!-- /header --> 
 
	<div data-role="content"> 
	    <form action="<?=base_url("mobile/c_page/add_gourp_submit"); ?>" method="post" data-ajax="false" >
		    <h3>蜗群名称</h3>
			<input id="group_name" name="group_name" type="text" value="" placeholder="请输入您蜗群名称"/>
			<h3>蜗群介绍</h3>
			<input id="group_destription" name="group_destription" type="text" value="" placeholder="请输入您蜗群的介绍"/>
			<h3>蜗群类别</h3>
			<select name="group_category" id="group_category" data-native-menu="false">
				<option>选择蜗群类别</option>
				<option value="班级">班级</option>
			</select>
	    	<input type="submit" value="创建"/>
	    </form>
	</div> 
</div><!-- /page --> 

