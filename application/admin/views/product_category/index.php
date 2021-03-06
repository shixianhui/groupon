<?php echo $tool; ?>
<table class="table_list" id="news_list" cellpadding="0" cellspacing="1">
<caption>信息管理</caption>
<tbody>
<tr class="mouseover">
<th width="70">选中</th>
<th width="50">排序</th>
<th>分类名称</th>
<th width="60">图片</th>
<th width="50">状态</th>
<th width="150">所属场馆</th>
<th width="150">操作</th>
</tr>
<?php if (! empty($item_list)) { ?>
<?php foreach ($item_list as $key=>$value) { ?>
<tr id="id_<?php echo $value['id']; ?>"  onMouseOver="this.style.backgroundColor='#ECF7FE'" onMouseOut="this.style.background=''">
<td><input  class="checkbox_style" name="ids" value="<?php echo $value['id']; ?>" type="checkbox"> <?php echo $value['id']; ?></td>
<td class="align_c"><input style="background-color:#E0E0E0;" class="input_blur" name="sort[]" id="sort_<?php echo $value['id']; ?>" value="<?php echo $value['sort']; ?>" size="4" type="text"></td>
<td onclick="javascript:get_pct_list(<?php echo$value['id']; ?>, '<?php echo $value['product_category_name']; ?>');"><?php echo $value['product_category_name']; ?></td>
<td class="align_c"><img src="<?php if ($value['path']){echo preg_replace('/\./', '_thumb.', $value['path']);} ?>" onerror="javascript:this.src='images/admin/no_pic.png';" width="20" height="20" /></td>
<td class="align_c"><?php echo $value['display']?'显示':'<font color="#FF0000">隐藏</font>'; ?></td>
<td class="align_c"><?php echo $value['product_venue_name']; ?></td>
<td class="align_c">
<a href="admincp.php/<?php echo $table; ?>/save/<?php echo $value['id']; ?>">添加子分类</a> | <a href="admincp.php/<?php echo $table; ?>/save/0/<?php echo $value['id']; ?>">修改</a> | <a href="javascript:delete_item(<?php echo $value['id']; ?>)">删除</a>
</td>
</tr>
<?php if ($value['subMenuList']) { ?>
<?php foreach ($value['subMenuList'] as $sub_key=>$sub_value) { ?>
<tr id="id_<?php echo $sub_value['id']; ?>"  onMouseOver="this.style.backgroundColor='#ECF7FE'" onMouseOut="this.style.background=''">
<td><input  class="checkbox_style" name="ids" value="<?php echo $sub_value['id']; ?>" type="checkbox"> <?php echo $sub_value['id']; ?></td>
<td class="align_c"><input class="input_blur" name="sort[]" id="sort_<?php echo $sub_value['id']; ?>" value="<?php echo $sub_value['sort']; ?>" size="4" type="text"></td>
<td onclick="javascript:get_pct_list(<?php echo$value['id']; ?>, '<?php echo $value['product_category_name']; ?>');">&nbsp;&nbsp;┣<?php echo $sub_value['product_category_name']; ?></td>
<td class="align_c"><img src="<?php if ($sub_value['path']){echo preg_replace('/\./', '_thumb.', $sub_value['path']);} ?>" onerror="javascript:this.src='images/admin/no_pic.png';" width="20" height="20" /></td>
<td class="align_c"><?php echo $sub_value['display']?'显示':'<font color="#FF0000">隐藏</font>'; ?></td>
<td class="align_c"><?php echo $sub_value['product_venue_name']; ?></td>
<td class="align_c">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="admincp.php/<?php echo $table; ?>/save/0/<?php echo $sub_value['id']; ?>">修改</a> | <a href="javascript:delete_item(<?php echo $sub_value['id']; ?>)">删除</a>
</td>
</tr>
<?php }} ?>
<?php } ?>
<?php } ?>
</tbody>
</table>
<div class="button_box">
<span style="width: 60px;"><a href="javascript:void(0);" onclick="javascript:$('input[type=checkbox]').prop('checked', true)">全选</a>/
<a href="javascript:void(0);" onclick="javascript:$('input[type=checkbox]').prop('checked', false)">取消</a></span>
<input style="margin-left: 10px;" class="button_style" name="list_order" id="list_order" value=" 排序 "  type="button">
<select class="input_blur" name="select_display" id="select_display">
<option value="">选择状态</option>
<option value="1">显示</option>
<option value="0">隐藏</option>
</select>
</div>
<br/><br/>
<script language="javascript" type="text/javascript">
function delete_item(id) {
	var con = confirm("你确定要删除[ID:"+id+"]吗？删除后将不可恢复！");
	if (con == true) {
		$.post("<?php echo base_url(); ?>admincp.php/"+controller+"/delete",
			{	"id": id
			},
			function(res){
				if(res.success){
					$("#id_"+res.data.id).remove();
					return false;
				}else{
					alert(res.message);
					return false;
				}
			},
			"json"
		);
	}
}

function get_pct_list(id, product_category_name) {
	$.get("<?php echo base_url(); ?>admincp.php/"+controller+"/get_pct_list/"+id,
			{},
			function(res){
				if(res.success){
					var html = '';
					html += '<table class="table_form" cellpadding="0" cellspacing="1">';
					html += '<thead>';
					html += '<tr>';
					html += ' <th class="align_c">国家</th>';
					html += '<th class="align_c">增值税率</th>';
					html += '<th class="align_c">消费税率</th>';
					html += '<th class="align_c">跨境电商综合税</th>';
					html += '</tr>';
					html += '</thead>';
                for (var i = 0, data = res.data.area_list, len = data.length; i < len; i++){
                	html += '<tr>';
                	html += '<th class="align_c">'+data[i].name+'</th>';
					html += '<th class="align_c">'+data[i]['pct_list'].vat_rate+' %';
				    html += '</th>';
					html += '<th class="align_c">'+data[i]['pct_list'].consume_rate+' %';
					html += '</th>';
					html += '<th class="align_c">'+data[i]['pct_list'].consolidated_tax+' %';
					html += '</th>';
					html += '</tr>';
                }
                html += '</table>';
					var d = dialog({
	                    width: 350,
	                    title: product_category_name+'税率：',
	                    fixed: true,
	                    content: html
	                });
	                d.show();
				}else{
					alert(res.message);
					return false;
				}
			},
			"json"
		);
}
</script>