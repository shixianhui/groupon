<div class="bk_cc">
  <div class="bk_top">
   	<div class="lbt">
   	<div id="box_banner">
   	<!-- 广告开始 -->
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
			$adList = $this->advdbclass->getAd(1);
			if ($adList) {
			foreach ($adList as $ad) {
			?>
		   <a href="<?php echo $ad['url']; ?>" target="_blank"><img alt="<?php echo clearstring($ad['ad_text']); ?>" src="<?php echo $ad['path']; ?>" width="650" height="320" /></a>
		<?php }} ?>
		</div>
	<!-- 广告结束 -->
      </div>
    </div>
    	<div class="rdt">
        <div class="rdt_bt"><strong style="color:red;">热点内容示例</strong></div>
        <div class="rdt_nn">
       	  <div class="one_k">
       	   <?php
       	   if ($hotItemList) {
       	   	     foreach ($hotItemList as $item) {
				$url = getBaseUrl($html, "{$item['html_path']}/{$item['id']}.html", "{$template}/detail/{$item['id']}.html", $client_index);
	       ?>
            <ul>
            <li class="one_l"><a title="<?php echo clearstring($item['title']); ?>" href="<?php echo $url; ?>"><img alt="<?php echo clearstring($item['title']); ?>" src='<?php echo preg_replace('/\./', '_thumb.', $item['path']); ?>' border='0' width='85' height='96'></a></li>
            <li class="wz_z"><span><A title="<?php echo clearstring($item['title']); ?>" href="<?php echo $url; ?>"><?php echo my_substr($item['title'], 20, ''); ?></A></span><br/><A><?php echo my_substr($item['abstract'], 90); ?></A></li>
            </ul>
          <?php }} ?>
          </div>
        	<div class="clear"></div>
        	<div class="line_x"><img src="images/default/line_ll.gif" /></div>
        	<div class="ll_wzj">
            <ul>
            <li><strong style="color:red;">推荐内容示例</strong></li>
            <?php 
            $cus_list = $this->advdbclass->get_cus_list($template, 138, 'c', 0, 10);
            if ($cus_list) { 
            	foreach ($cus_list as $item) {
				$url = getBaseUrl($html, "{$item['html_path']}/{$item['id']}.html", "{$template}/detail/{$item['id']}.html", $client_index);
			?>
            <li><A href="<?php echo $url; ?>" title="<?php echo clearstring($item['title']); ?>"><font color="<?php echo $item['title_color']; ?>"><?php echo my_substr($item['title'], 40); ?></font></A>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo date('Y-m-d H:i', $item['add_time']); ?></li>
            <?php }} ?>
            </ul>
            </div>
        </div>
        </div>       
        </div>
        <div class="clear"></div>
        <div class="nn_sk">
        <!-- 分类示例 -->
        <?php
        $itemMenuList = $this->advdbclass->getMenuClass($parent_id);
	    if ($itemMenuList) {
	    foreach ($itemMenuList as $key=>$menu) {
	    	if ($menu['menu_type'] == '3') {
	    		$url = $menu['url'];    		
	    	} else {
	    		$url = getBaseUrl($html, "{$menu['html_path']}/index{$menu['id']}.html", "{$menu['template']}/index/{$menu['id']}.html", $client_index);
	    	}
	    	//样式
	    	$strClass = '';
	    	if ($key%3 != 0) {
	    		$strClass = 'style=" margin-left:16px;"';
	    	}
	    ?>
        <div class="rdt_1" <?php echo $strClass; ?>>
        <a href="<?php echo $url; ?>"><div class="rdt_bt_1"><?php echo $menu['menu_name']; ?></div></a>
        <div class="rdt_nn">
        	<div class="clear"></div>
        	<div class="line_x"><img src="images/default/line_ll.gif" /></div>
        	<div class="ll_wzj">
            <ul>
            <li><strong style="color:red;">调用分类里的数据示例</strong></li>
            <?php 
            $cus_list = $this->advdbclass->get_cus_list($template, $menu['id'], 'c', 0, 10);
            if ($cus_list) {
            	foreach ($cus_list as $item) {
				$url = getBaseUrl($html, "{$item['html_path']}/{$item['id']}.html", "{$template}/detail/{$item['id']}.html", $client_index);
			?>
            <li><a href="<?php echo $url; ?>" title="<?php echo clearstring($item['title']); ?>"><font color="<?php echo $item['title_color']; ?>"><?php echo my_substr($item['title'], 40); ?></font></a></li>
            <?php }} ?>
            </ul>
            </div>
        </div>
        </div>
        <?php if (($key+1)%3 == 0 && count($itemMenuList) != ($key+1)) { ?>
        </div>
        <div class="clear"></div>
        <div class="nn_sk">
        <?php }}} ?>
        </div>
    </div>
<style>
.foot{ width:940px;_width:938px; height:152px; background:#000; margin:0 auto; margin-top:10px; padding-top:10px; padding-left:20px; padding-right:20px;}
.ls{ height:28px; color:#d0d0d0;  font-size:12px; background:#252324; line-height:28px; padding-left:10px;}
.ls span{ font-weight:bold; color:#959595;}
.ls a{ color:#CCC}
.logo_f{ width:197px; height:83px; float:left;}
.bq{ float:right; width:740px;_width:720px; height:83px;}
.mm{  margin-top:30px; text-align:center; line-height:30px; color:#414141;}
.mm a{font-size:12px; color:#999; text-decoration:none;}
</style>