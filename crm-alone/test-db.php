<?php
/**
 * 数据库连接测试脚本
 */
require_once("webroot/inc/config.php");
require_once("webroot/inc/db.php");

echo "========================================\n";
echo "  数据库连接测试\n";
echo "========================================\n";
echo "服务器: $MYSQL_SERVER\n";
echo "用户名: $MYSQL_USER\n";
echo "数据库: $MYSQL_DB\n";
echo "========================================\n\n";

try {
    $conn = TD::conn();
    if ($conn) {
        echo "✓ 数据库连接成功！\n\n";
        
        // 测试查询
        $result = exequery($conn, "SELECT DATABASE() as db_name");
        if ($row = mysql_fetch_assoc($result)) {
            echo "当前数据库: " . $row['db_name'] . "\n";
        }
        
        // 检查必要的表是否存在
        $tables = array(
            'inhe_customer_data',
            'inhe_customer_contact',
            'inhe_customer_company',
            'user'
        );
        
        echo "\n检查必要的数据表:\n";
        foreach ($tables as $table) {
            $result = exequery($conn, "SHOW TABLES LIKE '$table'");
            if (mysql_num_rows($result) > 0) {
                echo "  ✓ $table 存在\n";
            } else {
                echo "  ✗ $table 不存在\n";
            }
        }
        
        TD::close();
        echo "\n✓ 测试完成！\n";
    } else {
        echo "✗ 数据库连接失败！\n";
        exit(1);
    }
} catch (Exception $e) {
    echo "✗ 错误: " . $e->getMessage() . "\n";
    exit(1);
}
?>






