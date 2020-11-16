<div id="position" >
    <strong>当前位置：</strong>
    <a href="javascript:void(0);">订单管理</a>
    <a href="javascript:void(0);">拼团记录列表</a>
</div>
<br />
<table class="table_form" cellpadding="0" cellspacing="1">
    <caption>快捷方式</caption>
    <tbody>
    <tr>
        <td>
            <a href="admincp.php/<?php echo $table; ?>/index/1"><span id="<?php echo $table; ?>_">拼团记录列表</span></a> |
            <a href="admincp.php/groupon_record/export"><span id="groupon_record_export">导出数据</span></a>
        </td>
    </tr>
    </tbody>
</table>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
<table class="table_form" cellpadding="0" cellspacing="1">
  <caption>信息查询</caption>
<tbody>
<tr>
<td class="align_c">
    微信支付订单号 <input class="input_blur" name="trade_no" id="trade_no" size="20" type="text">&nbsp;
活动ID <input class="input_blur" name="groupon_id" id="groupon_id" size="10" type="text">&nbsp;
手机号 <input class="input_blur" name="mobile" id="mobile" size="20" type="text">&nbsp;
    <select class="input_blur" name="is_success">
        <option value="">选择拼团状态</option>
        <option value="0">拼团中</option>
        <option value="1">拼团成功</option>
        <option value="2">拼团失败</option>
    </select>&nbsp;
    <select class="input_blur" name="is_refund">
        <option value="">选择退定金状态</option>
        <option value="1">已退定金</option>
        <option value="0">正常状态</option>
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
<th>微信支付订单号</th>
<th width="200">收货人信息</th>
<th>订单描述</th>
<th width="120">活动描述</th>
<th width="120">定金</th>
<th width="80">推荐人</th>
<th width="80">下单时间</th>
<th width="80">拼团状态</th>
<th width="80">管理操作</th>
</tr>
<?php if (! empty($item_list)): ?>
<?php foreach ($item_list as $key=>$value): ?>
<tr id="id_<?php echo $value['id']; ?>"  onMouseOver="this.style.backgroundColor='#ECF7FE'" onMouseOut="this.style.background=''">
<td class="align_c">
    <?php echo $value['trade_no']; ?>
</td>
    <?php if ($value['user_address']){ ?>
        <td class="align_c" title="<?php echo clearstring($value['user_address']['txt_address'].$value['user_address']['address']); ?>"><?php echo my_substr($value['user_address']['txt_address'].$value['user_address']['address'], 30); ?><br/><?php echo $value['user_address']['buyer_name']; ?><br/><?php echo $value['user_address']['mobile']; ?></td>
    <?php }else{ ?>
        <td class="align_c"></td>
    <?php } ?>
<td class="align_c">
<table  width="100%" cellpadding="0" cellspacing="1">

<tr>
<td class="align_c" width="60">
<img src="<?php if ($value['product_info']['path']){ echo preg_replace('/\./', '_thumb.', $value['product_info']['path']);;}else{echo 'images/admin/nopic.gif';} ?>" width="50px" height="50px" />
</td>
<td>
<span><?php echo $value['product_info']['title']; ?></span><br/>
<span style="color: #999;">产品编号：<?php echo $value['product_info']['product_num']; ?></span><br/>
<?php if ($value['product_info']['color_size_open']) { ?>
<span style="color: #999;"><?php echo $value['product_info']['product_color_name']; ?>：<?php echo $value['product_info']['color_name']; ?> <?php echo $value['product_info']['product_size_name']; ?>：<?php echo $value['product_info']['size_name']; ?></span>
<?php } ?>
</td>
<td style="width: 60px;text-align:center;" title="单价">¥<?php echo number_format($value['product_info']['cur_price'], 2, '.', ''); ?></td>
<td style="width: 20px;text-align:center;" title="购买数量"><?php echo $value['buy_number']; ?></td>
</tr>
    
</table>
</td>
    <td class="align_c">
        <?php if ($value['type']){  ?>
        <?php echo $value['name_format'].'[活动ID:'.$value['groupon_id'].']';?><br/><?php if ($value['surplus_people']){ ?>还差<?php echo $value['surplus_people'];?>人成团!<?php }else{ echo '团满了!';}?>
        <?php }else{ echo '大型团[活动ID:'.$value['groupon_id'].']'; }?>
    </td>
<td class="align_c">
<span class="priceColor">¥<?php echo $value['deposit']; ?></span><br/>
</td>
    <td class="align_c"><?php echo $value['parent_info']; ?></td>
<td class="align_c"><?php echo date("Y-m-d H:i", $value['add_time']); ?></td>
<td class="align_c"><?php echo $status_arr[$value['is_success']]; ?><br><?php if ($value['is_free']){ echo '<font color="red">(免单)</font>'; } ?></td>
<td class="align_c">
<span style="line-height:25px;"><?php if ($value['is_refund']){ echo '<font color="red">已退定金</font>'; }else{?><a href="javascript:void(0);" onclick="refund(<?php echo $value['id']; ?>);">退定金</a><?php } ?></span>
</td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>
<div class="button_box">

</div>
<div id="pages" style="margin-top: 5px;">
<?php echo $pagination; ?>
<a>总条数：<?php echo $paginationCount; ?></a>
<a>总页数：<?php echo $pageCount; ?></a>
</div>
<br/><br/>
<script>
    function refund(id) {
        var d = dialog({
            width:300,
            fixed: true,
            title: '修改定金状态提示',
            content: '确定已将定金退至用户？一经修改无法撤回',
            okValue: '确认',
            ok: function () {
                $.post(base_url+'admincp.php/groupon_record/change_refund',
                    {record_id:id},
                    function (data) {
                        my_alert_flush('',0,data.message);
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