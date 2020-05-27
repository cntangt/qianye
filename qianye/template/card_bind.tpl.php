<form id="cardbuildform" action="<?php echo $url ?>" autocomplete="off">
    <div class="modal-header">
        <h5 class="modal-title"><?php echo $title ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label>卡号规则设置<span style="color:red">（注意：仅当卡状态为【销售】时，才可以进行绑卡，且已绑定的卡不支持再次绑定）</span></label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">前缀</span>
                </div>
                <input type="text" class="form-control checkno" name="data[pre]" placeholder="输入卡号前缀，最多6位" required maxlength="6" />
                <div class="input-group-prepend">
                    <span class="input-group-text">起始卡号</span>
                </div>
                <input type="text" class="form-control checkno" name="data[begin]" placeholder="输入起始卡号，最多12位" required maxlength="12" />
                <div class="input-group-prepend">
                    <span class="input-group-text">结尾卡号</span>
                </div>
                <input type="number" class="form-control checkno" name="data[end]" placeholder="输入结束卡号，最多12位" required maxlength="12" />
            </div>
        </div>
        <div class="form-group">
            <label>卡号范围</label>
            <div class="input-group mb-3">
                <input disabled id="demo" class="form-control" data-url="/?c=api&a=checkcount">
            </div>
        </div>
        <div class="form-group">
            <label>选择会员</label>
            <div class="input-group mb-3">
                <select type="text" id="customer" name="data[customer]" class="form-control"></select>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
        <button type="submit" name="data[submit]" value="true" class="btn btn-primary save" disabled="true">保存</button>
    </div>
</form>
<script>
    var form = $('#cardbuildform');
    form.validate({
        submitHandler: function() {
            $.post(form.attr('action'), form.serialize(), function(res) {
                if (res.succ) {
                    alert(res.msg);
                    reload();
                } else {
                    alert(res.msg);
                }
            });
            return false;
        }
    });
    $('.checkno').blur(function() {
        $.post($('#demo').data('url'), form.serialize(), function(res) {
            if (res.succ) {
                $('#demo').val(res.msg).css('color', 'green');
            } else {
                $('#demo').val(res.msg).css('color', 'red');
            }
            $('.save').attr('disabled', res.val == 0);
        })
    });
    $('#customer').select2({
        placeholder: '输入用户或手机号查询',
        ajax: {
            url: "/?c=api&a=customer",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    key: params.term,
                };
            },
            processResults: function(data) {
                return {
                    results: data.val
                };
            }
        }
    });
</script>