<?php echo $tool; ?>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" id="jsonForm" >
    <table class="table_form" cellpadding="0" cellspacing="1">
        <caption>基本信息</caption>
        <tbody>
            <tr>
                <th width="20%">
                    <font color="red">*</font> <strong>名称</strong> <br/>
                </th>
                <td>
                   <input valid="required" errmsg="名称不能为空!" name="name" id="name" value="<?php if (!empty($item_info)) { echo $item_info['name'];} ?>" size="80" type="text">
                </td>
            </tr>
            <tr>
                <th width="20%">
                    <font color="red">*</font> <strong>选中概率</strong> <br/>
                </th>
                <td>
                    <input valid="required|isNumber" errmsg="概率不能为空!|请填写正确的概率" name="rate" id="rate" value="<?php if (!empty($item_info)) { echo $item_info['rate'];} ?>" size="10" type="text"> %
                </td>
            </tr>
<!--            <tr>-->
<!--                <th width="20%"><strong>排序</strong> <br/>-->
<!--                </th>-->
<!--                <td>-->
<!--                    <input name="sort" id="sort" value="--><?php //if (!empty($item_info)) {echo $item_info['sort'];} else {echo '0';} ?><!--" size="30" type="text">-->
<!--                </td>-->
<!--            </tr>-->
            <tr>
                <th width="20%">
                    <strong>未点亮图片</strong>
                </th>
                <td>
                <a id="path_src_a" title="点击查看大图" href="<?php if ($item_info && $item_info['img_path']){echo $item_info['img_path'];}else{echo 'images/admin/no_pic.png';} ?>" target="_blank" style="float:left;"><img id="path_src" width="60px" src="<?php if ($item_info && $item_info['img_path']){echo preg_replace('/\./', '_thumb.', $item_info['img_path']);}else{echo 'images/admin/no_pic.png';} ?>" onerror="javascript:this.src='images/admin/no_pic.png';" /></a>

                <div style="float:left; margin-top:22px;">
                <a style=" position:relative; width:auto; " >
		        <span style="cursor:pointer;" class="but_4">上传照片<input style="left:0px;top:0px; background:#000; width:100%;height:36px;line-height:36px; position:absolute;filter:alpha(opacity=0);-moz-opacity:0;opacity:0;" type="file" accept=".gif,.jpg,.jpeg,.png" id="path_file" name="path_file" ></span>
		        <i class="load" id="path_load" style="cursor:pointer;display:none;width:auto;padding-left:0px; left:50%; margin-left:-16px;"><img src="images/admin/loading_2.gif" width="32" height="32"></i>
		       </a>

		       <input value="<?php if ($item_info){echo $item_info['img_path'];} ?>" type="hidden" id="path" name="path">
		       <input name="model" id="model"  value="<?php echo $table; ?>" type="hidden" />
               <span id="cut_image" style="cursor:pointer;" class="but_4">裁剪图片</span>
               </div>
                </td>
            </tr>
            <tr>
                <th width="20%">
                    <strong>点亮后图片</strong>
                </th>
                <td>
                <a id="light_img_path_src_a" title="点击查看大图" href="<?php if ($item_info && $item_info['light_img_path']){echo $item_info['light_img_path'];}else{echo 'images/admin/no_pic.png';} ?>" target="_blank" style="float:left;"><img id="light_img_path_src" width="60px" src="<?php if ($item_info && $item_info['light_img_path']){echo preg_replace('/\./', '_thumb.', $item_info['light_img_path']);}else{echo 'images/admin/no_pic.png';} ?>" onerror="javascript:this.src='images/admin/no_pic.png';" /></a>

                <div style="float:left; margin-top:22px;">
                <a style=" position:relative; width:auto; " >
		        <span style="cursor:pointer;" class="but_4">上传照片<input style="left:0px;top:0px; background:#000; width:100%;height:36px;line-height:36px; position:absolute;filter:alpha(opacity=0);-moz-opacity:0;opacity:0;" type="file" accept=".gif,.jpg,.jpeg,.png" id="light_img_path_file" name="light_img_path_file" ></span>
		        <i class="load" id="light_img_path_load" style="cursor:pointer;display:none;width:auto;padding-left:0px; left:50%; margin-left:-16px;"><img src="images/admin/loading_2.gif" width="32" height="32"></i>
		       </a>

		       <input value="<?php if ($item_info){echo $item_info['light_img_path'];} ?>" type="hidden" id="light_img_path" name="light_img_path">
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
                'model': 'light_img',
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
	$("#light_img_path_file").wrap("<form id='light_img_path_upload' action='<?php echo base_url(); ?>admincp.php/upload/uploadImage2' method='post' enctype='multipart/form-data'></form>");
    $("#light_img_path_file").change(function(){ //选择文件
		$("#light_img_path_upload").ajaxSubmit({
			dataType:  'json',
			data: {
                'model': 'light_img',
                'field': 'light_img_path_file'
            },
			beforeSend: function() {
            	$("#light_img_path_load").show();
    		},
    		uploadProgress: function(event, position, total, percentComplete) {
    		},
			success: function(res) {
    			$("#light_img_path_load").hide();
    			if (res.success) {
    				$("#light_img_path_src_a").attr("href", res.data.file_path);
      			    $("#light_img_path_src").attr("src", res.data.file_path.replace('.', '_thumb.')+"?"+res.data.field);
      			    $("#light_img_path").val(res.data.file_path);
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
	var light_img_path = $('#light_img_path').val();
	if (light_img_path) {
		$("#light_img_path_src_a").attr("href", light_img_path);
	    $("#light_img_path_src").attr("src", light_img_path.replace('.', '_thumb.'));
	}
}
</script>