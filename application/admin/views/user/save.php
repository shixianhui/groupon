<?php echo $tool; ?>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" id="jsonForm" >
<table class="table_form" cellpadding="0" cellspacing="1">
  <caption>基本信息</caption>
 	<tbody>
<!--	<tr>-->
<!--      <th width="20%">-->
<!--      <font color="red">*</font> <strong>用户名</strong> <br/>-->
<!--	  </th>-->
<!--      <td>-->
<!--      <input class="input_blur" name="username" id="username" value="--><?php //if(! empty($itemInfo)){ echo $itemInfo['username'];} ?><!--" --><?php //if(! empty($itemInfo)){ echo 'readonly="true"';} ?><!-- size="50" maxlength="50" valid="required" errmsg="用户名不能为空!" type="text">-->
<!--	</td>-->
<!--    </tr>-->
<!--    <tr>-->
<!--      <th width="20%">-->
<!--      <font color="red">*</font> <strong>密&nbsp;&nbsp;&nbsp;码</strong> <br/>-->
<!--	  </th>-->
<!--      <td>-->
<!--      <input class="input_blur" name="password" id="password" value="" size="50" maxlength="50" --><?php //if(empty($itemInfo)){ echo 'valid="required" errmsg="密码不能为空!"';} ?><!-- type="password">-->
<!--	</td>-->
<!--    </tr>-->
<!--    <tr>-->
<!--      <th width="20%">-->
<!--      <font color="red">*</font> <strong>确认密码</strong> <br/>-->
<!--	  </th>-->
<!--      <td>-->
<!--      <input class="input_blur" name="ref_password" id="ref_password" value="" size="50" maxlength="50" valid="eqaul" eqaulName="password" errmsg="前后密码不一致!" type="password">-->
<!--	</td>-->
<!--    </tr>-->
<!--    <tr>-->
<!--      <th width="20%">-->
<!--      <strong>会员类型</strong> <br/>-->
<!--	  </th>-->
<!--      <td>-->
<!--      <input type="radio" value="0" name="type" class="radio_style" --><?php //if($itemInfo){if($itemInfo['type']=='0'){echo 'checked="checked"';}}else{echo 'checked="checked"';} ?><!-- > 普通会员-->
<!--      <input type="radio" value="1" name="type" class="radio_style" --><?php //if($itemInfo){if($itemInfo['type']=='1'){echo 'checked="checked"';}} ?><!-- > VIP会员-->
<!--      <input type="radio" value="2" name="type" class="radio_style" --><?php //if($itemInfo){if($itemInfo['type']=='2'){echo 'checked="checked"';}} ?><!-- > 策略分析师-->
<!--	</td>-->
<!--    </tr>-->
<!--    <tr>-->
<!--    <th width="20%">-->
<!--    <font color="red">*</font> <strong>头像</strong> <br/>-->
<!--	</th>-->
<!--    <td>-->
<!--    <input name="model" id="model"  value="--><?php //echo $template; ?><!--" type="hidden" />-->
<!--    <input valid="required" errmsg="头像不能为空!" name="path" id="path" size="50" class="input_blur" value="--><?php //if(! empty($itemInfo)){ echo $itemInfo['path'];} ?><!--" type="text" />-->
<!--    <input class="button_style" name="upload_image" id="upload_image" value="上传图片" style="width: 60px;"  type="button" />  <input class="button_style" value="浏览..."-->
<!--style="cursor: pointer;" name="select_image" id="select_image" type="button" /> <input class="button_style" style="cursor: pointer;"  name="cut_image" id="cut_image" value="裁剪图片" type="button"  />-->
<!--    </td>-->
<!--    </tr>-->
<!--    <tr>-->
<!--      <th width="20%">-->
<!--      <font color="red">*</font> <strong>姓名</strong> <br/>-->
<!--	  </th>-->
<!--      <td>-->
<!--     <input valid="required" errmsg="姓名不能为空!" class="input_blur" name="real_name" id="real_name" value="--><?php //if(! empty($itemInfo)){ echo $itemInfo['real_name'];} ?><!--" size="50" type="text">-->
<!--	</td>-->
<!--    </tr>-->
<!--    <tr>-->
<!--      <th width="20%">-->
<!--      <strong>性别</strong> <br/>-->
<!--	  </th>-->
<!--      <td>-->
<!--      <input type="radio" value="0" name="sex" class="radio_style" --><?php //if($itemInfo){if($itemInfo['sex']=='0'){echo 'checked="checked"';}}else{echo 'checked="checked"';} ?><!-- > 未知-->
<!--      <input type="radio" value="1" name="sex" class="radio_style" --><?php //if($itemInfo){if($itemInfo['sex']=='1'){echo 'checked="checked"';}} ?><!-- > 男-->
<!--      <input type="radio" value="2" name="sex" class="radio_style" --><?php //if($itemInfo){if($itemInfo['sex']=='2'){echo 'checked="checked"';}} ?><!-- > 女-->
<!--	</td>-->
<!--	</tr>-->
<!--    <tr>-->
<!--      <th width="20%"><font color="red">*</font> <strong>QQ号</strong> <br/>-->
<!--	  </th>-->
<!--      <td>-->
<!--     <input class="input_blur" name="qq" id="qq" value="--><?php //if(! empty($itemInfo)){ echo $itemInfo['qq'];} ?><!--" size="50" valid="required|isQQ" errmsg="QQ号不能为空!|QQ号格式错误!" type="text">-->
<!--	</td>-->
<!--    </tr>-->
<!--     <tr>-->
<!--      <th width="20%"><font color="red">*</font> <strong>手机</strong> <br/>-->
<!--	  </th>-->
<!--      <td>-->
<!--     <input valid="required" errmsg="手机号码不能为空!" class="input_blur" name="mobile" id="mobile" value="--><?php //if(! empty($itemInfo)){ echo $itemInfo['mobile'];} ?><!--" size="50" type="text">-->
<!--	</td>-->
<!--    </tr>    -->
<!--    <tr>-->
<!--      <th width="20%"> <strong>邮件</strong> <br/>-->
<!--	  </th>-->
<!--      <td>-->
<!--     <input class="input_blur" name="email" id="email" value="--><?php //if(! empty($itemInfo)){ echo $itemInfo['email'];} ?><!--" size="50" type="text">-->
<!--	</td>-->
<!--    </tr>-->
<!--    <tr>-->
<!--      <th width="20%"><strong>积分</strong> <br/>-->
<!--	  </th>-->
<!--      <td>-->
<!--     <input class="input_blur" name="score" id="score" value="--><?php //if(! empty($itemInfo)){ echo $itemInfo['score'];}else{echo '0';} ?><!--" size="50" type="text">-->
<!--	</td>-->
<!--    </tr>-->
	<tr>
      <th width="20%">
       <strong>昵称</strong> <br/>
	  </th>
      <td>
      <?php if(! empty($itemInfo)){ echo $itemInfo['nickname'];} ?>
	</td>
    </tr>
