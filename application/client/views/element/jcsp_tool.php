<div class="jcsp"><h2>精彩视频</h2>
            <div class="jc_nn">
            <?php if ($hotVideoList) {
	           foreach ($hotVideoList as $hkey=>$video) {
		          $url = getBaseUrl($html, "{$video['html_path']}/{$video['id']}.html", "video/detail/{$video['id']}.html", $client_index);
           ?>
              <div class="othor">
                <div style="font-size:14px;font-weight:bold; color:#FFFFFF;padding-left:4px;width:16px;float:left;"><?php echo $hkey+1; ?></div>
                <div class="pic_tt"><a title="<?php echo clearstring($video['title']); ?>" href="<?php echo $url; ?>"><img src='<?php echo preg_replace('/\./', '_thumb.', $video['path']); ?>' border='0' width='110' height='60' alt='<?php echo clearstring($video['title']); ?>'></a></div>
                <div class="o_wz"><span><a title="<?php echo clearstring($video['title']); ?>" href="<?php echo $url; ?>"><?php echo my_substr($video['title'], 10, ''); ?></a></span><?php echo my_substr($video['abstract'], 24); ?></div>
              </div>
              <div class="line"></div>
              <?php }} ?>
            </div>
            </div>