<?php include $this->admin_tpl('header'); ?>
<iframe name="export" style="display:none"></iframe>
<div class="subnav">
    <div class="content-menu">
        <div class="left">
            <?php if ($this->menu('order-export')) { ?>
                <a class="btn btn-sm btn-primary" href="#" style="color:#fff" data-url="<?php echo url('order/export') ?>" id="export" target="export">导出</a>
            <?php } ?>
            <?php if ($this->menu('order-send')) { ?>
                <a class="btn btn-sm btn-primary" href="#" style="color:#fff" data-url="<?php echo url('order/send') ?>" id="send" target="export">发货</a>
            <?php } ?>
        </div>
        <div class="right">
            <form autocomplete="off" class="form-inline" data-url="<?php echo url('order/index') ?>" id="searchform">
                <!-- <div class="input-group input-group-sm mx-sm-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">区域</span>
                    </div>
                    <select name="area" class="form-control">
                        <option value="">全部</option>
                        <option value="重庆市">重庆市</option>
                        <option value="万州区">万州区</option>
                        <option value="涪陵区">涪陵区</option>
                        <option value="渝中区">渝中区</option>
                        <option value="大渡口区">大渡口区</option>
                        <option value="江北区">江北区</option>
                        <option value="沙坪坝区">沙坪坝区</option>
                        <option value="九龙坡区">九龙坡区</option>
                        <option value="南岸区">南岸区</option>
                        <option value="北碚区">北碚区</option>
                        <option value="綦江区">綦江区</option>
                        <option value="大足区">大足区</option>
                        <option value="渝北区">渝北区</option>
                        <option value="巴南区">巴南区</option>
                        <option value="黔江区">黔江区</option>
                        <option value="长寿区">长寿区</option>
                        <option value="江津区">江津区</option>
                        <option value="合川区">合川区</option>
                        <option value="永川区">永川区</option>
                        <option value="南川区">南川区</option>
                        <option value="璧山区">璧山区</option>
                        <option value="铜梁区">铜梁区</option>
                        <option value="潼南区">潼南区</option>
                        <option value="荣昌区">荣昌区</option>
                        <option value="开州区">开州区</option>
                        <option value="梁平区">梁平区</option>
                        <option value="武隆区">武隆区</option>
                        <option value="城口县">城口县</option>
                        <option value="丰都县">丰都县</option>
                        <option value="垫江县">垫江县</option>
                        <option value="忠县">忠县</option>
                        <option value="云阳县">云阳县</option>
                        <option value="奉节县">奉节县</option>
                        <option value="巫山县">巫山县</option>
                        <option value="巫溪县">巫溪县</option>
                        <option value="石柱土家族自治县">石柱土家族自治县</option>
                        <option value="秀山土家族苗族自治县">秀山土家族苗族自治县</option>
                        <option value="酉阳土家族苗族自治县">酉阳土家族苗族自治县</option>
                        <option value="彭水苗族土家族自治县">彭水苗族土家族自治县</option>
                    </select>
                </div> -->
                <div class="input-daterange input-group input-group-sm" id="datepicker">
                    <div class="input-group-prepend">
                        <span class="input-group-text">提货时间</span>
                    </div>
                    <input type="text" class="form-control" name="begin" placeholder="选择开始时间" />
                    <div class="input-group-prepend">
                        <span class="input-group-text">到</span>
                    </div>
                    <input type="text" class="form-control" name="end" placeholder="选择结束时间" />
                </div>
                <div class="input-group input-group-sm mx-sm-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">商品</span>
                    </div>
                    <input type="text" class="form-control form-control-sm" name="pdname" placeholder="提货商品关键字" />
                </div>
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text">手机</span>
                    </div>
                    <input type="text" class="form-control form-control-sm" name="mobile" placeholder="客户手机号查询" />
                </div>
                <div class="input-group input-group-sm mx-sm-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">状态</span>
                    </div>
                    <select name="status" class="form-control">
                        <option value="">全部</option>
                        <option value="10">待发货</option>
                        <option value="20">待揽收</option>
                        <option value="30">待配送</option>
                        <option value="40">配送中</option>
                        <option value="50">待签收</option>
                        <option value="60">已签收</option>
                        <option value="70">已完成</option>
                        <option value="-10">关闭</option>
                    </select>
                </div>
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
    var html = '<div class="progress"><span>加载中...</span><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"></div></div>';
    $(function() {
        $('#listcontainer,.content-menu').on('click', '.dialog', function() {
            $('#modal').modal('show');
            $('#modal .modal-content').html(html).load($(this).data('url'));
        }).on('click', '.xiaocms-page a', function() {
            load($(this).attr('href'));
            return false;
        }).on('click', '.confirm', function() {
            if (confirm($(this).data('tip'))) {
                $.post($(this).data('url'), reload);
            }
        });

        $('#export').click(function() {
            $(this).attr('href', $(this).data('url') + '&' + $('#searchform').serialize());
        });

        $('#send').click(function() {
            $.get($(this).data('url'), $('#searchform').serialize(),function(res){
                if(res.succ){
                    loadlist();
                }
                else{
                    alert(res.msg);
                }
            });
            return false;
        });

        $('#datepicker').datepicker({
            clearBtn: true,
            language: "zh-CN",
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        $('#modal').on('click', '#addnew', function() {
            $($('#row0')[0].innerHTML).appendTo('#rows').find('.pdid').select2({
                data: list
            });
        }).on('click', '.del', function() {
            $(this).parent('div').parent('div.row').remove();
        });

        $('#searchform').submit(loadlist);
        $('#searchform #ctid').select2({
            placeholder: "选择卡券类型",
            allowClear: true
        }).val(null).trigger("change");
        $('#searchform select').change(loadlist);
        loadlist();
    });

    function loadlist() {
        var form = $('#searchform');
        load(form.data('url'), form.serialize());
        return false;
    }

    function reload() {
        load($('#currentpage').val());
    }

    function load(url, data) {
        $('#modal').modal('show');
        $('#modal .modal-content').html(html);
        $('#listcontainer').load(url, data, function() {
            $('#modal').modal('hide');
        });
    }
</script>
</body>

</html>