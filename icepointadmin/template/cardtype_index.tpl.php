<?php include $this->admin_tpl('header'); ?>
<script type="text/javascript">
    top.document.getElementById('position').innerHTML = '卡券管理';
</script>

<div class="subnav">
    <div class="content-menu">
        <div class="left">
            <?php if ($this->menu('cardtype-add')) {
                      ; ?>
                <button type="button" class="btn btn-sm btn-primary dialog" data-url="<?php echo url('cardtype/add') ?>">添加卡券类型</button>
            <?php
                  } ?>
        </div>
        <div class="right">
            <form autocomplete="off" class="form-inline" data-url="<?php echo url('cardtype/list') ?>" id="searchform">
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
<script>var list=<?php echo $json?></script>
<script>
    $(function() {
        $('#listcontainer,.subnav').on('click','.dialog',function() {
            var html = '<div class="progress"><span>加载中...</span><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"></div></div>';
            $('#modal').modal('show');
            $('#modal .modal-content').html(html).load($(this).data('url'));
        });

        $('#modal').on('submit', 'form', function() {
            var form = $('#typeaddform');
            $.post(form.attr('action'), form.serialize(), function(res) {
                if (res.succ) {
                    $('#modal').modal('hide');
                    loadlist();
                } else {
                    alert(res.msg);
                }
            });
            return false;
        }).on('click', '#addnew', function () {
            $($('#row0')[0].innerHTML).appendTo('#rows');
            $('#rows .row').last().find('.pditem').select2({ data: list, placeholder: '选择商品' });
        });

        $('#listcontainer').on('click', '.xiaocms-page a', function() {
            $('#listcontainer').load($(this).attr('href'));
            return false;
        });

        var form = $('#searchform').submit(loadlist);
        function loadlist() {
            $('#listcontainer').load(form.data('url'), form.serialize());
            return false;
        }
        loadlist();
    });
</script>
</body>

</html>