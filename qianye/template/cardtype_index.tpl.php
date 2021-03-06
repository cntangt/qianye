<?php include $this->admin_tpl('header'); ?>
<iframe name="export" style="display:none"></iframe>
<div class="subnav">
    <div class="content-menu">
        <div class="left">
            <?php if ($this->menu('cardtype-add')) { ?>
                <button type="button" class="btn btn-sm btn-primary dialog" data-url="<?php echo url('cardtype/add') ?>">添加卡券类型</button>
            <?php } if ($this->menu('cardtype-export')) {?>
                <a class="btn btn-sm btn-primary" style="color:#fff" href="<?php echo url('cardtype/export') ?>" target="export">导出</a>
            <?php } ?>
        </div>
        <div class="right">
            <form autocomplete="off" class="form-inline" data-url="<?php echo url('cardtype/index') ?>" id="searchform">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text">状态</span>
                    </div>
                    <select name="canedit" class="form-control">
                    <option value="">全部</option>
                    <option value="false">已生成</option>
                    <option value="true">未生成</option>
                </select>
                </div>
                <input type="text" class="form-control form-control-sm mx-sm-3" name="name" />
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
    var list = <?php echo $json ?>
</script>
<script>
    $(function() {
        $('#listcontainer,.content-menu').on('click', '.dialog', function() {
            $('#modal').modal('show');
            $('#modal .modal-content').html(html).load($(this).data('url'));
        }).on('click', '.xiaocms-page a', function() {
            $('#listcontainer').load($(this).attr('href'));
            return false;
        });

        $('#modal').on('click', '#addnew', function() {
            $($('#row0')[0].innerHTML).appendTo('#rows').find('.pdid').select2({
                data: list
            });
        }).on('click', '.del', function() {
            $(this).parent('div').parent('div.row').remove();
        });

        $('#searchform').submit(loadlist);
        $('#searchform select').change(loadlist);
        loadlist();
    });
</script>
</body>

</html>