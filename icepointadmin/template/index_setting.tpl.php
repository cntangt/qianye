<?php include $this->admin_tpl('header'); ?>
<div class="subnav">
  <div class="bk10"></div>
  <div class="container">
    <form id="updatekvform" action="<?php echo url('index/setting') ?>" method="post">
      <?php
      $i = 0;
      foreach ($list as $t) { ?>
        <div class="form-group">
          <label><?php echo $t['key'] ?></label>
          <input type="hidden" name="data[<?php echo $i ?>][key]" value="<?php echo $t['key'] ?>">
          <input type="text" class="form-control" name="data[<?php echo $i ?>][value]" value="<?php echo $t['value'] ?>" required>
        </div>
        <?php
        $i++;
      } ?>
      <button type="submit" class="btn btn-primary">保存</button>
    </form>
  </div>
</div>
<script>
  $(function() {
    var form = $('#updatekvform');
    form.validate({
      submitHandler: function(f) {
        $.post(form.attr('action'), form.serialize(), function(res) {
          alert(res.msg);
        });
        return false;
      }
    })
  })
</script>
</body>

</html>