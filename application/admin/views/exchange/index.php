<div id="position" >
    <strong>当前位置：</strong>
    <a href="javascript:void(0);">订单管理</a>
    <a href="javascript:void(0);">退款申请管理</a>
</div>
<br />
<table class="table_form" cellpadding="0" cellspacing="1">
    <caption>快捷方式</caption>
    <tbody>
    <tr>
        <td>
            <a href="admincp.php/<?php echo $table; ?>/index/1"><span id="<?php echo $table; ?>_">退款申请列表</span></a> |
            <a href="admincp.php/exchange/export"><span id="exchange_export">导出数据</span></a>
        </td>
    </tr>
    </tbody>
</table>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
<table class="table_form" cellpadding="0" cellspacing="1">
  <caption>信息查询</caption>
<tbody>
<tr>
<td class="align_c">订单号 <input class="input_blur" name="order_number" id="order_number" size="20" type="text">&nbsp;
手机号 <input class="input_blur" name="mobile" id="mobile" size="20" type="text">&nbsp;
<select class="input_blur" name="status">
    <option value="">选择状态</option>
    <?php if ($status_arr) { ?>
        <?php foreach ($status_arr as $key=>$value) { ?>
            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
        <?php }} ?>
</select>&nbsp;
发布时间 <input class="input_blur" name="inputdate_start" id="inputdate_start" size="10" readonly="readonly" type="text">&nbsp;<script language="javascript" type="text/javascript">
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
        <th width="120">退款订单号</th>
        <th width="50">客户</th>
        <th width="60">手机号</th>
        <th>订单描述</th>
        <th width="120">活动描述</th>
        <th width="120">地址</th>
        <th width="80">退款金额</th>
        <th width="120">申请时间</th>
        <th width="80">申请人</th>
        <th width="80">推荐人</th>
        <th width="100">状态</th>
        <th width="100">后台备注</th>
        <th width="70">管理操作</th>
    </tr>
    <?php if (! empty($item_list)): ?>
        <?php foreach ($item_list as $key=>$value): ?>
            <tr id="id_<?php echo $value['id']; ?>"  onMouseOver="this.style.backgroundColor='#ECF7FE'" onMouseOut="this.style.background='#FFFFFF'">
                <td><input  class="checkbox_style" name="ids" value="<?php echo $value['id']; ?>" type="checkbox"> <?php echo $value['id']; ?></td>
                <td class="align_c"><?php echo $value['order_num']; ?></td>
                <td class="align_c"><?php echo $value['order_info']['buyer_name']; ?><br/></td>
                <td class="align_c"><?php echo $value['order_info']['mobile']; ?><br/></td>
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
                <td class="align_c"><?php echo $value['name_format']; ?></td>
                <td class="align_c"><?php echo $value['order_info']['txt_address'].$value['order_info']['address']; ?><br/></td>
                <td class="align_c"><?php echo $value['price']; ?></td>
                <td class="align_c"><?php echo date("Y-m-d H:i:s", $value['add_time']); ?></td>
                <td class="align_c">
                    <?php echo $value['username']; ?>
                    <br/>
                    （ID:<?php echo $value['user_id']; ?>）
                </td>
                <td class="align_c"><?php echo $value['parent_info']; ?></td>
                <td class="align_c"><?php echo $status_arr[$value['status']]; ?><br><?php if ($value['order_info']['is_free']){ echo '<font color="red">(免单)</font>'; } ?></td>
                <td class="align_c"><?php echo $value['admin_remark']; ?></td>
                <td class="align_c"><a href="javascript:void(0);" onclick="change_check('<?php echo $value['id']; ?>','<?php echo $value['order_num']; ?>','<?php echo date('Y-m-d H:i:s', $value['add_time']); ?>','<?php echo $value['price']; ?>')">处理</a></td>
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
<script>
    function change_check(id, order_num, add_time, total) {
        var html = '<table class="table_form" cellpadding="0" cellspacing="1">';
        html += '<tr><th width="30%"><strong>退款订单编号</strong> <br/></th>';
        html += '<td>'+order_num+'</td></tr>';
        html += '<tr><th width="30%"><strong>申请时间</strong> <br/></th>';
        html += '<td>'+add_time+'</td></tr>';
        html += '<tr><th width="30%"><strong>退款金额</strong> <br/></th>';
        html += '<td>'+total+' 元</td></tr>';
        html += '<tr><th width="30%"><font color="red">*</font> <strong>审核状态</strong><br/></th>';
        html += '<td>';
        html += '<label><input type="radio" value="2" name="status" class="radio_style"> 审核通过</label>';
        html += '&nbsp;<label><input type="radio" value="1" name="status" class="radio_style"> 审核未通过</label>';
        html += '&nbsp;<label><input type="radio" value="3" name="status" class="radio_style"> 退款成功</label>';
        html += '</td>';
        html += '</tr>';
//        html += '<tr><th width="30%"><strong>备注</strong><br/><font color="red">[会员看的]</font></th>';
//        html += '<td>';
//        html += '<textarea maxlength="140" id="client_remark" rows="4" cols="30"  class="textarea_style"></textarea>';
//        html += '</td>';
//        html += '</tr>';
        html += '<tr><th width="30%"> <strong>备注</strong><br/><font color="red">[管理员看的]</font></th>';
        html += '<td>';
        html += '<textarea maxlength="140" id="admin_remark" rows="4" cols="30"  class="textarea_style"></textarea>';
        html += '</td>';
        html += '</tr>';
        html += '</table>';
        var d = dialog({
            width:350,
            fixed: true,
            title: '退款审核提示',
            content: html,
            okValue: '确认',
            ok: function () {
                var status = $('input[name="status"]:checked').val();
//                var client_remark = $('#client_remark').val();
                var admin_remark = $('#admin_remark').val();
                if (!status) {
                    return my_alert('fail', 0, '请选择状态');
                }
                if (status == 1) {
//                    if (!client_remark) {
//                        return my_alert('client_remark', 1, '备注不能为空');
//                    }
                    if (!admin_remark) {
                        return my_alert('admin_remark', 1, '备注不能为空');
                    }
                }
                $.post(base_url+"admincp.php/"+controller+"/change_check",
                    {	"id": id,
                        "status":status,
//                        "client_remark":client_remark,
                        "admin_remark":admin_remark
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