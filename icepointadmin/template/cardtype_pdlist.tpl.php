
<form id="typeaddform" action="<?php echo url('cardtype/editpd') ?>">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">绑定卡券商品</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div style="min-height:300px;" id="rows"></div>
    </div>
    <div class="modal-footer">
        <button type="button" style="position:absolute;left:10px" class="btn btn-success" id="addnew">新添加</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
        <button type="submit" name="data[submit]" value="true" class="btn btn-primary">保存</button>
    </div>
</form>
<div style="display:none;" id="row0">
    <div class="row">
        <div class="col-sm-6">
            <div class="input-group mb-3">
                <input type="hidden" name="pdid" class="pdid" />
                <select class="form-control pditem" name="pdname"></select>
                <!--<input type="text" class="form-control pditem" name="pdname" placeholder="输入关键字查询" />-->
            </div>
        </div>
        <div class="col-sm-4">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">数量</span>
                </div>
                <input type="number" name="quantity" class="form-control quantity" min="1" />
            </div>
        </div>
        <div class="col-sm-2">
            <button class="btn btn-sm btn-primary" type="button">删除</button>
        </div>
    </div>
</div>