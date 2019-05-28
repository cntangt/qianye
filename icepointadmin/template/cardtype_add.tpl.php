<form>
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">添加卡券</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label>卡券类型名称</label>
            <input type="text" class="form-control" placeholder="输入类型名称">
        </div>
        <div class="form-group">
            <label>激活有效期</label>
            <div class="input-daterange input-group" id="datepicker">
                <div class="input-group-prepend">
                    <span class="input-group-text">从</span>
                </div>
                <input type="text" class="form-control" name="start" placeholder="选择开始时间">
                <div class="input-group-prepend">
                    <span class="input-group-text">到</span>
                </div>
                <input type="text" class="form-control" name="end" placeholder="选择结束时间">
                <script>$('#datepicker').datepicker({ language: "zh-CN" });</script>
            </div>
        </div>
        <div class="form-group">
            <label>提货有效期（天）</label>
            <input type="number" class="form-control" placeholder="输入有效天数">
        </div>
        <div id="items">

        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
        <button type="submit" class="btn btn-primary">保存</button>
    </div>
</form>