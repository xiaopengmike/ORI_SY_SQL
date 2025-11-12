"""
操作控制器（AJAX接口）
对应原PHP项目的action.php
"""
from flask import Blueprint, request, jsonify
from app.utils.auth import login_required
from app.utils.common import get_param_to_dict
from app.customer.services.customer_service import CustomerService
from app.utils.response import ResponseCode

action_bp = Blueprint('action', __name__)

@action_bp.route('/action', methods=['GET', 'POST'])
@login_required
def action():
    """
    统一操作接口
    对应原PHP的action.php
    
    支持的action:
    - Add: 添加
    - Submit: 提交
    - Save: 保存
    - Modify: 修改
    - Change: 变更
    - ChangeSave: 变更保存
    - Delete: 删除
    - Approve: 审批
    - Back: 退回
    """
    action_type = request.args.get('action') or request.form.get('action', '')
    
    if not action_type:
        return jsonify(ResponseCode.failed(False, '缺少action参数'))
    
    # 获取参数
    params = get_param_to_dict(request)
    
    # 添加用户信息
    from app.utils.auth import get_login_user_id, get_login_user_name, get_login_dept_id
    params['user_id'] = get_login_user_id()
    params['user_name'] = get_login_user_name()
    params['dept_id'] = get_login_dept_id()
    params['organ_id'] = get_login_dept_id()
    
    service = CustomerService()
    result = None
    
    # 根据action类型执行相应操作
    if action_type == 'Add':
        result = service.add(params)
        result = ResponseCode.success(params.get('form_id')) if result else ResponseCode.failed(False, '添加失败')
    
    elif action_type == 'Submit':
        result = service.submit(params)
    
    elif action_type == 'Save':
        result = service.save(params)
    
    elif action_type == 'Modify':
        result = service.update(params)
        result = ResponseCode.success(params.get('form_id')) if result else ResponseCode.failed(False, '修改失败')
    
    elif action_type == 'Change':
        result = service.change(params)
    
    elif action_type == 'ChangeSave':
        result = service.change_save(params)
    
    elif action_type == 'Delete':
        result = service.delete(params)
        result = ResponseCode.success(True) if result else ResponseCode.failed(False, '删除失败')
    
    elif action_type == 'Approve':
        result = service.update_approve_opinion(params)
    
    elif action_type == 'Back':
        result = service.update_approve_opinion(params)
    
    else:
        result = ResponseCode.failed(False, f'不支持的操作类型: {action_type}')
    
    # 转换为JSON响应
    return jsonify(result)

@action_bp.route('/query', methods=['GET', 'POST'])
@login_required
def query():
    """
    查询接口
    对应原PHP的query_page.php
    """
    try:
        from app.common.services.query_service import QueryService
        
        params = get_param_to_dict(request)
        
        # 设置默认值
        params.setdefault('page', 1)
        params.setdefault('page_size', 10)
        params.setdefault('type', 'customer_data')
        
        # 执行查询
        query_service = QueryService()
        result = query_service.query_customer_list(params)
        
        return jsonify(ResponseCode.success(result))
    except Exception as e:
        # 添加错误处理和日志
        import traceback
        error_msg = f"查询接口错误: {str(e)}"
        print(error_msg)
        traceback.print_exc()
        # 返回错误响应
        return jsonify(ResponseCode.failed(None, error_msg)), 500

