<?php echo $tool; ?>
<form name="search" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <table class="table_form" cellpadding="0" cellspacing="1">
        <caption>信息查询</caption>
        <tbody>
        <tr>
            <td class="align_c">
<!--            会员ID <input class="input_blur" name="user_id" id="user_id" size="20" type="text">&nbsp;-->
            手机号 <input class="input_blur" name="mobile" id="mobile" size="20" type="text">&nbsp;
                <select class="input_blur" name="display" style="display: none">
                    <option value="">是否显示</option>
                    <option value="1">显示</option>
                    <option value="0">隐藏</option>
                </select>&nbsp;
                报价时间 <input class="input_blur" name="inputdate_start" id="inputdate_start" size="10" readonly="readonly" type="text">&nbsp;-&nbsp;<input class="input_blur" name="inputdate_end" id="inputdate_end" size="10"  readonly="readonly" type="text">&nbsp;
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
            <th width="200">会员信息</th>
            <th width="150">面积</th>
            <th width="150">户型</th>
            <th width="150">手机号</th>
            <th width="100">报价时间</th>
        </tr>
        <?php
        foreach ($item_list as $item) {
            ?>
            <tr id="id_<?php echo $item['id'] ?>"  onMouseOver="this.style.backgroundColor = '#ECF7FE'" onMouseOut="this.style.background = ''">
                <td class="align_c"><input class="checkbox_style" name="ids" value="<?php echo $item['id'] ?>" type="checkbox"> <?php echo $item['id'] ?></td>
                <td class="align_c"><?php if ($item['user_id']){ ?><a href="admincp.php/user/save/<?php echo $item['user_id']; ?>"><?php echo $item['nickname']; ?><br>
                [ID:<?php echo $item['user_id']; ?>]</a><?php } ?>
                </td>
                <td class="align_c"><?php echo floatval($item['area']); ?>㎡</td>
                <td class="align_c"><?php echo $item['type_name']; ?></td>
                <td class="align_c"><?php echo $item['mobile']; ?></td>
                <td class="align_c"><?php echo date('Y-m-d H:i:s', $item['add_time']); ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<div class="button_box">
    <span style="width: 60px;">
        <a href="javascript:void(0);" onclick="javascript:$('input[type=checkbox]').prop('checked', true)">全选</a>/
        <a href="javascript:void(0);" onclick="javascript:$('input[type=checkbox]').prop('checked', false)">取消</a>
    </span>
    <input id="delete" class="button_style" name="delete" value=" 删除 "  type="button">
    <select class="input_blur" name="select_display" id="select_display" style="display: none">
        <option value="">是否显示</option>
        <option value="1">显示</option>
        <option value="0">隐藏</option>
    </select>
</div>
<div id="pages" style="margin-top: 5px;">
    <?php echo $pagination; ?>
    <a>总条数：<?php echo $paginationCount;  ?></a>
    <a>总页数：<?php echo $pageCount;  ?></a>
</div>
<br/>
<br/>