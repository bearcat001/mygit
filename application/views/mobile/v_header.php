<!DOCTYPE html> 
<html> 
	<head> 
	<meta charset="utf-8" /> 
	<title>蜗临客</title> 
	<meta name="viewport" content="width=device-width, initial-scale=1"> 	 
	<link rel="stylesheet" href="<?=base_url("css/jquery.mobile-1.0.1.min.css");?>" /> 
	<link rel="stylesheet" href="<?=base_url("css/jqm-docs.css");?>"/>
	<script src="<?=base_url('js/jquery.js');?>"></script> 
	<script src="<?=base_url('js/jquery.mobile.themeswitcher.js');?>"></script>
	<script src="<?=base_url('js/jqm-docs.js');?>"></script>
	<script type="text/javascript"> 
            $(document).bind("mobileinit", function() {
                $.mobile.ajaxEnabled=false;
                $.linkBindingEnabled=false;
                $.mobile.loadingMessage = "数据努力加载中……"; 
                $.mobile.pageLoadErrorMessage = "很抱歉，貌似出错了！"; 
            }); 
        </script>
	<script src="<?=base_url('js/jquery.mobile-1.0.1.min.js');?>"></script> 
</head> 
<body> 