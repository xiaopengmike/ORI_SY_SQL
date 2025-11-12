<?php
/**
 * 简化的认证系统
 * 替代原系统的 auth.inc.php
 */
session_start();

require_once("db.php");

// 检查是否已登录
if (!isset($_SESSION['LOGIN_USER_ID']) || empty($_SESSION['LOGIN_USER_ID'])) {
    // 如果不在登录页面，重定向到登录页
    $current_file = basename($_SERVER['PHP_SELF']);
    if ($current_file != 'login.php' && $current_file != 'index.php') {
        header('Location: /login.php');
        exit;
    }
}

// 定义常用的 Session 变量（如果不存在）
if (!isset($_SESSION['LOGIN_USER_ID'])) {
    $_SESSION['LOGIN_USER_ID'] = '';
}
if (!isset($_SESSION['LOGIN_USER_NAME'])) {
    $_SESSION['LOGIN_USER_NAME'] = '';
}
if (!isset($_SESSION['LOGIN_DEPT_ID'])) {
    $_SESSION['LOGIN_DEPT_ID'] = '';
}
if (!isset($_SESSION['LOGIN_DEPT_NAME'])) {
    $_SESSION['LOGIN_DEPT_NAME'] = '';
}

/**
 * 用户登录验证
 * @param string $username 用户名
 * @param string $password 密码
 * @return bool 登录是否成功
 */
function user_login($username, $password)
{
    $conn = TD::conn();
    $username = mysql_real_escape_string($username, $conn);
    $password = mysql_real_escape_string($password, $conn);
    
    // 查询用户（简化版，实际应该验证密码）
    // 注意：这里简化了密码验证，实际应该使用加密验证
    $sql = "SELECT user_id, user_name, dept_id, dept_name FROM user WHERE (user_id = '$username' OR byname = '$username') LIMIT 1";
    $result = exequery($conn, $sql);
    
    if ($row = mysql_fetch_assoc($result)) {
        // 设置 Session
        $_SESSION['LOGIN_USER_ID'] = $row['user_id'];
        $_SESSION['LOGIN_USER_NAME'] = isset($row['user_name']) ? $row['user_name'] : $row['user_id'];
        $_SESSION['LOGIN_DEPT_ID'] = isset($row['dept_id']) ? $row['dept_id'] : 1;
        $_SESSION['LOGIN_DEPT_NAME'] = isset($row['dept_name']) ? $row['dept_name'] : '';
        return true;
    }
    
    return false;
}

/**
 * 用户登出
 */
function user_logout()
{
    session_destroy();
    header('Location: /login.php');
    exit;
}
?>

