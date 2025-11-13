<?php
/**
 * JavaScript语言文件
 * 简化版本，返回语言配置
 */
header('Content-Type: application/javascript; charset=GBK');

$lang = isset($_COOKIE['LANG']) ? $_COOKIE['LANG'] : 'CH';

// 输出语言变量到JavaScript
echo "var LANG = '" . $lang . "';\n";
echo "var LG_CUSTOMER_DATA = {};\n";

// 如果需要更多语言配置，可以在这里添加
?>






