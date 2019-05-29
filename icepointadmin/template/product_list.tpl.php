<?php include $this->admin_tpl('header');?>
<script type="text/javascript">
top.document.getElementById('position').innerHTML = '栏目管理';
</script>
<?php
foreach ($cards as $r) {
    echo $r['name'];
}
?>
<div>aaa</div>
</body>
</html>