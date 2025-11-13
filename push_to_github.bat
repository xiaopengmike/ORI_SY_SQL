@echo off
chcp 65001 >nul
echo ========================================
echo   CRM系统侧边栏 - 推送到GitHub
echo ========================================
echo.

echo 步骤1: 检查Git状态...
git status
echo.

echo 步骤2: 添加所有文件...
git add crm-py/app/static/css/sidebar.css
git add crm-py/app/static/js/sidebar.js
git add crm-py/app/templates/common/base.html
git add crm-py/app/templates/customer/list.html
git add crm-py/app/templates/customer/index.html
git add crm-py/app/templates/customer/add_with_sidebar.html
git add crm-py/app/templates/demo_sidebar.html
git add crm-py/app/controllers/customer_controller.py
git add crm-py/SIDEBAR_IMPLEMENTATION.md
git add crm-py/SIDEBAR_QUICK_START.md
git add crm-py/SIDEBAR_CHANGES_SUMMARY.md
git add crm-py/README_SIDEBAR.md
git add push_to_github.bat
echo.

echo 步骤3: 提交更改...
git commit -m "feat: 添加完整的左侧二级导航栏系统" -m "- 新增侧边栏CSS和JavaScript" -m "- 创建base.html基础模板，包含完整导航菜单" -m "- 更新客户列表和首页使用新模板" -m "- 添加演示页面和完整文档" -m "- 支持9个一级菜单，60+个二级菜单" -m "- 支持侧边栏展开/收起功能" -m "- 支持状态持久化和响应式设计"
echo.

echo 步骤4: 推送到GitHub...
echo 请确认您的分支名称（main 或 master）
echo.
set /p branch="请输入分支名称 (默认: main): "
if "%branch%"=="" set branch=main

git push origin %branch%
echo.

echo ========================================
echo   推送完成！
echo ========================================
echo.
echo 如果遇到问题，请检查：
echo 1. Git是否已安装并配置
echo 2. 是否已登录GitHub账户
echo 3. 远程仓库地址是否正确
echo 4. 是否有推送权限
echo.
pause



