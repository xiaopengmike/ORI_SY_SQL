# 项目评审模块路由文档

## 路由前缀

所有项目评审模块的路由都使用前缀：`/project_review`

## 页面路由（project_review_bp）

### 1. 首页和列表页

| 路由 | 方法 | 说明 | 对应PHP文件 |
|------|------|------|------------|
| `/project_review/` | GET | 首页，重定向到列表页 | index.php |
| `/project_review/list` | GET | 项目评审列表查询页 | list.php |

**访问示例：**
- `http://localhost:5002/project_review/`
- `http://localhost:5002/project_review/list`

---

### 2. 新增页面

| 路由 | 方法 | 说明 | 对应PHP文件 |
|------|------|------|------------|
| `/project_review/add` | GET | 新增项目评审（通用，根据公司类型自动重定向） | add.php |
| `/project_review/add_inhenergy` | GET | INHENERGY/HKNERGY公司新增页 | add_inhenergy.php |
| `/project_review/add_witlink` | GET | WITLINK公司新增页 | add_witlink.php |

**访问示例：**
- `http://localhost:5002/project_review/add`
- `http://localhost:5002/project_review/add_inhenergy?company=INHENERGY`
- `http://localhost:5002/project_review/add_witlink?company=WITLINK`

**参数说明：**
- `company` (可选): 公司代码，用于指定公司类型

---

### 3. 详情页面

| 路由 | 方法 | 说明 | 对应PHP文件 |
|------|------|------|------------|
| `/project_review/detail` | GET | 项目评审详情页（通用，根据数据自动重定向） | detail.php |
| `/project_review/detail_inhenergy` | GET | INHENERGY/HKNERGY公司详情页 | detail_inhenergy.php |
| `/project_review/detail_witlink` | GET | WITLINK公司详情页 | detail_witlink.php |

**访问示例：**
- `http://localhost:5002/project_review/detail?id=123&step=1&operate=detail`
- `http://localhost:5002/project_review/detail_inhenergy?id=123&step=1&operate=approve`
- `http://localhost:5002/project_review/detail_witlink?id=123&step=1&operate=detail`

**参数说明：**
- `id` (必需): 项目评审ID（form_id）
- `step` (可选): 审批步骤
- `operate` (可选): 操作类型（detail-查看, approve-审批）

---

### 4. 修改页面

| 路由 | 方法 | 说明 | 对应PHP文件 |
|------|------|------|------------|
| `/project_review/modify/<form_id>` | GET | 修改项目评审（通用，根据公司类型自动重定向） | modify.php |
| `/project_review/modify_inhenergy/<form_id>` | GET | INHENERGY/HKNERGY公司修改页 | modify_inhenergy.php |
| `/project_review/modify_witlink/<form_id>` | GET | WITLINK公司修改页 | modify_witlink.php |

**访问示例：**
- `http://localhost:5002/project_review/modify/123`
- `http://localhost:5002/project_review/modify_inhenergy/123`
- `http://localhost:5002/project_review/modify_witlink/123`

**路径参数：**
- `form_id` (必需): 项目评审表单ID

---

### 5. 变更页面

| 路由 | 方法 | 说明 | 对应PHP文件 |
|------|------|------|------------|
| `/project_review/change/<form_id>` | GET | 变更项目评审（通用，根据公司类型自动重定向） | change.php |
| `/project_review/change_inhenergy/<form_id>` | GET | INHENERGY/HKNERGY公司变更页 | change_inhenergy.php |
| `/project_review/change_witlink/<form_id>` | GET | WITLINK公司变更页 | change_witlink.php |

**访问示例：**
- `http://localhost:5002/project_review/change/123`
- `http://localhost:5002/project_review/change_inhenergy/123`
- `http://localhost:5002/project_review/change_witlink/123`

**路径参数：**
- `form_id` (必需): 项目评审表单ID

---

### 6. 项目代码取号页面

| 路由 | 方法 | 说明 | 对应PHP文件 |
|------|------|------|------------|
| `/project_review/get_project_code` | GET | 获取项目代码页面 | get_project_code.php |

**访问示例：**
- `http://localhost:5002/project_review/get_project_code?company=INHENERGY`

**参数说明：**
- `company` (可选): 公司代码

---

## API接口路由（project_review_action_bp）

### 1. 统一操作接口

| 路由 | 方法 | 说明 | 支持的Action |
|------|------|------|-------------|
| `/project_review/action` | GET/POST | 统一操作接口 | Retrieve, Add, Submit, Save, Modify, Change, ChangeSave, Delete, Approve, Back |

**访问示例：**
- `http://localhost:5002/project_review/action?action=Retrieve`
- `http://localhost:5002/project_review/action?action=Add`

