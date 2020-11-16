<?php echo $tool; ?>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" id="jsonForm" >
    <div id="basics">
<table class="table_form" cellpadding="0" cellspacing="1">
  <caption>基本信息</caption>
 	<tbody>
    <?php if(! empty($item_info)){ ?>
    <tr>
        <th width="20%"><strong>会员</strong> <br/></th>
        <td><?php if($item_info){echo $item_info['nickname']."[ID:{$item_info['user_id']}]";} ?></td>
    </tr>
    <?php } ?>

    <tr>
        <th width="20%"><font color="red">*</font> <strong>姓名</strong> <br/></th>
        <td><input type="text" size="50" name="real_name" value="<?php if($item_info){echo $item_info['real_name'];}?>" valid="required" errmsg="请输入姓名">
        </td>
    </tr>
    <tr>
        <th width="20%"><font color="red">*</font> <strong>手机号</strong> <br/></th>
        <td><input type="text" size="50" name="mobile" value="<?php if($item_info){echo $item_info['mobile'];}?>" valid="required" errmsg="请输入手机号">
        </td>
    </tr>
    <?php if ($item_info){ ?>
    <tr>
        <th width="20%">
            <strong>报名时间</strong> <br/>
        </th>
        <td>
            <?php if(! empty($item_info)){echo date('Y-m-d H:i:s',$item_info['add_time']);}?>
        </td>
    </tr>
    <?php } ?>
    <tr>
      <td>&nbsp;</td>
      <td>
	  <input class="button_style" name="dosubmit" value=" 保存 " type="submit">
	  &nbsp;&nbsp; <input onclick="javascript:window.location.href='<?php echo $prfUrl; ?>';" class="button_style" name="reset" value=" 返回 " type="button">
	  </td>
    </tr>

</tbody>
</table>
    </div>
    <?php if ($progress_list){ ?>
    <table class="table_form" cellpadding="0" cellspacing="1">
        <caption>基本信息</caption>
        <tbody>
        <?php foreach ($progress_list as $value){ ?>

        <tr>
            <th width="20%">
                <?php echo date('Y-m-d H:i:s',$value['add_time']); ?>
            </th>
                <td>
                    <?php echo $value['nickname']."ID[{$value['user_id']}]  为他点亮了{$value['name']}"; ?>
                </td>
        </tr>
        <?php } ?>

        </tbody>
    </table>
        <?php } ?>

</form>
<style>
    .align_c{text-align: center}
</style>

