<div class="centt">
		<div class="left1">
		<div class="left_zw">
        	<div class="left_zw_bt"><span>当前位置：</span><u><?php echo $location; ?>正文 </u></div>
            <div class="title"><h1><?php if ($itemInfo){ echo $itemInfo['title'];} ?></h1></div>
            <div class="bjsm">
            <span>更新日期：<?php if ($itemInfo){ echo date('Y-m-d H:i', $itemInfo['add_time']);} ?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span>作者：<?php if ($itemInfo){ echo $itemInfo['author'];} ?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span>来源：<?php if ($itemInfo){ echo $itemInfo['source'];} ?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span>点击：<?php if ($itemInfo){ echo $itemInfo['hits'];} ?>次</span>
            </div>
            
                       
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
			  <tr>
			    <td width="150" rowspan="5"><img src="<?php if ($itemInfo){if($itemInfo['path']){echo $itemInfo['path'];}else{echo 'uploads/noimagemax.jpg';}} ?>" width="130px" height="130px" /></td>
			    <td colspan="2" ><h1><?php if ($itemInfo){echo $itemInfo['title'];} ?></h1></td>
		      </tr>
			  <tr>
			    <td width="80" height="26"><span>推荐星级：</span></td>
			    <td><em><?php if ($itemInfo){if($itemInfo['mark'] == 1){echo '★☆☆☆☆';}else if($itemInfo['mark'] == 2){echo '★★☆☆☆';}else if($itemInfo['mark'] == 3){echo '★★★☆☆';}else if($itemInfo['mark'] == 4){echo '★★★★☆';}else if($itemInfo['mark'] == 5){echo '★★★★★';}} ?></em></td>
			  </tr>
			  <tr>
			    <td height="26"><span>下载次数：</span></td>
			    <td><em><?php if ($itemInfo){echo $itemInfo['download_time'];} ?></em></td>
			  </tr>
			  <tr>
			    <td height="26"><span>浏览次数：</span></td>
			    <td><em><?php if ($itemInfo){echo $itemInfo['hits'];} ?></em></td>
              </tr>
			  <tr>
			    <td height="26"><span>文件大小：</span></td>
			    <td><em><?php if ($itemInfo){echo $itemInfo['soft_size'].' '.$itemInfo['unit'];} ?></em></td>
			  </tr>
			</table>     
			  <ul class="info">			   
			    <li class="skim j-views"><span>适用固件：</span><em><?php if ($itemInfo){echo $itemInfo['platform'];} ?></em></li>
			    <li class="skim j-views"><span>软件语言：</span><em><?php if ($itemInfo){echo $itemInfo['language'];} ?></em></li>
			    <li class="skim j-views"><span>更新时间：</span><em><?php if ($itemInfo){echo date('Y-m-d H:i', $itemInfo['add_time']);} ?></em></li>
			    <li class="skim j-views"><span>软件格式：</span><em><?php if ($itemInfo){echo $itemInfo['file_type'];} ?></em></li>
			    <li class="skim j-views"><span>授权方式：</span><em><?php if ($itemInfo){echo $itemInfo['license'];} ?></em></li>		    
			    </ul>
			</div>
            </div> 
           </div> 
            
            
            <div class="nraa">
              <!-- /info -->
              <div class="daodu"><strong>导读：</strong>                  
                  <div class="intro"><?php if ($itemInfo){ echo $itemInfo['abstract'];} ?></div>
              </div>
  <div class="content"><strong>正文：</strong>
   <?php if ($itemInfo){ echo html($itemInfo['content']);} ?>
  </div>
    <!-- /content -->
  <div class="dede_pages">
   <ul class="pagelist">
    
   </ul>
  </div>
  <!-- /pages -->
          </div>
        </div>
         <div class="pny_next">
                 <ul>
                 <li class="pny_L">上一篇：
                 <?php if($prvInfo) {
				$url = getBaseUrl($html, "{$prvInfo['html_path']}/{$prvInfo['id']}.html", "{$template}/detail/{$prvInfo['id']}.html", $client_index);
			    ?>
    <a href='<?php echo $url; ?>' title="<?php echo clearstring($prvInfo['title']); ?>"><?php echo $prvInfo['title']; ?></a>
    <?php } else {echo '没有了';} ?>
                 </li>&nbsp;&nbsp;&nbsp;&nbsp;
                 <li class="puy_R">下一篇：
      <?php if($nextInfo){
	$url = getBaseUrl($html, "{$nextInfo['html_path']}/{$nextInfo['id']}.html", "{$template}/detail/{$nextInfo['id']}.html", $client_index);
    ?>           
                <a title="<?php echo $nextInfo['title']; ?>" href="<?php echo $url; ?>"><?php echo $nextInfo['title']; ?></a>
    <?php } else {echo '没有了';} ?>
     </li>
                 </ul>
         </div>
                 
                 
        <div class="clear"></div>
        	<div class="xgwz">
        	<div class="xgwz_bt">相关文章</div>
            <div class="xgnn">
            <ul>
            <?php if ($relationList) { ?>
			<?php foreach ($relationList as $item) {
				$url = getBaseUrl($html, "{$item['html_path']}/{$item['id']}.html", "{$template}/detail/{$item['id']}.html", $client_index);
			    ?>
            <li><a href="<?php echo $url; ?>" title="<?php echo clearstring($item['title']); ?>" target="_blank"><font color="<?php echo $item['title_color']; ?>"><?php echo my_substr($item['title'], 50); ?></font></a></li>
            <?php }} ?>
            </ul>
            </div>
        
        </div>        
        <!--ajax评论--><!-- //主模板必须要引入/include/dedeajax2.js -->
<a name='postform'></a>
</div>
<?php echo $this->load->view('element/right_tool', NULL, TRUE); ?>
</div>
<div class="clear"></div>