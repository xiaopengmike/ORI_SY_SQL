"""
客户控制器
对应原PHP项目的页面文件（index.php, list.php, add.php, detail.php等）
"""
from flask import Blueprint, render_template, request, session, redirect, url_for
from app.utils.auth import login_required, get_login_user_id, get_login_user_name, get_login_dept_id
from app.services.customer_service import CustomerService
from app.common.data_model import DataModel
from app.common.process_model import ProcessModel

customer_bp = Blueprint('customer', __name__)

@customer_bp.route('/')
@login_required
def index():
    """
    客户列表首页
    对应原PHP的index.php
    重定向到list页面，因为list页面包含完整的查询和操作功能
    """
    return redirect(url_for('customer.list_page'))

@customer_bp.route('/list')
@login_required
def list_page():
    """
    客户列表查询页
    对应原PHP的list.php
    支持tab参数：list(客户信息列表), change(客户信息登记变更), share(客户信息共享), flow(跟进记录)
    """
    try:
        # 获取tab参数，默认为list
        tab = request.args.get('tab', 'list')
        
        # 获取用户信息
        user_id = get_login_user_id()
        dept_id = get_login_dept_id()
        
        # 获取数据模型
        data_model = DataModel()
        
        # 获取通用参数
        select_param = {
            'is_used': '1',
            'type': 'crm_process_sort,crm_process_type'
        }
        select_arr = data_model.get_common_param(select_param)
        
        # 获取大洲和国家数据 - 添加错误处理
        try:
            continent_arr = data_model.get_continent_data()
        except Exception as e:
            print(f"获取大洲数据错误: {str(e)}")
            continent_arr = []
        
        try:
            country_arr = data_model.get_country_data()
        except Exception as e:
            print(f"获取国家数据错误: {str(e)}")
            country_arr = []
        
        # 处理crm_process_sort - 确保有默认值
        crm_process_sort = {}
        if 'crm_process_sort' in select_arr and select_arr['crm_process_sort']:
            for item in select_arr['crm_process_sort']:
                crm_process_sort[item['paras_value']] = item
        
        # 处理crm_process_type - 确保有默认值
        crm_process_type = {}
        if 'crm_process_type' in select_arr and select_arr['crm_process_type']:
            for item in select_arr['crm_process_type']:
                crm_process_type[item['paras_value']] = item.get('paras_desc', '')
        
        # 获取用户公司（简化处理）- 确保有默认值
        user_bu = session.get('LOGIN_USER_BU', '')
        
        return render_template('customer/list.html',
                             crm_process_sort=crm_process_sort,
                             crm_process_type=crm_process_type,
                             continent_arr=continent_arr or [],
                             country_arr=country_arr or [],
                             user_bu=user_bu or '',
                             user_id=user_id or '',
                             current_tab=tab)
    except Exception as e:
        # 错误处理 - 返回错误页面或重定向
        print(f"渲染客户列表页面错误: {str(e)}")
        import traceback
        traceback.print_exc()
        from flask import render_template_string
        return render_template_string('''
            <html>
            <head><title>错误</title></head>
            <body>
                <h1>页面加载错误</h1>
                <p>错误信息: {{ error }}</p>
                <p><a href="/customer/list">重试</a></p>
            </body>
            </html>
        ''', error=str(e)), 500

@customer_bp.route('/add')
@login_required
def add():
    """
    新增客户页面
    对应原PHP的add.php
    """
    user_id = get_login_user_id()
    dept_id = get_login_dept_id()
    user_name = get_login_user_name()
    
    # 获取数据模型
    data_model = DataModel()
    
    # 获取大洲和国家数据
    continent_arr = data_model.get_continent_data()
    country_arr = data_model.get_country_data()
    
    # 获取通用参数
    select_param = {
        'is_used': '1',
        'type': 'customer_type,project_level,customer_source,yes_or_no'
    }
    select_arr = data_model.get_common_param(select_param)
    
    # 获取用户公司（简化处理）
    company = session.get('LOGIN_USER_BU', '')
    is_nergy = company in ['INHENERGY', 'HKNERGY']
    
    return render_template('customer/add.html',
                         continent_arr=continent_arr,
                         country_arr=country_arr,
                         select_arr=select_arr,
                         company=company,
                         is_nergy=is_nergy,
                         user_id=user_id,
                         user_name=user_name,
                         dept_id=dept_id)

