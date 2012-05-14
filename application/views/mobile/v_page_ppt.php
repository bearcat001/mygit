<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=600" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>华丽的PPT</title>
    <link href="<?php echo base_url("css/impress-demo.css");?>" rel="stylesheet" />
    <link rel="apple-touch-icon" href="<?php echo base_url("image/apple-touch-icon.png");?>" />
    <style>
#top_banner {
	height:auto;
	width:100%;
	text-shadow: 0 1px 1px #000;
	margin:0 auto;
	position:fixed;
	z-index:100;
	height: 20px;
	text-align: center;
	background: #bbb;
	color: #000;
	font-size: 14px;
	font-weight: bold;
}

#bottom_banner {
	height:auto;
	width:100%;
	text-shadow: 0 1px 1px #000;
	margin:0 auto;
	bottom: 0;
	position:fixed;
	z-index:100;
	height: 20px;
	text-align: center;
	background: #bbb;
	color: #000;
	font-size: 14px;
	font-weight: bold;
}
</style>
</head>

<body class="impress-not-supported">

<div id="impress">

    <div id="bored" class="step slide" data-x="-1000" data-y="0">
        <q>你是不是也因为其他PPT而<b>烦恼</b>?</q>
    </div>
    <div class="step slide" data-x="0" data-y="0">
        <q>Don't you think that presentations given <strong>in modern browsers</strong> shouldn't <strong>copy the limits</strong> of 'classic' slide decks?</q>
    </div>
    <div class="step slide" data-x="1000" data-y="0">
        <q>Would you like to <strong>impress your audience</strong> with <strong>stunning visualization</strong> of your talk?</q>
    </div>
    <div class="step slide" data-x="2000" data-y="0">
        <q>Would you like to <strong>impress your audience</strong> with <strong>stunning visualization</strong> of your talk?</q>
    </div>
    <div class="step slide" data-x="3000" data-y="0">
        <q>Would you like to <strong>impress your audience</strong> with <strong>stunning visualization</strong> of your talk?</q>
    </div>

</div>
<div class="hint">
    <p>使用左右键控制PPT</p>
</div>
<script>
if ("ontouchstart" in document.documentElement) { 
    document.querySelector(".hint").innerHTML = "<p>使用左右键控制PPT</p>";
}
</script>
<script src="<?php echo base_url("js/impress.js");?>"></script>
<script>impress();</script>
<div id="top_banner">这里是顶部的横幅，随着页面滚动而浮动</div>
<div id="bottom_banner">这里是底部的横幅，随着页面滚动而浮动</div>
</body>
</html>
