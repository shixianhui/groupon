<?php echo $tool; ?>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" id="jsonForm" >
<input name="id" value="" type="hidden">
<table class="table_form" cellpadding="0" cellspacing="1">
  <caption>基本信息</caption>
 	<tbody>
 	<tr>
      <th width="20%">
      <strong>网站名称</strong> <br/>
	  </th>
      <td>
      <input name="site_name" id="site_name" value="<?php if($itemInfo){echo $itemInfo['site_name'];} ?>" size="80"  class="input_blur" type="text">
	</td>
	</tr>
	<tr>
      <th width="20%">
      <strong>首页标题</strong> <br/>
	  </th>
      <td>
      <input name="index_site_name" id="index_site_name" value="<?php if($itemInfo){echo $itemInfo['index_site_name'];} ?>" size="80"  class="input_blur" type="text">
	</td>
	</tr>
    <tr>
      <th width="20%">
      <strong>主页链接名</strong> <br/>
	  </th>
      <td>
      <input name="index_name" id="index_name" value="<?php if($itemInfo){echo $itemInfo['index_name'];} ?>" size="80"  class="input_blur" type="text">
	</td>
	</tr>
	<tr>
      <th width="20%">
      <strong>主调文件名称</strong> <br/>
	  </th>
      <td>
      <input name="client_index" id="client_index" value="<?php if($itemInfo){echo $itemInfo['client_index'];} ?>" size="80" class="input_blur" type="text">
     <font color="red"> 当web服务品不能去掉index.php时,请填写index.php</font>
	</td>
	</tr>
    <tr>
      <th width="20%"> <strong>关键词</strong> <br/>
	  多关键词之间用逗号隔开
	  </th>
      <td>
      <input name="site_keycode" id="site_keycode" size="80" value="<?php if($itemInfo){echo $itemInfo['site_keycode'];} ?>" class="input_blur" type="text" />
      </td>
    </tr>
	<tr>
      <th width="20%"> <strong>站点描述</strong> <br/>
	  </th>
      <td>
      <textarea name="site_description" id="site_description" rows="4" cols="50"  class="textarea_style" style="width: 80%;" ><?php if($itemInfo){echo $itemInfo['site_description'];} ?></textarea>
    </td>
    </tr>
    <tr>
      <th width="20%"> <strong>站点版权信息</strong> <br/>
	  </th>
      <td>
      <textarea name="site_copyright" id="site_copyright" rows="4" cols="50"  class="textarea_style" style="width: 80%;" ><?php if($itemInfo){echo $itemInfo['site_copyright'];} ?></textarea>
    </td>
    </tr>
    <tr>
      <th width="20%">
      <strong>网站备案号</strong> <br/>
	  </th>
      <td>
      <input name="icp_code" id="icp_code" value="<?php if($itemInfo){echo $itemInfo['icp_code'];} ?>" size="80"  class="input_blur" type="text">
	</td>
	</tr>
	<tr>
      <th width="20%">
      <strong>是否开启静态</strong> <br/>
	  </th>
      <td>
      <label>
      <input type="radio" value="0" name="html" class="radio_style" <?php if($itemInfo){if($itemInfo['html']=='0'){echo 'checked="checked"';}}else{echo 'checked="checked"';} ?> > 关闭
      </label>
      <label>
      <input type="radio" value="1" name="html" class="radio_style" <?php if($itemInfo){if($itemInfo['html']=='1'){echo 'checked="checked"';}} ?> > 开启&nbsp;&nbsp;&nbsp;&nbsp;<font color="red">注:静态优先缓存</font>
	  </label>
	</td>
	</tr>
	<tr>
      <th width="20%">
      <strong>静态文件夹名称</strong> <br/>
	  </th>
      <td>
      <input name="html_folder" id="html_folder" value="<?php if($itemInfo){echo $itemInfo['html_folder'];} ?>" size="10"  class="input_blur" type="text"> <font color="red">注：为静态文件放在的一个根目录文件夹，保证此文件夹有读写权限，后面不要带“/”</font>
	</td>
	</tr>
	<tr>
      <th width="20%">
      <strong>网站级别</strong> <br/>
	  </th>
      <td>
      <label>
      <input type="radio" value="1" name="html_level" class="radio_style" <?php if($itemInfo){if($itemInfo['html_level']=='1'){echo 'checked="checked"';}}else{echo 'checked="checked"';} ?> > 企业站
      </label>
      <label style="display:none;">
      <input type="radio" value="0" name="html_level" class="radio_style" <?php if($itemInfo){if($itemInfo['html_level']=='0'){echo 'checked="checked"';}} ?> > 行业站
      </label>
      <font color="red">注：企业站为所有静态文件放在同一目录下；行业站为静态文件放在相应目录下；一般建议选择企业站，对SEO来说有好处</font>
	</td>
	</tr>
	<tr>
      <th width="20%">
      <strong>是否开启缓存</strong> <br/>
	  </th>
      <td>
      <label>
      <input type="radio" value="0" name="cache" class="radio_style" <?php if($itemInfo){if($itemInfo['cache']=='0'){echo 'checked="checked"';}}else{echo 'checked="checked"';} ?> > 关闭
      </label>
      <label>
      <input type="radio" value="1" name="cache" class="radio_style" <?php if($itemInfo){if($itemInfo['cache']=='1'){echo 'checked="checked"';}} ?> > 开启
	  </label>
	</td>
	</tr>
	<tr>
      <th width="20%">
      <strong>缓存时间</strong> <br/>
	  </th>
      <td>
      <input name="cache_time" id="cache_time" value="<?php if($itemInfo){echo $itemInfo['cache_time'];} ?>" size="80" class="input_blur" type="text"> <font color="red">分钟</font>
	</td>
	</tr>
    <tr>
      <td>&nbsp;</td>
      <td>
	  <input class="button_style" name="dosubmit" value=" 保存 " type="submit">
	  &nbsp;&nbsp; <input class="button_style_red" name="reset" value=" 重置 " type="reset">
	  </td>
    </tr>
</tbody>
</table>
</form>