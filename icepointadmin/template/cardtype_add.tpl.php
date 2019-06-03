
<form id="typeaddform" action="<?php echo $url ?>">
    <input type="hidden" name="id" value="<?php echo $data['id'] ?>">
    <div class="modal-header">
        <h5 class="modal-title" ><?php echo $title ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label>卡券类型名称</label>
            <input type="text" class="form-control" name="data[name]" value="<?php echo $data['name'] ?>" placeholder="输入类型名称，最多20个字符" required maxlength="40" />
        </div>
        <div class="form-group">
            <label>激活有效期</label>
            <div class="input-daterange input-group" id="datepicker">
                <div class="input-group-prepend">
                    <span class="input-group-text">从</span>
                </div>
                <input type="text" class="form-control" name="data[begintime]" value="<?php echo date('Y-m-d', $data['begintime']) ?>" placeholder="选择开始时间" required />
                <div class="input-group-prepend">
                    <span class="input-group-text">到</span>
                </div>
                <input type="text" class="form-control" name="data[endtime]" value="<?php echo date('Y-m-d', $data['endtime']) ?>" placeholder="选择结束时间" required/ />
            </div>
        </div>
        <div class="form-group">
            <label>提货有效期（天）</label>
            <input type="number" class="form-control" name="data[vailddays]" value="<?php echo $data['vailddays'] ?>" placeholder="输入有效天数" required />
        </div>
        <div id="items"></div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
        <button type="submit" name="data[submit]" value="true" class="btn btn-primary">保存</button>
    </div>
</form>
<script>
    $('#datepicker').datepicker({
        language: "zh-CN",
        format: 'yyyy-mm-dd'
    });
    $('#typeaddform').validate({submitHandler:function(){
        var form = $('#typeaddform');
        $.post(form.attr('action'), form.serialize(), function(res) {
            if (res.succ) {
                reload();
            } else {
                alert(res.msg);
            }
        });
        return false;
    }});
</script>