**支持的Action类型：**

1. **Retrieve** - 查询
   - `dataType=getCustomerDetail` - 获取客户详情
   - `dataType=verifyProjectCode` - 验证项目代码
   - `dataType=getFormalProjectCode` - 获取正式项目代码
   - 默认：查询列表数据

2. **Add** - 添加新项目评审

3. **Submit** - 提交项目评审（进入审批流程）

4. **Save** - 保存项目评审（草稿）

5. **Modify** - 修改项目评审

6. **Change** - 变更项目评审（提交）

7. **ChangeSave** - 变更项目评审（保存草稿）

8. **Delete** - 删除项目评审

9. **Approve** - 审批通过

10. **Back** - 退回

**请求示例：**
```javascript
// 查询客户详情
$.post('/project_review/action?action=Retrieve', {
    dataType: 'getCustomerDetail',
    id: '123'
}, function(res) {
    console.log(res);
});

// 提交项目评审
$.post('/project_review/action?action=Submit', {
    form_id: '123',
    title: '项目标题',
    // ... 其他字段
}, function(res) {
    console.log(res);
});
```

---

### 2. 查询接口

| 路由 | 方法 | 说明 |
|------|------|------|
| `/project_review/query` | GET/POST | 查询接口（用于列表查询） |

**访问示例：**
- `http://localhost:5002/project_review/query`

**请求参数：**
- 支持分页、搜索、筛选等参数

---

## 路由注册信息

在 `app/__init__.py` 中注册：

```python
from app.project_review.controllers.project_review_controller import project_review_bp
from app.project_review.controllers.project_review_action_controller import project_review_action_bp

app.register_blueprint(project_review_bp, url_prefix='/project_review')
app.register_blueprint(project_review_action_bp, url_prefix='/project_review')
```

---

## 路由总结

### 页面路由（共16个）

1. `/project_review/` - 首页（重定向）
2. `/project_review/list` - 列表页
3. `/project_review/add` - 新增页（通用）
4. `/project_review/add_inhenergy` - 新增页（INHENERGY）
5. `/project_review/add_witlink` - 新增页（WITLINK）
6. `/project_review/get_project_code` - 项目代码取号
7. `/project_review/detail` - 详情页（通用）
8. `/project_review/detail_inhenergy` - 详情页（INHENERGY）
9. `/project_review/detail_witlink` - 详情页（WITLINK）
10. `/project_review/modify/<form_id>` - 修改页（通用）
11. `/project_review/modify_inhenergy/<form_id>` - 修改页（INHENERGY）
12. `/project_review/modify_witlink/<form_id>` - 修改页（WITLINK）
13. `/project_review/change/<form_id>` - 变更页（通用）
14. `/project_review/change_inhenergy/<form_id>` - 变更页（INHENERGY）
15. `/project_review/change_witlink/<form_id>` - 变更页（WITLINK）

### API接口路由（共2个）

1. `/project_review/action` - 统一操作接口
2. `/project_review/query` - 查询接口

---

## 使用说明

### 1. 访问列表页
```python
# 在模板中使用
<a href="{{ url_for('project_review.list_page') }}">项目评审列表</a>

# 在控制器中重定向
return redirect(url_for('project_review.list_page'))
```

### 2. 访问详情页
```python
# 在模板中使用
<a href="{{ url_for('project_review.detail_query', id=form_id) }}">查看详情</a>

# 在控制器中重定向
return redirect(url_for('project_review.detail_query', id=form_id, step=step, operate='detail'))
```

### 3. 访问新增页
```python
# 在模板中使用
<a href="{{ url_for('project_review.add') }}">新增项目评审</a>
```

### 4. 调用API接口
```javascript
// 提交表单
$.ajax({
    url: '/project_review/action',
    type: 'POST',
    data: {
        action: 'Submit',
        form_id: '123',
        // ... 其他字段
    },
    success: function(res) {
        if (res.code == '200') {
            alert('提交成功');
        }
    }
});
```

---

## 注意事项

1. **所有路由都需要登录**：使用 `@login_required` 装饰器
2. **公司类型自动重定向**：通用路由会根据用户公司类型自动重定向到专用页面
3. **参数传递**：详情页、修改页、变更页需要传递 `form_id` 参数
4. **API接口**：统一使用 `/project_review/action` 接口，通过 `action` 参数区分操作类型

---

## 相关文件

- 控制器：`app/project_review/controllers/project_review_controller.py`
- API控制器：`app/project_review/controllers/project_review_action_controller.py`
- 服务层：`app/project_review/services/project_review_service.py`
- 模板目录：`app/project_review/templates/project_review/`

