<?php include $this->admin_tpl('header'); ?>
<div class="subnav">
	<div class="content-menu">
		<?php if ($this->menu('administrator-add')) { ?>
			<a class="btn btn-sm btn-primary" href="<?php echo url('administrator/add') ?>">添加用户</a>
		<?php } ?>
	</div>
	<div class="bk10"></div>
	<table width="100%" class="m-table m-table2 m-table-row">
		<thead class="m-table-thead s-table-thead">
			<tr>
				<th width="60" align="left">ID</th>
				<th align="left">用户名</th>
				<th align="left">是否超级管理员</th>
				<th align="left">备注</th>
				<th width="120" align="left">操作</th>
			</tr>
		</thead>
		<tbody class="line-box">
			<?php if (is_array($list)) foreach ($list as $t) { ?>
				<tr height="25">
					<td align="left"><?php echo $t['userid']; ?></td>
					<td align="left"><a href="<?php echo url('administrator/edit', array('userid' => $t['userid'])); ?>"><?php echo $t['username']; ?></a></td>
					<td align="left">
						<?php if ($t['roleid'] == 1) {
							echo '是';
						} else {
							echo '否';
						} ?>
					</td>
					<td align="left"><?php echo $t['realname']; ?></td>
					<td align="left">
						<a class="btn btn-sm btn-primary" href="<?php echo url('administrator/edit', array('userid' => $t['userid'])); ?>">编辑</a>
						<a class="btn btn-sm btn-danger" href="javascript:confirmurl('<?php echo url('administrator/del/', array('userid' => $t['userid'])); ?>','确定删除 『<?php echo $t['username']; ?> 』用户吗？')">删除</a>
					</td>
				</tr>
			<?php } ?>
		<tbody>
	</table>
</div>
</body>

</html>