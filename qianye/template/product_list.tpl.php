<input type="hidden" id="currentpage" value="<?php echo $_SERVER["REQUEST_URI"] ?>">
<table width="100%" class="m-table m-table-row">
    <thead class="m-table-thead s-table-thead">
        <tr>
            <th width="60">ID </th>
            <th class="l">商品名称</th>
            <th class="l">SKU</th>
            <th width="160">缩略图</th>
            <th width="100">创建时间</th>
            <th width="123">操作</th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($list)) foreach ($list as $t) { ?>
            <tr>
                <td>
                    <?php echo $t['id'] ?>
                </td>
                <td class="l">
                    <?php echo $t['title'] ?>
                </td>
                <td class="l">
                    <?php echo $t['sku'] ?>
                </td>
                <td><img width="40" height="40" src="<?php echo $imgdomain ?><?php echo $t['thumb']?>" alt="<?php echo $t['thumb']?>"></td>
                <td>
                    <?php echo date('Y-m-d', $t['createtime']) ?>
                </td>
                <td>
                    <?php if ($this->menu('product-edit')) { ?>
                        <button class="btn btn-sm btn-primary dialog" data-url="<?php echo url('product/edit', ['id' => $t['id']]) ?>">修改</button>
                    <?php } ?>
                    <?php if ($this->menu('product-del')) { ?>
                        <button class="btn btn-sm btn-danger confirm" data-url="<?php echo url('product/del', ['id' => $t['id']]) ?>" data-tip="确定要删除商品？">删除</button>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td colspan="6" align="left" style="border-bottom:0px;">
                <div class="pageright">
                    <?php echo $pagelist; ?>
                </div>
            </td>
        </tr>
    </tbody>
</table>