<tr>
    <th width="20%">
        <strong>昵称</strong> <br/>
    </th>
    <td>
        <?php if(! empty($itemInfo)){ echo $sexArr[$itemInfo['sex']];} ?>
    </td>
</tr>
<tr>
    <th width="20%">
        <strong>注册时间</strong> <br/>
    </th>
    <td>
        <?php if(! empty($itemInfo)){ echo date('Y-m-d H:i:s',$itemInfo['add_time']);} ?>
    </td>
</tr>
<tr>
    <th width="20%">
        <strong>联系地址</strong> <br/>
    </th>
    <td>
        <?php if(! empty($user_address_list)){
            foreach ($user_address_list as $value){ ?>
                <?php echo $value['txt_address'].$value['address']; ?><br>
                联系人：<?php echo $value['buyer_name']; ?><br>
                手机：<?php echo $value['mobile']; ?><br><br>
        <?php }} ?>
    </td>
</tr>
    <tr>
      <td>&nbsp;</td>
      <td>
<!--	  <input class="button_style" name="dosubmit" value=" 保存 " type="submit">-->
	  &nbsp;&nbsp; <input onclick="javascript:window.location.href='<?php echo $prfUrl; ?>';" class="button_style" name="reset" value=" 返回 " type="button">
	  </td>
    </tr>
</tbody>
</table>
</form>
<script type="text/javascript">
function works_upload_image(div_id) {
	var imagePath = $("#"+div_id).val();
	var model = $("#model_works").val();
	var parseImagePath = imagePath.replace(/\//g, ":");
    parseImagePath = parseImagePath.replace(/\./g, "_");
	window.open(base_url+"admincp.php/upload/works_index/"+model+"/"+div_id+"/"+parseImagePath, "upload", "top=100, left=200, width=500, height=400, scrollbars=1, resizable=yes");	
}

function works_cut_image(div_id) {
	var imagePath = $("#"+div_id).val();
	var model = $("#model_works").val();
	var cutImagePath = imagePath.replace(/\//g, ":");
	    cutImagePath = cutImagePath.replace(/\./g, "_");
	window.open(base_url+"admincp.php/upload/cutWorksPicture/"+model+"/"+div_id+"/"+cutImagePath, "cutpic", "top=100, left=200, width=700, height=500, scrollbars=1, resizable=yes");
}
</script>