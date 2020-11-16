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
<?php foreach ($itemList as $item) { ?>
    <li>
      <a class="title" title="<?php echo clearstring($item['title']); ?>"><?php echo my_substr($item['title'], 40); ?></a> 
     <p class="intro"><?php echo $item['abstract']; ?></p>
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
</div>
<?php echo $this->load->view('element/right_tool', NULL, TRUE); ?>
</div>
<div class="clear"></div>