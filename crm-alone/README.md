# CRM 独立部署系统

## 项目说明

这是从原 OA 系统中独立出来的客户数据管理模块（customer_data），已重构为可独立部署的 CRM 系统。

## 技术栈

- PHP 5.x（使用 mysql_* 函数）
- MySQL 数据库
- 前后端不分离架构

## 目录结构

```
crm-alone/
├── webroot/                    # Web 根目录
│   ├── index.php              # 系统入口
│   ├── login.php              # 登录页面
│   ├── inc/                   # 核心库文件
│   │   ├── config.php         # 数据库配置
│   │   ├── db.php             # 数据库连接类
│   │   ├── auth.php           # 认证系统
│   │   └── utility_*.php      # 工具函数
│   ├── general/
│   │   └── customer_data/     # 客户数据模块
│   ├── common/                # 公共资源
│   └── attachment/            # 附件目录
└── README.md
```

## 数据库配置

数据库连接信息配置在 `webroot/inc/config.php` 中：

- 服务器：192.168.4.89:3306
- 用户名：inhe1329
- 密码：inhe1329
- 数据库：td_oa_no_data

## 部署步骤

1. 将 `crm-alone` 目录复制到 Web 服务器根目录
2. 配置 Web 服务器（Apache/Nginx）指向 `webroot` 目录
3. 确保 PHP 版本为 5.x（支持 mysql_* 函数）
4. 确保数据库连接信息正确
5. 设置 `attachment` 目录的写权限
6. 访问系统：http://your-domain/index.php

## 数据库表

系统依赖以下数据库表：

- `inhe_customer_data` - 客户主表
- `inhe_customer_contact` - 联系人表
- `inhe_customer_company` - 公司表
- `inhe_customer_data_flow` - 流程表
- `inhe_customer_share` - 分享表
- `user` - 用户表
- `department` - 部门表
- `continent_code` - 大洲代码表
- `country_code` - 国家代码表
- `inhe_flow_manage` - 流程管理表
- `inhe_flow_opinion` - 流程意见表

## 注意事项

1. 系统使用 GBK 编码，确保数据库字符集为 GBK
2. 认证系统已简化，实际部署时建议加强密码验证
3. 部分功能（如 Excel 导出）可能需要额外实现
4. 附件上传功能需要配置正确的目录权限

## 主要功能

- 客户信息管理
- 联系人管理
- 公司信息管理
- 客户跟进流程
- 客户分享功能
- 数据导入导出

## 开发说明

系统从原 OA 系统中独立出来，保持了原有的代码结构和业务逻辑，但简化了部分依赖关系，使其可以独立运行。






