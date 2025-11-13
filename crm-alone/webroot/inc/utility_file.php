<?php
/**
 * 文件工具函数
 * 简化版本
 */

/**
 * 获取文件扩展名
 * @param string $filename 文件名
 * @return string 扩展名
 */
function get_file_ext($filename)
{
    return strtolower(substr(strrchr($filename, '.'), 1));
}

/**
 * 检查文件类型是否允许
 * @param string $filename 文件名
 * @param string $allow_types 允许的类型，逗号分隔
 * @return bool
 */
function check_file_type($filename, $allow_types)
{
    $ext = get_file_ext($filename);
    $types = explode(',', $allow_types);
    foreach ($types as $type) {
        if (trim($type) == $ext) {
            return true;
        }
    }
    return false;
}
?>






