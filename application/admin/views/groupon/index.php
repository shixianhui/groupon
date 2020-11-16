<?php echo $tool; ?>
<form name="search" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <table class="table_form" cellpadding="0" cellspacing="1">
        <caption>信息查询</caption>
        <tbody>
            <tr>
                <td class="align_c"></td>
            </tr>
        </tbody>
    </table>
</form>
<table class="table_list" id="news_list" cellpadding="0" cellspacing="1">
    <caption>信息管理</caption>
    <tbody>
        <tr class="mouseover">
            <th width="70">选中</th>
            <th>商品信息</th>
            <th width="100">拼团类型</th>
            <th width="200">结束时间</th>
            <th width="200">发布时间</th>
            <th width="80">参团人数</th>
            <th width="60">状态</th>
            <th width="70">管理操作</th>
        </tr>
        <?php
        foreach ($item_list as $item) {
            ?>
            <tr id="id_<?php echo $item['id'] ?>"  onMouseOver="this.style.backgroundColor = '#ECF7FE'" onMouseOut="this.style.background = ''">
                <td><input  class="checkbox_style" name="ids" value="<?php echo $item['id'] ?>" type="checkbox"> <?php echo $item['id'] ?></td>
                <td class="align_c"><?php echo $item['title']; ?>
                    <p>原价：<?php echo $item['sell_price']; ?>元</p>
                </td>
                <td class="align_c"><?php echo $item['type_format']; ?></td>
                <td class="align_c"><?php echo date('Y-m-d H:i:s', $item['end_time']); ?>
                    <?php
                     if($item['end_time'] > time()){
                       echo  '<font color="red">进行中...</font>';
                     }
                     if( $item['end_time'] < time()){
                         echo  '<font color="red">已结束</font>';
                     }
//                     if( $item['start_time'] > time()){
//                         echo  '<font color="red">暂未开始</font>';
//                     }
                    ?>
                </td>
                <td class="align_c"><?php echo date('Y-m-d H:i:s', $item['add_time']); ?></td>
                <td class="align_c"><?php echo $item['join_people']; ?></td>
                <td class="align_c">
                    <?php echo $item['is_open'] == 0 ? '关闭' : '开启'; ?>
                </td>
                <td class="align_c">
                <?php
//                if($item['start_time'] > time() || $item['is_open']==0){ ?>
                <a href="admincp.php/<?php echo $template; ?>/save/<?php echo $item['id']; ?>">修改</a>
<!--                --><?php //}?>
<!--                <a href="admincp.php/--><?php //echo $template; ?><!--/view/--><?php //echo $item['id']; ?><!--">查看</a>-->
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
</div>
<div id="pages" style="margin-top: 5px;">
    <?php echo $pagination; ?>
    <a>总条数：<?php echo $paginationCount;  ?></a>
    <a>总页数：<?php echo $pageCount;  ?></a>
</div>
<br/>
<br/>