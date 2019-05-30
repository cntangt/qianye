<?php
ini_set("display_errors", "On");
error_reporting(E_ALL);
define('XIAOCMS_PATH',   dirname(__FILE__) . DIRECTORY_SEPARATOR);
include XIAOCMS_PATH . 'core/xiaocms.php';
xiaocms::run();