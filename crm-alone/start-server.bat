@echo off
echo ========================================
echo   CRM 独立服务启动脚本
echo ========================================
echo.
echo 正在启动 PHP 内置服务器...
echo 端口: 8008
echo 文档根目录: webroot
echo.
echo 访问地址:
echo   - 主页: http://localhost:8008/
echo   - 登录页: http://localhost:8008/login.php
echo   - 客户列表: http://localhost:8008/general/customer_data/list.php
echo.
echo 按 Ctrl+C 停止服务器
echo ========================================
echo.

cd /d %~dp0
php -S localhost:8008 -t webroot

pause

