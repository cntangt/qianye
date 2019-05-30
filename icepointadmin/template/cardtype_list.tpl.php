<?php include $this->admin_tpl('header'); ?>
<script type="text/javascript">
top.document.getElementById('position').innerHTML = '卡券管理';
</script>

<div class="subnav">
	<div class="content-menu">
		<div class="left">
		<?php if ($this->menu('cardtype-add')) {
                  ; ?>
            <button type="button" class="btn btn-primary dialog" data-url="<?php echo url('cardtype/add') ?>">添加卡券类型</button>
    	<?php 
              } ?>
		</div>
		<div class="right">
		    <form method="get" autocomplete="off" class="form-inline">
		        <input type="text" class="form-control mx-sm-3" name="title" />
		        <button type="submit" class="btn btn-success">查询</button>
            </form>
		</div>
	</div>
	<div class="bk10"></div>
		<table class="m-table m-table-row">
		<thead class="m-table-thead s-table-thead">
		<tr>
			<th width="20" align="left"><input id="deletec" type="checkbox" onClick="setC()"></th>
			<th width="25" align="left">ID </th>
			<th align="left">标题</th>
			<th width="80" align="left">栏目</th>
			<th width="60" align="left">发布人</th>
			<th width="80" align="left">更新时间</th>
			<th width="160" align="left">操作</th>
			<th width="40" align="left">排序</th>
		</tr>
		</thead>
		<tbody>
		<?php if (is_array($list)) foreach ($list as $t) { ?>
		<tr>
			<td align="left">
			
			<input name="batch[]" value="<?php echo $t['id']; ?>" type="checkbox" class="deletec"></td>
			<td align="left"><?php echo $t['id']; ?></td>
			<td align="left">
			<?php if (is_array($this->status_arr)) foreach ($this->status_arr as $key => $r) { ?>
			<?php if ($t['status'] == $key && $key != 1) { ?>
			<a href="<?php echo url('content/index', array('catid' => $catid, 'status' => $key)); ?>"><font color="#f00">[<?php echo $r; ?>]</font></a>
			<?php 
                  } ?>
			<?php 
                      } ?>
			<a href="<?php echo url('content/edit', array('id' => $t['id'])); ?>"><?php echo $t['title']; ?></a>
			</td>
			<td align="left"><a href="<?php echo url('content/index', array('catid' => $t['catid'])); ?>"><?php echo $this->category_cache[$t['catid']]['catname']; ?></a></td>

			<td align="left"><a href="<?php echo url('content/index', array('username' => $t['username'], 'catid' => $t['catid'])); ?>"><?php echo $t['username']; ?></a></td>
			
			<td align="left"><span style="<?php if (date('Y-m-d', $t['time']) == date('Y-m-d')) { ?>color:#F00<?php 
                                                } ?>" title="<?php echo date('H:i', $t['time']); ?>"><?php echo date('Y-m-d', $t['time']); ?></span></td>
			
			<td align="left">
			<?php if (get_cache('form_model')) foreach (get_cache('form_model') as $j) {
                          if ($j['joinid'] == $modelid && !empty($catid) && empty($child)) { ?>
			<a href="<?php echo url('form/index', array('cid' => $t['id'], 'modelid' => $j['modelid'])); ?>"><?php echo $j['modelname']; ?></a> |
			<?php 
                          }
                      } ?>

			
			<a href="<?php echo $this->view->get_show_url($t); ?>" target="_blank">查看</a> | 
			<a href="<?php echo url('content/edit', array('id' => $t['id'])); ?>" >编辑</a> | 
			<a href="javascript:confirmurl('<?php echo url('content/del/', array('catid' => $t['catid'], 'id' => $t['id'])); ?>','确定删除 『 <?php echo $t['title']; ?> 』吗？ ')" >删除</a> 
			</td>
			<td align="left"><input type="text" name="listorder[<?php echo $t['id']; ?>]" class="input-text-c"  size='1'  value="<?php echo $t['listorder']; ?>"></td>
		</tr>
		<?php 
                  } ?>
		<tr >
			<td colspan="8"  align="left" style="border-bottom:0px;">
			<div  class="pageleft">

			<input type="submit"  class="button" value="删除" name="delete" onClick="confirm_delete()" >&nbsp;
			
			<input type="submit"  class="button" value="排序" name="order" onClick="$('#list_form').val('listorder')">&nbsp;

		<?php if (is_array($this->status_arr)) foreach ($this->status_arr as $key => $t) { ?>
    		<input type="submit"  class="button" value="设为<?php echo $t; ?>" onClick="$('#list_form').val('<?php echo $key; ?>')">&nbsp;
		<?php 
                  } ?>
		<?php if (empty($child) && !empty($catid)) { ?>
			批量移动至
			<select class="select"  name="movecatid">
			<?php echo $category; ?>
			</select>
			<input type="submit" class="button" value="确定移动" name="move" onClick="$('#list_form').val('move')">
		<?php 
              } ?>
			</div>
			<div class="pageright"><?php echo $pagelist; ?></div>
			</td>
		</tr>	

		</tbody>
		</table>
</div>
<div id="modal" class="modal" tabindex="-1" role="dialog" data-backdrop="static"><div class="modal-dialog" role="document" style="max-width:800px;"><div class="modal-content"></div></div></div>
<script>
    $(function () {
        $('.dialog').click(function () {
            var html = '<div class="progress"><span>加载中...</span><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"></div></div>';
            $('#modal').modal('show');
            $('#modal .modal-content').html(html).load($(this).data('url'));
        });

        $('#modal').on('submit','form',function () {
            var form = $('#typeaddform');
            $.post(form.attr('action'), form.serialize(), function (res) {
                if (res.succ) {
                    $('#modal').modal('hide');
                    window.location.reload();
                }
                else {
                    alert(res.msg);
                }
            });
            return false;
        });
    });
</script>
</body>
</html>