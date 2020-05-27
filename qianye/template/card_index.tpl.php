<?php include $this->admin_tpl('header'); ?>
<iframe name="export" style="display:none"></iframe>
<div class="subnav">
    <div class="content-menu">
        <div class="left">
            <?php if ($this->menu('card-add')) { ?>
                <button type="button" class="btn btn-sm btn-primary dialog" data-url="<?php echo url('card/build') ?>">生成卡券</button>
            <?php }
        if ($this->menu('card-export')) { ?>
                <a class="btn btn-sm btn-primary" href="#" style="color:#fff" data-url="<?php echo url('card/export') ?>" id="export" target="export">导出</a>
            <?php }
        if ($this->menu('card-sale')) { ?>
                <button class="btn btn-sm btn-success dialog" data-url="<?php echo url('card/sale') ?>">批量销售</button>
            <?php }
        if ($this->menu('card-disable')) { ?>
                <button class="btn btn-sm btn-danger dialog" data-url="<?php echo url('card/disable') ?>">批量作废</button>
            <?php }
        if ($this->menu('card-bind')) { ?>
                <button class="btn btn-sm btn-success dialog" data-url="<?php echo url('card/bind') ?>">批量绑定</button>
        <?php } ?>
        </div>
        <div class="right">
            <form autocomplete="off" class="form-inline" data-url="<?php echo url('card/index') ?>" id="searchform">
                <div class="input-group input-group-sm" id="ctidc">
                    <div class="input-group-prepend">
                        <span class="input-group-text">类型</span>
                    </div>
                    <select name="ctid" id="ctid" class="form-control" style="width:200px;height:26px;" data-="">
                        <?php if (is_array($types)) foreach ($types as $t) { ?>
                            <option value="<?php echo $t['id'] ?>">【<?php echo $t['name'] ?>】<?php echo $t['description'] ?></option>
                        <?php
                    } ?>
                    </select>
                </div>
                <div class="input-group input-group-sm mx-sm-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">手机</span>
                    </div>
                    <input type="text" class="form-control form-control-sm" name="mobile" placeholder="客户手机号查询" />
                </div>
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text">卡号</span>
                    </div>
                    <input type="text" class="form-control form-control-sm" name="code" placeholder="卡号查询" />
                </div>
                <div class="input-group input-group-sm mx-sm-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">状态</span>
                    </div>
                    <select name="status" class="form-control">
                        <option value="">全部</option>
                        <option value="10">未销售</option>
                        <option value="20">销售</option>
                        <option value="30">激活</option>
                        <option value="40">作废</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-sm btn-success">查询</button>
            </form>
        </div>
    </div>
    <div class="bk10"></div>
    <div id="listcontainer"></div>
</div>
<div id="modal" class="modal" role="dialog" data-backdrop="static">
    <div class="modal-dialog" role="document" style="max-width:800px;">
        <div class="modal-content"></div>
    </div>
</div>
<script>
    $(function() {
        $('#listcontainer,.content-menu').on('click', '.dialog', function() {
            $('#modal').modal('show');
            $('#modal .modal-content').html(html).load($(this).data('url'));
        }).on('click', '.xiaocms-page a', function() {
            load($(this).attr('href'));
            return false;
        }).on('click', '.confirm', function() {
            if (confirm($(this).data('tip'))) {
                $.post($(this).data('url'), reload);
            }
        });

        $('#export').click(function() {
            $(this).attr('href', $(this).data('url') + '&' + $('#searchform').serialize())
        })

        $('#modal').on('click', '#addnew', function() {
            $($('#row0')[0].innerHTML).appendTo('#rows').find('.pdid').select2({
                data: list
            });
        }).on('click', '.del', function() {
            $(this).parent('div').parent('div.row').remove();
        });

        $('#searchform').submit(loadlist);
        $('#searchform #ctid').select2({
            placeholder: "选择卡券类型",
            allowClear: true
        }).val(null).trigger("change");
        $('#searchform select').change(loadlist);
        loadlist();
    });
</script>
</body>

</html>