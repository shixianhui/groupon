<?php echo $tool; ?>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" id="jsonForm" >
<input name="id" value="" type="hidden">
<table class="table_form" cellpadding="0" cellspacing="1">
  <caption>基本信息</caption>
 	<tbody>
    <tr>
        <th width="20%"><font color="red">*</font> <strong>活动标题</strong> <br/>
        </th>
        <td>
            <input name="title" id="title" value="<?php if(! empty($item_info)){ echo $item_info['title'];} ?>" valid="required" errmsg="活动标题不能为空!" type="text" size="50"/>
        </td>
    </tr>
    <tr>
        <th width="20%"> <strong>活动关键字</strong> <br/>
        </th>
        <td>
            <input name="keyword" id="keyword" value="<?php if(! empty($item_info)){ echo $item_info['keyword'];}else{echo '尊享专属 更懂品位';} ?>" type="text" size="50"/>
        </td>
    </tr>
    <tr>
        <th width="20%"> <strong>活动简介</strong> <br/>
        </th>
        <td>
            <input name="abstract" id="abstract" value="<?php if(! empty($item_info)){ echo $item_info['abstract'];}else{echo '3人团 拼团立省7000元';} ?>" type="text" size="50"/>
        </td>
    </tr>

    <tr>
        <th width="20%"><font color="red">*</font>
            <strong>活动结束时间</strong> <br/>
        </th>
        <td>
            <input name="end_time" id="end_time" size="20" value="<?php if(! empty($item_info)){echo date('Y-m-d H:i:s',$item_info['end_time']);}?>" class="input_blur" type="text" valid="required" errmsg="活动结束时间不能为空" readonly="readonly">
        </td>
    </tr>
    <tr>
        <th width="20%">
            <strong>设置团购商品</strong> <br/>
        </th>
        <td>
            <table class="table_form" cellpadding="0" cellspacing="1" style="width:700px;margin-left:0px;">
                <tr>
                    <th width="150px" class="align_c"><strong>产品图片</strong></th>
                    <th class="align_c"><strong>产品标题</strong></th>
                    <th width="90px" class="align_c"><strong>原价（元）</strong></th>
                </tr>
                <tr>
                    <td class="align_c"><img src="<?php if($item_info){echo $productInfo['path'];}?>" style="max-width:140px;" id="productImg" onerror="this.src='images/admin/no_pic.png'"></td>
                    <td class="align_c"><span id="productTitle"><?php if($item_info){echo $productInfo['title'];}?></span></td>
                    <td class="align_c" id="price"><?php if($item_info){echo $productInfo['sell_price'];}?></td>
                </tr>
                <td colspan="4">
                    <button class="button_style" type="button" onclick="javascript:window.open('admincp.php/product/selector', 'add', 'top=100, left=200, width=900, height=400, scrollbars=1, resizable=yes');"><span>请选择活动商品</span></button>
                    <label style="color:red;">注： 请选择活动商品，每次仅能设置一种商品</label>
                    <input type="hidden" name="product_id" value="<?php if($item_info){echo $item_info['product_id'];}?>" id="productId" onchange="selectProduct()" valid="required" errmsg="请选择商品">
                </td>
            </table>
        </td>
    </tr>

    <tr>
        <th width="20%">
            <strong>拼团类型</strong> <br>
        </th>
        <td>
            <input onclick="$('.type_0').show();$('.type_1').hide();" type="radio" value="0" name="type" class="radio_style" <?php if($item_info){ echo $item_info['type']==0 ? 'checked' : '';}else{ echo "checked";} ?>> 大型团
            <input onclick="$('.type_1').show();$('.type_0').hide();" type="radio" value="1" name="type" class="radio_style" <?php if($item_info){ echo $item_info['type']==1 ? 'checked' : '';} ?>> 小型团
        </td>
    </tr>
    <tr class="type_0"  <?php if ($item_info && $item_info['type']){ ?>style="display: none" <?php } ?>>
        <th width="20%">
            <strong>拼团规则</strong> <br/>
        </th>
        <td>
            <table class="table_form" cellpadding="0" cellspacing="1" style="width:400px;margin-left:0px;">
                <tr>
                    <th class="align_c"><strong>区间</strong></th>
                    <th width="80px" class="align_c"><strong>价格(元)</strong></th>
                    <th width="60px" class="align_c"><strong>操作</strong></th>
                </tr>
                <?php
                if($item_info){
                    foreach($rule_arr as $ls){
                        ?>
                        <tr>
                            <td>
                                <input type="text" name="low[]" valid="isInt" errmsg="人数为正整数" size="5" value="<?php echo $ls['low'];?>"> 人 ～ <input type="text" name="high[]" valid="isInt" errmsg="人数为正整数" value="<?php echo $ls['high'];?>" size="5"> 人
                            </td>
                            <td class="align_c">
                                <input type="text" name="money[]" value="<?php echo $ls['money'];?>"  valid="isMoney" errmsg="金钱格式不正确" size="8">
                            </td>
                            <td class="align_c">
                                <a href="javascript:;" onclick="$(this).parent().parent().remove();">删除</a>
                            </td>
                        </tr>
                    <?php }}?>
                <tr  id="firstRow">
                    <td colspan="3"><button type="button" class="button_style" id="addPtrule" style="margin-top:10px;"><span>添加</span></button></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr class="type_1" <?php if (empty($item_info) || !$item_info['type']){ ?>style="display: none" <?php } ?>>
        <th width="20%"><font color="red">*</font><strong>活动价</strong> <br/></th>
        <td><input type="text" size="10" name="sale_price" value="<?php if($item_info){echo $item_info['sale_price'];}?>" valid="isMoney" errmsg="活动价格格式有误"> 元</td>
    </tr>
    <tr class="type_1" <?php if (empty($item_info) || !$item_info['type']){ ?>style="display: none" <?php } ?>>
        <th width="20%"><font color="red">*</font> <strong>人数要求</strong> <br/></th>
        <td><input type="text" size="10" name="min_number" value="<?php if(! empty($item_info)){echo $item_info['min_number'];}?>" valid="isInt" errmsg="人数格式有误">
        </td>
    </tr>
    <tr>
        <th width="20%"><font color="red">*</font> <strong>定金</strong> <br/></th>
        <td><input type="text" size="10" name="deposit" value="<?php if($item_info){echo $item_info['deposit'];}?>" valid="required|isMoney" errmsg="请输入定金|定金格式有误"> 元</td>
    </tr>
    <tr>
        <th width="20%">
            <strong>是否开启活动</strong> <br>
        </th>
        <td>
            <input type="radio" value="0" name="is_open" class="radio_style" <?php if($item_info){ echo $item_info['is_open']==0 ? 'checked' : '';}else{ echo "checked";} ?>> 关闭
            <input type="radio" value="1" name="is_open" class="radio_style" <?php if($item_info){ echo $item_info['is_open']==1 ? 'checked' : '';} ?>> 开启
        </td>
    </tr>
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
<script>
    function selectProduct() {
        if(!$("input[name=name]").val()){
            $("input[name=name]").val($("#productTitle").html());
        }
    }
    $("#addPtrule").click(function () {
        var html = '<tr><td>\
                      <input type="text" name="low[]" valid="isInt" errmsg="人数为正整数" size="5"> 人 ～ <input type="text" valid="isInt" errmsg="人数为正整数" name="high[]" size="5"> 人\
                  </td>\
                  <td>\
                      <input type="text" name="money[]" size="10" valid="isMoney" errmsg="金钱格式不正确">\
                  </td> <td>\
                       <a href="javascript:;" onclick="$(this).parent().parent().remove();">删除</a>\
                  </td></tr>';
        $("#firstRow").before(html);
    });
</script>