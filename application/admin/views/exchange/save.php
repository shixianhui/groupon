<?php echo $tool; ?>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" id="jsonForm" >
<input name="id" value="" type="hidden">
<table class="table_form" cellpadding="0" cellspacing="1">
  <caption>基本信息</caption>
 	<tbody>
            <tr>
      <th width="20%"> <br/>
	  </th>
      <td align="center" style="height:40px;">
          <strong style="color:#077ac7;font-size:18px;">第一阶段 审核阶段</strong>
	</td>
    </tr>
	<tr>
      <th width="20%"><strong>退换货订单号</strong> <br/>
	  </th>
      <td>
      <?php if(! empty($itemInfo)){ echo $itemInfo['order_number'];} ?>
	</td>
    </tr>
    <tr>
      <th width="20%">
      <strong>服务类型:</strong> <br/>
	  </th>
      <td>
           <?php if(! empty($itemInfo)){ echo $exchange_type[$itemInfo['exchange_type']];} ?>
      </td>
    </tr>
    <tr>
      <th width="20%"> <strong>要退货的商品</strong> <br/>
	  </th>
      <td>
         <?php
         if($product){
            echo '<img src="'.$product['path'].'" width="120">';
            echo '<p style="position:relative;line-height:40px;">'.$product['product_title'].'</p>';
            echo '<p style="position:relative;line-height:40px;">颜色:'.$product['color_name'].' 尺码：'.$product['size_name'].'</p>';
         }?>
      </td>
    </tr>
    <tr>
      <th width="20%"> <strong>凭证图片</strong> <br/>
	  </th>
      <td>
         <?php
         if($attachmentList){
           foreach($attachmentList as $ls){
         ?>
            <a target="_blank" href="<?php echo $ls['path']; ?>" title="点击图片看大图"><img src="<?php echo str_replace('.','_thumb.',$ls['path']); ?>" width="120" height="120" /></a>
         <?php }}?>
      </td>
    </tr>
    <tr>
      <th width="20%"> <strong>原因:</strong> <br/>
	  </th>
      <td>
      <?php if(! empty($itemInfo)){ echo $itemInfo['refund_cause'];} ?>
      </td>
    </tr>
     <tr>
      <th width="20%"> <strong>说明:</strong> <br/>
	  </th>
      <td>
      <?php if(! empty($itemInfo)){ echo html($itemInfo['content']);} ?>
      </td>
    </tr>
