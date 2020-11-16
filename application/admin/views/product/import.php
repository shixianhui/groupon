<?php echo $tool; ?>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" id="jsonForm" >
<input name="id" value="" type="hidden">
<table class="table_form" cellpadding="0" cellspacing="1">
  <caption>基本信息</caption>
 	<tbody> 	
	<tr>
      <th width="15%">
      <strong>文件路径</strong> <br/>
	  </th>
      <td>
      栏目ID <input name="category_id" type="text" valid="required" errmsg=" 栏目ID不能为空!" /> <font color="red">注：格式“1,2,”</font><br/>
      文件 <input name="filePath" id="filePath" type="file" size="50" />
      <input class="button_style" type="submit" name="dosubmit" value=" 上传 " /> <font color="red">目前只支持.csv格式的文件，是否导入结束，大文件请看浏览器加载状态</font>
    </td>
    </tr>
</tbody>
</table>
</form>