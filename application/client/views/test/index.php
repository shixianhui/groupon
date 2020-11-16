<br/>
<strong>--------------------------------------------------------头部栏目/底部栏目--------------------------------------------------</strong>
<br/><br/>
<ul>
<?php
	$getHeaderMenuList = $this->advdbclass->getHeadMenu();    
	if ($getHeaderMenuList) {
		foreach ($getHeaderMenuList as $key=>$menu) {
			if ($menu['menu_type'] == '3') {
	    		$url = $menu['url'];    		
	    	} else {
	    	    if ($menu['menu_type'] == 1 && $menu['cover_function']) {
	    	    	$url = getBaseUrl($html, "{$menu['html_path']}/{$menu['cover_function']}{$menu['id']}.html", "{$menu['template']}/{$menu['cover_function']}/{$menu['id']}.html", $client_index);
	    		} else {
	    			$url = getBaseUrl($html, "{$menu['html_path']}/index{$menu['id']}.html", "{$menu['template']}/index/{$menu['id']}.html", $client_index);
	    		}
	    	}
?>
<li><a href="<?php echo $url; ?>"><?php echo $menu['menu_name']; ?></a></li>
<?php }} ?>
</ul>
<br/>
<strong>--------------------------------------------------------更多链接--------------------------------------------------------</strong>
<br/><br/>
<ul>
<li><a href="<?php echo $this->advdbclass->getMenuUrl(5, false, $client_index); ?>">更多</a></li>
</ul>
<br/>
<strong>--------------------------------------------------------单页面内容/首页公司简介--------------------------------------------------------</strong>
<br/><br/>
<ul>
<?php 
		 $getPageList = $this->advdbclass->getPageList(143, 1);
		 if ($getPageList) {
		 	foreach ($getPageList as $key=>$value) { ?>
		 	
<li><?php echo html($value['abstract']); ?></li>
<?php }} ?>
</ul>
<br/>
<strong>--------------------------------------------------------js特殊效果--------------------------------------------------</strong>
<br/><br/>
<ul>
<li>123456广告效果1</li>
<li>
<!-- 广告开始 -->
	<div id="focusViwer">
<script src="js/default/rollflash.js"></script>
<script language="javascript">
<!--
var focus_width=970
var focus_height=200
var text_height=0
var swf_height = focus_height+text_height
<?php
    $pics = '';
    $links = '';
	$adList = $this->advdbclass->getAd(1);
    if ($adList) {
		foreach ($adList as $key=>$ad) {
			$pics.=$ad['path'].'|';
			$links.=$ad['url'].'|';
	}} ?>
var pics='<?php echo substr($pics, 0, -1); ?>';
var links='<?php echo substr($links, 0, -1); ?>';
var texts='|||||';
var FocusFlash = new reddotFlash("flash/default/pixviewer.swf", "focusflash", focus_width, swf_height, "7", "#FFFFFF", false, "High");
FocusFlash.addParam("allowScriptAccess", "sameDomain");
FocusFlash.addParam("menu", "false");
FocusFlash.addParam("wmode", "opaque");
FocusFlash.addVariable("pics", pics);
FocusFlash.addVariable("links", links);
FocusFlash.addVariable("texts", texts);
FocusFlash.addVariable("borderwidth", focus_width);
FocusFlash.addVariable("borderheight", focus_height);
FocusFlash.addVariable("textheight", text_height);
//FocusFlash.addVariable("curhref", curhref);
FocusFlash.write("focusViwer");
//-->
</script>
</div>
	<!-- 广告结束 -->
</li>
<li>123456广告效果2</li>
<li>
<script src="js/default/jquery-1.4.2.min.js" type="text/javascript"></script>
<script type=text/javascript src="js/default/scroll123456.js"></script>
<script type="text/javascript">
$(function(){
	$("#KinSlideshow").KinSlideshow({
			moveStyle:"left",
			intervalTime:8,
			mouseEvent:"mouseover",
			isHasTitleBar:false,
			titleBar:{titleBar_height:30,titleBar_bgColor:"#08355c",titleBar_alpha:0.5},
			titleFont:{TitleFont_size:12,TitleFont_color:"#FFFFFF",TitleFont_weight:"normal"},
			isHasTitleFont:false,
			btn:{btn_bgColor:"#FFFFFF",btn_bgHoverColor:"#1072aa",btn_fontColor:"#000000",btn_fontHoverColor:"#FFFFFF",btn_borderColor:"#cccccc",btn_borderHoverColor:"#1188c0",btn_borderWidth:1}
	});
})
</script>
<div id="KinSlideshow" style="visibility:hidden;">
 <?php
	$adList = $this->advdbclass->getAd(1, 1);
	if ($adList) {
	foreach ($adList as $ad) {
	?>
   <a href="<?php echo $ad['url']; ?>" target="_blank"><img src="<?php echo $ad['path']; ?>" alt="<?php echo $ad['ad_text']; ?>" width="968" height="120" /></a>
<?php }} ?>
</div>
</li>
<li>123456广告效果3</li>
<li>
<link rel=Stylesheet type=text/css href="css/default/solid.css">
    <script type=text/javascript src="js/default/index_solid.js"></script>
    <div id="banner">
	<div id="ifocus">
	<div style="OVERFLOW: hidden" id=ifocus_pic>
	<div style="OVERFLOW: hidden; TOP: 0px; LEFT: 0px" id=ifocus_piclist>
	 <ul><!--大图_start -->
	  <?php
	   $adList = $this->advdbclass->getAd(1);
	   if ($adList) {
		foreach ($adList as $ad) {
	    ?>
	  <li><a href="<?php echo $ad['url']; ?>" target=_blank><img border=0 src="<?php echo $ad['path']; ?>" width="970px" height="300px"></a> </li>
	 <?php }} ?>
	 </ul>
	</div>
	<div id=ifocus_opdiv></div>
	<div id=ifocus_tx>
	<ul><!--小图列表_start -->
	 <?php if ($adList) {	 	
		foreach ($adList as $key=>$ad) {
			$class = "normal";
			if ($key == 0) {
				$class = "current";
			}
	    ?>
	  <li class="<?php echo $class; ?>"></li>
	   <?php }} ?>
	 </ul>
	</div>
	<div id=ifocus_btn>
	<ul><!--小图_start -->
	 <?php if ($adList) {
		foreach ($adList as $key=>$ad) {
			$class = "normal";
			if ($key == 0) {
				$class = "current";
			}
	    ?>
	  <li class="<?php echo $class; ?>"><img border=0 src="<?php echo $ad['path']; ?>"> </li>
	  <?php }} ?>
	 </ul>
	</div>
	</div>
	</div>
	</div>
</li>
<li>---------------------------------------------左右滚动效果----------------------------------------------</li>
<li>
<a href="http://www.51daima.com/index.html">官方网站首页</a>
</li>
<li>
<a href="http://localhost/51daima_jianzhan_com/index.php">婚宴场地</a>
</li>
</ul>
