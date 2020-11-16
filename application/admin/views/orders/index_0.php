<?php echo $tool; ?>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
<table class="table_form" cellpadding="0" cellspacing="1">
  <caption>信息查询</caption>
<tbody>
<tr>
    <td class="align_c">
        订单编号 <input class="input_blur" name="order_number" id="order_number" size="20" type="text">&nbsp;
        客户姓名 <input class="input_blur" name="buyer_name" id="buyer_name" size="20" type="text">&nbsp;
        手机号码 <input class="input_blur" name="mobile" id="mobile" size="20" type="text">&nbsp;
        <select name="status" style="height: 22px">
            <option value="">订单状态</option>
            <?php
            foreach($status_arr as $key=>$value){
                ?>
                <option value="<?php echo $key;?>"><?php echo $value;?></option>
            <?php }?>
        </select>
        下单时间 <input class="input_blur" name="inputdate_start" id="inputdate_start" size="10" readonly="readonly" type="text">&nbsp;<script language="javascript" type="text/javascript">
            date = new Date();
            Calendar.setup({
                inputField     :    "inputdate_start",
                ifFormat       :    "%Y-%m-%d",
                showsTime      :    false,
                timeFormat     :    "24"
            });
        </script> - <input class="input_blur" name="inputdate_end"
                           id="inputdate_end" size="10"  readonly="readonly" type="text">&nbsp;<script language="javascript" type="text/javascript">
            date = new Date();
            Calendar.setup({
                inputField     :    "inputdate_end",
                ifFormat       :    "%Y-%m-%d",
                showsTime      :    false,
                timeFormat     :    "24"
            });
        </script>&nbsp;
        <input class="button_style" name="dosubmit" value=" 查询 " type="submit">
    </td>
</tr>
</tbody>
</table>
</form>
<table class="table_list" id="news_list" cellpadding="0" cellspacing="1">
    <caption>信息管理</caption>
    <tbody>
    <tr class="mouseover">
        <th width="50">选中</th>
        <th width="80">订单编号</th>
        <th width="50">客户姓名</th>
        <th width="60">手机</th>
        <th>订单描述</th>
        <th width="50">城市</th>
        <th width="120">地址</th>
        <th width="150">时间</th>
        <th width="60">订单状态</th>
        <th width="100">总金额</th>
        <th width="50">人数</th>
        <th width="50">推荐人</th>
        <th>备注</th>
        <th width="90">操作</th>
    </tr>
<?php if (! empty($item_list)): ?>
    <?php foreach ($item_list as $key=>$value): ?>
        <tr id="id_<?php echo $value['id']; ?>"  onMouseOver="this.style.backgroundColor='#ECF7FE'" onMouseOut="this.style.background=''">
            <td><input  class="checkbox_style" name="ids" value="<?php echo $value['id']; ?>" type="checkbox">  <?php echo $value['id']; ?></td>
            <td class="align_c">
                <?php echo $value['order_number']; ?><br/>
            </td>
            <td class="align_c"><?php echo $value['buyer_name']; ?><br/></td>
            <td class="align_c"><?php echo $value['mobile']; ?><br/></td>
            <td class="align_c">
                <table  width="100%" cellpadding="0" cellspacing="1">
                    <?php if ($value['orderdetailList']) { ?>
                        <?php foreach ($value['orderdetailList'] as $key=>$orderdetail) {
                            $strClass = 'table_td';
                            if ($key+1 == count($value['orderdetailList'])) {
                                $strClass = '';
                            }
                            ?>
                            <tr>
                                <td class="align_c" width="60" class="<?php echo $strClass; ?>">
                                    <img src="<?php if ($orderdetail['path']){ echo preg_replace('/\./', '_thumb.', $orderdetail['path']);;}else{echo 'images/admin/nopic.gif';} ?>" width="50px" height="50px" />
                                </td>
                                <td class="<?php echo $strClass; ?>">
                                    <span><?php echo $orderdetail['product_title']; ?></span><br/>
                                    <span style="color: #999;">产品编号：<?php echo $orderdetail['product_num']; ?></span><br/>
                                    <?php if ($orderdetail['color_size_open']) { ?>
                                        <span style="color: #999;"><?php echo $orderdetail['product_color_name']; ?>：<?php echo $orderdetail['color_name']; ?> <?php echo $orderdetail['product_size_name']; ?>：<?php echo $orderdetail['size_name']; ?></span>
                                    <?php } ?>
                                </td>
                                <td class="<?php echo $strClass; ?>" style="width: 60px;text-align:center;" title="单价">¥<?php echo number_format($orderdetail['buy_price'], 2, '.', ''); ?></td>
                                <td class="<?php echo $strClass; ?>" style="width: 20px;text-align:center;" title="购买数量"><?php echo $orderdetail['buy_number']; ?></td>
                            </tr>
                        <?php }} ?>
                </table>
            </td>
            <td class="align_c"><?php echo $value['city']; ?><br/></td>
            <td class="align_c"><?php echo $value['address']; ?><br/></td>

            <td>
                下单：<?php echo date("Y-m-d H:i", $value['add_time']); ?><br/>
                付款：<?php echo $value['pay_time'] ? date("Y-m-d H:i", $value['pay_time']) : ''; ?>
            </td>
            <td class="align_c"><?php echo $status_arr[$value['status']]; ?></td>
            <td class="align_c">
                <span class="priceColor">¥<?php echo $value['total']; ?></span><br/>
                <?php if ($value['deposit'] > 0) { ?>
                    (定金：¥<?php echo $value['deposit']; ?>)
                <?php } ?>
            </td>
            <td class="align_c"><?php echo $value['join_people']; ?><br/></td>
            <td class="align_c"><?php echo $value['parent_info']; ?><br/></td>
            <td class="align_c"><?php echo $value['remark']; ?><br/></td>
