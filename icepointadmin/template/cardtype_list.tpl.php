
<table width="100%" class="m-table m-table-row">
    <thead class="m-table-thead s-table-thead">
        <tr>
            <th width="40" align="left">ID </th>
            <th align="left" width="20%">名称</th>
            <th align="left">商品内容</th>
            <th width="100">开始时间</th>
            <th width="100">结束时间</th>
            <th width="80">有效期</th>
            <th width="123">操作</th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($list)) foreach ($list as $t) { ?>
        <tr>
            <td>
                <?php echo $t['id'] ?>
            </td>
            <td>
                <?php echo $t['name'] ?>
            </td>
            <td>
                <?php echo $t['description'] ?>
            </td>
            <td>
                <?php echo date('Y-m-d',$t['begintime']) ?>
            </td>
            <td>
                <?php echo date('Y-m-d',$t['endtime']) ?>
            </td>
            <td>
                <?php echo $t['vailddays'] ?>天
            </td>
            <td>
                <button class="btn btn-sm btn-success dialog" data-url="<?php echo url('cardtype/pdlist', ['id'=>$t['id']])?>">商品管理</button>
                <button class="btn btn-sm btn-danger">作废</button>
            </td>
        </tr>
        <?php } ?>
        <tr>
            <td colspan="7" align="left" style="border-bottom:0px;">
                <div class="pageright">
                    <?php echo $pagelist; ?>
                </div>
            </td>
        </tr>
    </tbody>
</table>