<?php echo $tool; ?>
<style type="text/css">
.size_color_list th {
    background-color: #EDEDED;
    border: 1px solid #D7D7D7;
    font-weight: 400;
    height: 25px;
    text-align: center;
    vertical-align: middle;
    min-width:60px;
}
.size_color_list td {
    border: 1px solid #D7D7D7;
    height: 25px;
    max-width: 200px;
    min-width: 60px;
    padding: 3px 5px;
    text-align: center;
    vertical-align: middle;
}
</style>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" id="jsonForm" >
<div class="tag_menu" style="width: 99%; margin-top: 10px;display: none">
<ul>
  <li><a href="javascript:void(0);" id="basic" class="selected">产品信息</a></li>
<!--  <li><a href="javascript:void(0);" id="advanced" >产品描述</a></li>-->
<!--  <li><a href="javascript:void(0);" id="appAdvanced" >App产品描述</a></li>-->
<!--  <li><a href="javascript:void(0);" id="kefu" >营销选项</a></li>-->
<!--  <li><a href="javascript:void(0);" id="chouzhi" >备注说明</a></li>-->
</ul>
</div>
<!--<div id="basics" style="border-top:2px solid #99d3fb;" >-->
<table class="table_form" cellpadding="0" cellspacing="1">
  <caption>基本信息</caption>
 	<tbody>
 	<tr>
      <th width="20%">
      <font color="red">*</font> <strong>商品标题</strong> <br/>
	  </th>
      <td>
      <input name="title" id="title" value="<?php if(! empty($item_info)){ echo $item_info['title'];} ?>" size="80" maxlength="100" valid="required" errmsg="产品名称不能为空!" class="inputtitle input_blur" type="text">
	</td>
    </tr>
    <tr>
        <th width="20%"> <strong>商品简介</strong> <br/>
        </th>
        <td>
            <input name="content_short" id="content_short" value="<?php if(! empty($item_info)){ echo $item_info['content_short'];} ?>" type="text" size="50"/>
        </td>
    </tr>
    <tr style="display: none">
      <th width="20%"><font color="red">*</font> <strong>快递模板</strong> <br/>
	  </th>
      <td>
      <?php if ($postage_way_list) { ?>
      <?php foreach ($postage_way_list as $key=>$value) { ?>
     <label><input <?php if (($key+1) == count($postage_way_list)) {echo 'valid="requireChecked" errmsg="请选择快递模板"';} ?> type="radio" value="<?php echo $value['id'] ?>" name="postage_way_id" class="radio_style" <?php if($item_info){if($item_info['postage_way_id']==$value['id']){echo 'checked="checked"';}} ?> > <?php echo $value['title']; ?></label>
      <?php }} ?>
      </td>
    </tr>
 	<tr style="display: none">
      <th width="20%"> <strong>品牌</strong> <br/></th>
      <td style="position:relative ;">
      <input onkeyup="javascript:get_brand_list(this);" onclick="javascript:get_brand_list(this);" autocomplete="off" name="brand_name" id="brand_name" value="<?php if(! empty($item_info)){ echo $item_info['brand_name'];} ?>" style="width: 200px;" maxlength="50" class="input_blur" type="text">
      <select onchange="javascript:select_brand_val(this);" id="select_brand" class="select_brand" size="6" style="width: 210px;display: none; position:absolute;top:28px; left:0px;z-index: 999;">
	  <?php if ($brand_item_list) { ?>
	  <?php foreach ($brand_item_list as $key=>$value) { ?>
	  <option value="<?php echo $value['brand_name'];  ?>"><?php echo $value['brand_name'];  ?></option>
	  <?php }} ?>
	  </select>
      </td>
    </tr>
    <tr style="display: none">
      <th width="20%"><strong>规格</strong> </th>
      <td>
      <input type="hidden" name="color_size_open" id="color_size_open" value="<?php if(! empty($item_info)){ echo $item_info['color_size_open'];}else{echo '0';} ?>" />
      <button onclick="javascript:change_color_size_open(1);" id="color_size_open_btn" type="button" class="button_style" style="margin-top: 5px;<?php if(! empty($item_info) && $item_info['color_size_open']){ echo 'display:none;';} ?>">开启规格</button>
      <button onclick="javascript:change_color_size_open(0);" id="color_size_close_btn" type="button" class="button_style" style="margin-top: 5px;<?php if(! empty($item_info) && $item_info['color_size_open']){ echo '';}else{echo 'display:none;';} ?>">关闭规格</button>
      <div id="size_color_list" class="size_color_list" style="height: auto; width: auto; overflow: auto;<?php if(! empty($item_info) && $item_info['color_size_open']){ echo '';}else{echo 'display:none;';} ?>">
      <!-- 颜色 -->
      <table id="color_table" class="size_color_list" style="width: 700px;margin-top:10px;" cellspacing="0" border="0">
      <tr>
	    <th colspan="4"><input name="product_color_name" id="product_color_name" value="<?php if ($item_info && $item_info['product_color_name']) {echo $item_info['product_color_name'];}else{echo '颜色';} ?>" style="text-align: center;" class="input_blur" type="text"></th>
	   </tr>
	   <?php $color_index = 1;  ?>
	   <?php if ($color_list) { ?>
	   <?php foreach ($color_list as $key=>$value) {
	   	         $color_index = max($value['color_id'], $color_index)+1;
	   	?>
	   <tr class="color_list">
	    <td width="10%">
	    <input onclick="javascript:get_size_color_list(this, 'color');"  class="checkbox_style" name="attribute_color_ids[]" value="<?php echo $value['color_id']; ?>" checked="checked" type="checkbox">
	    </td>
	    <td>
	    <input onchange="javascript:change_color(this);" name="attribute_color_name[]" placeholder="请输入主属性" size="40" maxlength="60" class="input_blur" value="<?php echo $value['color_name']; ?>" type="text">
	    </td>
	    <td width="25%">
	    <input name="attribute_color_hint[]" placeholder="主属性备注" size="20" maxlength="60" class="input_blur" value="<?php echo $value['color_name_hint']; ?>" type="text">
	    </td>
	    <td width="20%">
	    <a class="good_url_path_file_src_a" title="点击查看大图" href="<?php echo $value['path']; ?>" target="_blank" style="float:left;"><img class="good_url_path_file_src" width="30px" height="30px" src="<?php if ($value['path']) { echo preg_replace('/\./', '_thumb.', $value['path']);}else{echo 'images/admin/no_pic.png';} ?>" onerror="javascript:this.src='images/admin/no_pic.png';" /></a>

        <div style="float:left; margin-top:0px;">
           <a style=" position:relative; width:auto; " >
		   <span style="cursor:pointer;font-size:12px;height:30px;" class="but_4">传图<input style="left:0px;top:0px; background:#000; width:100%;height:25px;line-height:25px; position:absolute;filter:alpha(opacity=0);-moz-opacity:0;opacity:0;" type="file" accept=".gif,.jpg,.jpeg,.png" class="good_url_path_file" onchange="javascript:file_change_upload(this);" name="good_url_path_file" ></span>
		   <i class="load good_url_path_file_load" style="cursor:pointer;display:none;width:auto;padding-left:0px; left:50%; margin-left:-16px;"><img src="images/admin/loading_2.gif" width="32" height="32"></i>
		   </a>
            <input value="<?php echo $value['path']; ?>" type="hidden" name="attribute_path[]">
		 </div>
	    </td>
	   </tr>
	   <?php }} ?>
	   <tr class="color_list">
	    <td width="10%">
	    <input onclick="javascript:get_size_color_list(this, 'color');"  class="checkbox_style" name="attribute_color_ids[]" value="<?php echo $color_index; ?>" type="checkbox">
	    </td>
	    <td>
	    <input onchange="javascript:change_color(this);" name="attribute_color_name[]" placeholder="请输入主属性" size="40" maxlength="60" class="input_blur" type="text">
	    </td>
	    <td width="25%">
	    <input name="attribute_color_hint[]" placeholder="主属性备注" size="20" maxlength="60" class="input_blur" type="text">
	    </td>
	    <td width="20%">
	    <a class="good_url_path_file_src_a" title="点击查看大图" href="images/admin/no_pic.png" target="_blank" style="float:left;"><img class="good_url_path_file_src" width="30px" height="30px" src="images/admin/no_pic.png" onerror="javascript:this.src='images/admin/no_pic.png';" /></a>

        <div style="float:left; margin-top:0px;">
           <a style=" position:relative; width:auto; " >
		   <span style="cursor:pointer;font-size:12px;height:30px;" class="but_4">传图<input style="left:0px;top:0px; background:#000; width:100%;height:25px;line-height:25px; position:absolute;filter:alpha(opacity=0);-moz-opacity:0;opacity:0;" type="file" accept=".gif,.jpg,.jpeg,.png" class="good_url_path_file" onchange="javascript:file_change_upload(this);" name="good_url_path_file" ></span>
		   <i class="load good_url_path_file_load" style="cursor:pointer;display:none;width:auto;padding-left:0px; left:50%; margin-left:-16px;"><img src="images/admin/loading_2.gif" width="32" height="32"></i>
		   </a>
            <input value="" type="hidden" name="attribute_path[]">
		 </div>
	    </td>
	   </tr>
	   </table>
	   <!-- 尺码 -->
	  <table id="size_table" class="size_color_list" style="width: 700px;margin-top:10px;" cellspacing="0" border="0">
      <tr>
	    <th colspan="3"><input name="product_size_name" id="product_size_name" value="<?php if ($item_info && $item_info['product_size_name']) {echo $item_info['product_size_name'];}else{echo '尺码';} ?>" class="input_blur" style="text-align: center;" type="text"></th>
	   </tr>
	   <?php $size_index = 1;  ?>
	   <?php if ($size_list) { ?>
	   <?php foreach ($size_list as $key=>$value) {
	   	         $size_index = max($value['size_id'], $size_index)+1;
	   	?>
	   <tr class="size_list">
	    <td width="10%">
	    <input onclick="javascript:get_size_color_list(this, 'size');"  class="checkbox_style" name="attribute_size_ids[]" value="<?php echo $value['size_id']; ?>" checked="checked" type="checkbox">
	    </td>
	    <td>
	    <input onchange="javascript:change_size(this);" name="attribute_size_name[]" placeholder="请输入主属性" size="60" maxlength="60" class="input_blur" value="<?php echo $value['size_name']; ?>" type="text">
	    </td>
	    <td width="20%">
	    <input name="attribute_size_hint[]" placeholder="主属性备注" size="15" maxlength="60" class="input_blur" value="<?php echo $value['size_name_hint']; ?>" type="text">
	    </td>
	   </tr>
	   <?php }} ?>
	   <tr class="size_list">
	    <td width="10%">
	    <input onclick="javascript:get_size_color_list(this, 'size');"  class="checkbox_style" name="attribute_size_ids[]" value="<?php echo $size_index; ?>" type="checkbox">
	    </td>
	    <td>
	    <input onchange="javascript:change_size(this);" name="attribute_size_name[]" placeholder="请输入主属性" size="60" maxlength="60" class="input_blur" type="text">
	    </td>
	    <td width="20%">
	    <input name="attribute_size_hint[]" placeholder="主属性备注" size="15" maxlength="60" class="input_blur" type="text">
	    </td>
	   </tr>
	   </table>
	   <!-- 颜色-尺码 -->
      <table style="width: 700px;margin-top:10px;" id="table_size_color" cellspacing="0" border="0">
	  <tr>
		 <th>颜色</th>
		 <th>尺码</th>
		 <th>价格</th>
		 <th>库存</th>
		 <th>货号</th>
	   </tr>
	   <?php if ($color_size_list) { ?>
	   <?php foreach ($color_size_list as $key=>$value) { ?>
	   <?php foreach ($value['size_list'] as $s_key=>$s_value) { ?>
	   <tr class="color_index_<?php echo $value['color_id'] ?>">
	    <?php if ($s_key == 0) { ?>
    	<td rowspan="<?php echo count($value['size_list']); ?>"><?php echo $value['color_name']; ?></td>
    	<?php } ?>
    	<td class="size_index_<?php echo $s_value['size_id']; ?>"><?php echo $s_value['size_name']; ?></td>
    	<td><input value="<?php echo $s_value['price']; ?>" onchange="javascript:change_price(this);" size="10" type="text" name="attribute_price[]"></td>
    	<td><input value="<?php echo $s_value['stock']; ?>" onchange="javascript:change_stock(this);" size="10" type="text" name="attribute_stock[]"></td>
    	<td><input value="<?php echo $s_value['product_num']; ?>" size="10" type="text" name="attribute_product_num[]"></td>
    	</tr>
	   <?php }}} ?>
      </table>
      </div>
      </td>
    </tr>
    <tr>
      <th width="20%"><font color="red">*</font> <strong>出售价</strong> <br/>
	  </th>
      <td>
      <input name="sell_price" id="sell_price" value="<?php if(! empty($item_info)){ echo $item_info['sell_price'];} ?>"  type="text" valid="required|isMoney" errmsg="出售价不能为空!|请填写正确的出售价格!"/> <font color="red">元</font>
      </td>
    </tr>
 	<tr>
      <th width="20%"><font color="red">*</font> <strong>市场价</strong> <br/>
	  </th>
      <td>
      <input name="market_price" id="market_price" value="<?php if(! empty($item_info)){ echo $item_info['market_price'];} ?>"  type="text" valid="required|isMoney" errmsg="市场价不能为空!|市场价必须为数字!"/> <font color="red">元</font>
      </td>
    </tr>
    <tr>
      <th width="20%"><font color="red">*</font> <strong>库存数量</strong> <br/>
	  </th>
      <td>
      <input name="stock" id="stock" value="<?php if(! empty($item_info)){ echo $item_info['stock'];} ?>"  type="text" valid="required|isNumber" errmsg="库存不能为空!|库存必须为数字!"/>
      </td>
    </tr>
    <tr style="display: none">
      <th width="20%"><strong>产品重量</strong> <br/>
	  </th>
      <td>
      <input name="weight" id="weight" value="<?php if(! empty($item_info)){ echo $item_info['weight'];}else{echo '0';} ?>"  type="text" valid="isNumber" errmsg="重量必须为数字!"/> <font color="red">克(g)</font>
      </td>
    </tr>

    <tr>
      <th width="20%"><font color="red">*</font> <strong>产品编号</strong> <br/>
	  </th>
      <td>
      <input name="product_num" id="product_num" value="<?php if(! empty($item_info)){ echo $item_info['product_num'];}else{echo $tmp_product_num;} ?>" valid="required" errmsg="产品编号不能为空!" type="text"/>
      </td>
    </tr>
 	<tr style="display: none">
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
		        	<input <?php if(! empty($item_info)){if(myInArray($subMenu['id'], $pci_info)){echo "checked=true";}} ?> type="checkbox" value="<?php echo $subMenu['parent_id'].','.$subMenu['id']; ?>" name="product_category_id[]" class="checkbox select_product_class">
		        	<label for="shopCatId285432655"><?php echo $subMenu['product_category_name']; ?></label>
		        </li>
		        <?php } ?>
				</ul>
			</li>
			<?php } else { ?>
			<li>
				<input <?php if(! empty($item_info)){if(myInArray($menu['id'], $pci_info)){echo "checked=true";}} ?> type="checkbox" value="<?php echo $menu['parent_id'].','.$menu['id']; ?>" name="product_category_id[]" class="checkbox select_product_class">
				<label for="shopCatId411110266"><?php echo $menu['product_category_name']; ?></label>
			</li>
			<?php }} ?>
			</ul>
			<?php } ?>
		</div>
		<a href="admincp.php/product_category">点我添加产品分类</a>&nbsp;
      </td>
    </tr>
  <tr>
    <th width="20%">
    <font color="red">*</font> <strong>商品主图</strong> <br/>
	</th>
    <td>
    <input name="model" id="model"  value="product" type="hidden" />
    <input name="path" id="path" size="50" class="input_blur" value="<?php if(! empty($item_info)){ echo $item_info['path'];} ?>"  type="text" />
    <input class="button_style" name="upload_image" id="upload_image1" value="上传图片" style="width: 60px;"  type="button" />  <input class="button_style" value="浏览..."
