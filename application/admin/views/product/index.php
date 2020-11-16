<style>
    .but_4 {
        background:rgb(29,160,212) none repeat scroll 0 0;
        border-radius: 6px;
        color: #fff;
        display: inline-block;
        font: 1.2em/32px "微软雅黑";
        height: 32px;
        margin-left: 10px;
        padding: 0 10px;
        position: relative;
    }
    .load {
        background: #fff none repeat scroll 0 0;
        left: 0;
        margin-top: -11px;
        opacity: 0.7;
        padding-left: 0px;
        position: absolute;
        top: 0;
        width: 63px;
    }
</style>
<?php echo $tool; ?>
<form name="search" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
<table class="table_form" cellpadding="0" cellspacing="1">
  <caption>信息查询</caption>
<tbody>
<tr>
<td class="align_c">
产品ID <input class="input_blur" name="id" id="id" size="10" type="text">&nbsp;
标题  <input class="input_blur" name="title" id="title" size="20" type="text">&nbsp;
产品编号  <input class="input_blur" name="product_num" id="product_num" size="20" type="text">&nbsp;
<select class="input_blur" name="display">
<option value="">选择状态</option>
<option value="1">上架</option>
<option value="0">下架</option>
</select>&nbsp;
<select name="custom_attribute" style="display: none">
<option value="">选择属性</option>
<?php
foreach($attribute_arr as $key=>$value){
?>
<option value="<?php echo $key;?>"><?php echo $value;?>[<?php echo $key;?>]</option>
<?php }?>
</select>
<select class="input_blur" name="select_category_id" style="display: none">
<option value="">--选择商品分类--</option>
<!-- 一级 -->
<?php
if($product_category){
    foreach($product_category as $menu){
        $category_ids = '';
        foreach($menu['subMenuList'] as $subMenu){
            $category_ids .= $subMenu['id'].',';
        }
?>
<option value="<?php echo trim($category_ids,',');?>"><?php echo $menu['product_category_name'];?></option>
<!-- 二级 -->
        <?php
            foreach($menu['subMenuList'] as $subMenu){
        ?>
<option value="<?php echo $subMenu['id'];?>">&nbsp;&nbsp;┣<?php echo $subMenu['product_category_name'];?></option>
            <?php }?>
    <?php }}?>
</select>

发布时间 <input class="input_blur" name="inputdate_start" id="inputdate_start" size="10" readonly="readonly" type="text">&nbsp;<script language="javascript" type="text/javascript">
					date = new Date();
					Calendar.setup({
						inputField     :    "inputdate_start",
						ifFormat       :    "%Y-%m-%d",
						showsTime      :    false,
						timeFormat     :    "24"
					});
				 </script> - <input class="input_blur" name="inputdate_end"
id="inputdate_end" size="10"  readonly="readonly" type="text">&nbsp;<script language="javascript" type="text/javascript">
					date = new Date();
					Calendar.setup({
						inputField     :    "inputdate_end",
						ifFormat       :    "%Y-%m-%d",
						showsTime      :    false,
						timeFormat     :    "24"
					});
				 </script>&nbsp;
<input class="button_style" name="dosubmit" value=" 查询 " type="submit">
</td>
</tr>
</tbody></table>
</form>
<table class="table_list" id="news_list" cellpadding="0" cellspacing="1">
<caption>信息管理</caption>
<tbody>
<tr class="mouseover">
<th width="60">选中</th>
<th width="40">排序</th>
<th width="60">&nbsp;</th>
<th>产品名称</th>
<!--<th width="80">快递模板</th>-->
<!--<th width="140">产品分类</th>-->
<!--<th width="60">产品属性</th>-->
<th width="100">出售价</th>
<th width="40">库存</th>
<th width="40">状态</th>
<th width="120">发布时间</th>
<th width="60">管理操作</th>
</tr>
<?php if (! empty($productList)): ?>
<?php foreach ($productList as $key=>$value): ?>
<tr id="id_<?php echo $value['id']; ?>"  onMouseOver="this.style.backgroundColor='#ECF7FE'" onMouseOut="this.style.background=''">
<td><input  class="checkbox_style" name="ids" value="<?php echo $value['id']; ?>" type="checkbox"> <?php echo $value['id']; ?></td>
<td class="align_c"><input class="input_blur" name="sort[]" id="sort_<?php echo $value['id']; ?>" value="<?php echo $value['sort']; ?>" size="4" type="text"></td>
<td>
    <?php if($value['path']){ ?>
    	<img src="<?php echo str_replace('.','_thumb.',$value['path']);?>" style="width:60px;height:60px;">
    <?php } else { ?>
    	<img src="images/default/default_img.png" style="width:60px;height:60px;">
    <?php } ?>
