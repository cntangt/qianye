<form id="typeaddform" action="<?php echo url('wealth/edit') ?>" autocomplete="off">
    <input type="hidden" name="id" value="<?php echo $data['id'] ?>">
    <div class="modal-header">
        <h5 class="modal-title">修改会员财富</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label>当前剩余可提数量</label>
            <input type="number" class="form-control" disabled value="<?php echo $data['validquantity'] ?>" />
        </div>
        <div class="form-group">
            <label>增加可提货数量<span class="red">(注意减少输入负数)</span></label>
            <input type="number" class="form-control" name="quantity" placeholder="输入增加数量" required />
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
        <button type="submit" name="data[submit]" value="true" class="btn btn-primary">保存</button>
    </div>
</form>
<script>
    $('#typeaddform').validate({
        submitHandler: function() {
            var form = $('#typeaddform');
            $.post(form.attr('action'), form.serialize(), function(res) {
                if (res.succ) {
                    reload();
                } else {
                    alert(res.msg);
                }
            });
            return false;
        }
    });
</script>