<td class="align_c">
<span style="line-height:25px;"><a onclick="javascript:change_pay(<?php echo $value['id']; ?>,'<?php echo $value['order_number']; ?>','<?php echo date('Y-m-d H:i:s', $value['add_time']); ?>','<?php echo $value['total']; ?>');" href="javascript:void(0);" title="用于线下付款修改订单状态">设为已付款</a></span><br/>
<span style="line-height:25px;"><a onclick="javascript:change_price(<?php echo $value['id']; ?>,'<?php echo $value['order_number']; ?>','<?php echo date('Y-m-d H:i:s', $value['add_time']); ?>','<?php echo $value['total']; ?>');" href="javascript:void(0);">修改金额</a></span><br/>
<span style="line-height:25px;"><a onclick="javascript:close_order(<?php echo $value['id']; ?>,'<?php echo $value['order_number']; ?>','<?php echo date('Y-m-d H:i:s', $value['add_time']); ?>','<?php echo $value['total']; ?>');" href="javascript:void(0);">交易关闭</a></span><br/>
<span style="line-height:25px;"><a href="admincp.php/<?php echo $table; ?>/view/<?php echo $value['id']; ?>">详情</a></span>
</td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>
<div class="button_box">
<span style="width: 60px;"><a href="javascript:void(0);" onclick="javascript:$('input[type=checkbox]').prop('checked', true)">全选</a>/
<a href="javascript:void(0);" onclick="javascript:$('input[type=checkbox]').prop('checked', false)">取消</a></span>
<input class="button_style" name="delete" id="delete" value=" 删除 "  type="button">
</div>
<div id="pages" style="margin-top: 5px;">
<?php echo $pagination; ?>
<a>总条数：<?php echo $paginationCount; ?></a>
<a>总页数：<?php echo $pageCount; ?></a>
</div>
<br/><br/>
<script  type="text/javascript">
//修改金额
function change_price(id, order_num, add_time, total) {
	var html = '<table class="table_form" cellpadding="0" cellspacing="1">';
		html += '<tr><th width="30%"><strong>订单编号</strong> <br/></th>';
		html += '<td>'+order_num+'</td></tr>';
		html += '<tr><th width="30%"><strong>下单时间</strong> <br/></th>';
		html += '<td>'+add_time+'</td></tr>';
		html += '<tr><th width="30%"><strong>订单金额</strong> <br/></th>';
		html += '<td>'+total+'元</td></tr>';
		html += '<tr><th width="30%"> <strong>订单金额修改为</strong> <br/></th>';
		html += '<td>';
		html += '<input id="order_total" value="'+total+'" size="10" class="input_blur" type="text" /> 元';
		html += '</td>';
		html += '</tr>';
		html += '</table>';
	var d = dialog({
		width:350,
		fixed: true,
	    title: '修改订单金额提示',
	    content: html,
	    okValue: '确认修改订单金额',
	    ok: function () {
		    var order_total = $('#order_total').val();
		    if (!order_total) {
                return my_alert('order_total', 1, '订单金额不能为空');
			}
			if (total == order_total) {
				return my_alert('order_total', 1, '修改金额不能跟原金额一样');
			}
	        $.post(base_url+"admincp.php/"+controller+"/change_price",
    				{	"id": id,
				        "order_total":order_total
    				},
    				function(res){
    					if(res.success){
                            return my_alert_flush('fail', 0, res.message);
    					} else {
        					if (res.field == 'fail') {
        						return my_alert('fail', 0, res.message);
            				} else {
            					return my_alert(res.field, 1, res.message);
                		    }
    					}
    				},
    				"json"
    		);
		    return false;
	    },
	    cancelValue: '取消',
	    cancel: function () {
	    }
	});
	d.show();
}

