# 提交代码到 GitHub 的步骤

## 当前修改的文件

根据本次实现，以下文件已被修改：

1. `crm-py/app/controllers/customer_controller.py` - 添加了语言支持
2. `crm-py/app/services/query_service.py` - 完善了权限控制逻辑
3. `crm-py/app/templates/customer/list.html` - 修复了表头显示，确保所有11个字段都显示

## 提交步骤

### 1. 检查 Git 是否已安装

在 PowerShell 或命令提示符中执行：
```powershell
git --version
```

如果提示找不到命令，请先安装 Git：
- 下载地址：https://git-scm.com/download/win
- 安装后重启命令行工具

### 2. 初始化 Git 仓库（如果还没有）

如果当前目录还没有 git 仓库，执行：
```powershell
git init
```

### 3. 检查当前状态

```powershell
git status
```

### 4. 添加修改的文件

```powershell
# 添加所有修改的文件
git add .

# 或者只添加本次修改的文件
git add crm-py/app/controllers/customer_controller.py
git add crm-py/app/services/query_service.py
git add crm-py/app/templates/customer/list.html
```

### 5. 提交更改

```powershell
git commit -m "实现客户信息列表功能：完善表头显示、权限控制和多语言支持

- 修复表头显示，确保所有11个字段都显示（选择、序号、登记单号、客户名称、大洲、国家、制单人、制单时间、跟进距今天数、办理状态、操作）
- 完善查询服务的权限控制逻辑（管理员权限、部门权限、用户权限、共享权限）
- 添加多语言支持（大洲、国家名称根据LANG显示中文或英文）
- 修复制单人显示（部门名 + 用户名）
- 完善操作按钮逻辑（修改、删除、审批、变更、跟进）"
```

### 6. 添加远程仓库（如果还没有）

如果还没有添加远程仓库，执行：
```powershell
# 使用 HTTPS（推荐新手）
git remote add origin https://github.com/您的用户名/仓库名.git

# 或使用 SSH
git remote add origin git@github.com:您的用户名/仓库名.git
```

### 7. 推送到 GitHub

```powershell
# 如果这是第一次推送
git branch -M main
git push -u origin main

# 如果已经推送过
git push
```

## 注意事项

1. **认证问题**：如果使用 HTTPS，推送时会要求输入用户名和密码
   - 用户名：您的 GitHub 用户名
   - 密码：使用 Personal Access Token（不是账户密码）
   - 如何创建 Token：GitHub Settings → Developer settings → Personal access tokens

2. **如果遇到冲突**：先拉取远程代码
   ```powershell
   git pull origin main
   ```
   解决冲突后再推送

3. **查看提交历史**：
   ```powershell
   git log --oneline
   ```

## 本次实现的功能

✅ 客户信息列表页面 (`http://localhost:5002/customer/list?tab=list`)
- 完整的11个表头字段显示
- 数据查询和分页功能
- 权限控制（管理员、部门、用户、共享权限）
- 多语言支持（大洲、国家名称）
- 操作按钮（详情、修改、删除、审批、变更、跟进）



