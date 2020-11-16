<?php echo $tool; ?>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" id="jsonForm" >
<table class="table_form" cellpadding="0" cellspacing="1">
  <caption>基本信息</caption>
 	<tbody>
	<tr>
      <th width="20%">
      <font color="red">*</font> <strong>品牌名称</strong> <br/>
	  </th>
      <td>
      <input valid="required" errmsg="品牌名称不能为空!" name="brand_name" id="brand_name" value="<?php if(! empty($item_info)){ echo $item_info['brand_name'];} ?>" size="80" type="text">
      <br/><font color="red">注：中间加“|”分隔符可以实现批量添加，格式如：“韩都衣舍|南极人|秋水伊人|初语|欧时力”</font>
	</td>
    </tr>
    <tr>
      <th width="20%">
      <strong>首字母</strong> <br/>
	  </th>
      <td>
      <input name="first_letter" id="first_letter" value="<?php if(! empty($item_info)){ echo $item_info['first_letter'];} ?>" size="20" type="text">
      <br/><font color="red">注：默认不用手动填写，在识别不了的情况下，请手动填写，只对修改起作用</font>
      </td>
    </tr>
    <tr>
      <th width="20%">
      <strong>标签</strong> <br/>
	  </th>
      <td>
      <input name="tag" id="tag" value="<?php if(! empty($item_info)){ echo $item_info['tag'];} ?>" size="20" type="text">
      </td>
    </tr>
    <tr>
      <th width="20%"><strong>排序</strong> <br/>
	  </th>
      <td>
      <input name="sort" id="sort" value="<?php if(! empty($item_info)){ echo $item_info['sort'];}else{ echo '0';} ?>" size="20" type="text">
	</td>
    </tr>
    <tr>
        <th width="20%">
           <strong>图片</strong>
        </th>
        <td>
                <a id="path_src_a" title="点击查看大图" href="<?php if ($item_info && $item_info['path']){echo $item_info['path'];}else{echo 'images/admin/no_pic.png';} ?>" target="_blank" style="float:left;"><img id="path_src" width="60px" src="<?php if ($item_info && $item_info['path']){echo preg_replace('/\./', '_thumb.', $item_info['path']);}else{echo 'images/admin/no_pic.png';} ?>" onerror="javascript:this.src='images/admin/no_pic.png';" /></a>

                <div style="float:left; margin-top:22px;">
                <a style=" position:relative; width:auto; " >
		        <span style="cursor:pointer;" class="but_4">上传照片<input style="left:0px;top:0px; background:#000; width:100%;height:36px;line-height:36px; position:absolute;filter:alpha(opacity=0);-moz-opacity:0;opacity:0;" type="file" accept=".gif,.jpg,.jpeg,.png" id="path_file" name="path_file" ></span>
		        <i class="load" id="path_load" style="cursor:pointer;display:none;width:auto;padding-left:0px; left:50%; margin-left:-16px;"><img src="images/admin/loading_2.gif" width="32" height="32"></i>
		       </a>

		       <input value="<?php if ($item_info){echo $item_info['path'];} ?>" type="hidden" id="path" name="path">
		       <input name="model" id="model"  value="<?php echo $table; ?>" type="hidden" />
               <span id="cut_image" style="cursor:pointer;" class="but_4">裁剪图片</span>
               <?php $image_size_arr = get_image_size($table);
                     if ($image_size_arr) {
               ?>
               <span style="color:#9c9c9c;margin-left:30px;">注：缩略图大小＝<?php echo $image_size_arr['width']; ?>x<?php echo $image_size_arr['height']; ?></span>
               <?php } ?>
               </div>
       </td>
    </tr>
    <tr>
      <th width="20%">
      <font color="red">*</font> <strong>产品分类</strong> <br/>
	</th>
      <td>
<style type="text/css">
.shop-cat-list {
    border: 1px solid #E4E4E4;
    height: 135px;
    margin-right: 10px;
	margin-bottom: 5px;
    overflow: auto;
    padding: 3px;
    width: 637px;
}
input.checkbox {
    background: none repeat scroll 0 0 transparent;
    height: 18px;
    margin: 0 4px 0 5px;
    vertical-align: middle;
}

