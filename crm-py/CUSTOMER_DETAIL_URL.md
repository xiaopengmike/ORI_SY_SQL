# 客户详情页面访问URL

## 可访问的客户详情URL

根据数据库查询结果，以下是一个可用的客户详情页面URL：

### 方式1：使用主键id查询（推荐）
```
http://localhost:5002/customer/detail?id=1
```
或路径参数方式：
```
http://localhost:5002/customer/detail/1
```

### 方式2：使用form_id查询（向后兼容）
```
http://localhost:5002/customer/detail?id=khxx202511070001
```
或路径参数方式：
```
http://localhost:5002/customer/detail/khxx202511070001
```

## 访问前提

1. **应用必须已启动**
   - 运行 `python run.py` 启动应用
   - 应用默认运行在 `http://localhost:5002`

2. **需要登录**
   - 客户详情页面需要登录才能访问（@login_required装饰器）
   - 如果未登录，会自动重定向到登录页面
   - 登录页面：`http://localhost:5002/auth/login`

## URL格式说明

### 使用主键id查询（推荐）

**查询参数方式：**
```
http://localhost:5002/customer/detail?id=<id>
```
- `id`: 客户主键ID（整数），从数据库表 `inhe_customer_data` 的 `id` 字段获取

**路径参数方式：**
```
http://localhost:5002/customer/detail/<id>
```
- `<id>`: 客户主键ID（整数），直接放在URL路径中

### 使用form_id查询（向后兼容）

**查询参数方式：**
```
http://localhost:5002/customer/detail?id=<form_id>
```
或
```
http://localhost:5002/customer/detail?form_id=<form_id>
```
- `form_id`: 客户表单ID（字符串），从数据库表 `inhe_customer_data` 的 `form_id` 字段获取

**路径参数方式：**
```
http://localhost:5002/customer/detail/<form_id>
```
- `<form_id>`: 客户表单ID（字符串），直接放在URL路径中

## 查询逻辑说明

系统会智能识别参数类型：
- 如果 `id` 参数是数字（整数），则使用主键id查询
- 如果 `id` 参数是字符串，则当作form_id查询（向后兼容）
- 路径参数方式会自动判断：能转换为整数则使用主键id，否则使用form_id

## 获取客户ID

如果需要获取客户的id或form_id，可以查询数据库：
```sql
SELECT id, form_id, customer_name FROM inhe_customer_data LIMIT 10;
```

## 注意事项

- **优先使用主键id查询**：主键id查询更高效，推荐使用
- 如果id或form_id不存在，页面会重定向到客户列表页
- 确保数据库连接正常
- 确保有相应的权限访问该客户数据
- 系统保持向后兼容，仍支持form_id查询

