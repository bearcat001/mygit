<div data-role="page" id="add_place" > 
 
	<div data-role="header" data-position="fixed" data-theme="f">
		<h1>蜗临客</h1>
		<a data-rel="back" data-icon="arrow-l">返回</a>
	</div><!-- /header --> 
 
	<div data-role="content"> 
	    <form action="<?=base_url("mobile/c_page/add_place_submit"); ?>" method="post" data-ajax="false" >
		    <h3>聚点名称</h3>
			<input id="place_name" name="place_name" type="text" value="" placeholder="请输入聚点名称"/>
			<h3>聚点介绍</h3>
			<input id="place_destription" name="place_destription" type="text" value="" placeholder="请输入聚点的介绍"/>
			<h3>聚点类别</h3>
			<select name="place_category" id="place_category" data-native-menu="false">
				<option>选择类别</option>
				<option value="教室">教室</option>
				<option value="实验室">实验室</option>
			</select>
	    	<input type="submit" value="创建" />
	    </form>
	</div> 
</div><!-- /page --> 
