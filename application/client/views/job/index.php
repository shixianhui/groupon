<div class="centt">
		<div class="left1">
		<div class="left_zw">
        	<div class="left_zw_bt"><span>当前位置：</span><u><?php echo $location; ?>列表 </u></div>            	
                <div class="xgzix">
                	<div class="xgzix_bt">相关资讯</div>
                    <div class="xg_nn">   
   <!-- /listbox -->
<div class="dede_pages">
<ul class="pagelist">
<?php if ($pagination) {echo $pagination;} ?>
<li>
<select name='sldd' style='width:36px' onchange='location.href=this.options[this.selectedIndex].value;'>
<?php for ($i = 0; $i < $pageCount; $i++) {?>
<option <?php if(($this->uri->segment(4)/$perPage+1) == ($i+1)){echo 'selected="selected"';} ?> value='index.php/news/index/83/<?php echo $i*$perPage;  ?>.html'><?php echo ($i+1); ?></option>
<?php } ?>
</select>
</li>
<li><span class="pageinfo">共 <strong><?php if ($pageCount){echo $pageCount;} ?></strong> 页<strong><?php if ($paginationCount){echo $paginationCount;} ?></strong> 条</span></li>
</ul>
</div>
<!-- /pages --> 
                       
    <div class="listbox">
   <ul class="e2">
<?php if ($itemList) { ?>
<?php foreach ($itemList as $item) {
	$url = getBaseUrl($html, "{$item['html_path']}/{$item['id']}.html", "{$template}/detail/{$item['id']}.html", $client_index);
?>
    <li> <a href='<?php echo $url; ?>' class='preview'><img src='<?php echo preg_replace('/\./', '_thumb.', $item['path']); ?>'/></a>
      <a href="<?php echo $url; ?>" class="title"><?php echo $item['title']; ?></a> 
     <p class="intro"><?php echo $item['abstract']; ?></p>
     <span class="info"><small>日期：</small><?php echo date('Y-m-d H:i:s', $item['add_time']); ?> <small>点击：</small><?php echo $item['hits']; ?></span>
    </li>
<?php }} ?>
   </ul>
  </div>
  <!-- /listbox -->
  <div class="dede_pages">
   <ul class="pagelist">
   <?php if ($pagination) {echo $pagination;} ?>
	<li>
	<select name='sldd' style='width:36px' onchange='location.href=this.options[this.selectedIndex].value;'>
	<?php for ($i = 0; $i < $pageCount; $i++) { ?>
	<option <?php if(($this->uri->segment(4)/$perPage+1) == ($i+1)){echo 'selected="selected"';} ?> value='index.php/news/index/83/<?php echo $i*$perPage;  ?>.html'><?php echo ($i+1); ?></option>
	<?php } ?>
	</select>
	</li>
	<li><span class="pageinfo">共 <strong><?php if ($pageCount){echo $pageCount;} ?></strong> 页<strong><?php if ($paginationCount){echo $paginationCount;} ?></strong> 条</span></li>
   </ul>
  </div>
  <!-- /pages -->
     </div>
    </div>       
        </div>
        <div class="clear"></div>
        <div class="tuij">
       <div class="tuij_bt"><strong>MEMORY WEDDING -推荐阅读</strong></div>
          <div class="tjnn">
           <ul>

                </ul>
            </div>
        </div>

</div>
<?php echo $this->load->view('element/right_tool', NULL, TRUE); ?>
</div>
<div class="clear"></div>