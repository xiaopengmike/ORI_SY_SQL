# ORI_SY_SQL 项目

## 项目概述

ORI_SY_SQL 是一个客户关系管理系统（CRM）项目集合，包含多个版本的实现和原始系统代码。

## 项目结构

本项目包含三个主要子项目：

### 1. crm-alone
PHP版本的独立CRM系统，从原OA系统中独立出来的客户数据管理模块。

- **技术栈**: PHP 5.x, MySQL
- **特点**: 前后端不分离架构，可独立部署
- **详细说明**: 参见 [crm-alone/README.md](crm-alone/README.md)

### 2. crm-py
Python版本的CRM系统，使用Flask框架重构的客户数据管理模块。

- **技术栈**: Flask 2.3.0, PyMySQL, Jinja2
- **特点**: 现代化Python Web应用，前后端不分离
- **详细说明**: 参见 [crm-py/README.md](crm-py/README.md)

### 3. ori
原始MYOA系统代码，包含完整的OA系统实现。

- **技术栈**: PHP, MySQL
- **特点**: 完整的OA系统，包含CRM模块的原始实现
- **数据库**: 包含数据库结构文件 `inhe_crm.sql`

## 快速开始

### crm-alone (PHP版本)

1. 将 `crm-alone` 目录部署到Web服务器
2. 配置 `webroot/inc/config.php` 中的数据库连接信息
3. 确保PHP版本为5.x（支持mysql_*函数）
4. 访问系统

详细步骤请参考 [crm-alone/README.md](crm-alone/README.md)

### crm-py (Python版本)

1. 安装依赖：
   ```bash
   cd crm-py
   pip install -r requirements.txt
   ```

2. 配置数据库：
   编辑 `config.ini` 文件，修改数据库连接信息

3. 运行应用：
   ```bash
   python run.py
   ```

4. 访问应用：
   打开浏览器访问 http://localhost:5000

详细步骤请参考 [crm-py/README.md](crm-py/README.md)

## 数据库

项目使用MySQL数据库，字符集为GBK。

主要数据表包括：
- `inhe_customer_data` - 客户主表
- `inhe_customer_contact` - 联系人表
- `inhe_customer_company` - 公司表
- `inhe_customer_data_flow` - 流程表
- `inhe_customer_share` - 分享表

完整的数据库结构请参考 `ori/inhe_crm.sql`

## 技术栈

- **后端**: PHP 5.x, Python 3.x (Flask)
- **数据库**: MySQL 5.7+
- **前端**: HTML, CSS, JavaScript, jQuery, Layui
- **字符编码**: GBK

## 主要功能

- 客户信息管理
- 联系人管理
- 公司信息管理
- 客户跟进流程
- 客户分享功能
- 数据导入导出

## 注意事项

1. 数据库使用GBK字符集，确保数据库和代码文件编码一致
2. 配置文件中的敏感信息（如数据库密码）建议使用环境变量
3. 附件上传功能需要配置正确的目录权限
4. PHP版本需要支持mysql_*函数（PHP 5.x）

## 开发说明

- `crm-alone` 和 `crm-py` 是从原始OA系统中独立出来的CRM模块
- 保持了原有的业务逻辑和数据库结构
- 简化了部分依赖关系，使其可以独立运行

## 许可证

请根据实际情况添加许可证信息。

## 贡献

欢迎提交Issue和Pull Request。



