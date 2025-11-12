<?php
/**
 * 字段工具函数
 * 简化版本
 */

/**
 * 转义字段值
 * @param string $value 字段值
 * @return string 转义后的值
 */
function escape_field($value)
{
    if (function_exists('mysql_real_escape_string')) {
        $conn = TD::conn();
        return mysql_real_escape_string($value, $conn);
    }
    return addslashes($value);
}
?>



