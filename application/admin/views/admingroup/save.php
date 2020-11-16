<?php echo $tool; ?>
<table class="table_form" cellpadding="0" cellspacing="1">
  <caption>基本信息</caption>
 	<tbody>
	<tr>
      <th width="20%">
      <font color="red">*</font> <strong>管理员名称</strong> <br/>
	  </th>
      <td>
      <input name="id" id="id" value="<?php if(! empty($itemInfo)){ echo $itemInfo['id'];} ?>" type="hidden">
      <input class="input_blur" name="group_name" id="group_name" value="<?php if(! empty($itemInfo)){ echo $itemInfo['group_name'];} ?>" size="50" maxlength="50" valid="required" errmsg="角色不能为空!" type="text">
	</td>
    </tr>
    <tr>
      <th width="20%">
      <font color="red">*</font> <strong>设置权限</strong> <br/>
	  </th>
      <td>
      <font color="red">注：查看权限优先；删除，修改等权限一般在列表上才能看到</font><br/>
      <div id="permissions_tree"></div>
	</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>
	  <input class="button_style" name="btn_admin_group_save" id="btn_admin_group_save" value=" 保存 " type="button">
	  &nbsp;&nbsp; <input onclick="javascript:window.location.href='<?php echo $prfUrl; ?>';" class="button_style" name="reset" value=" 返回 " type="button">
	  </td>
    </tr>