.form li span li, .form li span .module-property li span li {
    clear: none;
    float: left;
    margin: 0 20px 0 0;
    white-space: nowrap;
}
.shop-cat-list li {
    float: none !important;
    margin: 0 !important;
}
li {
    list-style: none outside none;
}
.category2{
	margin: 0;
    padding: 0 0 0 20px;
}
.category3{
	margin: 0;
    padding: 0 0 0 40px;
}
</style>
      <div class="shop-cat-list">
		<!-- feilv 2012-03-02 店铺分类新加钩子J_ShopCatList -->
		<?php if (! empty($product_category_list)) { ?>
		<ul class="J_ShopCatList">
			<!-- 一级 -->
			<?php foreach ($product_category_list as $menu) {
			    if ($menu['subMenuList']) {
				?>
			<li>
				<?php echo $menu['product_category_name']; ?>
				<ul class="category2">
				<!-- 二级 -->
		        <?php foreach ($menu['subMenuList'] as $subMenu) { ?>
		        <li>
		        	<input <?php if(! empty($item_info)){if(myInArray($subMenu['id'], $bci_info)){echo "checked=true";}} ?> type="checkbox" value="<?php echo $subMenu['parent_id'].','.$subMenu['id']; ?>" name="product_category_id[]" class="checkbox select_product_class">
		        	<label for="shopCatId285432655"><?php echo $subMenu['product_category_name']; ?></label>
		        </li>
		        <?php } ?>
				</ul>
			</li>
			<?php } else { ?>
			<li>
				<input <?php if(! empty($item_info)){if(myInArray($menu['id'], $bci_info)){echo "checked=true";}} ?> type="checkbox" value="<?php echo $menu['parent_id'].','.$menu['id']; ?>" name="product_category_id[]" class="checkbox select_product_class">
				<label for="shopCatId411110266"><?php echo $menu['product_category_name']; ?></label>
			</li>
			<?php }} ?>
			</ul>
			<?php } ?>
		</div>
		<a href="admincp.php/product_category">点我添加产品分类</a>&nbsp;<font color="red">注：选择与品牌关联的产品分类</font>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>
	  <input class="button_style" name="dosubmit" value=" 保存 " type="submit">
	  &nbsp;&nbsp; <input onclick="javascript:window.location.href='<?php echo $prfUrl; ?>';" class="button_style" name="reset" value=" 返回 " type="button">
	  </td>
    </tr>
</tbody>
</table>
</form>
<script type="text/javascript">
//参数mulu
$(function () {
	//形象照片
	$("#path_file").wrap("<form id='path_upload' action='<?php echo base_url(); ?>admincp.php/upload/uploadImage2' method='post' enctype='multipart/form-data'></form>");
    $("#path_file").change(function(){ //选择文件
		$("#path_upload").ajaxSubmit({
			dataType:  'json',
			data: {
                'model': 'brand',
                'field': 'path_file'
            },
			beforeSend: function() {
            	$("#path_load").show();
    		},
    		uploadProgress: function(event, position, total, percentComplete) {
    		},
			success: function(res) {
    			$("#path_load").hide();
    			if (res.success) {
    				$("#path_src_a").attr("href", res.data.file_path);
      			    $("#path_src").attr("src", res.data.file_path.replace('.', '_thumb.')+"?"+res.data.field);
      			    $("#path").val(res.data.file_path);
        		} else {
        			var d = dialog({
        				fixed: true,
    				    title: '提示',
    				    content: res.message
    				});
    				d.show();
    				setTimeout(function () {
    				    d.close().remove();
    				}, 2000);
            	}
			},
			error:function(xhr){
			}
		});
	});
});
load_image();
function load_image() {
	var path = $('#path').val();
	if (path) {
		$("#path_src_a").attr("href", path);
	    $("#path_src").attr("src", path.replace('.', '_thumb.'));
	}
}
</script>