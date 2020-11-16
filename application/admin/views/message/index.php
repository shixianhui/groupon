<table cellpadding="0" cellspacing="0" class="table_info" style="width:400px">
  <caption>
  提示信息
  </caption>
  <tr>
    <td height="60" valign="middle" class="align_c"><?php if (isset($msg)){echo $msg;} ?></td>
  </tr>
  <tr>
    <td height="20" valign="middle" class="align_c">
    <?php if ($url == "goback") { ?>
    <a href="javascript:history.go(-1);" >[ 点这里返回上一页 ]</a>
    <?php } else if (isset($url)) {?>
    <a href="<?php echo $url;?>">如果您的浏览器没有自动跳转，请点击这里</a>
    <script>window.setTimeout("window.location.href='<?php echo $url;?>'",3000);</script>
    <?php } ?>
    </td>
  </tr>
</table>