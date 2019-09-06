<?php include $this->admin_tpl('header'); ?>
<div class="subnav">
	<div class="bk10"></div>
	<div class="container">
		<form id="updatepassform" action="" method="post">
			<div class="form-group">
				<label>用户名</label>
				<input type="text" class="form-control" value="<?php echo $data['username'] ?>" <?php if ($data['username']) echo 'disabled'; ?> placeholder="请填写用户登录名称">
			</div>
			<div class="form-group">
				<label>密码</label>
				<input type="password" class="form-control" placeholder="用户密码" name="data[password]" value="<?php echo $data['password'] ?>" required minlength="6">
			</div>
			<div class="form-group">
				<label>备注</label>
				<input type="text" class="form-control" placeholder="用户真实姓名备注" name="data[realname]" value="<?php echo $data['realname'] ?>" required>
			</div>
			<div class="form-group">
				<label>超级管理员</label>
				<label><input name="data[roleid]" type="radio" value="1" <?php if ($data['roleid']) echo 'checked' ?>> 是</label>
				<label><input name="data[roleid]" type="radio" value="0" <?php if (!$data['roleid']) echo 'checked' ?>> 否</label>
				<div class="onShow">超级管理员不受权限所控制(注:系统必须含有一个超级管理员，否则会出问题)</div>
			</div>
			<div class="form-group">
				<label>操作权限</label>
				<ul class="list-group auths">
					<li class="list-group-item">
						<label>卡券管理：</label>
						<label><input name="auth[card-index]" value="1" type="checkbox" <?php if ($auth['card-index']) echo 'checked' ?>>列表</label>
						<label><input name="auth[card-add]" value="1" type="checkbox" <?php if ($auth['card-add']) echo 'checked' ?>>生成</label>
						<label><input name="auth[card-export]" value="1" type="checkbox" <?php if ($auth['card-export']) echo 'checked' ?>>导出</label>
						<label><input name="auth[card-sale]" value="1" type="checkbox" <?php if ($auth['card-sale']) echo 'checked' ?>>销售</label>
						<label><input name="auth[card-disable]" value="1" type="checkbox" <?php if ($auth['card-disable']) echo 'checked' ?>>作废</label>
						<label class="checkall"><input type="checkbox" class="checkall">全选</label>
					</li>
					<li class="list-group-item">
						<label>卡券类型：</label>
						<label><input name="auth[cardtype-index]" value="1" type="checkbox" <?php if ($auth['cardtype-index']) echo 'checked' ?>>列表</label>
						<label><input name="auth[cardtype-add]" value="1" type="checkbox" <?php if ($auth['cardtype-add']) echo 'checked' ?>>添加</label>
						<label><input name="auth[cardtype-edit]" value="1" type="checkbox" <?php if ($auth['cardtype-edit']) echo 'checked' ?>>修改</label>
						<label><input name="auth[cardtype-pdlist]" value="1" type="checkbox" <?php if ($auth['cardtype-pdlist']) echo 'checked' ?>>绑定商品</label>
						<label><input name="auth[cardtype-export]" value="1" type="checkbox" <?php if ($auth['cardtype-export']) echo 'checked' ?>>导出</label>
						<label class="checkall"><input type="checkbox" class="checkall">全选</label>
					</li>
					<li class="list-group-item">
						<label>提货管理：</label>
						<label><input name="auth[order-index]" value="1" type="checkbox" <?php if ($auth['order-index']) echo 'checked' ?>>列表</label>
						<label><input name="auth[order-close]" value="1" type="checkbox" <?php if ($auth['order-close']) echo 'checked' ?>>关闭</label>
						<label><input name="auth[order-export]" value="1" type="checkbox" <?php if ($auth['order-export']) echo 'checked' ?>>导出</label>
						<label><input name="auth[order-send]" value="1" type="checkbox" <?php if ($auth['order-send']) echo 'checked' ?>>发货</label>
						<label class="checkall"><input type="checkbox" class="checkall">全选</label>
					</li>
					<li class="list-group-item">
						<label>会员财富：</label>
						<label><input name="auth[wealth-index]" value="1" type="checkbox" <?php if ($auth['wealth-index']) echo 'checked' ?>>列表</label>
						<label><input name="auth[wealth-edit]" value="1" type="checkbox" <?php if ($auth['wealth-edit']) echo 'checked' ?>>编辑</label>
						<label><input name="auth[wealth-export]" value="1" type="checkbox" <?php if ($auth['wealth-export']) echo 'checked' ?>>导出</label>
						<label class="checkall"><input type="checkbox" class="checkall">全选</label>
					</li>
					<li class="list-group-item">
						<label>评价管理：</label>
						<label><input name="auth[comment-index]" value="1" type="checkbox" <?php if ($auth['comment-index']) echo 'checked' ?>>列表</label>
						<label><input name="auth[comment-export]" value="1" type="checkbox" <?php if ($auth['comment-export']) echo 'checked' ?>>导出</label>
						<label class="checkall"><input type="checkbox" class="checkall">全选</label>
					</li>
				</ul>
			</div>
			<button type="submit" class="btn btn-primary">保存</button>
		</form>
	</div>
</div>
<script type="text/javascript">
	$('.list-group-item .checkall input').click(function() {
		$(this).parent('label').parent('li').find('label input').prop('checked', $(this).prop('checked'));
	});
</script>
</body>

</html>