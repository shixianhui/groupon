<?php echo $tool; ?>
<form name="search" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <table class="table_form" cellpadding="0" cellspacing="1">
        <caption>信息查询</caption>
        <tbody>
        <tr>
            <td class="align_c">
            会员ID <input class="input_blur" name="user_id" id="user_id" size="20" type="text">&nbsp;
                <select class="input_blur" name="display" style="display: none">
                    <option value="">是否显示</option>
                    <option value="1">显示</option>
                    <option value="0">隐藏</option>
                </select>&nbsp;
                发布时间 <input class="input_blur" name="inputdate_start" id="inputdate_start" size="10" readonly="readonly" type="text">&nbsp;-&nbsp;<input class="input_blur" name="inputdate_end" id="inputdate_end" size="10"  readonly="readonly" type="text">&nbsp;
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
            <th width="150">姓名</th>
            <th width="150">手机号</th>
            <th>集赞情况</th>
            <th width="100">报名时间</th>
            <th width="70">管理操作</th>
        </tr>
        <?php
        foreach ($item_list as $item) {
            ?>
            <tr id="id_<?php echo $item['id'] ?>"  onMouseOver="this.style.backgroundColor = '#ECF7FE'" onMouseOut="this.style.background = ''">
                <td class="align_c"><input class="checkbox_style" name="ids" value="<?php echo $item['id'] ?>" type="checkbox"></td>
                <td class="align_c"><a href="admincp.php/user/save/<?php echo $item['user_id']; ?>"><?php echo $item['nickname']; ?><br>
                [ID:<?php echo $item['user_id']; ?>]</a></td>
                <td class="align_c"><?php echo $item['real_name']; ?></td>
                <td class="align_c"><?php echo $item['mobile']; ?></td>
                <td class="align_c"><?php if (!empty($item['light_count_list'])){
                    foreach ($item['light_count_list'] as $value){
                        echo "{$value['name']}({$value['count']})  ";
                    }
                    } ?></td>
                <td class="align_c"><?php echo date('Y-m-d H:i:s', $item['add_time']); ?></td>
                <td class="align_c">
                <a href="admincp.php/<?php echo $template; ?>/save/<?php echo $item['id']; ?>">修改</a><br>
                </td>
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