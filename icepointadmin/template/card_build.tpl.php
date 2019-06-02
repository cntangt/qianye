<form id="cardbuildform" action="<?php echo url('card/build') ?>" autocomplete="off">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">生成卡券</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label>卡号规则设置</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">前缀</span>
                </div>
                <input type="text" class="form-control checkno" name="data[pre]" placeholder="输入卡号前缀，最多6位" required maxlength="6" />
                <div class="input-group-prepend">
                    <span class="input-group-text">自增号</span>
                </div>
                <input type="text" class="form-control checkno" name="data[no]" placeholder="输入卡号自增号，最多12位" required maxlength="12" />
                <div class="input-group-prepend">
                    <span class="input-group-text">数量</span>
                </div>
                <input type="number" class="form-control checkno" name="data[qua]" placeholder="输入生成卡号数量" required min="1" />
            </div>
        </div>
        <div class="form-group">
            <label>卡号示例</label>
            <div class="input-group mb-3">
                <input disabled id="demo" class="form-control" data-url="/?c=api&a=checkno">
                <!-- <div class="input-group-append">
                    <button type="button" id="check" class="btn btn-success" data-url="/?c=api&a=checkno">检查</button>
                </div> -->
            </div>
        </div>
        <div class="form-group">
            <label>卡券密码规则设置</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">长度</span>
                </div>
                <select class="form-control" name="data[passlen]">
                    <option value="6">6位</option>
                    <option value="8">8位</option>
                    <option value="10">10位</option>
                    <option value="12">12位</option>
                    <option value="16">16位</option>
                    <option value="24">24位</option>
                </select>
                <div class="input-group-prepend">
                    <span class="input-group-text">组成</span>
                </div>
                <select class="form-control" name="data[passtype]">
                    <option value="10">数字</option>
                    <option value="20">字母</option>
                    <option value="30">数字+字母</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>卡券类型</label>
            <select name="data[ctid]" class="form-control" id="cardtypeid">
                <?php if (is_array($list)) foreach ($list as $t) { ?>
                    <option value="<?php echo $t['id'] ?>">【<?php echo $t['name'] ?>】<?php echo $t['description'] ?></option>
                <?php
            } ?>
            </select>
        </div>
        <ul id="errors"></ul>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
        <button type="submit" name="data[submit]" value="true" class="btn btn-primary save" disabled="true">保存</button>
    </div>
</form>
<script>
    var form = $('#cardbuildform');
    $('#cardtypeid').select2();
    $('#datepicker').datepicker({
        language: "zh-CN",
        format: 'yyyy-mm-dd'
    });
    form.validate({
        errorLabelContainer: $('#errors'),
        errorElement: 'li',
        messages: {
            'data[pre]': {
                required: '请输入卡号前缀'
            },
            'data[no]': {
                required: '请输入自增号'
            },
            'data[qua]': {
                required: '请输入生成卡号数量'
            }
        },
        submitHandler: function() {
            $.post(form.attr('action'), form.serialize(), function(res) {
                debugger
                if (res.succ) {
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
                $('#demo').val(res.val).css('color', 'green');
            } else {
                $('#demo').val(res.msg).css('color', 'red');
            }
            $('.save').attr('disabled', !res.succ);
        })
    });
</script>