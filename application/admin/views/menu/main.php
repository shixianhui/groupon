<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<base href="<?php echo base_url(); ?>" />
<link rel="stylesheet" href="css/admin/system.css">
<title>系统主页</title>
<style>
body {
	width:100%;
	background:#fff;
	}
</style>
</head>
<body>
<div id="admin_right">
<table class="table_form" cellpadding="0" cellspacing="1">
  <caption>基本信息</caption>
 	<tbody>
 	<tr>
      <th width="20%"><strong>PHP版本 </strong> <br/>
	  </th>
      <td><?php echo phpversion(); ?>
      注：>=5.3.7，建议5.6或更高版本
      </td>
    </tr>
	<tr>
      <th width="20%"><strong>GD库版本</strong> <br/>
	  </th>
      <td>
      <?php $gdInfo = gd_info();
            echo $gdInfo['GD Version'];
      ?>
	</td>
    </tr>
	<tr>
      <th width="20%">
      <strong>文件大小</strong> <br/>
	  </th>
      <td>最大能上传<?php echo ini_get("upload_max_filesize"); ?>
   </td>
  </tr>
  <tr>
      <th width="20%">
      <strong>MySql版本</strong> <br/>
	  </th>
      <td><?php echo $this->db->version();  ?>
   </td>
  </tr>
  <tr>
    <th width="20%">
    <strong>软件版本</strong> <br/>
	</th>
    <td>无忧建站企业版 v 2.1.02</td>
    </tr>
    <tr>
      <th width="20%"> <strong>技术支持QQ</strong>
	  </th>
      <td>
      1633839035
      </td>
    </tr>
    <tr>
      <th width="20%"> <strong>官方网站</strong>
	  </th>
      <td>
      <a href="http://www.51daima.com" target="_blank">http://www.51daima.com</a>
      </td>
    </tr>
    <tr>
      <th width="20%"> <strong>服务器时间</strong>
	  </th>
      <td>
      <?php echo date('Y-m-d H:i:s', time()); ?>
      </td>
    </tr>
    <tr>
      <th width="20%"> <strong>上次登录IP</strong>
	  </th>
      <td>
      <?php echo get_cookie('admin_ip'); ?>
      </td>
    </tr>
    <tr>
      <th width="20%"> <strong>上次登录地址</strong>
	  </th>
      <td>
      <?php echo get_cookie('admin_ip_address'); ?>
      </td>
    </tr>
</tbody>
</table>
</div>
</body>
</html>