</td>
<td>
    <a href="admincp.php/product/save/<?php echo $value['id']; ?>" ><?php echo $value['title']; ?></a>
</td>
<!--<td class="align_c">--><?php //echo $value['postage_way_title']; ?><!--</td>-->
<!--<td class="align_c">--><?php //echo $value['product_category_str']; ?><!--</td>-->
<!--<td class="align_c">--><?php //echo $product_type_arr[$value['product_type']]; ?><!--</td>-->
<td class="align_c"><?php echo $value['sell_price'].' 元'; ?></td>
<td class="align_c"><?php echo $value['stock']; ?></td>

<td class="align_c"><?php echo $display_arr[$value['display']]; ?></td>
<td class="align_c"><?php echo date("Y-m-d H:i", $value['add_time']); ?></td>
<td class="align_c">
    <a href="admincp.php/product/save/<?php echo $value['id']; ?>">修改</a><br>
    <a onclick="add_comment(<?php echo $value['id']; ?>)" href="javascript:void(0)" style="display: none">评价</a>
</td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>
<div class="button_box">
<span style="width: 60px;"><a href="javascript:void(0);" onclick="javascript:$('input[type=checkbox]').prop('checked', true)">全选</a>/
<a href="javascript:void(0);" onclick="javascript:$('input[type=checkbox]').prop('checked', false)">取消</a></span>
<input class="button_style" name="list_order" id="list_order" value=" 排序 "  type="button">
<input class="button_style" name="delete" id="delete" value=" 删除 "  type="button">
<select class="input_blur" name="select_display" id="select_display" onchange="#">
<option value="">选择状态</option>
<?php if ($display_arr) { ?>
<?php foreach ($display_arr as $key=>$value) { ?>
<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
<?php }} ?>
</select>
<select name="custom_attribute" id="custom_attribute" onchange="#" style="display: none">
<option value="">选择属性</option>
<option value="clear">去除属性</option>
<?php
foreach($attribute_arr as $key=>$value){
?>
<option value="<?php echo $key;?>"><?php echo $value;?>[<?php echo $key;?>]</option>
<?php }?>
</select>
<select onchange="javascript:change_postage_way_id(this);" name="postage_way_id" id="postage_way_id" style="display: none">
<option value="">-请选择快递模板-</option>
<?php if ($postage_way_list) { ?>
<?php foreach ($postage_way_list as $key=>$value) { ?>
<option value="<?php echo $value['id']; ?>"><?php echo $value['title']; ?></option>
<?php }} ?>
</select>
</div>
<div id="pages" style="margin-top: 5px;">
<?php echo $pagination; ?>
<a>总条数：<?php echo $paginationCount; ?></a>
<a>总页数：<?php echo $pageCount; ?></a>
</div>
<br/>
<br/>
<script type="text/javascript">
function change_postage_way_id(obj) {
	var ids = "";
	var postage_way_id = $(obj).val();
	$("input[name='ids']:checked").each(function(i,n){
		ids += $(this).val() + ",";
	});
	if (!ids) {
		return my_alert('fail', 0, '请选定值');
	}
	if (!postage_way_id) {
		return my_alert('fail', 0, '请选择快递模板');
	}
	$.post(base_url+"admincp.php/"+controller+"/change_postage_way_id",
			{	"ids": ids.substr(0, ids.length - 1),
				"postage_way_id": postage_way_id
			},
			function(res){
				if(res.success){
					return my_alert_flush('fail', 0, res.message);
				}else{
					return my_alert('fail', 0, res.message);
				}
			},
			"json"
	);
}
    function add_comment(product_id) {
        $.post(base_url + "admincp.php/" + controller + "/get_product_info",
            {
                "product_id": product_id
            },
            function (res) {
                if (res.success) {
                    var html = '<table class="table_form" cellpadding="0" cellspacing="1">';
                    html += '<tbody> <tr>';
                    html += '<th width="20%"> <strong>商品名称</strong> <br/> </th>';
                    html += '<td id="product_title">'+res.data.title+'</td>';
                    html += '</tr>';
                    html += '<tr>';
                    html += '<th width="20%"><strong>评论人</strong> <br/> </th> ';
                    html += '<td><input id="username" type="text" class="input_blur"></td>';
                    html += '</tr>';
                    html += '<tr>';
                    html += '<th width="20%"> <strong>分数</strong> <br/></th>';
                    html += '<td><input id="grade" type="number" value="5" min="1" max="5" class="input_blur"><font color="red">注：分数范围1~5</font></td>';
                    html += '</tr>';
                    html += '<tr>';
                    html += '<th width="20%"><strong>评价时间</strong> <br/> </th>';
                    html += '<td><input class="input_blur" name="add_time" id="add_time" size="20" readonly="readonly" type="text">&nbsp;';
                    html += '</td>';
                    html += '</tr>';
                    html += '<tr style="display:none;">';
                    html += '<th width="20%"><font color="red">*</font> <strong>显示状态</strong> <br/> </th>';
                    html += '<td>';
                    for(var i=0;i<2;i++){
                        html += '<label><input value="' + i + '" type="radio" name="display" class="radio_style">' + res.data.comment_display_arr[i] + '</label>';
                    }
                    html += '</td> </tr>';
                    html += '<tr>';
                    html += '<tr>';
                    html += '<th width="20%"><font color="red">*</font> <strong>评价内容</strong> <br/> </th>';
                    html += '<td> <textarea maxlength="140" name="content" id="content" rows="3" cols="60"></textarea>';
                    html += '</td>';
                    html += '</tr>';
//                    html += '<tr style="color:#077ac7;" class="add_div"><th width="25%">晒图：</th><td>';
//                    html += '<div id="batch_path_div" style="margin-bottom:5px;height:70px;">';
//                    html += '<div style="width:64px;height:64px;position:relative;"><img style="padding: 2px; border:1px solid #CCC;" width="60px" height="60px" src="images/admin/nopic.gif" /></div>';
//                    html += '</div><a style=" position:relative;" ><span style="cursor:pointer;margin-left:0px;" class="but_4">上传图片<input style="left:0px;top:0px; background:#000; width:80px;height:35px;line-height:30px; position:absolute;filter:alpha(opacity=0);-moz-opacity:0;opacity:0;" type="file" accept=".gif,.jpg,.jpeg,.png" id="path_file" name="path_file" ></span>';
//                    html += '<i class="load" id="path_load" style="cursor:pointer;display:none;width:130px;padding-left:0px;"><img src="images/admin/loading_2.gif" width="32" height="32"></i></a>';
//                    html += '<font color="red"> 注：每次只能传一张，但可以传多次</font></td></tr>';
                    html += '</tbody> </table>';
                    var p_d = dialog({
                        fixed: true,
                        width: 750,
                        title: '添加评价：',
                        content: html,
                        okValue: '添加评价',
                        ok: function () {
                            var display = 1;// $('input[name="display"]:checked').val();
                            var content = $('#content').val();
                            var add_time = $('#add_time').val();
                            var grade = $('#grade').val();
                            var username = $('#username').val();
                            var product_title = $('#product_title').text();
                            var path = res.data.path;
                            if (!display) {
                                return my_alert('display', 1, '请选择显示状态');
                            }
                            if (!content) {
                                return my_alert('content', 1, '请填写评价内容');
                            }
                            if (!add_time) {
                                return my_alert('add_time', 1, '请填写评价时间');
                            }
                            if (!grade) {
                                return my_alert('grade', 1, '请填写分数');
                            }
                            if (!username) {
                                return my_alert('username', 1, '请填写评论人');
                            }
//                            var batch_path_ids = "";
//                            $("input[name='path_batch_path_ids[]']").each(function(i,n){
//                                batch_path_ids += $(this).val() + "_";
//                            });
//                            if (!batch_path_ids) {
//                                return my_alert('fail', 0, '请上传评价图片');
//                            }

                            $.post(base_url + "admincp.php/" + controller + "/add_comment",
                                {
                                    "display": display,
                                    "content": content,
                                    "add_time":add_time,
                                    "grade": grade,
                                    "username": username,
                                    "product_title": product_title,
//                                    "batch_path_ids":batch_path_ids,
                                    "path":path,
                                    "product_id": product_id
                                },
                                function (res) {
                                    if (res.success) {
                                        var d = dialog({
                                            width: 200,
                                            fixed: true,
                                            title: '提示：',
                                            content: res.message
                                        });
                                        d.show();
                                        setTimeout(function () {
                                            window.location.reload(false);
                                            p_d.close().remove();
                                            d.close().remove();
                                        }, 2000);
                                        return false;
                                    } else {
                                        return my_alert('fail', 0, res.message);
                                    }
                                },
                                "json"
                            );
                            return false;
                        },
                        cancelValue: '取消',
                        cancel: function () {
                        }
                    });
                    p_d.show();

                    //时间
                    date = new Date();
                    var datetime = date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate()+" "+date.getHours()+":"+date.getMinutes()+":"+date.getSeconds();
                    document.getElementById ("add_time").value =datetime;
                    Calendar.setup({
                        inputField     :    "add_time",
                        ifFormat       :    "%Y-%m-%d %H:%M:%S",
                        showsTime      :    true,
                        timeFormat     :    "24",
                        align          :    "Tr"
                    });

//                    //形象照片
//                    $("#path_file").wrap("<form id='path_upload' action='<?php //echo base_url(); ?>//admincp.php/upload/uploadImage2' method='post' enctype='multipart/form-data'></form>");
//                    $("#path_file").change(function(){ //选择文件
//                        $("#path_upload").ajaxSubmit({
//                            dataType:  'json',
//                            data: {
//                                'model': 'comment',
//                                'field': 'path_file'
//                            },
//                            beforeSend: function() {
//                                $("#path_load").show();
//                            },
//                            uploadProgress: function(event, position, total, percentComplete) {
//                            },
//                            success: function(res) {
//                                $("#path_load").hide();
//                                if (res.success) {
//                                    var path_batch_path_ids = "";
//                                    $("input[name='path_batch_path_ids[]']").each(function(i,n){
//                                        path_batch_path_ids += $(this).val() + ",";
//                                    });
//                                    if (path_batch_path_ids) {
//                                        var upload_html = '<div id="path_div_'+res.data.id+'" style="width:64px;height:64px;margin-left:6px;position:relative;float:left;"><input type="hidden" name="path_batch_path_ids[]" value="'+res.data.id+'" /><img style="padding: 2px;border:1px solid #CCC;" width="60px" height="60px" src="'+res.data.file_path.replace('.', '_thumb.')+'" /><img onclick="javascript:delete_item('+res.data.id+');" style="position:absolute;cursor:pointer;left:50px;" src="images/admin/close_1.gif" /></div>';
//                                        $("#batch_path_div div:last").after(upload_html);
//                                    } else {
//                                        var upload_html = '<div id="path_div_'+res.data.id+'" style="width:64px;height:64px;position:relative;float:left;"><input type="hidden" name="path_batch_path_ids[]" value="'+res.data.id+'" /><img style="padding: 2px; border:1px solid #CCC;" width="60px" height="60px" src="'+res.data.file_path.replace('.', '_thumb.')+'" /><img onclick="javascript:delete_item('+res.data.id+');" style="position:absolute;cursor:pointer;left:50px;" src="images/admin/close_1.gif" /></div>';
//                                        $("#batch_path_div").html(upload_html);
//                                    }
//                                } else {
//                                    var d = dialog({
//                                        fixed: true,
//                                        title: '提示',
//                                        content: res.message
//                                    });
//                                    d.show();
//                                    setTimeout(function () {
//                                        d.close().remove();
//                                    }, 2000);
//                                }
//                            },
//                            error:function(xhr){
//                            }
//                        });
//                    });


                } else {
                    return my_alert('fail', 0, res.message);
                }
            },
            "json"
        );
    }
//    function delete_item(id) {
//        $("#path_div_"+id).remove();
//    }


</script>