<tr>
      <th width="20%">
      <strong>申请时间</strong> <br/>
	  </th>
      <td>
      <?php if(! empty($itemInfo)){ echo date('Y-m-d H:i:s', $itemInfo['add_time']);} ?>
      </td>
  </tr>
   <?php
        if($itemInfo['exchange_type']==1){
    ?>
   <tr>
        <th width="20%">
            <strong>买家期望退款金额</strong> <br/>
        </th>
      <td>
          <?php echo $itemInfo['refund_amount'];?>元
      </td>
   </tr>
        <?php }?>
    <tr>
      <th width="20%">
      <strong>请选择处理状态</strong> <br/>
	  </th>
      <td>
      <?php if(! empty($itemInfo)){if ($itemInfo['status'] == 0){ ?>
      <select name="status">
        <option value="">请选择状态</option>
        <option value="1">审核未通过</option>
        <option value="2">审核通过</option>
      </select>
      <?php } else if ($itemInfo['status'] == 1) {?>
      审核未通过
      <?php } else if ($itemInfo['status'] == 2) { ?>
      审核通过
      <?php }} ?>
      </td>
   </tr>
   
    <?php
      if($itemInfo['status'] == 1 || $itemInfo['status'] == 0){
    ?>
    <tr>
      <th width="20%">
      <strong>备注</strong> <br/>
       </th>
      <td>
      <textarea name="remark" id="remark" cols="40" rows="5"><?php if(! empty($itemInfo)){ echo $itemInfo['remark'];} ?></textarea>
      </td>
    </tr>
      <?php }?>
   
   <?php
       if($itemInfo['exchange_type']!=3){
   ?>
       <tr>
      <td>&nbsp;</td>
      <td>
          <input class="button_style" name="dosubmit" value=" 保存 " type="submit">
	  &nbsp;&nbsp; <input onclick="javascript:window.location.href='<?php echo $prfUrl; ?>';" class="button_style" name="reset" value=" 返回 " type="button">
        </td>
    </tr>
    <?php
       }
    ?>
   <?php if(($itemInfo['exchange_type']==1 || $itemInfo['exchange_type']==2)){?>
   <tr>
       <td style="height:100px;"><td>
   </tr>
                   <tr>
      <th width="20%"> <br/>
	  </th>
      <td align="center" style="height:40px;">
          <strong style="color:#077ac7;font-size:18px;">第二阶段  收货退款阶段</strong>
	</td>
    </tr>
   <tr>
        <th width="20%">
            <strong>买家退货的物流公司</strong> <br/>
        </th>
      <td>
          <?php echo $itemInfo['buyer_experss_com'];?>
      </td>
   </tr>
    <tr>
        <th width="20%">
            <strong>买家退货的运单号</strong> <br/>
        </th>
      <td>
          <?php echo $itemInfo['buyer_express_num'];?>
      </td>
   </tr>
      <tr>
        <th width="20%">
            <strong>买家退货说明</strong> <br/>
        </th>
      <td>
          <?php echo $itemInfo['buyer_post_remark'];?>
      </td>
   </tr>
   <tr>
        <th width="20%">
            <strong>商家是否收到货</strong> <br/>
        </th>
      <td>
          <label><input type="radio" name="seller_recieve_goods" value="0" <?php echo $itemInfo['seller_recieve_goods']==0 ? 'checked="checked"' : '';?>> 未收到货</label>
           <label><input type="radio" name="seller_recieve_goods" <?php echo ($itemInfo['status']==1 || $itemInfo['status']==0) ? 'onclick="return false;"' : '';?> value="1" <?php echo $itemInfo['seller_recieve_goods']==1 ? 'checked="checked"' : '';?>> 收到货<?php if($itemInfo['exchange_type']==2){echo '并同意换货';}?></label>
      </td>
   </tr>
   <?php
        if($itemInfo['exchange_type']==2){
   ?>
       <tr>
        <th width="20%">
            <strong>收货地址：</strong> <br/>
        </th>
      <td>
          <?php
             echo $itemInfo['user_address'];
          ?>
      </td>
   </tr>
        <?php }?>
   <?php }?>
    <?php
       if($itemInfo['exchange_type'] != 2){
    ?>
   <tr>
      <th width="20%">
      <strong>退换货金额</strong> <br/>
	  </th>
      <td>     
      <?php if(! empty($itemInfo)) { ?>
      <?php if ($itemInfo['status'] == 2) { ?>
      <input name="price" id="price" size="30" class="input_blur" value="<?php if(! empty($itemInfo)){ echo $itemInfo['price'];} ?>" type="text" /> <font color="red">元 注：只在审核通过并且卖家收到货才起作用，“0”表示没有退款</font>
      <?php } else { ?>
      <input name="price" id="price" size="30" class="input_blur" value="" type="text" /> <font color="red">元 注：只在审核通过并且卖家收到货才起作用，“0”表示没有退款</font>
      <?php }} ?>   
      </td>
    </tr>
    <tr>
          <th width="20%">
             <strong>买家付款方式</strong> <br/>
	  </th>
          <td>
              <?php echo $ordersInfo['payment_title'];?>
          </td>
    </tr>
    
       <?php }?>
    <?php
      if($itemInfo['status'] == 2){
    ?>
    <tr>
      <th width="20%">
      <strong>备注</strong> <br/>
       </th>
      <td>
      <textarea name="remark" id="remark" cols="40" rows="5"><?php if(! empty($itemInfo)){ echo $itemInfo['remark'];} ?></textarea>
      </td>
    </tr>
      <?php }?>
    <?php if(! empty($itemInfo) && $itemInfo['last_time']){ ?>
    <tr>
      <th width="20%">
      <strong>最后处理时间</strong> <br/>
            </th>
      <td>
      <?php echo date('Y-m-d H:i:s', $itemInfo['last_time']); ?>
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
</form>
<br/><br/>
<script>
    $("#jsonForm").submit(function(e){
         if(!confirm('请务必核实退款原因')){
             e.preventDefault();
         };    
    })
</script>