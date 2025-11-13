# Customer Data模块Python重构 - 实施说明

## 已完成的工作

### 1. 项目基础搭建 ✅
- ✅ 创建完整的项目目录结构
- ✅ 配置Flask应用（app/__init__.py）
- ✅ 配置数据库连接（app/config.py, app/utils/db.py）
- ✅ 创建基础工具类（auth, response, common）

### 2. 数据模型实现 ✅
- ✅ 实现数据库连接工具（Database类，兼容原TD类）
- ✅ 实现客户主表模型（Customer）
- ✅ 实现联系人模型（Contact）
- ✅ 实现公司信息模型（Company）
- ✅ 实现流程记录模型（Flow）
- ✅ 实现分享模型（Share）

### 3. 业务逻辑实现 ✅
- ✅ 实现FormModel（表单处理，对应原FormModel.php）
- ✅ 实现ProcessModel（流程处理，对应原ProcessModel.php）
- ✅ 实现DataModel（数据查询，对应原DataModel.php）
- ✅ 实现CustomerService（客户业务逻辑，对应原ActionModel.php）

### 4. 控制器和路由 ✅
- ✅ 实现客户控制器（customer_controller.py）
  - 列表页（index）
  - 列表查询页（list）
  - 新增页（add）
  - 详情页（detail）
  - 修改页（modify）
  - 变更页（change）
- ✅ 实现操作控制器（action_controller.py）
  - 统一操作接口（action）
  - 查询接口（query）

### 5. 前端模板 ✅
- ✅ 创建Jinja2模板文件结构
- ✅ 创建基础模板文件（需要根据原PHP完整转换）

### 6. 查询服务 ✅
- ✅ 实现QueryService（列表查询服务）

## 代码特点

### 1. 详细注释
所有类和方法都添加了详细的中文注释，包括：
- 功能说明
- 参数说明
- 返回值说明
- 对应原PHP代码位置

### 2. 保持业务逻辑一致
- 保持与原PHP系统相同的业务逻辑
- 保持数据库表结构不变
- 保持数据验证规则不变

### 3. 代码结构清晰
- 采用MVC架构
- 分层明确：models -> services -> controllers
- 公共模块独立（common目录）

## 需要注意的事项

### 1. 数据库连接
- 数据库使用GBK字符集
- 连接信息在config.ini中配置
- 需要确保数据库可访问

### 2. 认证系统
- 当前使用Flask session进行认证
- 需要实现登录功能（原系统可能有独立的登录系统）
- session中需要设置以下键：
  - LOGIN_USER_ID
  - LOGIN_USER_NAME
  - LOGIN_DEPT_ID
  - LOGIN_DEPT_NAME
  - LOGIN_USER_BU

### 3. 前端模板
- 当前模板文件是基础框架
- 需要根据原PHP模板完整转换：
  - 保留原有HTML结构
  - 保留原有CSS样式
  - 保留原有JavaScript逻辑
  - 将PHP语法转换为Jinja2语法

### 4. 静态资源
- 需要复制原系统的静态资源到app/static目录
- 包括CSS、JS、图片等文件

### 5. 权限控制
- 当前权限控制是简化版本
- 需要根据原系统的权限逻辑完善
- 特别是query_service.py中的权限判断

### 6. 工作流处理
- ProcessModel是简化版本
- 需要根据实际工作流需求完善
- 特别是审批流程的处理

### 7. 字符编码
- 原系统使用GBK编码
- Python默认使用UTF-8
- 数据库连接已配置为GBK
- 需要注意字符串编码转换

## 待完善的功能

### 1. 前端模板完整转换
- [ ] 完整转换index.html
- [ ] 完整转换list.html
- [ ] 完整转换add.html
- [ ] 完整转换detail.html
- [ ] 完整转换modify.html
- [ ] 完整转换change.html

### 2. 静态资源
- [ ] 复制CSS文件
- [ ] 复制JS文件
- [ ] 复制图片文件
- [ ] 配置静态资源路径

### 3. 登录功能
- [ ] 实现登录页面
- [ ] 实现登录验证
- [ ] 实现session管理

### 4. 权限系统
- [ ] 完善权限判断逻辑
- [ ] 实现管理员权限
- [ ] 实现部门权限
- [ ] 实现用户权限

### 5. 工作流
- [ ] 完善审批流程
- [ ] 实现审批通知
- [ ] 实现流程回退

### 6. 其他功能
- [ ] 文件上传功能
- [ ] 附件管理功能
- [ ] 数据导出功能
- [ ] 多语言支持（中英文切换）

## 运行说明

### 1. 安装依赖
```bash
pip install -r requirements.txt
```

### 2. 配置数据库
编辑 `config.ini` 文件，确认数据库连接信息

### 3. 运行应用
```bash
python run.py
```

### 4. 访问应用
打开浏览器访问 http://localhost:5000

## 文件结构说明

```
crm-py/
├── app/                    # 应用主目录
│   ├── __init__.py        # Flask应用初始化
│   ├── config.py          # 配置文件
│   ├── models/            # 数据模型
│   │   ├── customer.py    # 客户模型
│   │   ├── contact.py     # 联系人模型
│   │   ├── company.py     # 公司模型
│   │   ├── flow.py        # 流程模型
│   │   └── share.py       # 分享模型
│   ├── services/          # 业务逻辑层
│   │   ├── customer_service.py  # 客户业务逻辑
│   │   ├── form_service.py      # 表单服务
│   │   └── query_service.py     # 查询服务
│   ├── controllers/        # 控制器
│   │   ├── customer_controller.py  # 客户控制器
│   │   └── action_controller.py    # 操作控制器
│   ├── utils/             # 工具类
│   │   ├── db.py          # 数据库工具
│   │   ├── auth.py        # 认证工具
│   │   ├── response.py    # 响应工具
│   │   └── common.py      # 通用工具
│   ├── common/            # 公共模块
│   │   ├── form_model.py  # 表单模型
│   │   ├── process_model.py  # 流程模型
│   │   └── data_model.py  # 数据模型
│   ├── templates/         # 模板文件
│   │   └── customer/      # 客户相关模板
│   └── static/            # 静态资源
├── config.ini             # 配置文件
├── requirements.txt       # Python依赖
├── run.py                # 启动文件
└── README.md             # 项目说明
```

## 与原PHP系统的对应关系

| Python文件 | 原PHP文件 | 说明 |
|-----------|----------|------|
| app/models/customer.py | ActionModel.php (部分) | 客户数据操作 |
| app/services/customer_service.py | ActionModel.php | 客户业务逻辑 |
| app/common/form_model.py | FormModel.php | 表单处理 |
| app/common/process_model.py | ProcessModel.php | 流程处理 |
| app/common/data_model.py | DataModel.php | 数据查询 |
| app/controllers/customer_controller.py | index.php, list.php, add.php等 | 页面控制器 |
| app/controllers/action_controller.py | action.php | AJAX接口 |
| app/utils/db.py | db.php, TD类 | 数据库连接 |
| app/utils/auth.py | auth.inc.php | 认证工具 |

## 总结

本次重构已完成核心功能的Python实现，包括：
- ✅ 完整的项目结构
- ✅ 数据模型层
- ✅ 业务逻辑层
- ✅ 控制器层
- ✅ 基础工具类
- ✅ 详细的代码注释

下一步需要：
1. 完整转换前端模板
2. 复制静态资源
3. 实现登录功能
4. 完善权限系统
5. 测试和调试