style="cursor: pointer;" name="select_image" id="select_image" type="button" /> <input class="button_style" style="cursor: pointer;"  name="cut_image" id="cut_image" value="裁剪图片" type="button"  />
    </td>
    </tr>
    <tr>
      <th width="20%">
      <strong>商品轮播图</strong> <br/>
	  </th>
      <td>
    <input name="batch_path_ids" id="batch_path_ids" size="50" class="input_blur" value="<?php if(! empty($item_info)){ echo $item_info['batch_path_ids'];} ?>" type="text" />
    <input class="button_style" name="batch_upload_image" id="batch_upload_image" value="批量添加" style="width: 60px;"  type="button" />
    </td>
    </tr>
    <tr>
        <th width="20%"> <strong>名称</strong> <br/>
        </th>
        <td>
            <input name="name" id="name" value="<?php if(! empty($item_info)){ echo $item_info['name'];} ?>" type="text"/>
        </td>
    </tr>
    <tr>
        <th width="20%"> <strong>型号</strong> <br/>
        </th>
        <td>
            <input name="model" id="model" value="<?php if(! empty($item_info)){ echo $item_info['model'];} ?>" type="text"/>
        </td>
    </tr>
    <tr>
        <th width="20%"> <strong>功率（KW）</strong> <br/>
        </th>
        <td>
            <input name="power" id="power" value="<?php if(! empty($item_info)){ echo $item_info['power'];} ?>" type="text"/>
        </td>
    </tr>
    <tr>
        <th width="20%"> <strong>尺寸（长*宽*厚）</strong> <br/>
        </th>
        <td>
            <input name="size" id="size" value="<?php if(! empty($item_info)){ echo $item_info['size'];} ?>" type="text"/>
        </td>
    </tr>
    <tr>
        <th width="20%"> <strong>适用面积</strong> <br/>
        </th>
        <td>
            <input name="suit_area" id="suit_area" value="<?php if(! empty($item_info)){ echo $item_info['suit_area'];} ?>" type="text"/>
        </td>
    </tr>

    <tr>
        <th width="10%"><font color="red">*</font> <strong>商品描述</strong></th>
        <td>
            <?php echo $this->load->view('element/ckeditor_tool', NULL, TRUE); ?>
            <script id="content" name="content" type="text/plain" style="width:980px;height:600px;"><?php if(! empty($item_info)){ echo html($item_info["content"]);}else{echo "";} ?></script>
            <script type="text/javascript">
            var ue = UE.getEditor('content');
            </script>
        </td>
    </tr>
    <tr>
      <th width="20%"> <strong>状态</strong> <br/>
	  </th>
      <td><?php if(! empty($item_info)){ echo date('Y-m-d H:i:s', $item_info['display_time']);} ?>
      <select name="display" id="display">
      <?php if ($display_arr) { ?>
      <?php foreach ($display_arr as $key=>$value) {
            $selector = '';
            if($item_info) {
            	if ($item_info['display'] == $key) {
            		$selector = 'selected="selected"';
            	}
            }
      	?>
       <option <?php echo $selector; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
      <?php }} ?>
      </select>
      </td>
    </tr>
    <tr style="display: none">
      <th width="20%"> <strong>销售量</strong> <br/>
	  </th>
      <td>
      <input name="sales" id="sales" value="<?php if(! empty($item_info)){ echo $item_info['sales'];}else{echo '0';} ?>"  type="text" valid="isNumber" errmsg="浏览次数必须为数字!"/>
      </td>
    </tr>
    <tr style="display: none">
      <th width="20%"> <strong>浏览次数</strong> <br/>
	  </th>
      <td>
      <input name="hits" id="hits<?php if(! empty($item_info)){ echo $item_info['hits'];} ?>" value="<?php if(! empty($item_info)){ echo $item_info['hits'];} ?>"  type="text" valid="isNumber" errmsg="浏览次数必须为数字!"/>
      </td>
    </tr>
    <tr style="display: none">
    <th width="20%">
    <strong>自定义属性</strong> <br/>
	</th>
    <td>
        <?php
           foreach($attribute_arr as $key=>$item){
        ?>
        <label><input class="checkbox_style" name="custom_attribute[]" id="<?php echo $key;?>"  value="<?php echo $key;?>" <?php if(! empty($item_info)){if(substr_count($item_info['custom_attribute'],$key)>0){echo "checked=true";}} ?> type="checkbox"/> <?php echo $item."[$key]";?></label>
           <?php }?>

	</td>
    </tr>
	<tr>
      <th width="20%"> <strong>发布时间</strong> <br/>
	  </th>
      <td>
	<input class="input_blur" name="add_time" id="add_time" value="<?php if(! empty($item_info)){ echo date('Y-m-d H:i:s', $item_info['add_time']);}else{  echo date('Y-m-d H:i:s', time()); } ?>" size="21" readonly="readonly" type="text"/>
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
</div>
<div id="advanceds" style="display: none;border-top:2px solid #99d3fb;">
<table class="table_form" cellpadding="0" cellspacing="1">
  <caption>基本信息</caption>
 	<tbody>

    <tr>
      <td>&nbsp;</td>
      <td>
	  <input class="button_style" name="dosubmit" value=" 保存 " type="submit">
	  &nbsp;&nbsp; <input onclick="javascript:window.location.href='<?php echo $prfUrl; ?>';" class="button_style" name="reset" value=" 返回 " type="button">
	  </td>
    </tr>
 	</tbody>
