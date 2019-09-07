<?php include $this->admin_tpl('header'); ?>
<iframe name="export" style="display:none"></iframe>
<div class="subnav">
    <div class="content-menu">
        <div class="left">
            <?php if ($this->menu('product-add')) { ?>
                <button type="button" class="btn btn-sm btn-primary dialog" data-url="<?php echo url('product/add') ?>">添加商品</button>
            <?php }  ?>
        </div>
        <div class="right">
            <form autocomplete="off" class="form-inline" data-url="<?php echo url('product/index') ?>" id="searchform">
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
    $(function() {
        $('#listcontainer,.content-menu').on('click', '.dialog', function() {
            $('#modal').modal('show');
            $('#modal .modal-content').html(html).load($(this).data('url'));
        }).on('click', '.xiaocms-page a', function() {
            $('#listcontainer').load($(this).attr('href'));
            return false;
        });

        $('#searchform').submit(loadlist);
        $('#searchform select').change(loadlist);

        loadlist();
    });
</script>
</body>

</html>