@customer_bp.route('/detail')
@login_required
def detail_query():
    """
    处理访问 /customer/detail?id=xxx 的情况（查询参数方式）
    支持通过主键id或form_id查询（优先使用id）
    对应原PHP的detail.php?id=xxx格式
    直接处理详情页面，不进行重定向
    """
    # 优先获取id参数（主键）
    customer_id = request.args.get('id', '')
    # 如果没有id，尝试获取form_id参数（向后兼容）
    form_id = request.args.get('form_id', '')
    
    if not customer_id and not form_id:
        # 如果没有提供任何参数，重定向到列表页
        return redirect(url_for('customer.list_page'))
    
    # 直接处理详情页面，使用与detail()相同的逻辑
    user_id = get_login_user_id()
    dept_id = get_login_dept_id()
    
    # 查询客户数据（优先使用id）
    service = CustomerService()
    if customer_id:
        param = {'id': customer_id}
    else:
        param = {'form_id': form_id}
    data = service.query_by_id(param)
    
    if not data or not data.get('main'):
        return redirect(url_for('customer.index'))
    
    main = data['main']
    contacts = data.get('contacts', [])
    company = data.get('company', [])
    share = data.get('share', '')
    flow = data.get('flow', [])
    
    # 从查询结果中获取form_id（用于流程相关查询）
    actual_form_id = main.get('form_id', '')
    
    # 获取流程信息
    process = ProcessModel()
    approve_param = {'form_id': actual_form_id}
    approve_data = process.get_approve_data(approve_param)
    
    # 获取流程步骤
    flow_arr = process.get_flow_step(main.get('type', 'customer_data'), main.get('user_bu', ''))
    
    # 获取当前操作
    step = request.args.get('step', '')
    operate = request.args.get('operate', 'detail')
    
    # 获取审批信息
    condition_param = {
        'step': step,
        'form_id': actual_form_id,
        'approve_user': user_id
    }
    approve_info = process.find_approve_info(condition_param)
    
    if not approve_info:
        operate = 'detail'
        step = ''
    
    # 获取通用参数
    data_model = DataModel()
    select_param = {
        'is_used': '1',
        'type': 'customer_type,customer_source,yes_or_no'
    }
    select_arr = data_model.get_common_param(select_param)
    
    # 获取变更记录
    change_data = data_model.get_new_change(actual_form_id, 'customer_data')
    
    # 获取退回和通过记录
    back_record = process.find_back_record({'form_id': actual_form_id})
    pass_record = process.find_pass_record({'form_id': actual_form_id})
    
    return render_template('customer/detail.html',
                         main=main,
                         contacts=contacts,
                         company=company,
                         share=share,
                         flow=flow,
                         approve_data=approve_data,
                         flow_arr=flow_arr,
                         step=step,
                         operate=operate,
                         approve_info=approve_info,
                         select_arr=select_arr,
                         change_data=change_data,
                         back_record=back_record,
                         pass_record=pass_record)

@customer_bp.route('/detail/')
@login_required
def detail_empty():
    """
    处理访问 /customer/detail/ 但没有提供 form_id 的情况
    重定向到客户列表页面
    """
    return redirect(url_for('customer.list_page'))

