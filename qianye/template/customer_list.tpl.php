<input type="hidden" id="currentpage" value="<?php echo $_SERVER["REQUEST_URI"] ?>">
<table width="100%" class="m-table m-table-row">
    <thead class="m-table-thead s-table-thead">
        <tr>
            <th>编号</th>
            <th>头像</th>
            <th>会员名称</th>
            <th>手机号码</th>
            <th>创建时间</th>
            <th>上级</th>
            <th>同步</th>
            <th>同步编号</th>
            <th>下级</th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($list)) foreach ($list as $t) { ?>
            <tr>
                <td>
                    <?php echo str_pad($t['id'], 9, '0', STR_PAD_LEFT) ?>
                </td>
                <th>
                    <img src="<?php echo $t['headimg'] ?>" class="header">
                </th>
                <td>
                    <?php echo $t['name'] ?>
                </td>
                <td>
                    <?php echo $t['mobile'] ?>
                </td>
                <td>
                    <?php echo date('Y-m-d H:i', $t['createtime']) ?>
                </td>
                <td>
                    <?php echo $t['superiorname'] ?>
                </td>
                <td>
                    <?php
                    if ($t['sync']) {
                        echo '是';
                    } else {
                        echo '否';
                    }
                    ?>
                </td>
                <td>
                    <?php echo $t['agentid']; ?>
                </td>
                <td>
                    <a type="button" class="btn btn-sm btn-primary" target="_blank" href="<?php echo url('customer/index', ['superior' => $t['id']]) ?>">下级</a>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td colspan="9" align="left" style="border-bottom:0px;">
                <div class="pageright">
                    <?php echo $pagelist; ?>
                </div>
            </td>
        </tr>
    </tbody>
</table>