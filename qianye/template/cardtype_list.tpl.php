<input type="hidden" id="currentpage" value="<?php echo $_SERVER["REQUEST_URI"] ?>">
<table width="100%" class="m-table m-table-row">
    <thead class="m-table-thead s-table-thead">
        <tr>
            <th width="60">ID </th>
            <th class="l" width="15%">名称</th>
            <th class="l">商品内容</th>
            <th width="100">开始时间</th>
            <th width="100">结束时间</th>
            <th width="80">有效期</th>
            <th width="80">生成</th>
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
                    <?php echo $t['name'] ?>
                </td>
                <td class="l">
                    <?php echo $t['description'] ?>
                </td>
                <td>
                    <?php echo date('Y-m-d', $t['begintime']) ?>
                </td>
                <td>
                    <?php echo date('Y-m-d', $t['endtime']) ?>
                </td>
                <td>
                    <?php echo $t['vailddays'] ?>天
                </td>
                <td><?php echo $t['canedit'] ? '否' : '是' ?></td>
                <td>
                    <?php if ($this->menu('cardtype-pdlist')) { ?>
                        <?php if ($t['canedit']) { ?>
                            <button class="btn btn-sm btn-success dialog" data-url="<?php echo url('cardtype/pdlist', ['id' => $t['id']]) ?>">绑定管理</button>
                        <?php } else { ?>
                            <button class="btn btn-sm btn-success" disabled title="已生成卡券，不能修改">绑定管理</button>
                        <?php } ?>
                    <?php } ?>
                    <?php if ($this->menu('cardtype-edit')) { ?>
                        <?php if ($t['canedit']) { ?>
                            <button class="btn btn-sm btn-primary dialog" data-url="<?php echo url('cardtype/edit', ['id' => $t['id']]) ?>">修改</button>
                        <?php } else { ?>
                            <button class="btn btn-sm btn-primary" disabled title="已生成卡券，不能修改">修改</button>
                        <?php } ?>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td colspan="8" align="left" style="border-bottom:0px;">
                <div class="pageright">
                    <?php echo $pagelist; ?>
                </div>
            </td>
        </tr>
    </tbody>
</table>