//关闭交易
function close_order(id, order_num, add_time, total) {
	var html = '<table class="table_form" cellpadding="0" cellspacing="1">';
		html += '<tr><th width="30%"><strong>订单编号</strong> <br/></th>';
		html += '<td>'+order_num+'</td></tr>';
		html += '<tr><th width="30%"><strong>下单时间</strong> <br/></th>';
		html += '<td>'+add_time+'</td></tr>';
		html += '<tr><th width="30%"><strong>运费</strong> <br/></th>';
		html += '<td>'+total+'元</td></tr>';
		html += '<tr><th width="30%"> <strong>关闭原因</strong> <br/></th>';
		html += '<td>';
		html += '<textarea id="cancel_cause" rows="4" cols="30"  class="textarea_style"></textarea>';
		html += '</td>';
		html += '</tr>';
		html += '</table>';
	var d = dialog({
		width:350,
		fixed: true,
	    title: '关闭交易提示',
	    content: html,
	    okValue: '确认关闭交易',
	    ok: function () {
		    var cancel_cause = $('#cancel_cause').val();
		    if (!cancel_cause) {
                return my_alert('cancel_cause', 1, '请输入关闭原因');
			}
	        $.post(base_url+"admincp.php/"+controller+"/close_order",
    				{	"id": id,
				        "cancel_cause":cancel_cause
    				},
    				function(res){
    					if(res.success){
                            return my_alert_flush('fail', 0, res.message);
    					} else {
        					if (res.field == 'fail') {
        						return my_alert('fail', 0, res.message);
            				} else {
            					return my_alert(res.field, 1, res.message);
                		    }
    					}
    				},
    				"json"
    		);
		    return false;
	    },
	    cancelValue: '取消',
	    cancel: function () {
	    }
	});
	d.show();
}

//状态设为已付款
function change_pay(id, order_num, add_time, total) {
	var html = '<font color="red">注：确定要将订单状态修改为已付款吗，请确认已线下收到客户的打款？</font>';
	    html += '<table class="table_form" cellpadding="0" cellspacing="1">';
		html += '<tr><th width="30%"><strong>订单编号</strong> <br/></th>';
		html += '<td>'+order_num+'</td></tr>';
		html += '<tr><th width="30%"><strong>下单时间</strong> <br/></th>';
		html += '<td>'+add_time+'</td></tr>';
		html += '<tr><th width="30%"><strong>订单金额</strong> <br/></th>';
		html += '<td>'+total+'元</td></tr>';
		html += '<tr><th width="30%"> <strong>备注</strong> <br/></th>';
		html += '<td>';
		html += '<textarea id="remark" placeholder="请输入打款凭证" rows="4" cols="35"  class="textarea_style"></textarea>';
		html += '</td>';
		html += '</tr>';
		html += '</table>';
	var d = dialog({
		width:400,
		fixed: true,
	    title: '修改订单状态提示',
	    content: html,
	    okValue: '确认',
	    ok: function () {
		    var remark = $('#remark').val();
		    if (!remark) {
                return my_alert('remark', 1, '请输入确认');
			}
	        $.post(base_url+"admincp.php/"+controller+"/change_pay",
    				{	"id": id,
				        "remark":remark
    				},
    				function(res){
    					if(res.success){
                            return my_alert_flush('fail', 0, res.message);
    					} else {
        					if (res.field == 'fail') {
        						return my_alert('fail', 0, res.message);
            				} else {
            					return my_alert(res.field, 1, res.message);
                		    }
    					}
    				},
    				"json"
    		);
		    return false;
	    },
	    cancelValue: '取消',
	    cancel: function () {
	    }
	});
	d.show();
}
</script>