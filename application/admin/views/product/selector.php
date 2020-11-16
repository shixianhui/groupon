<form name="search" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
<table class="table_form" cellpadding="0" cellspacing="1">
  <caption>信息查询</caption>
<tbody>
<tr>
<td class="align_c">标题  <input class="input_blur" name="title" id="title" size="20" type="text">&nbsp;
产品编号  <input class="input_blur" name="product_num" id="product_num" size="20" type="text">&nbsp;
<select class="input_blur" name="display" style="display: none">
<option value="">选择状态</option>
<option value="1">上架</option>
<option value="0">下架</option>
</select>&nbsp;
<select name="custom_attribute" style="display: none">
<option value="">选择属性</option>
<option value="c">推荐[c]</option>
<option value="a">特荐[a]</option>
</select>
<select name="pay_mode" onchange="#" style="display: none">
<option value="">选择活动属性</option>
<option value="0">不参与活动</option>
<option value="1">参与限时抢购</option>
<option value="2">参与积分兑换</option>
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
<th width="70">选中</th>
<th>产品名称</th>
<!--<th width="80">产品编号</th>-->
<!--<th width="200">产品分类</th>-->
<!--<th width="60">销售量</th>-->
<th width="60">库存</th>
<th width="60">出售价</th>
<th width="60">状态</th>
<th width="60">发布时间</th>
</tr>
<?php if (! empty($productList)): ?>
<?php foreach ($productList as $key=>$value): ?>
<tr id="id_<?php echo $value['id']; ?>"  onMouseOver="this.style.backgroundColor='#ECF7FE'" onMouseOut="this.style.background=''">
<td><input class="checkbox_style" name="ids" value="<?php echo $value['id']; ?>" type="radio" onclick="select_pro_id(this.value,'<?php echo $value['path'];?>','<?php echo $value['title'];?>','<?php echo $value['sell_price'];?>')"> <?php echo $value['id']; ?></td>
<td>
    <?php if($value['path']){ ?><a href="<?php echo $value['path'];?>" style="float:left;margin-right:5px;" target="_blank"><img src="<?php echo str_replace('.','_thumb.',$value['path']);?>" style="width:60px;height:60px;"></a><?php } ?>
    <a href="admincp.php/product/save/<?php echo $value['id']; ?>" ><?php echo $value['title']; ?></a>
</td>
<!--<td class="align_c"><?php //echo $value['product_num']; ?></td>-->
<!--<td class="align_c">--><?php //echo $value['product_category_str']; ?><!--</td>-->
<!--<td class="align_c">--><?php //echo $value['sales']; ?><!--</td>-->
<td class="align_c"><?php echo $value['stock']; ?></td>
<td class="align_c"><?php echo $value['sell_price']; ?></td>
<td class="align_c"><?php echo $display_arr[$value['display']]; ?></td>
<td class="align_c"><?php echo date("Y-m-d H:i", $value['add_time']); ?></td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>

<div id="pages" style="margin-top: 5px;">
<?php echo $pagination; ?>
<a>总条数：<?php echo $paginationCount; ?></a>
<a>总页数：<?php echo $pageCount; ?></a>
</div>
<br/>
<br/>
<script>

    function select_pro_id(product_id,path,title,price){
                $("#productId",window.opener.document).val(product_id);
                $("#productImg",window.opener.document).attr('src',path);
                $("#productTitle",window.opener.document).html(title);
                $("#price",window.opener.document).html(price);
                $("#productId",window.opener.document).change();
                window.close();
    }
</script>