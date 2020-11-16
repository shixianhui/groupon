<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="x-ua-compatible" content="ie=7" />
<base href="<?php echo base_url(); ?>" />
<meta name="title" content="<?php echo clearstring($title); ?>" />
<meta name="keywords" content="<?php echo clearstring($keywords); ?>" />
<meta name="description" content="<?php echo clearstring($description); ?>" />
<title><?php echo $title; ?></title>
<?php if ($this->uri->segment(1) == 'page') { ?>
<link href="css/default/mstyle.css" rel="stylesheet"/>
<link href="css/default/style.css" rel="stylesheet" type="text/css" />
<?php } else if($this->uri->segment(1) == 'cases') {?>
<link href="css/default/hstyle.css" rel="stylesheet" type="text/css" />
<link href="css/default/dedecms.css" rel="stylesheet" media="screen" type="text/css" />
<link href="css/default/style.css" rel="stylesheet" type="text/css" />
<?php } else if ($this->uri->segment(1) == 'video' && $this->uri->segment(2) == 'cover') {?>
<link href="css/default/dedecms.css" rel="stylesheet" media="screen" type="text/css" />
<link href="css/default/video.css" rel="stylesheet" media="screen" type="text/css" />
<link href="css/default/style.css" rel="stylesheet" type="text/css" />
<?php } else if ($this->uri->segment(1) == 'news' && $this->uri->segment(2) == 'cover') {?>
<link href="css/default/bstyle.css" rel="stylesheet" type="text/css" />
<?php } else { ?>
<link href="css/default/dedecms.css" rel="stylesheet" media="screen" type="text/css" />
<link href="css/default/style.css" rel="stylesheet" type="text/css" />
<?php } ?>
</head>
<body> 
<script language="javascript">
	function qiehuan(num){
		for(var id = 0;id<=9;id++)
		{
			if(id==num)
			{
				document.getElementById("qh_con"+id).style.display="block";
				document.getElementById("mynav"+id).className="nav_on";
			}
			else
			{
				document.getElementById("qh_con"+id).style.display="none";
				document.getElementById("mynav"+id).className="";
			}
		}
	}
</script> 
<div class="box">
<div class="top">
    	<div class="t_left">高端婚礼订制领导品牌-国际连锁17年     蔓茉莉中国：<a>香港</a>   <a>广州</a>   <a>上海</a>   <a>三亚</a></div>
       <div class="t_right"><a href="http://weibo.com/memoryplot/">关注新浪微博</a>      <a href="http://blog.sina.com.cn/lovememoryplot">蔓茉莉博客</a></div>
    </div>
    <div class="clear"></div>
    <div class="head">
    <div class="logo"><h1><a href="<?php echo base_url(); ?>"><img src="images/default/logo.jpg"  /></a></h1></div>
    <div class="tel"><a href="<?php echo base_url(); ?>"><img src="images/default/tel.jpg" /></div>
    </div>
	<div class="clear"></div>
    <div id=menu_out>
<div id=menu_in>
<div id=menu>
<UL id=nav>
<LI><A class=nav_on id=mynav0 onmouseover=javascript:qiehuan(0) href="<?php if ($html){echo 'index.html';} else {echo $client_index;} ?>"><SPAN><?php echo $index_name; ?></SPAN></A></LI>
<LI class="menu_line"></LI>
<?php
    $menuList = $this->advdbclass->getNavigationMenu();    
    if ($menuList) {
    foreach ($menuList as $key=>$menu) {
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
<li><a href="<?php echo $url; ?>" onMouseOver="javascript:qiehuan(<?php echo $key+1; ?>)" id="mynav<?php echo $key+1; ?>" class="nav_off"><span><?php echo $menu['menu_name']; ?></span></a></li>
<li class="menu_line"></li>
<?php }} ?>
</UL>
<div id=menu_con><div id=qh_con0 style="DISPLAY: block"><UL>
  <LI><A href="<?php if ($html){echo 'index.html';} else {echo $client_index;} ?>"><SPAN>欢迎来到MEMORY婚礼策划！</SPAN></A></LI>
</UL></div> 
<div id=qh_con1 style="DISPLAY: none">
<UL>
  <LI><a href="<?php if ($html){echo 'index.html';} else {echo $client_index;} ?>"><span>欢迎来到MEMORY婚礼策划！</span></A></LI>
</UL>
</div>
<?php
    if ($menuList) {
    foreach ($menuList as $key=>$menu) {
?>
<div id="qh_con<?php echo $key+1; ?>" style="DISPLAY: none">
<UL>
<?php if ($menu['subMenuList']) {
	foreach ($menu['subMenuList'] as $subMenu) {
	if ($subMenu['menu_type'] == '3') {
		$url = $subMenu['url'];
	} else {
		$url = getBaseUrl($html, "{$subMenu['html_path']}/index{$subMenu['id']}.html", "{$subMenu['template']}/index/{$subMenu['id']}.html", $client_index);
    }
	?>
  <LI><a href="<?php echo $url; ?>"><span><?php echo $subMenu['menu_name']; ?></span></A></LI><LI class=menu_line2></LI>
  <?php }} else { ?>
  <LI><a href="<?php if ($html){echo 'index.html';} else {echo $client_index;} ?>"><span>欢迎来到MEMORY婚礼策划！</span></A></LI>
  <?php } ?>
</UL>
</div>
<?php }} ?>
</div>
</div>
</div>
</div> 
<?php echo $content; ?>
<div class="foot">
	<div class="ls">
    <span>高端婚礼定制-全球连锁:</span>&#160;&#160;&#160;
    <A>澳洲</A>&#160;&#160;&#160;
    <a>台湾</a>&#160;&#160;&#160;
    <a>香港</a>&#160;&#160;&#160;
    <a>广州</a>&#160;&#160;&#160;
    <a>三亚</a>&#160;&#160;&#160;
    <a>上海</a>
    </div>
    <div class="foot_z">
    	<div class="logo_f"> <A href="<?php echo base_url(); ?>"><img src="images/default/foot_logo.jpg" /></A></div>
        <div class="bq">
        	<div class="mm">
 <?php
    $footerMenuList = $this->advdbclass->getFooterMenu();
    if ($footerMenuList) {
	foreach ($footerMenuList as $footerMenu) {
		if ($footerMenu['menu_type'] == '3') {
    		$url = $footerMenu['url'];    		
    	} else {
    		$url = getBaseUrl($html, $footerMenu['html_path'], "{$footerMenu['template']}/index/{$footerMenu['id']}.html", $client_index);
        }
	?>
            <A href="<?php echo $url; ?>"><?php echo $footerMenu['menu_name'] ?></A>&#160;&#160;|&#160;&#160;
    <?php }} ?>
    <?php echo $site_copyright; ?><?php echo $icp_code; ?> 
            </div>
        </div>    
    </div>
</div>
<?php echo $this->load->view('element/gonline_tool', NULL, TRUE); ?>
</body>
</html>