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
    <div class="logo"><h1><a href="<?php echo base_url(); ?>"><img src="images/default/logo.jpg" /></a></h1></div>
    <div class="tel"><a href="<?php echo base_url(); ?>"><img src="images/default/tel.jpg" /></div>
    </div>
	<div class="clear"></div>
	<div id=menu_out>
<div id=menu_in>
<div id=menu>
<UL id=nav>
<LI><A class=nav_on id=mynav0 onmouseover=javascript:qiehuan(0) href="<?php if ($html){echo 'index.html';} else {echo $client_index;} ?>"><SPAN><?php echo $index_name; ?></SPAN></A></LI>
<LI class="menu_line"></LI>
<!-- 一级分类 -->
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
  <LI><a href="<?php echo base_url(); ?>"><span>欢迎来到MEMORY婚礼策划！</span></A></LI>
</UL>
</div>
<?php
    if ($menuList) {
    foreach ($menuList as $key=>$menu) {
?>
<div id="qh_con<?php echo $key+1; ?>" style="DISPLAY: none">
<UL>
<!-- 二级分类 -->
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
<br/>
说明：
<br/>
<br/>
1、新闻封面里有分类，推荐写法（getMenuClass()，get_cus_list()）
<br/>
<br/>
2、新闻列表里有列表的写法
<br/>
<br/>
3、新闻详细里有详细的写法与相关文章的写法
<br/>
<br/>
4、成功案例里有多图片的写法
<br/>
<br/>
5、高级里有更多内容的写法（getMenuUrl()）
<br/>
<br/>
6、新闻封面有广告的写法（getAd()）
<br/>
<br/>
7、顶级ID写法（$parentId = $this->Menu_model->getParentMenuId($menuId);）