</table>
</div>
    <div id="appAdvanceds" style="display: none;border-top:2px solid #99d3fb;">
<table class="table_form" cellpadding="0" cellspacing="1">
  <caption>基本信息</caption>
 	<tbody>
 	<tr>
      <th width="10%"><font color="red">*</font> <strong>App产品描述</strong></th>
      <td>
	<script id="app_content" name="app_content" type="text/plain" style="width:980px;height:600px;"><?php if(! empty($item_info)){ echo html($item_info["app_content"]);}else{echo "";} ?></script>
	<script type="text/javascript">
	    var ue = UE.getEditor('app_content');
	</script>
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
</div>
<div id="kefus" style="display: none;border-top:2px solid #99d3fb;">
<table class="table_form" cellpadding="0" cellspacing="1">
  <caption>基本信息</caption>
 	<tbody>
 	<tr>
      <th width="20%"> <strong>SEO关键词</strong> <br/>
	  多关键词之间用逗号隔开
	  </th>
      <td>
      <input name="keyword" id="keyword" size="50"  value="<?php if(! empty($item_info)){ echo $item_info['keyword'];} ?>"  maxlength="100" class="input_blur" type="text" />
      </td>
    </tr>
	<tr>
      <th width="20%"> <strong>SEO描述</strong> <br/>
	  </th>
      <td>还可以输入 <font id="ls_description" color="#ff0000">255</font> 个字符！<br/>
      <textarea name="abstract" id="abstract" rows="4" cols="50"  class="textarea_style" style="width: 80%;" ><?php if(! empty($item_info)){ echo $item_info['abstract'];} ?></textarea>
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
</div>
<div id="chouzhis" style="display: none;border-top:2px solid #99d3fb;" >
<table class="table_form" cellpadding="0" cellspacing="1">
  <caption>备注说明</caption>
 	<tbody>
	<tr>
      <th width="20%"> <strong>备注说明</strong> <br/>
	  </th>
      <td>
      <textarea maxlength="140" name="remark" id="remark" rows="4" cols="50"  class="textarea_style" style="width: 80%;" ><?php if(! empty($item_info)){ echo $item_info['remark'];} ?></textarea>
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
</div>
</form>
<br/>
<br/>
<script type="text/javascript">
    $("#upload_image1").click(function(){
        var imagePath = $("#path").val();
        var model = 'product_1';
        var parseImagePath = imagePath.replace(/\//g, ":");
        parseImagePath = parseImagePath.replace(/\./g, "_");
        var d = dialog({
            fixed: true,
            title: '上传图片：',
            content: '<iframe frameborder="0" src="'+base_url+'admincp.php/upload/index/'+model+'/'+parseImagePath+'" width="680" height="400"></iframe>'
        });
        d.show();
    });
	function select_product_type() {
        var product_type = $('input[name="product_type"]:checked').val();
        if (product_type == 'a') {
            $('.give_score').html('送银象积分数量');
        } else if (product_type == 'b') {
        	$('.give_score').html('送金象积分数量');
        } else if (product_type == 'c') {
        	$('.give_score').html('送金象积分数量');
        }
	}
	select_product_type();
/******************操作规格开始********************/
function change_color(obj) {
	var index = $(obj).parents('tr.color_list').find("input[name='attribute_color_ids[]']").val();
	$("#table_size_color").find(".color_index_"+index+":first").find("td:first[rowspan]").html($(obj).val());
}

function change_size(obj) {
	var index = $(obj).parents('tr.size_list').find("input[name='attribute_size_ids[]']").val();
	$("#table_size_color").find(".size_index_"+index).html($(obj).val());
}

function change_price(obj) {
    var price = $(obj).val();
    if (isNaN(price)) {
    	$(obj).val("")
    	var d = dialog({
            width: 200,
            title: '提示',
            fixed: true,
            content: '请输入正确的价格'
        });
        d.show();
        setTimeout(function () {
            d.close().remove();
        }, 2000);
        return false;
   	 }
}

function change_stock(obj) {
	var stock = $(obj).val();
    if (isNaN(stock)) {
    	$(obj).val("")
    	var d = dialog({
            width: 200,
            title: '提示',
            fixed: true,
            content: '请输入正确的库存数量'
        });
        d.show();
        setTimeout(function () {
            d.close().remove();
        }, 2000);
        return false;
   	 }
}

function change_color_size_open(val) {
    if (val == 1) {
        $('#color_size_open_btn').hide();
        $('#color_size_close_btn').show();
        $('#size_color_list').show();
    } else {
    	$('#color_size_open_btn').show();
        $('#color_size_close_btn').hide();
        $('#size_color_list').hide();
    }
    $('#color_size_open').val(val);
}

var color_id_index = 1;
var size_id_index = 1;
function get_size_color_list(obj, type) {
	var product_color_name = $('#product_color_name').val();
	var product_size_name = $('#product_size_name').val();
	var attribute_color_ids = '';
	var attribute_color_name = '';
	var attribute_size_ids = '';
	var attribute_size_name = '';
	var color_num = 0;
	var size_num = 0;
	if (!product_color_name) {
		$('#product_color_name').focus();
		var d = dialog({
            width: 200,
            title: '提示',
            fixed: true,
            content: '规格名称不能为空'
        });
        d.show();
        setTimeout(function () {
            d.close().remove();
        }, 2000);
        return false;
	}
	if (!product_size_name) {
		$('#product_size_name').focus();
		var d = dialog({
            width: 200,
            title: '提示',
            fixed: true,
            content: '规格名称不能为空'
        });
        d.show();
        setTimeout(function () {
            d.close().remove();
        }, 2000);
        return false;
	}
	if (type == 'color') {
		var color_tr_end = $('#color_table').find("tr:last").index();
		var color_tr_cur = $(obj).parents('tr').index();
		if (color_tr_end == color_tr_cur) {
			color_id_index++;
			//添加/删除颜色项
			var color_html = '<tr class="color_list">';
				color_html += '<td width="10%">';
				color_html += '<input onclick="javascript:get_size_color_list(this, \'color\');"  class="checkbox_style" name="attribute_color_ids[]" value="'+color_id_index+'" type="checkbox">';
				color_html += ' </td>';
				color_html += '<td>';
				color_html += '<input onchange="javascript:change_color(this);" name="attribute_color_name[]" placeholder="请输入主属性" size="40" maxlength="60" class="input_blur" type="text">';
				color_html += '</td>';
				color_html += '<td width="25%">';
				color_html += '<input name="attribute_color_hint[]" placeholder="主属性备注" size="20" maxlength="60" class="input_blur" type="text">';
				color_html += '</td>';
				color_html += '<td width="20%">';
				color_html += '<a class="good_url_path_file_src_a" title="点击查看大图" href="images/admin/no_pic.png" target="_blank" style="float:left;"><img class="good_url_path_file_src" width="30px" height="30px" src="images/admin/no_pic.png" onerror="javascript:this.src=\'images/admin/no_pic.png\';" /></a>';
				color_html += '<div style="float:left; margin-top:0px;">';
				color_html += '<a style=" position:relative; width:auto; " >';
				color_html += '<span style="cursor:pointer;font-size:12px;height:30px;" class="but_4">传图<form class="myupload" action="'+base_url+'admincp.php/upload/uploadImage2" method="post" enctype="multipart/form-data"><input style="left:0px;top:0px; background:#000; width:100%;height:25px;line-height:25px; position:absolute;filter:alpha(opacity=0);-moz-opacity:0;opacity:0;" type="file" accept=".gif,.jpg,.jpeg,.png" class="good_url_path_file" onchange="javascript:file_change_upload(this);" name="good_url_path_file" ></form></span>';
				color_html += '<i class="load good_url_path_file_load" style="cursor:pointer;display:none;width:auto;padding-left:0px; left:50%; margin-left:-16px;"><img src="images/admin/loading_2.gif" width="32" height="32"></i>';
				color_html += '</a>';
				color_html += '<input value="" type="hidden" name="attribute_path[]">';
				color_html += '</div>';
				color_html += '</td>';
				color_html += '</tr>';
			$('#color_table').find("tr:last").after(color_html);
		} else {
			//删除
			$(obj).parents('tr.color_list').remove();
			//删除属性里的所有颜色项
			var index_val = $(obj).val();
			$("#table_size_color").find("tr.color_index_"+index_val).remove();
		}
	} else if (type == 'size') {
		var size_tr_end = $('#size_table').find("tr:last").index();
		var size_tr_cur = $(obj).parents('tr').index();
		if (size_tr_end == size_tr_cur) {
			size_id_index++;
			//添加/删除尺码项
			var size_html = '<tr class="size_list">';
				size_html += '<td width="10%">';
				size_html += '<input onclick="javascript:get_size_color_list(this, \'size\');"  class="checkbox_style" name="attribute_size_ids[]" value="'+size_id_index+'" type="checkbox">';
				size_html += '</td>';
				size_html += '<td>';
				size_html += '<input onchange="javascript:change_size(this);"  name="attribute_size_name[]" placeholder="请输入主属性" size="60" maxlength="60" class="input_blur" type="text">';
				size_html += '</td>';
				size_html += '<td width="20%">';
				size_html += '<input name="attribute_size_hint[]" placeholder="主属性备注" size="15" maxlength="60" class="input_blur" type="text">';
				size_html += '</td>';
				size_html += '</tr>';
		    $('#size_table').find("tr:last").after(size_html);
		} else {
			$(obj).parents('tr.size_list').remove();
			//删除属性里的所有尺码项
			var index_val = $(obj).val();
			if (size_tr_cur == 1) {
				//当删除尺码是第一项值时，处理方法如下
				var tmp_size_num = 0;
				$("#table_size_color").find(".size_index_"+index_val).parent().remove();
				//获取尺码数量
				$("input[name='attribute_size_ids[]']:checked").each(function(i,n){
					tmp_size_num++;
				});
				$("input[name='attribute_color_ids[]']:checked").each(function(i,n){
                    var tmp_color_id = $(this).val();
                    var name = $(this).parents("tr.color_list").find("input[name='attribute_color_name[]']").val();
                    $("#table_size_color").find(".color_index_"+tmp_color_id+":first").find('td:first').before("<td rowspan='"+tmp_size_num+"'>"+name+"<\/td>");
		 		});
			} else {
				$("#table_size_color").find(".size_index_"+index_val).parent().remove();
			}
		}
	}
	//颜色
	$("input[name='attribute_color_ids[]']:checked").each(function(i,n){
		color_num++;
		attribute_color_ids += $(this).val() + ",";
		var name = $(this).parents('tr.color_list').find("input[name='attribute_color_name[]']").val();
		if (!name) {
			$(this).parents('tr.color_list').find("input[name='attribute_color_name[]']").focus();
			return false;
		}
		attribute_color_name += name + ",";
	});
	//尺码
	$("input[name='attribute_size_ids[]']:checked").each(function(i,n){
		size_num++;
		attribute_size_ids += $(this).val() + ",";
		var name = $(this).parents('tr.size_list').find("input[name='attribute_size_name[]']").val();
		if (!name) {
			$(this).parents('tr.size_list').find("input[name='attribute_size_name[]']").focus();
			return false;
		}
		attribute_size_name += name + ",";
	});
	//颜色与尺码组合
	var color_size_html = '';
	var first = 0;
	$("input[name='attribute_color_ids[]']:checked").each(function(i,n){
		var color_id = $(this).val();
		var color_name = $(this).parents('tr.color_list').find("input[name='attribute_color_name[]']").val();
		$("input[name='attribute_size_ids[]']:checked").each(function(x,y){
			var size_id = $(this).val();
			var size_name = $(this).parents('tr.size_list').find("input[name='attribute_size_name[]']").val();

            //判断颜色class是否已存在
			if ($("#table_size_color tr").hasClass("color_index_"+color_id)) {
				//修改颜色合并单元格里的值与名称
				$("#table_size_color").find(".color_index_"+color_id+":first").find("td:first").attr("rowspan", size_num);
				$("#table_size_color").find(".color_index_"+color_id+":first").find("td:first[rowspan]").html(color_name);
                //判断尺码项是否存在
				if ($("#table_size_color").find(".color_index_"+color_id).find("td").hasClass("size_index_"+size_id)) {
					//存在此项，直接修改尺码名称
					$("#table_size_color").find(".color_index_"+color_id).find("td.size_index_"+size_id).html(size_name);
				} else {
					//添加尺码项
					var tr_html = '';
					tr_html += '<tr class="color_index_'+color_id+'">';
					tr_html += '<td class="size_index_'+size_id+'">'+size_name+'</td>';
					tr_html += '<td><input onchange="javascript:change_price(this);" size="10" type="text" name="attribute_price[]"></td>';
					tr_html += '<td><input onchange="javascript:change_stock(this);" size="10" type="text" name="attribute_stock[]"></td>';
					tr_html += '<td><input size="10" type="text" name="attribute_product_num[]"></td>';
					tr_html += ' </tr>';
					$("#table_size_color").find(".color_index_"+color_id+":last").after(tr_html);
				}
            } else {
            	first = 1;
            	//第一项
            	if (x == 0) {
    				color_size_html += '<tr class="color_index_'+color_id+'">';
    				color_size_html += '<td rowspan="'+size_num+'">'+color_name+'</td>';
    				color_size_html += '<td class="size_index_'+size_id+'">'+size_name+'</td>';
    				color_size_html += '<td><input onchange="javascript:change_price(this);" size="10" type="text" name="attribute_price[]"></td>';
    				color_size_html += '<td><input onchange="javascript:change_stock(this);" size="10" type="text" name="attribute_stock[]"></td>';
    				color_size_html += '<td><input size="10" type="text" name="attribute_product_num[]"></td>';
    				color_size_html += '</tr>';
    			} else {
    				color_size_html += '<tr class="color_index_'+color_id+'">';
    				color_size_html += '<td class="size_index_'+size_id+'">'+size_name+'</td>';
    				color_size_html += '<td><input onchange="javascript:change_price(this);" size="10" type="text" name="attribute_price[]"></td>';
    				color_size_html += '<td><input onchange="javascript:change_stock(this);" size="10" type="text" name="attribute_stock[]"></td>';
    				color_size_html += '<td><input size="10" type="text" name="attribute_product_num[]"></td>';
    				color_size_html += ' </tr>';
    			}
            }
		});
	});
	if (first == 1) {
		$("#table_size_color").find("tr:last").after(color_size_html);
	}
}
/******************操作规格结束********************/
function file_change_upload(obj) {
    $(obj).parent().parent().find(".myupload").ajaxSubmit({
		dataType:  'json',
		data: {
            'model': 'product_size_color',
            'field': 'good_url_path_file'
        },
		beforeSend: function() {
        	$(obj).parent().parent().parent().find(".good_url_path_file_load").show();
		},
		uploadProgress: function(event, position, total, percentComplete) {
		},
		success: function(res) {
			$(obj).parent().parent().parent().find(".good_url_path_file_load").hide();
			if (res.success) {
				$(obj).parent().parent().parent().parent().parent().parent().find(".good_url_path_file_src_a").attr("href", res.data.file_path);
				$(obj).parent().parent().parent().parent().parent().parent().find(".good_url_path_file_src").attr("src", res.data.file_path.replace('.', '_thumb.')+"?"+res.data.field);
				$(obj).parent().parent().parent().parent().find("input[name='attribute_path[]']").attr("value", res.data.file_path);
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
}
//参数mulu
$(function () {
	//多链接传图
	if (!$(".good_url_path_file").parent('form').hasClass("myupload")) {
		$(".good_url_path_file").wrap("<form class='myupload' action='<?php echo base_url(); ?>admincp.php/upload/uploadImage2' method='post' enctype='multipart/form-data'></form>");
	}
	//形象照片
	$("#path_file").wrap("<form id='path_upload' action='<?php echo base_url(); ?>admincp.php/upload/uploadImage2' method='post' enctype='multipart/form-data'></form>");
    $("#path_file").change(function(){ //选择文件
		$("#path_upload").ajaxSubmit({
			dataType:  'json',
			data: {
                'model': 'product',
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

function get_brand_list(obj) {
	var item_name = $(obj).val();
	$(obj).parent().find('select').show();
	$.post(base_url+"admincp.php/"+controller+"/get_brand_list",
				{	"item_name": item_name
				},
				function(res){
					if(res.success) {
					    var html = '';
					    for (var i = 0, data = res.data, len = data.length; i < len; i++){
    					    html += '<option value="'+data[i].brand_name+'">'+data[i].brand_name+'</option>';
    					}
					    $("#select_brand").html(html);
					} else {
						$("#select_brand").html("");
    				}
				},
				"json"
		);
}

function select_brand_val(obj) {
	var item = $(obj).val();
	$('#brand_name').val(item);
	$(obj).hide();
}
</script>
