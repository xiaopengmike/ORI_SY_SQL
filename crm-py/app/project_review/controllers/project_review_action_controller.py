"""
项目评审操作控制器（AJAX接口）
对应原PHP项目的action.php
"""
from flask import Blueprint, request, jsonify
from app.utils.auth import login_required
from app.utils.common import get_param_to_dict
from app.project_review.services.project_review_service import ProjectReviewService
from app.common.data_model import DataModel
from app.common.services.query_service import QueryService
from app.utils.response import ResponseCode

project_review_action_bp = Blueprint('project_review_action', __name__)

@project_review_action_bp.route('/action', methods=['GET', 'POST'])
@login_required
def action():
    """
    统一操作接口
    对应原PHP的action.php
    
    支持的action:
    - Retrieve: 查询
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
    
    service = ProjectReviewService()
    result = None
    
    # 根据action类型执行相应操作
    if action_type == 'Retrieve':
        # 处理特殊查询
        data_type = params.get('dataType', '')
        if data_type == 'getCustomerDetail':
            # 获取客户详情
            from app.customer.models.customer import Customer
            from app.customer.models.contact import Contact
            customer_id = params.get('id', '')
            if customer_id:
                customer = Customer.query_by_primary_id(int(customer_id))
                if customer:
                    # 查询联系人信息（取第一个联系人）
                    form_id = customer.get('form_id', '')
                    contacts = Contact.query_by_form_id(form_id) if form_id else []
                    contact_info = contacts[0] if contacts else {}
                    
                    result = ResponseCode.success({
                        'short_name': customer.get('short_name', ''),
                        'country_desc': customer.get('countryName', ''),
                        'contact_name': contact_info.get('name', ''),
                        'contact_phone': contact_info.get('phone_one', '') or contact_info.get('phone_two', ''),
                        'email': contact_info.get('email', ''),
                        'summarize': customer.get('summarize', '')
                    })
                else:
                    result = ResponseCode.failed(False, '客户不存在')
            else:
                result = ResponseCode.failed(False, '缺少客户ID')
        elif data_type == 'verifyProjectCode':
            # 验证项目代码
            project_code = params.get('projectCode', '')
            user_bu = params.get('user_bu', '')
            temp_project_code = params.get('temp_project_code', '')
            if project_code:
                from app.utils.db import Database
                sql = "SELECT COUNT(*) AS cnt FROM inhe_project_main WHERE project_code = %s AND user_bu = %s"
                result_query = Database.execute_query(sql, (project_code, user_bu))
                if result_query and result_query[0]['cnt'] > 0:
                    result = ResponseCode.success({'code': '200', 'data': '项目代码已存在'})
                else:
                    result = ResponseCode.success({'code': '404', 'data': '项目代码不存在'})
            else:
                result = ResponseCode.failed(False, '缺少项目代码')
        elif data_type == 'getFormalProjectCode':
            # 获取正式项目代码
            char_length = int(params.get('charLength', 3))
            from app.common.form_model import FormModel
            form_model = FormModel()
            # 生成项目代码的逻辑（需要根据实际业务规则实现）
            # 这里简化处理，实际应该调用FormModel的createFlowId方法
            result = ResponseCode.success('')
        else:
            # 默认查询列表
            result = _query_data(params)
    
    elif action_type == 'Add':
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

def _query_data(param):
    """
    查询项目评审列表数据
    对应原PHP的query_page.php或DataModel::queryData()
    
    Args:
        param (dict): 查询参数字典
        
    Returns:
        dict: 查询结果
    """
    try:
        query_service = QueryService()
        
        # 设置查询类型
        param['type'] = 'project_review'
        
        # 调用查询服务
        result = query_service.query_project_review_list(param)
        
        return ResponseCode.success(result)
    except Exception as e:
        print(f"查询项目评审列表错误: {str(e)}")
        import traceback
        traceback.print_exc()
        return ResponseCode.failed(False, f'查询失败: {str(e)}')

@project_review_action_bp.route('/query', methods=['GET', 'POST'])
@login_required
def query():
    """
    查询接口
    对应原PHP的query_page.php
    """
    try:
        params = get_param_to_dict(request)
        
        # 设置默认值
        params.setdefault('page', 1)
        params.setdefault('page_size', 10)
        params.setdefault('type', 'project_review')
        
        query_service = QueryService()
        result = query_service.query_project_review_list(params)
        
        return jsonify(ResponseCode.success(result))
    except Exception as e:
        print(f"查询项目评审列表错误: {str(e)}")
        import traceback
        traceback.print_exc()
        return jsonify(ResponseCode.failed(False, f'查询失败: {str(e)}'))

