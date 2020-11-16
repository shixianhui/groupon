<div class="cc_gs">	
        <div class="nn_lr">
        <div class="l_nn">
        <div class="mm_h"><img src="images/default/mmh.gif"></div>
        <div class="nav_cd">
        <ul>
   <?php if ($itemList) {
	foreach ($itemList as $item) {
	if ($item['url']) {
		$url = $item['url'];
	} else {
		$url = getBaseUrl($html, "{$item['html_path']}/{$item['id']}.html", "page/index/{$item['category_id']}/{$item['id']}.html", $client_index);
	}
   ?>
        <li><a href="<?php echo $url; ?>"><?php echo $item['title']; ?></a></li>
    <?php }} ?>
        </ul>
        </div>        
        </div>
        <div class="r_nn">
        	<div class="wz_bt">当前位置：<?php echo $location; ?><?php if ($itemInfo){echo $itemInfo['title'];} ?></div>
            <div class="js_nnz">
            <?php if ($itemInfo){echo html($itemInfo['content']);} ?>
			</div>
        </div>
        </div>
</div>