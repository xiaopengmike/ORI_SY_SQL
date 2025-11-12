# Customer Data模块 - Python版本

## 项目说明

这是从ori项目中独立出来的customer_relationship/customer_data模块的Python重构版本。

## 技术栈

- Flask 2.3.0 - Web框架
- PyMySQL 1.1.0 - MySQL数据库驱动
- Jinja2 3.1.2 - 模板引擎

## 数据库配置

- 主机: 192.168.4.89
- 端口: 3306
- 用户名: inhe1329
- 密码: inhe1329
- 数据库: td_oa_no_data
- 字符集: GBK

## 安装和运行

1. 安装依赖:
```bash
pip install -r requirements.txt
```

2. 配置数据库:
编辑 `config.ini` 文件，修改数据库连接信息（如需要）

3. 运行应用:
```bash
python run.py
```

4. 访问应用:
打开浏览器访问 http://localhost:5000

## 项目结构

```
crm-py/
├── app/                    # 应用主目录
│   ├── models/             # 数据模型
│   ├── services/           # 业务逻辑
│   ├── controllers/        # 控制器（路由）
│   ├── utils/              # 工具类
│   ├── templates/          # 模板文件
│   ├── static/             # 静态资源
│   └── common/             # 公共模块
├── config.ini              # 配置文件
├── requirements.txt        # Python依赖
└── run.py                  # 启动文件
```

## 功能模块

- 客户信息管理
- 联系人管理
- 公司信息管理
- 流程记录
- 客户分享

## 注意事项

- 数据库使用GBK字符集
- 保持与原PHP系统的数据库表结构一致
- 前后端不分离模式



