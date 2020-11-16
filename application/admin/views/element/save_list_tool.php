<div id="position" >
<strong>当前位置：</strong>
<a href="javascript:void(0);"><?php echo $parent_title; ?></a>
<a href="javascript:void(0);"><?php echo $title; ?>管理</a>
</div>
<br />
<table class="table_form" cellpadding="0" cellspacing="1">
  <caption>快捷方式</caption>
  <tbody>
  <tr>
    <td>
        <?php if ($table != 'enroll' && $table != 'quote_price_record'){ ?>
    <a href="admincp.php/<?php echo $table; ?>/save"><span id="<?php echo $table; ?>_save">添加<?php echo $title; ?></span></a> |
        <?php } ?>
    <a href="admincp.php/<?php echo $table; ?>/index/1"><span id="<?php echo $table; ?>_"><?php echo $title; ?>列表</span></a>
     <?php if ($table == 'style' || $table == 'material' || $table == 'store') {?>
    <a href="admincp.php/<?php echo $table; ?>/index_0"><span id="<?php echo $table; ?>_index_0">待审核</span></a>
    <?php } ?>
    </td>
  </tr>
</tbody>
</table>