# CRM系统左侧导航栏实现说明

## 概述

本文档说明如何为 crm-py 项目的每个页面添加左侧二级导航栏。导航栏具有以下特性：

- ✅ 一级菜单点击可展开/收起
- ✅ 整个侧边栏可在左侧收起
- ✅ 菜单状态自动保存（localStorage）
- ✅ 当前页面自动高亮
- ✅ 响应式设计，支持移动端
- ✅ 平滑动画效果

## 文件结构

```
crm-py/
├── app/
│   ├── static/
│   │   ├── css/
│   │   │   └── sidebar.css          # 侧边栏样式
│   │   └── js/
│   │       └── sidebar.js           # 侧边栏功能
│   └── templates/
│       ├── common/
│       │   └── base.html            # 基础模板（包含侧边栏）
│       └── customer/
│           ├── list.html            # 已更新：使用基础模板
│           ├── index.html           # 已更新：使用基础模板
│           └── add_with_sidebar.html # 示例：完整表单页面
└── SIDEBAR_IMPLEMENTATION.md        # 本文档
```

## 菜单结构

导航栏包含以下菜单项：

### 1. 客户关系
- 待办工作
- CRM流程图
- 客户登记
- 客户共享记录
- 客户转移记录
- 项目代号取号
- 立项申请
- 销售合同评审
- 生产通知单
- 发货通知单
- 销售收款登记
- 项目参与人登记
- 项目编号参与人登记

### 2. 银河电力取号
- 销售合同取号
- 正式信函取号
- 报价单取号
- 形式发票取号
- 备忘录取号
- 非电表产品样机申请单取号
- 单相表样机申请单取号
- 三相表样机申请单取号
- 协议性质文件取号

### 3. 银河电网取号
- 销售合同取号
- 正式信函取号
- 报价单取号
- 形式发票取号
- 备忘录取号
- 协议性质文件取号
- 一次侧产品样机申请单取号
- 二次侧产品样机申请单取号

### 4. 耐吉取号
- 销售合同取号
- 正式信函取号
- 报价单取号
- 形式发票取号
- 备忘录取号
- 协议性质文件取号

### 5. 慧联取号
- 销售合同取号
- 正式函取号
- 报价单取号
- 形式发票取号
- 备忘录取号

### 6. 江西分公司取号
- 销售合同取号
- 正式函取号
- 报价单取号
- 形式发票取号
- 备忘录取号
- 非电表产品样机申请单取号
- 单相表样机申请单取号
- 三相表样机申请单取号
- 协议性质文件取号

### 7. 江西银河表计取号
- 销售合同取号
- 正式函取号
- 报价单取号
- 形式发票取号
- 备忘录取号
- 非电表产品样机申请单取号
- 单相表样机申请单取号
- 三相表样机申请单取号
- 协议性质文件取号

### 8. 项目组成员
- 项目组名单

### 9. 项目进度
- 进度登记

## 如何使用

### 方法1：新页面直接使用基础模板

对于新创建的页面，直接继承 `base.html` 模板：

```html
{% extends "common/base.html" %}

{% block title %}页面标题 - CRM系统{% endblock %}

{% block page_title %}页面标题{% endblock %}

{% block extra_css %}
<style>
    /* 页面特定样式 */
</style>
{% endblock %}

{% block content %}
    <!-- 页面内容 -->
{% endblock %}

{% block extra_js %}
<script>
    // 页面特定JavaScript
</script>
{% endblock %}
```

### 方法2：将现有页面迁移到新模板

对于现有的 HTML 页面，按以下步骤修改：

#### 步骤1：替换文件头部

**原代码：**
```html
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>页面标题</title>
    <link rel="stylesheet" href="...">
    <style>
        /* 样式 */
    </style>
</head>
<body>
```

**新代码：**
```html
{% extends "common/base.html" %}

{% block title %}页面标题 - CRM系统{% endblock %}

{% block page_title %}页面标题{% endblock %}

{% block extra_css %}
<style>
    /* 样式 */
</style>
{% endblock %}

{% block content %}
```

#### 步骤2：替换文件尾部

**原代码：**
```html
    <script src="..."></script>
    <script>
        // JavaScript代码
    </script>
</body>
</html>
```

**新代码：**
```html
{% endblock %}

{% block extra_js %}
<script>
    // JavaScript代码
</script>
{% endblock %}
```

#### 步骤3：注意事项

1. **不要重复引入 jQuery 和 Layui**
   - 基础模板已经引入，不需要再次引入

2. **保留所有页面特定的CSS和JavaScript**
   - 将原有的 `<style>` 内容放入 `{% block extra_css %}`
   - 将原有的 `<script>` 内容放入 `{% block extra_js %}`

