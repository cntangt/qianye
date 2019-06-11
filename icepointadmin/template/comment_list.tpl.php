<input type="hidden" id="currentpage" value="<?php echo $_SERVER["REQUEST_URI"] ?>">
<table width="100%" class="m-table m-table-row">
    <thead class="m-table-thead s-table-thead">
        <tr>
            <th width="100">订单编号</th>
            <th width="150">会员名称</th>
            <th width="150">提货手机</th>
            <th>评价内容</th>
            <th width="200">评价时间</th>
        </tr>
    </thead>
    <tbody class="mh">
        <?php if (is_array($list)) foreach ($list as $t) { ?>
            <tr>
                <td>
                    <?php echo $t['orderid'] ?>
                </td>
                <td>
                    <?php echo $t['name'] ?>
                </td>
                <td>
                    <?php echo $t['mobile'] ?>
                </td>
                <td class="comment">
                    <span>收货：<input type="checkbox" disabled <?php echo ($t['isreceive'] ? 'checked' : '') ?>></span>
                    <span>到位：<input type="checkbox" disabled <?php echo ($t['isdestination'] ? 'checked' : '') ?>></span>
                    <span>态度：<?php
                                switch ($t['attitude']) {
                                    case '10':
                                        echo '热情';
                                        break;
                                    case '20':
                                        echo '消极';
                                        break;
                                    default:
                                        echo '未知';
                                        break;
                                }
                                ?>
                    </span>
                    <span>衣着：<?php
                                switch ($t['clothing']) {
                                    case '10':
                                        echo '正规';
                                        break;
                                    case '20':
                                        echo '随意';
                                        break;
                                    default:
                                        echo '未知';
                                        break;
                                }
                                ?>
                    </span>
                </td>
                <td>
                    <?php echo date('Y-m-d H:i', $t['createtime']) ?>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td colspan="5" align="left" style="border-bottom:0px;">
                <div class="pageright">
                    <?php echo $pagelist; ?>
                </div>
            </td>
        </tr>
    </tbody>
</table>