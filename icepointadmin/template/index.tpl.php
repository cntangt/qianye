<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->site_config['site_name'];?> - 后台管理中心 - Powered by XiaoCms</title>
<script src="https://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
<link href="img/index.css" rel="stylesheet" />
<style type="text/css">
#main { background:url(./img/bg_y.gif) repeat-y <?php echo $left_width-6 ;?>px 0; }
#left { width:<?php echo $left_width ;?>px; background:url(./img/bg-left2.gif) repeat-x ; position:absolute; top:30px; left:0; }
#right { margin-left:<?php echo $left_width ;?>px; }
</style>
</head>
<body scroll="no">
<!--头部开始-->
<div id="head">
  <h1><?php echo $this->site_config['site_name']?></h1>
  <div id="menu_position">
    <ul id="menu">
        <li id="_MP104" ><a href="javascript:_MP(104,'<?php echo url('index/my') ;?>');">设置</a>
          <ul>
          <li id="_MP1045" ><a href="javascript:_MP(1045,'<?php echo url('index/my') ;?>');" >我的账号</a></li>
		  <?php if($this->menu('index-config') ) { ;?>
          <li id="_MP1042" ><a href="javascript:_MP(1042,'<?php echo url('index/config', array('type'=>1)) ;?>');" >系统设置</a></li>
		  <?php } ;?>

		  <?php if($this->menu('administrator-index')) { ;?>
          <li id="_MP1099" ><a href="javascript:_MP(1099,'<?php echo url('administrator/index') ;?>');" >账号管理</a></li>
		  <?php } ;?>

          <li id="_MP107" ><a href="javascript:_MP(107,'<?php echo url('index/cache') ;?>');" >更新缓存</a></li>
		  
		  <?php if($this->menu('database-index')) { ;?>
          <li id="_MP403" ><a href="javascript:_MP(403,'<?php echo url('database') ;?>');" >数据备份</a></li>
		  <?php } ;?>

		  <?php if($this->menu('models-index')) { ;?>
          <li id="_MP1046" ><a href="javascript:_MP(1046,'<?php echo url('models') ;?>');" >内容模型</a></li>
          <li id="_MP1047" ><a href="javascript:_MP(1047,'<?php echo url('models', array('typeid'=>3)) ;?>');" >表单模型</a></li>
          <li id="_MP1048" ><a href="javascript:_MP(1047,'<?php echo url('models', array('typeid'=>4)) ;?>');" >自定义表</a></li>
		  <?php } ;?>
		  
<!--    <li id="_MP403" ><a href="javascript:_MP(4031,'<?php echo url('uploadfile/manager') ;?>');" >附件管理</a></li> -->

		  <li class="menubtm"></li>
          </ul>
        </li>
		<?php if($this->menu('category-index')) { ;?>
        <li id="_MP101" ><a href="javascript:_MP(101,'<?php echo url('category') ;?>');" >栏目</a></li>
    	<?php } ?>
		<?php if($this->menu('block-index')) { ;?>
        <li id="_MP102" ><a href="javascript:_MP(102,'<?php echo url('block') ;?>');" >区块</a></li>
    	<?php } ?>
		<?php if (defined('XIAOCMS_MEMBER') && $this->menu('member-index')) {  ?>
        <li id="_MP103" ><a href="javascript:_MP(103,'<?php echo url('member') ;?>');" >会员</a></li>
		<?php } ?>
    	
		<?php if($this->menu('template-index')) { ;?>
         <li id="_MP105" ><a href="javascript:_MP(105,'<?php echo url('template') ;?>');" >模板</a></li>
		<?php } ?>

		<?php if($this->menu('createhtml-index')) { ?>
        <li id="_MP106" ><a href="javascript:_MP(106,'<?php echo url('createhtml') ;?>');" >生成</a>
          <ul>
          <li id="_MP1061" ><a href="javascript:_MP(1061,'<?php echo url('createhtml') ;?>');" >生成首页</a></li>
		  
	      <?php if($this->menu('createhtml-category')) { ;?>
          <li id="_MP1062" ><a href="javascript:_MP(1062,'<?php echo url('createhtml/category') ;?>');" >生成栏目页</a></li>
		  <?php } ?>
		  
	      <?php if($this->menu('createhtml-show')) { ;?>
          <li id="_MP1063" ><a href="javascript:_MP(1063,'<?php echo url('createhtml/show') ;?>');" >生成内容页</a></li>
		  <?php } ?>

		  <li class="menubtm"></li>
          </ul>
        </li>
		<?php } ?>
   </ul>
  </div>
  <!--账户信息-->
  <div class="user">
    <?php echo $this->admin['username']; ?>（<?php echo $this->admin['realname']; ?>），<a href="javascript:;" onClick="logout();">退出</a></div>
</div>
<!--头部结束-->
<div id="main">
  <!--左侧开始-->
  <div id="left">
    <h2>
      <span style="float:right;"></span>
      <label id='root_menu_name'>内容管理</label>
    </h2>
    <ul id="tree">
        <li><a href="<?php echo url('cardtype/index')?>" target="right">卡券类型</a></li>
        <li><a href="<?php echo url('card/index')?>" target="right">卡券管理</a></li>
        <li><a href="<?php echo url('content/index')?>" target="right">内容页【示例】</a></li>
    </ul>
    <!--<iframe name="leftMain" id="leftMain" src="<?php echo url('index/tree'); ?>" frameborder="false" scrolling="auto" style="border:none" width="100%" height="600" allowtransparency="true"></iframe>-->
  </div>
  <!--左侧结束-->
  <!--右侧开始-->
  <div id="right">
    <div id="home">
    	<div id="position">后台首页</div>
    </div>
    <iframe name="right" id="rightMain" src="<?php echo url('card/index'); ?>" frameborder="false" scrolling="auto" style="border:none;" width="100%" allowtransparency="true"></iframe>
  </div>
</div>
<script type="text/javascript"> 
window.onresize = function(){
	var heights = document.documentElement.clientHeight;
	document.getElementById('rightMain').height = heights-61;
}
window.onresize();
function _MP(id, target_show_url) {
	var title = $("#_MP"+id).find('a').html();
	$("#rightMain").prop('src', target_show_url);
	$('.focused').removeClass("focused");
	$('#_MP'+id).addClass("focused");
}
function logout(){
	if (confirm("确定退出吗"))
	top.location = '<?php echo url("login/logout"); ?>';
	return false;
}
function refresh() {
	document.getElementById('leftMain').src = '<?php echo url('index/tree'); ?>';
}
</script>
</body>
</html>