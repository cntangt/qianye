<?php include $this->admin_tpl('header'); ?>
<script type="text/javascript">
    top.document.getElementById('position').innerHTML = '卡券管理';
</script>
<iframe name="export" style="display:none"></iframe>
<div class="subnav">
    <div class="content-menu">
        <div class="left">
            <?php if ($this->menu('card-add')) { ?>
                <button type="button" class="btn btn-sm btn-primary dialog" data-url="<?php echo url('card/build') ?>">生成卡券</button>
            <?php } if ($this->menu('card-export')) {?>
                <a class="btn btn-sm btn-primary" style="color:#fff" href="<?php echo url('card/export') ?>" target="export">导出</a>
            <?php } ?>
        </div>
        <div class="right">
            <form autocomplete="off" class="form-inline" data-url="<?php echo url('card/index') ?>" id="searchform">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">状态</span>
                    </div>
                    <select name="canedit" class="form-control form-control-sm">
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
    $(function() {
        $('#listcontainer,.content-menu').on('click', '.dialog', function() {
            var html = '<div class="progress"><span>加载中...</span><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"></div></div>';
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

    function loadlist() {
        var form = $('#searchform');
        $('#listcontainer').load(form.data('url'), form.serialize());
        return false;
    }

    function reload() {
        $('#modal').modal('hide');
        $('#listcontainer').load($('#currentpage').val());
    }
</script>
</body>

</html>