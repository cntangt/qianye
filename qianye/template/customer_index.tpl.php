<?php include $this->admin_tpl('header'); ?>
<iframe name="export" style="display:none"></iframe>
<div class="subnav">
    <div class="content-menu">
        <div class="right">
            <form autocomplete="off" class="form-inline" data-url="<?php echo url('customer/index') ?>" id="searchform">
                <input type="hidden" name="superior" value="<?php echo $_GET['superior'] ?>">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text">昵称</span>
                    </div>
                    <input type="text" class="form-control form-control-sm" name="name" placeholder="会员关键字" />
                </div>
                <div class="input-group input-group-sm mx-sm-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">手机</span>
                    </div>
                    <input type="text" class="form-control form-control-sm" name="mobile" placeholder="会员手机号查询" />
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