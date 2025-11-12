<?php
/**
 * 通用工具函数
 * 简化版本，替代原系统的 utility_all.php
 */

/**
 * 显示消息并退出
 * @param string $title 标题
 * @param string $message 消息内容
 */
function Message($title, $message)
{
    echo "<!DOCTYPE html><html><head><meta charset='GBK'><title>$title</title></head><body>";
    echo "<script>alert('$message');history.back();</script>";
    echo "</body></html>";
    exit;
}

/**
 * 翻译函数（简化版）
 * @param string $text 文本
 * @return string 翻译后的文本
 */
function _($text)
{
    return $text;
}

/**
 * 获取客户端IP地址
 * @return string IP地址
 */
function get_client_ip()
{
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

/**
 * 添加日志（简化版）
 * @param int $type 日志类型
 * @param string $content 日志内容
 * @param string $user_id 用户ID
 */
function add_log($type, $content, $user_id = '')
{
    // 简化版，不实际记录日志
    // 原系统会记录到 SYS_LOG 表
}

/**
 * 检查时间范围
 * @param string $time_range 时间范围，格式：HH:MM:SS ~ HH:MM:SS
 * @return bool 是否在时间范围内
 */
function check_time_range($time_range)
{
    if (empty($time_range)) {
        return true;
    }
    
    $parts = explode('~', $time_range);
    if (count($parts) != 2) {
        return true;
    }
    
    $start = trim($parts[0]);
    $end = trim($parts[1]);
    $current = date('H:i:s');
    
    return ($current >= $start && $current <= $end);
}

/**
 * 获取系统参数（简化版）
 * @param string $para_names 参数名称，逗号分隔
 * @return array 参数数组
 */
function get_sys_para($para_names)
{
    // 简化版，返回空数组
    // 原系统会从 SYS_PARA 表查询
    return array();
}
?>



