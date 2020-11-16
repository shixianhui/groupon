<div id="position" >
    <strong>当前位置：</strong>
    <a href="javascript:void(0);">订单管理</a>
    <a href="javascript:void(0);">拼团记录列表</a>
</div>
<br />
<table class="table_form" cellpadding="0" cellspacing="1">
    <caption>快捷方式</caption>
    <tbody>
    <tr>
        <td>
            <a href="admincp.php/<?php echo $table; ?>/index/1"><span id="<?php echo $table; ?>_">拼团记录列表</span></a> |
            <a href="admincp.php/groupon_record/export"><span id="groupon_record_export">导出数据</span></a>
        </td>
    </tr>
    </tbody>
</table>
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
       <option value="" >请选择拼团状态</option>
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