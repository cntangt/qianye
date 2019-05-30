
<form id="pdeditform" action="<?php echo url('cardtype/editpd') ?>">
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
                <select class="form-control pdid" name="pdid" data-placeholder="选择商品或者关键字查询"></select>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">数量</span>
                </div>
                <input type="number" name="quantity" class="form-control quantity" min="1" value="1" />
            </div>
        </div>
        <div class="col-sm-2">
            <button class="btn btn-sm btn-primary del" type="button">删除</button>
        </div>
    </div>
</div>
<script>var data=<?php echo json_encode($list) ?></script>
<script>
    $(data).each(function(i,obj){
        var row=$($('#row0')[0].innerHTML);
        row.appendTo('#rows').find('.pdid').select2({ data: list }).val(obj.sku).trigger("change");
        row.find('.quantity').val(obj.quantity);
    });
    $('#pdeditform').validate({
        submitHandler:function() {
            var data=[];
            $('#rows .row').each(function(i,row){
                var quantity=$(row).find('.quantity').val();
                var pdid=$(row).find('.pdid').val();
                var name=$(row).find('.pdid option:selected').text();
                if(quantity==''){
                    alert('请填写数量');
                    $(row).find('.quantity').focus();
                }
                data.push({sku:pdid,productname:name,quantity:quantity});
            });
            if(data.length<1){
                alert('请绑定产品')
            }
            return false;
        }
    });
</script>