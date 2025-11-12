<?php
/**
 * 系统入口文件
 */
// 加载配置
require_once("inc/config.php");
require_once("inc/db.php");
require_once("inc/auth.php");

// 如果未登录，跳转到登录页
if (!isset($_SESSION['LOGIN_USER_ID']) || empty($_SESSION['LOGIN_USER_ID'])) {
    header('Location: login.php');
    exit;
}

// 默认跳转到客户数据模块
header('Location: general/customer_data/list.php');
exit;
?>

