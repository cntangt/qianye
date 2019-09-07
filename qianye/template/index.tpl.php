<!doctype html>
<html lang="zh-cn">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title><?php echo $this->site_config['site_name']; ?> - 后台管理中心</title>
  <link href="https://cdn.bootcss.com/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
  <script src="https://cdn.bootcss.com/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="https://cdn.bootcss.com/feather-icons/4.21.0/feather.min.js"></script>
  <link href="img/page.css" rel="stylesheet" />
</head>

<body>
  <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
    <span class="navbar-brand leftwidth mr-0 sitename"><?php echo $this->site_config['site_name'] ?></span>
    <!-- <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search"> -->
    <!--  -->
    <ul class="navbar-nav px-3">
      <li class="nav-item text-nowrap">
        <span class="nav-link"><?php echo $this->admin['username']; ?>（<?php echo $this->admin['realname']; ?>）</span>
      </li>
    </ul>
  </nav>
  <div class="container-fluid">
    <nav class="d-none d-md-block bg-light sidebar leftwidth">
      <div class="sidebar-sticky" id="leftmenu">
        <ul class="nav flex-column">
          <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>content manage</span>
          </h6>
          <?php if ($this->menu('card-index')) { ?>
            <li class="nav-item">
              <a class="nav-link active" href="<?php echo url('card/index') ?>" target="rightMain">
                <span data-feather="layers"></span>
                卡券管理
              </a>
            </li>
          <?php } ?>
          <?php if ($this->menu('cardtype-index')) { ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo url('cardtype/index') ?>" target="rightMain">
                <span data-feather="package"></span>
                卡券类型管理
              </a>
            </li>
          <?php } ?>
          <?php if ($this->menu('order-index')) { ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo url('order/index') ?>" target="rightMain">
                <span data-feather="shopping-cart"></span>
                提货管理
              </a>
            </li>
          <?php } ?>
          <?php if ($this->menu('product-index')) { ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo url('product/index') ?>" target="rightMain">
                <span data-feather="grid"></span>
                商品管理
              </a>
            </li>
          <?php } ?>
          <?php if ($this->menu('wealth-index')) { ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo url('wealth/index') ?>" target="rightMain">
                <span data-feather="dollar-sign"></span>
                会员财富
              </a>
            </li>
          <?php } ?>
          <?php if ($this->menu('comment-index')) { ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo url('comment/index') ?>" target="rightMain">
                <span data-feather="message-square"></span>
                评价管理
              </a>
            </li>
          <?php } ?>
        </ul>
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
          <span>System Config</span>
        </h6>
        <ul class="nav flex-column mb-2">
          <?php if ($this->menu('administrator-index')) { ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo url('administrator/index') ?>" target="rightMain">
                <span data-feather="users"></span>
                系统用户管理
              </a>
            </li>
          <?php } ?>
          <?php if ($this->menu('index-setting')) { ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo url('index/setting') ?>" target="rightMain">
                <span data-feather="settings"></span>
                配置管理
              </a>
            </li>
          <?php } ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo url('index/my') ?>" target="rightMain">
              <span data-feather="key"></span>
              修改密码
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo url("login/logout"); ?>">
              <span data-feather="log-out"></span>
              退出
            </a>
          </li>
        </ul>
      </div>
    </nav>

    <iframe name="rightMain" id="rightMain" src="<?php echo url('card/index'); ?>" frameborder="false" scrolling="auto" style="border:none;" allowtransparency="true"></iframe>
  </div>

  <!-- <div id="head">
    <div class="user">
      <?php echo $this->admin['username']; ?>（<?php echo $this->admin['realname']; ?>），<a href="javascript:;" onClick="logout();">退出</a></div>
  </div> -->
  <!--  -->
  <script type="text/javascript">
    $(function() {
      feather.replace();
      window.onresize = function() {
        $('#rightMain').css({
          marginLeft: 240,
          height: $(window).height() - $('nav.navbar').height() - 5,
          width: $(window).width() - 240
        });
      }
      window.onresize();
      $('#leftmenu a').click(function() {
        $('#leftmenu a').removeClass('active');
        $(this).addClass('active');
      })
    });
  </script>
</body>

</html>