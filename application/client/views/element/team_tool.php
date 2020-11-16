<script language="javascript" type="text/javascript" src="js/default/dedeajax2.js"></script>
<script src="js/default/jquery-1.2.6.min.js" type="text/javascript"></script>
<script src="js/default/photo-slide.js" type="text/javascript"></script>
	<script>
		$(function(){
			$('#slides').slides({
				preload: true,
				preloadImage: 'images/default/loading.gif',
				play: 5000,
				pause: 2500,
				hoverPause: true
			});
		});
	</script>
<div class="com1_right_pic">
		<div id="slide">
			<img alt="upload" src="images/default/loading1.gif" id="loading">
			<?php
		    $getAdList = $this->advdbclass->getAd(7);
		    if ($getAdList) {
		    foreach ($getAdList as $akey=>$ad) {
		    ?>  
			<a href="<?php echo $ad['url'] ?>" id="p<?php echo $akey+1; ?>" style="left: 0px;"><img alt="<?php echo clearstring($ad['ad_text']); ?>" src="<?php echo $ad['path'] ?>"></a>
			<?php }} ?>
		</div> <!-- end wrap -->
<div id="previous"><img src="images/default/arrow-prev.png"></div>
<div id="next"><img src="images/default/arrow-next.png"></div>
	</div>