<?php echo $tool; ?>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
<input name="id" value="" type="hidden">
<table class="table_form" cellpadding="0" cellspacing="1">
  <caption>基本信息</caption>
 	<tbody>
 	<tr>
      <th width="20%">
      <strong>订单状态</strong> <br/>
	  </th>
      <td>
      <select class="input_blur" name="status">
       <option value="" >请选择订单状态</option>
       <?php if ($status) { ?>
       <?php foreach ($status as $key=>$value) { ?>
       <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
       <?php }} ?>
      </select>
      </td>
    </tr>
	<tr>
      <th width="20%"> <strong>下单时间</strong> <br/>
	  </th>
      <td>
      <input class="input_blur" name="inputdate_start" id="inputdate_start" size="10" readonly="readonly" type="text"> 至 <input class="input_blur" name="inputdate_end"
id="inputdate_end" size="10"  readonly="readonly" type="text">
	 </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>
	  <input class="button_style" name="dosubmit" value=" 导出数据 " type="submit"></td>
    </tr>
</tbody>
</table>
</form>