3. **保持原有功能不变**
   - 所有表单提交、数据查询等功能保持不变
   - 只是改变了页面布局方式

## 已更新的文件

以下文件已经更新为使用新的基础模板：

1. `templates/customer/list.html` - 客户信息列表页面
2. `templates/customer/index.html` - 客户信息首页
3. `templates/customer/add_with_sidebar.html` - 新增客户页面（示例）

## 待更新的文件

以下文件建议更新为使用新模板（保留原文件作为备份）：

- `templates/customer/add.html` → 参考 `add_with_sidebar.html`
- `templates/customer/detail.html`
- `templates/customer/modify.html`
- `templates/customer/change.html`

## 自定义配置

### 修改侧边栏颜色

编辑 `static/css/sidebar.css`：

```css
/* 修改侧边栏背景色 */
.sidebar {
    background: #2c3e50;  /* 改为你想要的颜色 */
}

/* 修改激活项颜色 */
.menu-link.active {
    background: #3498db;  /* 改为你想要的颜色 */
}
```

### 添加新菜单项

编辑 `templates/common/base.html`，在 `<nav class="sidebar-menu">` 中添加：

```html
<li class="menu-item">
    <a href="#" class="menu-link" data-submenu="submenu-new" data-title="新菜单">
        <i class="menu-icon fas fa-icon-name"></i>
        <span class="menu-text">新菜单</span>
        <i class="menu-arrow fas fa-chevron-right"></i>
    </a>
    <ul class="submenu" id="submenu-new">
        <li class="submenu-item">
            <a href="/path/to/page" class="submenu-link">子菜单项</a>
        </li>
    </ul>
</li>
```

### 修改侧边栏宽度

编辑 `static/css/sidebar.css`：

```css
.sidebar {
    width: 250px;  /* 改为你想要的宽度 */
}

.main-content {
    margin-left: 250px;  /* 与侧边栏宽度保持一致 */
}
```

## 功能说明

### 侧边栏收起/展开

点击侧边栏顶部的 ☰ 按钮可以收起或展开侧边栏。收起状态下：
- 宽度变为 60px
- 只显示图标
- 鼠标悬停时显示提示文字

### 子菜单展开/收起

点击带有子菜单的一级菜单项，可以展开或收起对应的二级菜单。

### 状态持久化

- 侧边栏的展开/收起状态会保存在 localStorage
- 子菜单的展开状态会保存在 localStorage
- 页面刷新后会自动恢复之前的状态

### 当前页面高亮

根据当前 URL 路径，自动高亮对应的菜单项，并自动展开其父级菜单。

### 移动端适配

在移动设备上（屏幕宽度 < 768px）：
- 侧边栏默认隐藏
- 点击顶部 ☰ 按钮显示侧边栏
- 显示遮罩层，点击遮罩关闭侧边栏

## 浏览器兼容性

- ✅ Chrome 60+
- ✅ Firefox 55+
- ✅ Safari 11+
- ✅ Edge 79+
- ⚠️ IE 11（部分CSS3特性可能不支持）

## 常见问题

### Q: 侧边栏没有显示？

A: 检查以下几点：
1. 确认页面继承了 `base.html`
2. 检查浏览器控制台是否有JavaScript错误
3. 确认 `static` 文件路径配置正确

### Q: 菜单点击没有反应？

A: 确认：
1. `sidebar.js` 已正确加载
2. jQuery 和 Layui 已正确加载
3. 浏览器控制台没有错误

### Q: 如何禁用某个菜单项？

A: 在对应的 `<li>` 标签上添加类 `disabled`，并在CSS中添加样式：

```css
.menu-item.disabled .menu-link {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}
```

### Q: 如何让某个页面不显示侧边栏？

A: 不继承 `base.html`，创建一个新的模板文件，或者继承 `header.html`。

## 技术支持

如有问题，请查看：
- `BUG_REPORT.md` - 已知问题
- `IMPLEMENTATION_NOTES.md` - 实现说明
- 或联系开发团队

## 更新日志

### 2024-11-12
- ✅ 创建基础模板 `base.html`
- ✅ 实现侧边栏样式 `sidebar.css`
- ✅ 实现侧边栏功能 `sidebar.js`
- ✅ 更新客户列表页面 `list.html`
- ✅ 更新客户首页 `index.html`
- ✅ 创建示例页面 `add_with_sidebar.html`
- ✅ 编写使用文档

## 示例截图说明

### 展开状态
- 侧边栏宽度：250px
- 显示完整菜单文字
- 支持多级菜单展开

### 收起状态
- 侧边栏宽度：60px
- 仅显示图标
- 鼠标悬停显示提示

### 移动端
- 侧边栏默认隐藏
- 点击按钮从左侧滑出
- 显示半透明遮罩层

