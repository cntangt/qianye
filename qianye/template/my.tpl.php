<?php include $this->admin_tpl('header'); ?>
<div class="subnav">
  <div class="bk10"></div>
  <div class="container">
    <form id="updatepassform" action="<?php echo url('index/my')?>" method="post">
      <div class="form-group">
        <label>用户名</label>
        <input type="text" class="form-control" value="<?php echo $data['username'] ?>" disabled>
      </div>
      <div class="form-group">
        <label>原始密码</label>
        <input type="password" class="form-control" placeholder="请输入当前用户原始" name="data[old]" required minlength="6">
      </div>
      <div class="form-group">
        <label>新密码</label>
        <input type="password" class="form-control" placeholder="请输入新密码" name="data[new]" id="password" required minlength="6">
      </div>
      <div class="form-group">
        <label>确认密码</label>
        <input type="password" class="form-control" placeholder="请确认新密码" name="data[confirm]" required minlength="6">
      </div>
      <button type="submit" class="btn btn-primary">保存</button>
    </form>
  </div>
  <script>
    $(function() {
      $('#updatepassform').validate({
        rules: {
          'data[old]': {
            'required': true,
            'minlength': 6
          },
          'data[new]': {
            'required': true,
            'minlength': 6
          },
          'data[confirm]': {
            required: true,
            minlength: 6,
            equalTo: "#password"
          }
        },
        messages: {
          'data[confirm]': {
            equalTo: "确认密码和新密码不同"
          }
        }
      });
    });
  </script>
</div>
</body>

</html>