<form id="typeaddform" enctype="multipart/form-data" method="post" action="<?php echo url('product/' . $data['action']) ?>" autocomplete="off" target="res">
    <input type="hidden" name="id" value="<?php echo $data['id'] ?>">
    <div class="modal-header">
        <h5 class="modal-title">编辑商品 - <?php echo $data['title'] ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label>商品名称</label>
            <input type="text" class="form-control" name="title" placeholder="填写商品名称" value="<?php echo $data['title'] ?>" />
        </div>
        <div class="form-group">
            <label>SKU编码</label>
            <input type="text" class="form-control" name="sku" placeholder="填写商品SKU编码" value="<?php echo $data['sku'] ?>" />
        </div>
        <div class="form-group">
            <label>缩略图</label>
        </div>
        <input type="file" name="file" />
        <div>
            <iframe height="40" frameborder="0" scrolling="no" name="res"></iframe>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
        <button type="submit" name="data[submit]" value="true" class="btn btn-primary">保存</button>
    </div>
</form>

<script>
    // $('#typeaddform').validate({
    //     submitHandler: function() {
    //         var form = $('#typeaddform');
    //         $.post(form.attr('action'), form.serialize(), function(res) {
    //             if (res.succ) {
    //                 reload();
    //             } else {
    //                 alert(res.msg);
    //             }
    //         });
    //         return false;
    //     }
    // });
</script>