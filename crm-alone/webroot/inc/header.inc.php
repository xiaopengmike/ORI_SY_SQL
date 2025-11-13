<?php
/**
 * 页面头部
 * 简化版本
 */
if (!isset($HTML_PAGE_TITLE)) {
    $HTML_PAGE_TITLE = 'CRM系统';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="GBK">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($HTML_PAGE_TITLE, ENT_QUOTES, 'GBK'); ?></title>
</head>
<body>






