# 立项申请功能实现说明

## 功能概述

已完成从 PHP MYOA 到 crm-py 的"立项申请"功能迁移，包含3个标签页：

1. **立项申请表** - 新建立项申请
2. **立项申请变更** - 修改已有立项申请
3. **星级申请变更** - 修改项目星级评定

## 访问地址

- **列表页**: http://localhost:5002/project_review/list
- **新增页**: http://localhost:5002/project_review/add
- **详情页**: http://localhost:5002/project_review/detail?id=<form_id>
- **修改页**: http://localhost:5002/project_review/modify?id=<form_id>
- **变更页**: http://localhost:5002/project_review/change?id=<form_id>&changeStar=1

## 标签页说明

### 1. 立项申请表（默认标签）
- **URL**: `/project_review/list` 或 `/project_review/list?tab=list`
- **数据类型**: `type = 'project_review'`
- **功能**:
  - 查询、新增立项申请
  - 支持星级变更（选择已通过的记录进行星级变更）
- **操作按钮**: 查询、新增、星级变更

### 2. 立项申请变更
- **URL**: `/project_review/list?tab=change`
- **数据类型**: `type = 'project_review_change'` 且 `change_star != '1'`
- **功能**:
  - 查看立项申请变更记录
  - 查询变更历史
- **操作按钮**: 查询

### 3. 星级申请变更
- **URL**: `/project_review/list?tab=star`
- **数据类型**: `type = 'project_review_change'` 且 `change_star = '1'`
- **功能**:
  - 查看星级变更记录
  - 查询星级变更历史
- **操作按钮**: 查询

## 数据库表结构

### 主表（inhe_project_main）
- form_id - 表单ID
- related_id - 关联原始表单ID（变更时使用）
- project_code - 项目代号
- type - 类型（project_review 或 project_review_change）
- change_star - 星级变更标记（1=星级变更）
- status - 状态（T=待提交, P=审核中, Y=已通过, N=已退回）
- 其他字段...

### 客户信息表（inhe_customer_info）
- 支持3个客户类型：
  - type='1': 代理商/经销商
  - type='2': 招标方/业务方
  - type='3': 付款方

### 项目信息表（inhe_project_info）
- 项目基本信息（代号、范围、类型等）

### 项目产品明细表（inhe_project_detail）
- 产品列表（支持多种类型：电表、软件、电网等）

### 投标策略表（inhe_bidding_strategy）
- 招标项目专用（仅当 is_bidding_project='01' 时使用）

## 已实现功能

### 后端功能
- ✅ 数据模型层（5个模型文件）
- ✅ 服务层（业务逻辑处理）
- ✅ 控制器层（路由处理）
- ✅ 标签页切换（3个标签）
- ✅ 数据过滤（根据type和change_star）
- ✅ 列表查询（支持分页、筛选、排序）
- ✅ 新增立项申请
- ✅ 修改立项申请
- ✅ 删除立项申请
- ✅ 变更功能（普通变更和星级变更）
- ✅ 项目代号验证

### 前端功能
- ✅ 3个标签页导航
- ✅ 客户选择联动（自动填充客户信息）
- ✅ 项目代号验证（检查唯一性）
- ✅ 条件显示投标策略（根据是否招标项目）
- ✅ 星级变更操作（选择记录后点击星级变更按钮）
- ✅ 表单验证（必填项、格式验证）

### 左侧菜单
- ✅ 已添加到"客户关系"菜单下
- ✅ 菜单项名称：立项申请
- ✅ 菜单链接：/project_review/list

## 文件清单

### 模型层（app/models/）
- project_review.py - 立项申请主表模型
- project_customer_info.py - 项目客户信息模型
- project_info.py - 项目信息模型
- project_detail.py - 项目产品明细模型
- bidding_strategy.py - 投标策略模型

### 服务层（app/services/）
- project_review_service.py - 立项申请业务逻辑服务

### 控制器层（app/controllers/）
- project_review_controller.py - 立项申请路由控制器

### 模板层（app/templates/project_review/）
- list.html - 列表页面（包含3个标签页）
- add.html - 新增页面
- detail.html - 详情页面
- modify.html - 修改页面
- change.html - 变更页面
- form_modules/customer_info.html - 客户信息表单模块
- form_modules/bidding_strategy.html - 投标策略表单模块

### 共用组件（app/common/）
- data_model.py - 新增 get_customer_list, get_temp_code, verify_project_manager 方法

## 使用说明

### 新增立项申请
1. 点击左侧菜单"客户关系" -> "立项申请"
2. 点击"新增"按钮
3. 填写表单（客户信息、项目情况、投标策略）
4. 点击"提交"或"保存"

### 星级变更
1. 在"立项申请表"标签页
2. 选择一条已通过的记录（状态为Y/R/E）
3. 点击"星级变更"按钮
4. 选择新的星级并填写变更原因
5. 点击"提交"或"保存"

### 查看变更记录
1. 切换到"立项申请变更"或"星级申请变更"标签页
2. 查询和查看变更历史记录

## 与 PHP 原版的差异

1. **简化版本**: 当前版本未包含审批流程功能（待后续集成）
2. **公司分支**: 未实现 INHENERGY 和 WITLINK 的特殊表单（可后续扩展）
3. **附件功能**: 附件上传功能已预留接口，待后续实现
4. **产品明细**: 简化版产品明细表格（未实现动态添加行功能）

## 后续优化建议

1. 集成审批流程（多步骤审批、审批意见、退回等）
2. 实现附件上传和管理
3. 实现动态产品明细表格（添加/删除行）
4. 添加 INHENERGY 和 WITLINK 的特殊表单
5. 实现项目代号弹窗选择
6. 添加数据导出功能
7. 完善表单验证和错误提示
8. 添加操作日志记录

## 测试建议

1. 测试新增立项申请功能
2. 测试标签页切换
3. 测试客户选择联动
4. 测试项目代号验证
5. 测试星级变更功能
6. 测试列表查询和过滤
7. 测试修改和删除功能

