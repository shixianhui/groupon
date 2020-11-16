<?php echo $tool; ?>
<table class="table_list" id="news_list" cellpadding="0" cellspacing="1">
<caption>信息管理</caption>
<tbody>
<tr class="mouseover">
<th width="70">选中</th>
<th width="50">排序</th>
<th>名称</th>
<th width="200">未点亮图片</th>
<th width="200">点亮后图片</th>
<th width="200">选中概率</th>
<th width="200">总计</th>
<th width="150">操作</th>
</tr>
<?php if (! empty($item_list)) { ?>
<?php foreach ($item_list as $key=>$value) { ?>
<tr id="id_<?php echo $value['id']; ?>"  onMouseOver="this.style.backgroundColor='#ECF7FE'" onMouseOut="this.style.background=''">
<td><input  class="checkbox_style" name="ids" value="<?php echo $value['id']; ?>" type="checkbox"> <?php echo $value['id']; ?></td>
<td class="align_c"><input style="background-color:#E0E0E0;" class="input_blur" name="sort[]" id="sort_<?php echo $value['id']; ?>" value="<?php echo $value['sort']; ?>" size="4" type="text"></td>
    <td class="align_c"><?php echo $value['name']; ?></td>
<td class="align_c"><img src="<?php if ($value['img_path']){echo preg_replace('/\./', '_thumb.', $value['img_path']);} ?>" onerror="javascript:this.src='images/admin/no_pic.png';" width="60" height="60" /></td>
<td class="align_c"><img src="<?php if ($value['light_img_path']){echo preg_replace('/\./', '_thumb.', $value['light_img_path']);} ?>" onerror="javascript:this.src='images/admin/no_pic.png';" width="60" height="60" /></td>
    <td class="align_c"><?php echo floatval($value['rate']); ?> %</td>
    <td class="align_c"><?php echo $value['total']; ?></td>

    <td class="align_c">
 <a href="admincp.php/<?php echo $table; ?>/save/<?php echo $value['id']; ?>">修改</a> | <a href="javascript:delete_item(<?php echo $value['id']; ?>)">删除</a>
</td>
</tr>
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
					$("#id_"+res.data.ids).remove();
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

</script>