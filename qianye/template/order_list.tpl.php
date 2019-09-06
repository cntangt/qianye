<input type="hidden" id="currentpage" value="<?php echo $_SERVER["REQUEST_URI"] ?>">
<table width="100%" class="m-table m-table-row">
    <thead class="m-table-thead s-table-thead">
        <tr>
            <th width="90">提货单号</th>
            <th width="100">提货会员</th>
            <th width="120">会员电话</th>
            <th class="l">提货商品</th>
            <th width="50">数量</th>
            <th width="100">收货人</th>
            <th width="110">收货电话</th>
            <th class="l">收货地址</th>
            <th width="140">提货时间</th>
            <th width="60">状态</th>
            <th width="60">操作</th>
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
                    <?php echo $t['customermobile'] ?>
                </td>
                <td class="l">
                    <?php echo $t['productname'] ?>
                </td>
                <td>
                    <?php echo $t['quantity'] ?>
                </td>
                <td>
                    <?php echo $t['contact'] ?>
                </td>
                <td>
                    <?php echo $t['mobile'] ?>
                </td>
                <td class="l">
                    <?php echo $t['address'] ?>
                </td>
                <td>
                    <?php echo date('Y-m-d H:i', $t['createtime']) ?>
                </td>
                <td><?php switch ($t['status']) {
                        case '10':
                            echo '待发货';
                            break;
                        case '20':
                            echo '待揽收';
                            break;
                        case '30':
                            echo '待配送';
                            break;
                        case '40':
                            echo '配送中';
                            break;
                        case '50':
                            echo '待签收';
                            break;
                        case '60':
                            echo '已签收';
                            break;
                        case '70':
                            echo '已完成';
                            break;
                        case '-10':
                            echo '关闭';
                            break;
                        default:
                            echo '其它';
                            break;
                    } ?></td>
                <td>
                    <?php if ($t['status'] != 10) { ?>
                        <button class="btn btn-sm btn-secondary" title="仅待发货订单可以关闭" disabled>关闭</button>
                    <?php } else if ($this->menu('order-close')) { ?>
                        <button class="btn btn-sm btn-danger confirm" data-url="<?php echo url('order/close', ['id' => $t['id']]) ?>" data-tip="确定关闭当前提货单，此操作不可撤回！">关闭</button>
                    <?php } else { ?>
                        <button class="btn btn-sm btn-secondary" title="没有操作权限" disabled>关闭</button>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td colspan="11" align="left" style="border-bottom:0px;">
                <div class="pageright">
                    <?php echo $pagelist; ?>
                </div>
            </td>
        </tr>
    </tbody>
</table>