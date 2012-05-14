<div data-role="page" id='post_weibo'> 
 
	<div data-role="header" data-position="fixed" data-theme="f">
		<h1><?=$input_title;?></h1>
		<a data-rel="back" data-icon="arrow-l">返回</a>
	</div><!-- /header --> 
 	
	<div data-role="content"> 
		<form action="<?=$input_action_url;?>" method="post" data-ajax="false" >
			<textarea rows="15" name="input_content" id="input_content" placeholder=<?=$input_prompt?> style="width:99%; "></textarea>
			<input type="submit" value="提交"/>
		</form>
	</div>
</div><!-- /page --> 