</tbody>
</table>
</div>
<script type="text/javascript" src="js/admin/jquery.tree.js"></script>
<script type="text/javascript" src="js/admin/jquery.tree.checkbox.js"></script>
<script>	
    //==================================管理员组权限====================================
	var permissions = "<?php if(! empty($itemInfo)){ echo $itemInfo['permissions'];} ?>".split(',');    
    var permissionsList = [{
		data: '频道管理 ',
		attributes:{'permission':'menu'},
		state: "open",
		children:[{data: '查看',attributes:{'permission':'menu_menuList'}},
			  	  {data: '添加',attributes:{'permission':'menu_add'}},
		          {data: '修改',attributes:{'permission':'menu_edit'}},
		          {data: '删除',attributes:{'permission':'menu_delete'}},
		          {data: '排序',attributes:{'permission':'menu_sort'}}]
		},{
		data: '内容管理',
		attributes:{'permission':'content'},
		state: "close",
		children:[
			  	<?php if ($patternList) { ?>
			  	<?php foreach ($patternList as $key=>$value) {
			  	      $str_class = ',';
			  	      if (count($patternList) == $key+1) {
			  	          $str_class = '';
			  	      }
			  	    ?>
			  		{
			       data: '<?php echo $value['title']; ?>',
				   attributes:{'permission':'<?php echo $value['file_name']; ?>'},
				   state: "open",
				   children:[
                             <?php if ($value['file_name'] == 'sitemap') { ?>
                             {data: '查看',attributes:{'permission':'<?php echo $value['file_name']; ?>_index'}}
                             <?php } else if ($value['file_name'] == 'guestbook') { ?>
                             {data: '查看',attributes:{'permission':'<?php echo $value['file_name']; ?>_index'}},
				             {data: '修改',attributes:{'permission':'<?php echo $value['file_name']; ?>_edit'}},
				             {data: '删除',attributes:{'permission':'<?php echo $value['file_name']; ?>_delete'}}
                             <?php } else { ?>
							 {data: '查看',attributes:{'permission':'<?php echo $value['file_name']; ?>_index'}},
						     {data: '添加',attributes:{'permission':'<?php echo $value['file_name']; ?>_add'}},
				             {data: '修改',attributes:{'permission':'<?php echo $value['file_name']; ?>_edit'}},
				             {data: '删除',attributes:{'permission':'<?php echo $value['file_name']; ?>_delete'}}
				             <?php if ($value['file_name'] != 'link' && $value['file_name'] != 'job' && $is_html) { ?>
				             ,
				             {data: '查看html',attributes:{'permission':'<?php echo $value['file_name']; ?>_html'}},
				             {data: '更新html',attributes:{'permission':'<?php echo $value['file_name']; ?>_htmlUpdate'}},
				             {data: '删除html',attributes:{'permission':'<?php echo $value['file_name']; ?>_htmlDelete'}}
				             <?php }} ?>
				             ]
			      }
                <?php echo $str_class; }} ?>
			      ]
		},{
        data: '商品活动 ',
        attributes:{'permission':'product_manage'},
        state: "close",
        children:[{
            data: '商品管理',
            attributes:{'permission':'product'},
            state : "open",
            children:[{data: '查看',attributes:{'permission':'product_index'}},
                {data: '添加',attributes:{'permission':'product_add'}},
                {data: '修改',attributes:{'permission':'product_edit'}},
                {data: '删除',attributes:{'permission':'product_delete'}}]
        },{
            data: '活动管理',
            attributes:{'permission':'groupon'},
            state : "open",
            children:[{data: '查看',attributes:{'permission':'groupon_index'}},
                {data: '添加',attributes:{'permission':'groupon_add'}},
                {data: '修改',attributes:{'permission':'groupon_edit'}},
                {data: '删除',attributes:{'permission':'groupon_delete'}}]
        }
        ]
    },{
        data: '订单管理 ',
        attributes:{'permission':'orders_manage'},
        state: "close",
        children:[{
            data: '订单列表',
            attributes:{'permission':'orders'},
            state : "open",
            children:[{data: '查看',attributes:{'permission':'orders_index'}},
                {data: '添加',attributes:{'permission':'orders_add'}},
                {data: '修改',attributes:{'permission':'orders_edit'}},
                {data: '详情',attributes:{'permission':'orders_view'}},
                {data: '删除',attributes:{'permission':'orders_delete'}}]
        },{
            data: '拼团记录列表',
            attributes:{'permission':'groupon_record'},
            state : "open",
            children:[{data: '查看',attributes:{'permission':'groupon_record_index'}},
                {data: '添加',attributes:{'permission':'orders_add'}},
                {data: '修改',attributes:{'permission':'groupon_record_edit'}},
                {data: '详情',attributes:{'permission':'orders_view'}},
                {data: '删除',attributes:{'permission':'orders_delete'}}]
        },{
            data: '退款申请管理',
            attributes:{'permission':'exchange'},
            state : "open",
            children:[{data: '查看',attributes:{'permission':'exchange_index'}},
                {data: '添加',attributes:{'permission':'exchange_add'}},
                {data: '修改',attributes:{'permission':'exchange_edit'}},
                {data: '删除',attributes:{'permission':'exchange_delete'}}]
        }
        ]
    },{
		    data: '会员管理 ',
			attributes:{'permission':'user_g'},
			state: "close",
			children:[{
    			data: '会员列表',
    			attributes:{'permission':'user'},
    			state: "open",
    			children:[{data: '查看',attributes:{'permission':'user_index'}},
    			          {data: '添加',attributes:{'permission':'user_add'}},
    			          {data: '修改',attributes:{'permission':'user_edit'}},
    			          {data: '删除',attributes:{'permission':'user_delete'}}]
    		},{
    			data: '会员组列表',
    			attributes:{'permission':'usergroup'},
    			state: "open",
    			children:[{data: '查看',attributes:{'permission':'usergroup_index'}},
    	    			  {data: '添加',attributes:{'permission':'usergroup_add'}},
    	    			  {data: '修改',attributes:{'permission':'usergroup_edit'}},
    	    			  {data: '删除',attributes:{'permission':'usergroup_delete'}}]
    		}]
		},{
	    data: '管理员管理 ',
		attributes:{'permission':'admin_g'},
		state: "close",
		children:[{
			       data: '管理员列表',
			       attributes:{'permission':'admin'},
			       state: "open",
			       children:[{data: '查看',attributes:{'permission':'admin_index'}},
						     {data: '添加',attributes:{'permission':'admin_add'}},
						     {data: '修改',attributes:{'permission':'admin_edit'}},
						     {data: '删除',attributes:{'permission':'admin_delete'}}]
		          },{
			       data: '管理组列表',
			       attributes:{'permission':'admingroup'},
			       state: "open",
	               children:[{data: '查看',attributes:{'permission':'admingroup_index'}},
		      	             {data: '添加',attributes:{'permission':'admingroup_add'}},
				             {data: '修改',attributes:{'permission':'admingroup_edit'}},
				             {data: '删除',attributes:{'permission':'admingroup_delete'}}]
				 }]
		},{
			data: '生成静态 ',
			attributes:{'permission':'html'},
			state: "close",
			children:[{data: '生成静态 ',attributes:{'permission':'html_index'}}]
		},{
		    data: '广告管理  ',
			attributes:{'permission':'ad_g'},
			state: "close",
			children:[{
				       data: '广告内容管理',
				       attributes:{'permission':'ad'},
				       state: "open",
				       children:[{data: '查看',attributes:{'permission':'ad_index'}},
							     {data: '添加',attributes:{'permission':'ad_add'}},
							     {data: '修改',attributes:{'permission':'ad_edit'}},
							     {data: '删除',attributes:{'permission':'ad_delete'}},
							     {data: '排序',attributes:{'permission':'ad_sort'}}]
			          },{
				       data: '广告位管理',
				       attributes:{'permission':'adgroup'},
				       state: "open",
		               children:[{data: '查看',attributes:{'permission':'adgroup_index'}},
			      	             {data: '添加',attributes:{'permission':'adgroup_add'}},
					             {data: '修改',attributes:{'permission':'adgroup_edit'}},
					             {data: '删除',attributes:{'permission':'adgroup_delete'}}]
					 }]
		},{
			data: '系统设置',
			attributes:{'permission':'system'},
			state: "close",
			children:[{data: '基本设置',attributes:{'permission':'system_save'}},
			          {data: '图片水印设置',attributes:{'permission':'watermark_save'}}]
		},{
		    data: '系统维护  ',
			attributes:{'permission':'system_wf'},
			state: "close",
			children:[{
			       data: '数据库备份',
			       attributes:{'permission':'backup'},
			       state: "open",
	               children:[{data: '查看',attributes:{'permission':'backup_index'}},
		      	             {data: '优化',attributes:{'permission':'backup_optimize'}},
			      	         {data: '修复',attributes:{'permission':'backup_repair'}},
				      	     {data: '备份',attributes:{'permission':'backup_backupDatabase'}}]
				   },{
				       data: '文件管理',
				       attributes:{'permission':'file'},
				       state: "open",
		               children:[{data: '查看',attributes:{'permission':'file_index'}},
			      	             {data: '删除',attributes:{'permission':'file_deleteFile'}}]
					 }]
	    },{
			data: '系统登录日志',
			attributes:{'permission':'systemloginlog'},
			state: "close",
			children:[{data: '登录日志',attributes:{'permission':'systemloginlog_index'}}]
		}];
	if ($("#permissions_tree").size()) {
	$("#permissions_tree").tree({
		data: {
			'type': "json",
			opts: {
				'static': {
					data: "所有权限",
					children: permissionsList,
					state: "open"
				}
			}
		},
		ui: {
			theme_name: "checkbox"
		},
		plugins: {
			checkbox: {}
		},
		types: {
			'default':{
				draggable	: false,
			}
		}
	});
	if(permissions){
		$.each($("#permissions_tree li"),function(i,n){
		    if(jQuery.inArray($(n).attr('permission'),permissions)!=-1){
		        $(n).children('a')[0].className = 'checked';
				}
		});
		
	}
	}
$(document).ready(function() {
	$("#btn_admin_group_save").click(function(){
		var $id = $("#id").val();
		var $group_name = $("#group_name").val();
		var $permission = '';
		$.each($("#permissions_tree a.checked"),function(i,n){
    		if($(n).parent().attr('permission')){
    			$permission += $(n).parent().attr('permission')+',';
    		}
		})
		if (! $group_name) {
			alert('管理组名称不能为空！');
			$("#group_name").focus();
			return false;
		}
		if (! $permission) {
			alert('权限设置不能为空！');
			return false;
		}
		$.post(base_url+"admincp.php/admingroup/save/"+$id, 
				{	"group_name": $group_name,
			        "permissions": $permission.substr(0, $permission.length-1)
				},
				function(res){
					if(res.success){
						window.location.href = res.url;
						return false;
					}else{
						alert(res.message);
						return false;
					}
				},
				"json"
		);
	});
});
</script>