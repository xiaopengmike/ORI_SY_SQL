<?php
/**
 * 数据库连接类
 * 实现 TD 类和 exequery 函数
 */
require_once("config.php");

class TD
{
    private static $conn = null;
    
    /**
     * 获取数据库连接
     * @return resource MySQL连接资源
     */
    public static function conn()
    {
        global $MYSQL_SERVER, $MYSQL_USER, $MYSQL_PASS, $MYSQL_DB;
        
        if (self::$conn === null) {
            // 解析服务器地址和端口
            $serverParts = explode(':', $MYSQL_SERVER);
            $host = $serverParts[0];
            $port = isset($serverParts[1]) ? $serverParts[1] : 3306;
            
            // 建立数据库连接
            self::$conn = @mysql_connect($host . ':' . $port, $MYSQL_USER, $MYSQL_PASS);
            if (!self::$conn) {
                die('数据库连接失败: ' . mysql_error());
            }
            
            // 选择数据库
            if (!mysql_select_db($MYSQL_DB, self::$conn)) {
                die('选择数据库失败: ' . mysql_error());
            }
            
            // 设置字符集为 GBK（兼容原系统）
            mysql_query("SET NAMES 'gbk'", self::$conn);
            mysql_query("SET CHARACTER SET 'gbk'", self::$conn);
        }
        
        return self::$conn;
    }
    
    /**
     * 关闭数据库连接
     */
    public static function close()
    {
        if (self::$conn !== null) {
            mysql_close(self::$conn);
            self::$conn = null;
        }
    }
}

/**
 * 执行SQL查询
 * @param resource $conn 数据库连接
 * @param string $query SQL查询语句
 * @return resource 查询结果资源
 */
function exequery($conn, $query)
{
    $result = mysql_query($query, $conn);
    if (!$result) {
        // 记录错误但不中断执行（兼容原系统行为）
        error_log('SQL Error: ' . mysql_error($conn) . ' | Query: ' . $query);
    }
    return $result;
}
?>

