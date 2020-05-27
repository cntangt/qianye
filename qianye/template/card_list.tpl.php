<input type="hidden" id="currentpage" value="<?php echo $_SERVER["REQUEST_URI"] ?>">
<table width="100%" class="m-table m-table-row">
    <thead class="m-table-thead s-table-thead">
        <tr>
            <th>ID </th>
            <th class="l">卡券类型</th>
            <th>卡券编号</th>
            <th>卡券密码</th>
            <th>手机号</th>
            <th>创建时间</th>
            <th>创建人</th>
            <th>销售时间</th>
            <th>激活时间</th>
            <th>激活人</th>
            <th>绑定人</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($list)) foreach ($list as $t) { ?>
            <tr>
                <td>
                    <?php echo $t['id'] ?>
                </td>
                <td class="l">
                    <?php echo $t['cardtypename'] ?>
                </td>
                <td>
                    <?php echo $t['code'] ?>
                </td>
                <td>
                    <?php echo $t['pass'] ?>
                </td>
                <td><?php echo $t['customermobile'] ?></td>
                <td>
                    <?php echo date('Y-m-d H:i', $t['createtime']) ?>
                </td>
                <td><?php echo $t['createby'] ?></td>
                <td>
                    <?php if ($t['saletime']) echo date('Y-m-d H:i', $t['saletime']) ?>
                </td>
                <td>
                    <?php if ($t['activetime']) echo date('Y-m-d H:i', $t['activetime']) ?>
                </td>
                <td><?php echo $t['cname'] ?></td>
                <td><?php echo $t['fname'] ?></td>
                <td><?php switch ($t['status']) {
                        case '10':
                            echo '未销售';
                            break;
                        case '20':
                            echo '销售';
                            break;
                        case '30':
                            echo '激活';
                            break;
                        default:
                            echo '作废';
                            break;
                    } ?></td>
                <td>
                    <?php if ($t['status'] == 40) { ?>
                        <button class="btn btn-sm btn-secondary" title="已作废" disabled>作废</button>
                    <?php } else if ($this->menu('card-disable')) { ?>
                        <button class="btn btn-sm btn-danger confirm" data-url="<?php echo url('card/disable', ['id' => $t['id']]) ?>" data-tip="确定作废当前卡券，此操作不可撤回！">作废</button>
                    <?php } else { ?>
                        <button class="btn btn-sm btn-secondary" title="没有操作权限" disabled>作废</button>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td colspan="13" align="left" style="border-bottom:0px;">
                <div class="pageright">
                    <?php echo $pagelist; ?>
                </div>
            </td>
        </tr>
    </tbody>
</table>