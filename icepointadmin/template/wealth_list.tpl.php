<input type="hidden" id="currentpage" value="<?php echo $_SERVER["REQUEST_URI"] ?>">
<table width="100%" class="m-table m-table-row">
    <thead class="m-table-thead s-table-thead">
        <tr>
            <th>编号</th>
            <th>会员名称</th>
            <th>手机号码</th>
            <th class="l">商品名称</th>
            <!-- <th>原始数量</th> -->
            <th>剩余数量</th>
            <th>提货有效期</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($list)) foreach ($list as $t) { ?>
            <tr>
                <td>
                    <?php echo str_pad($t['id'], 9, '0', STR_PAD_LEFT) ?>
                </td>
                <td>
                    <?php echo $t['name'] ?>
                </td>
                <td>
                    <?php echo $t['mobile'] ?>
                </td>
                <td class="l">
                    <?php echo $t['productname'] ?>
                </td>
                <!-- <td>
                    <?php echo $t['quantity'] ?>
                </td> -->
                <td>
                    <?php echo $t['validquantity'] ?>
                </td>
                <td>
                    <?php echo date('Y-m-d H:i', $t['exptime']) ?>
                </td>
                <td>
                    <?php if ($this->menu('wealth-edit')) { ?>
                        <button class="btn btn-sm btn-success dialog" data-url="<?php echo url('wealth/edit', ['id' => $t['id']]) ?>">编辑</button>
                    <?php } else { ?>
                        <button class="btn btn-sm btn-secondary" title="没有操作权限" disabled>编辑</button>
                    <?php } ?>
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