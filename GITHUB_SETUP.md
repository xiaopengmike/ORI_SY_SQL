# GitHub 仓库设置指南

本指南将帮助您将 ORI_SY_SQL 项目提交到 GitHub。

## 前置要求

### 1. 安装 Git

如果您的系统上还没有安装 Git，请按照以下步骤安装：

#### Windows 系统

1. 访问 [Git for Windows 下载页面](https://git-scm.com/download/win)
2. 下载最新版本的 Git for Windows
3. 运行安装程序，使用默认设置即可
4. 安装完成后，重启命令行工具

验证安装：
```bash
git --version
```

#### macOS 系统

使用 Homebrew 安装：
```bash
brew install git
```

或从 [Git 官网](https://git-scm.com/download/mac) 下载安装程序。

#### Linux 系统

Ubuntu/Debian:
```bash
sudo apt-get update
sudo apt-get install git
```

CentOS/RHEL:
```bash
sudo yum install git
```

### 2. 配置 Git 用户信息

首次使用 Git 需要配置用户信息：

```bash
git config --global user.name "您的姓名"
git config --global user.email "您的邮箱"
```

### 3. GitHub 账户

确保您已经拥有 GitHub 账户。如果还没有，请访问 [GitHub](https://github.com) 注册。

### 4. 认证方式选择

GitHub 支持两种认证方式：

#### 方式一：HTTPS + Personal Access Token（推荐新手）

1. 在 GitHub 上创建 Personal Access Token：
   - 访问 GitHub Settings → Developer settings → Personal access tokens → Tokens (classic)
   - 点击 "Generate new token (classic)"
   - 选择权限：至少勾选 `repo` 权限
   - 生成并复制 token（只显示一次，请妥善保存）

2. 推送代码时，用户名使用您的 GitHub 用户名，密码使用 Personal Access Token

#### 方式二：SSH 密钥（推荐）

1. 生成 SSH 密钥：
   ```bash
   ssh-keygen -t ed25519 -C "您的邮箱"
   ```
   按回车使用默认路径，可以设置密码或直接回车跳过。

2. 复制公钥内容：
   ```bash
   # Windows
   type %USERPROFILE%\.ssh\id_ed25519.pub
   
   # macOS/Linux
   cat ~/.ssh/id_ed25519.pub
   ```

3. 在 GitHub 上添加 SSH 密钥：
   - 访问 GitHub Settings → SSH and GPG keys
   - 点击 "New SSH key"
   - 粘贴公钥内容并保存

## 创建 GitHub 仓库

### 步骤 1: 在 GitHub 上创建新仓库

1. 登录 GitHub
2. 点击右上角的 "+" 号，选择 "New repository"
3. 填写仓库信息：
   - **Repository name**: `ORI_SY_SQL`（或您喜欢的名称）
   - **Description**: 客户关系管理系统项目集合
   - **Visibility**: 选择 Public（公开）或 Private（私有）
   - **不要**勾选 "Initialize this repository with a README"（因为本地已有代码）
4. 点击 "Create repository"

### 步骤 2: 初始化本地 Git 仓库

在项目根目录执行以下命令：

```bash
# 初始化 Git 仓库
git init

# 添加所有文件到暂存区
git add .

# 创建初始提交
git commit -m "Initial commit: ORI_SY_SQL project"
```

### 步骤 3: 添加远程仓库

复制 GitHub 仓库的 URL（在仓库页面可以看到），然后执行：

**如果使用 HTTPS：**
```bash
git remote add origin https://github.com/您的用户名/ORI_SY_SQL.git
```

**如果使用 SSH：**
```bash
git remote add origin git@github.com:您的用户名/ORI_SY_SQL.git
```

验证远程仓库：
```bash
git remote -v
```

### 步骤 4: 推送代码到 GitHub

```bash
# 推送代码到主分支
git branch -M main
git push -u origin main
```

如果使用 HTTPS 且遇到认证问题，系统会提示输入用户名和密码：
- 用户名：您的 GitHub 用户名
- 密码：使用 Personal Access Token（不是账户密码）

## 后续操作

### 日常提交代码

```bash
# 查看修改状态
git status

# 添加修改的文件
git add .

# 或添加特定文件
git add 文件名

# 提交修改
git commit -m "描述本次修改的内容"

# 推送到 GitHub
git push
```

### 创建分支

```bash
# 创建并切换到新分支
git checkout -b feature/新功能名称

# 或使用新语法
git switch -c feature/新功能名称

# 推送新分支到 GitHub
git push -u origin feature/新功能名称
```

### 查看提交历史

```bash
git log
```

### 查看远程仓库信息

```bash
git remote -v
```

## 常见问题

### 1. 推送时提示认证失败

- 如果使用 HTTPS：确保使用 Personal Access Token 而不是账户密码
- 如果使用 SSH：确保 SSH 密钥已正确添加到 GitHub

### 2. 文件太大无法推送

GitHub 对单个文件大小有限制（100MB）。如果遇到大文件：
- 检查 `.gitignore` 文件是否正确排除了大文件
- 考虑使用 Git LFS（Large File Storage）

### 3. 想要忽略某些文件

编辑项目根目录的 `.gitignore` 文件，添加要忽略的文件或目录。

### 4. 撤销最后一次提交

```bash
# 保留修改，只撤销提交
git reset --soft HEAD~1

# 完全撤销提交和修改（谨慎使用）
git reset --hard HEAD~1
```

## 推荐工作流程

1. 在本地进行开发和修改
2. 使用 `git add` 添加修改的文件
3. 使用 `git commit` 提交修改（写清楚提交信息）
4. 使用 `git push` 推送到 GitHub
5. 定期从 GitHub 拉取最新代码：`git pull`

## 参考资源

- [Git 官方文档](https://git-scm.com/doc)
- [GitHub 帮助文档](https://docs.github.com)
- [Git 教程](https://www.atlassian.com/git/tutorials)