@customer_bp.route('/detail/<identifier>')
@login_required
def detail(identifier):
    """
    客户详情页面（路径参数方式）
    支持通过主键id或form_id访问
    对应原PHP的detail.php
    
    Args:
        identifier (str): 客户主键ID（数字）或表单ID（字符串）
    """
    user_id = get_login_user_id()
    dept_id = get_login_dept_id()
    
    # 判断identifier是主键id还是form_id
    # 尝试转换为整数，如果成功则是主键id，否则是form_id
    try:
        customer_id = int(identifier)
        param = {'id': customer_id}
    except (ValueError, TypeError):
        param = {'form_id': identifier}
    
    # 查询客户数据
    service = CustomerService()
    data = service.query_by_id(param)
    
    if not data or not data.get('main'):
        return redirect(url_for('customer.index'))
    
    main = data['main']
    contacts = data.get('contacts', [])
    company = data.get('company', [])
    share = data.get('share', '')
    flow = data.get('flow', [])
    
    # 从查询结果中获取form_id（用于流程相关查询）
    actual_form_id = main.get('form_id', '')
    
    # 获取流程信息
    process = ProcessModel()
    approve_param = {'form_id': actual_form_id}
    approve_data = process.get_approve_data(approve_param)
    
    # 获取流程步骤
    flow_arr = process.get_flow_step(main.get('type', 'customer_data'), main.get('user_bu', ''))
    
    # 获取当前操作
    step = request.args.get('step', '')
    operate = request.args.get('operate', 'detail')
    
    # 获取审批信息
    condition_param = {
        'step': step,
        'form_id': actual_form_id,
        'approve_user': user_id
    }
    approve_info = process.find_approve_info(condition_param)
    
    if not approve_info:
        operate = 'detail'
        step = ''
    
    # 获取通用参数
    data_model = DataModel()
    select_param = {
        'is_used': '1',
        'type': 'customer_type,customer_source,yes_or_no'
    }
    select_arr = data_model.get_common_param(select_param)
    
    # 获取变更记录
    change_data = data_model.get_new_change(actual_form_id, 'customer_data')
    
    # 获取退回和通过记录
    back_record = process.find_back_record({'form_id': actual_form_id})
    pass_record = process.find_pass_record({'form_id': actual_form_id})
    
    return render_template('customer/detail.html',
                         main=main,
                         contacts=contacts,
                         company=company,
                         share=share,
                         flow=flow,
                         approve_data=approve_data,
                         flow_arr=flow_arr,
                         step=step,
                         operate=operate,
                         approve_info=approve_info,
                         select_arr=select_arr,
                         change_data=change_data,
                         back_record=back_record,
                         pass_record=pass_record)

@customer_bp.route('/modify/<form_id>')
@login_required
def modify(form_id):
    """
    修改客户页面
    对应原PHP的modify.php
    
    Args:
        form_id (str): 表单ID
    """
    user_id = get_login_user_id()
    dept_id = get_login_dept_id()
    
    # 查询客户数据
    service = CustomerService()
    param = {'id': form_id}
    data = service.query_by_id(param)
    
    if not data or not data.get('main'):
        return redirect(url_for('customer.index'))
    
    main = data['main']
    contacts = data.get('contacts', [])
    company = data.get('company', [])
    
    # 获取数据模型
    data_model = DataModel()
    
    # 获取大洲和国家数据
    continent_arr = data_model.get_continent_data()
    country_arr = data_model.get_country_data()
    
    # 获取通用参数
    select_param = {
        'is_used': '1',
        'type': 'customer_type,customer_source,yes_or_no'
    }
    select_arr = data_model.get_common_param(select_param)
    
    # 获取用户公司
    company_bu = main.get('user_bu', '')
    is_nergy = company_bu in ['INHENERGY', 'HKNERGY']
    
    return render_template('customer/modify.html',
                         main=main,
                         contacts=contacts,
                         company=company,
                         continent_arr=continent_arr,
                         country_arr=country_arr,
                         select_arr=select_arr,
                         company_bu=company_bu,
                         is_nergy=is_nergy)

@customer_bp.route('/change/<form_id>')
@login_required
def change(form_id):
    """
    变更客户页面
    对应原PHP的change.php
    
    Args:
        form_id (str): 表单ID
    """
    user_id = get_login_user_id()
    dept_id = get_login_dept_id()
    user_name = get_login_user_name()
    
    # 查询客户数据
    service = CustomerService()
    param = {'id': form_id}
    data = service.query_by_id(param)
    
    if not data or not data.get('main'):
        return redirect(url_for('customer.index'))
    
    main = data['main']
    contacts = data.get('contacts', [])
    company = data.get('company', [])
    
    # 获取数据模型
    data_model = DataModel()
    
    # 获取大洲和国家数据
    continent_arr = data_model.get_continent_data()
    country_arr = data_model.get_country_data()
    
    # 获取通用参数
    select_param = {
        'is_used': '1',
        'type': 'customer_type,customer_source,yes_or_no'
    }
    select_arr = data_model.get_common_param(select_param)
    
    # 获取用户公司
    company_bu = main.get('user_bu', '')
    is_nergy = company_bu in ['INHENERGY', 'HKNERGY']
    
    # 获取部门名称
    from app.utils.auth import get_login_dept_name
    dept_name = get_login_dept_name()
    
    return render_template('customer/change.html',
                         main=main,
                         contacts=contacts,
                         company=company,
                         continent_arr=continent_arr,
                         country_arr=country_arr,
                         select_arr=select_arr,
                         company_bu=company_bu,
                         is_nergy=is_nergy,
                         related_id=form_id,
                         user_name=user_name,
                         dept_name=dept_name)

