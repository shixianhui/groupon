<?php echo $tool; ?>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" id="jsonForm" >
    <table class="table_form" cellpadding="0" cellspacing="1">
        <caption>基本信息</caption>
        <tbody>
            <tr>
                <th width="20%"><strong>上级分类</strong> <br/>
                </th>
                <td>
                    <select class="input_blur" name="parent_id" id="parent_id">
                        <option value="0" >请选择上级分类</option>
                        <?php if (!empty($item_list)) { ?>
                            <?php
                            foreach ($item_list as $key => $value) {
                                $selector = '';
                                if ($item_info) {
                                    if ($item_info['parent_id'] == $value['id']) {
                                        $selector = 'selected="selected"';
                                    }
                                } else {
                                    if ($value['id'] == $tmp_parent_id) {
                                        $selector = 'selected="selected"';
                                    }
                                }
                                ?>
                                <option <?php echo $selector; ?> value="<?php echo $value['id']; ?>"><?php echo $value['product_category_name']; ?></option>
                            <?php }} ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th width="20%">
                    <font color="red">*</font> <strong>分类名称</strong> <br/>
                </th>
                <td>
                   <input valid="required" errmsg="分类名称不能为空!" name="product_category_name" id="product_category_name" value="<?php if (!empty($item_info)) { echo $item_info['product_category_name'];} ?>" size="80" type="text">
                   <br/><font color="red">注：中间加“|”分隔符可以实现批量添加，格式如：“衣服|鞋子|裤子|手机”</font>
                </td>
            </tr>
            <tr>
                <th width="20%"><strong>排序</strong> <br/>
                </th>
                <td>
                    <input name="sort" id="sort" value="<?php if (!empty($item_info)) {echo $item_info['sort'];} else {echo '0';} ?>" size="30" type="text">
                </td>
            </tr>
            <tr>
		      <th width="20%">
		      <font color="red">*</font> <strong>所属场馆</strong> <br/>
			  </th>
		      <td>
		      <?php if ($product_venue_list) { ?>
		      <?php foreach ($product_venue_list as $key=>$value) {
		      	    $selector = '';
		            if ($item_info) {
		            	if ($item_info['product_venue_id'] == $value['id']) {
		            		$selector = 'checked="checked"';
		            	}
		            }
		      	?>
		      <input type="radio" value="<?php echo $value['id']; ?>" name="product_venue_id" class="radio_style" <?php echo $selector; ?>> <?php echo $value['product_venue_name']; ?>
			  <?php }} ?>
			  <font color="red">注：父级与子级请保持一样的属性</font>
			</td>
			</tr>
            <tr>
                <th width="20%">
                    <strong>分类图片</strong>
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
                    <strong>一级分类广告图</strong>
                </th>
                <td>
                <a id="big_path_src_a" title="点击查看大图" href="<?php if ($item_info && $item_info['big_path']){echo $item_info['big_path'];}else{echo 'images/admin/no_pic.png';} ?>" target="_blank" style="float:left;"><img id="big_path_src" width="60px" src="<?php if ($item_info && $item_info['big_path']){echo preg_replace('/\./', '_thumb.', $item_info['big_path']);}else{echo 'images/admin/no_pic.png';} ?>" onerror="javascript:this.src='images/admin/no_pic.png';" /></a>

                <div style="float:left; margin-top:22px;">
                <a style=" position:relative; width:auto; " >
		        <span style="cursor:pointer;" class="but_4">上传照片<input style="left:0px;top:0px; background:#000; width:100%;height:36px;line-height:36px; position:absolute;filter:alpha(opacity=0);-moz-opacity:0;opacity:0;" type="file" accept=".gif,.jpg,.jpeg,.png" id="big_path_file" name="big_path_file" ></span>
		        <i class="load" id="big_path_load" style="cursor:pointer;display:none;width:auto;padding-left:0px; left:50%; margin-left:-16px;"><img src="images/admin/loading_2.gif" width="32" height="32"></i>
		       </a>

		       <input value="<?php if ($item_info){echo $item_info['big_path'];} ?>" type="hidden" id="big_path" name="big_path">
		       <span style="color:#9c9c9c;margin-left:30px;">注：原图大小＝750x440</span>
               </div>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>
                    <input class="button_style" name="dosubmit" value=" 保存 " type="submit">
                    &nbsp;&nbsp; <input onclick="javascript:window.location.href = '<?php echo $prfUrl; ?>';" class="button_style" name="reset" value=" 返回 " type="button">
                </td>
            </tr>
        </tbody>
    </table>
</form>
<script type="text/javascript">
//参数mulu
$(function () {
	//小图
	$("#path_file").wrap("<form id='path_upload' action='<?php echo base_url(); ?>admincp.php/upload/uploadImage2' method='post' enctype='multipart/form-data'></form>");
    $("#path_file").change(function(){ //选择文件
		$("#path_upload").ajaxSubmit({
			dataType:  'json',
			data: {
                'model': 'product_category',
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
    //大图
	$("#big_path_file").wrap("<form id='big_path_upload' action='<?php echo base_url(); ?>admincp.php/upload/uploadImage2' method='post' enctype='multipart/form-data'></form>");
    $("#big_path_file").change(function(){ //选择文件
		$("#big_path_upload").ajaxSubmit({
			dataType:  'json',
			data: {
                'model': 'product_category',
                'field': 'big_path_file'
            },
			beforeSend: function() {
            	$("#big_path_load").show();
    		},
    		uploadProgress: function(event, position, total, percentComplete) {
    		},
			success: function(res) {
    			$("#big_path_load").hide();
    			if (res.success) {
    				$("#big_path_src_a").attr("href", res.data.file_path);
      			    $("#big_path_src").attr("src", res.data.file_path.replace('.', '_thumb.')+"?"+res.data.field);
      			    $("#big_path").val(res.data.file_path);
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
	var big_path = $('#big_path').val();
	if (big_path) {
		$("#big_path_src_a").attr("href", big_path);
	    $("#big_path_src").attr("src", big_path.replace('.', '_thumb.'));
	}
}
</script>