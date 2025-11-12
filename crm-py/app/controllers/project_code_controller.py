"""
项目代号控制器
对应原PHP项目的页面文件（index.php, list.php, add.php等）
"""
from flask import Blueprint, render_template, request, session, redirect, url_for, jsonify
from app.utils.auth import login_required, get_login_user_id, get_login_user_name, get_login_dept_id
from app.services.project_code_service import ProjectCodeService
from app.common.data_model import DataModel
from app.utils.common import get_param_to_dict
from app.utils.response import ResponseCode

project_code_bp = Blueprint('project_code', __name__)

@project_code_bp.route('/')
@login_required
def index():
    """
    项目代号列表首页
    对应原PHP的index.php
    重定向到list页面
    """
    return redirect(url_for('project_code.list_page'))

@project_code_bp.route('/list')
@login_required
def list_page():
    """
    项目代号列表查询页
    对应原PHP的list.php
    """
    try:
        user_id = get_login_user_id()
        dept_id = get_login_dept_id()
        
        # 获取数据模型
        data_model = DataModel()
        
        # 获取通用参数（公司列表）
        select_param = {
            'is_used': '1',
            'type': 'company'
        }
        select_arr = data_model.get_common_param(select_param)
        
        # 获取用户公司
        from app.utils.db import Database
        company_sql = "SELECT user_bu FROM department WHERE dept_id = %s"
        company_result = Database.execute_query(company_sql, (dept_id,))
        company = company_result[0]['user_bu'] if company_result else ''
        
        return render_template('project_code/list.html',
                             select_arr=select_arr.get('company', []),
                             company=company,
                             user_id=user_id)
    except Exception as e:
        print(f"渲染项目代号列表页面错误: {str(e)}")
        import traceback
        traceback.print_exc()
        from flask import render_template_string
        return render_template_string('''
            <html>
            <head><title>错误</title></head>
            <body>
                <h1>页面加载错误</h1>
                <p>错误信息: {{ error }}</p>
                <p><a href="/project_code/list">重试</a></p>
            </body>
            </html>
        ''', error=str(e)), 500

@project_code_bp.route('/add')
@login_required
def add():
    """
    新增项目代号页面
    对应原PHP的add.php
    """
    user_id = get_login_user_id()
    dept_id = get_login_dept_id()
    user_name = get_login_user_name()
    
    # 获取数据模型
    data_model = DataModel()
    
    # 获取通用参数（临时代号规则）
    select_param = {
        'is_used': '1',
        'type': 'temp_code_rule'
    }
    select_arr = data_model.get_common_param(select_param)
    temp_code_rule = {}
    if 'temp_code_rule' in select_arr:
        for item in select_arr['temp_code_rule']:
            temp_code_rule[item['paras_value']] = item
    
    # 获取用户公司
    from app.utils.db import Database
    company_sql = "SELECT user_bu FROM department WHERE dept_id = %s"
    company_result = Database.execute_query(company_sql, (dept_id,))
    company = company_result[0]['user_bu'] if company_result else ''
    
    return render_template('project_code/add.html',
                         temp_code_rule=temp_code_rule,
                         company=company,
                         user_id=user_id,
                         user_name=user_name,
                         dept_id=dept_id)

@project_code_bp.route('/query_data', methods=['GET', 'POST'])
@login_required
def query_data():
    """
    查询项目代号数据接口
    对应原PHP的get_data.php
    """
    try:
        params = get_param_to_dict(request)
        
        # 设置默认分页参数
        if 'page' not in params:
            params['page'] = request.args.get('page', 1)
        if 'limit' not in params:
            params['limit'] = request.args.get('limit', 10)
        
        # 转换分页参数为整数
        try:
            params['page'] = int(params['page'])
            params['limit'] = int(params['limit'])
        except (ValueError, TypeError):
            params['page'] = 1
            params['limit'] = 10
        
        service = ProjectCodeService()
        result = service.query_list(params)
        
        return jsonify(result)
    except Exception as e:
        import traceback
        error_msg = f"查询项目代号数据错误: {str(e)}"
        print(error_msg)
        traceback.print_exc()
        return jsonify({
            "code": 500,
            "msg": error_msg,
            "count": 0,
            "data": []
        }), 500

@project_code_bp.route('/action', methods=['GET', 'POST'])
@login_required
def action():
    """
    项目代号操作接口
    对应原PHP的action.php
    
    支持的action:
    - Submit: 提交（添加）
    - Retrieve: 查询/验证
    """
    action_type = request.args.get('action') or request.form.get('action', '')
    
    if not action_type:
        return jsonify(ResponseCode.failed(False, '缺少action参数'))
    
    # 获取参数
    params = get_param_to_dict(request)
    
    # 添加用户信息
    params['user_id'] = get_login_user_id()
    params['user_name'] = get_login_user_name()
    params['dept_id'] = get_login_dept_id()
    
    service = ProjectCodeService()
    result = None
    
    # 根据action类型执行相应操作
    if action_type == 'Submit':
        result = service.submit(params)
    
    elif action_type == 'Retrieve':
        # 验证项目代号是否存在
        data_type = params.get('dataType', '')
        if data_type == 'verifyProjectCode':
            project_code = params.get('projectCode', '')
            exists = service.verify_code_exists(project_code)
            result = ResponseCode.success(exists)
        else:
            # 其他查询操作
            result = service.query_list(params)
    
    else:
        result = ResponseCode.failed(False, f'不支持的操作类型: {action_type}')
    
    # 转换为JSON响应
    return jsonify(result)

