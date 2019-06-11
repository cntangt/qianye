<?php

ini_set("display_errors", "On");
error_reporting(E_ALL);
define('XIAOCMS_ADMIN', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('CONTROLLER_DIR', XIAOCMS_ADMIN . 'controller' . DIRECTORY_SEPARATOR);
define('XIAOCMS_PATH', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
include XIAOCMS_PATH . 'core/xiaocms.php';
xiaocms::load_file(CONTROLLER_DIR . 'Admin.class.php');
xiaocms::run();