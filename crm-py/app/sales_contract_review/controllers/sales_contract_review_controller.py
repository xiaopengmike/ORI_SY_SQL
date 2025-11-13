"""
销售合同评审控制器
对应原PHP项目的页面文件（list.php）
"""
from flask import Blueprint, render_template, request, session, redirect, url_for
from app.utils.auth import login_required, get_login_user_id, get_login_user_name, get_login_dept_id
from app.common.data_model import DataModel
from app.common.user_model import UserModel

sales_contract_review_bp = Blueprint('sales_contract_review', __name__)

@sales_contract_review_bp.route('/')
@login_required
def index():
    """
    销售合同评审列表首页
    对应原PHP的list.php
    重定向到list页面
    """
    return redirect(url_for('sales_contract_review.list_page'))

@sales_contract_review_bp.route('/list')
@login_required
def list_page():
    """
    销售合同评审列表查询页
    对应原PHP的list.php
    """
    try:
        # 获取用户信息
        user_id = get_login_user_id()
        dept_id = get_login_dept_id()
        
        # 获取系统类型
        system_id = "sales_contract_review"
        
        # 获取管理员权限信息
        from app.utils.auth import get_admin_data_query
        admin_data = get_admin_data_query(user_id, dept_id, system_id)
        is_admin = admin_data.get('isAdmin', False)
        system_admin = admin_data.get('systemAdmin', False)
        
        # 获取数据模型
        data_model = DataModel()
        user_model = UserModel()
        
        # 获取用户公司
        company = user_model.get_user_bu(dept_id)
        
        # 获取通用参数
        select_param = {
            'is_used': '1',
            'type': 'crm_process_sort,crm_process_type'
        }
        select_arr = data_model.get_common_param(select_param)
        
        # 处理crm_process_sort
        crm_process_sort = {}
        if 'crm_process_sort' in select_arr and select_arr['crm_process_sort']:
            for item in select_arr['crm_process_sort']:
                crm_process_sort[item['paras_value']] = item
        
        # 处理crm_process_type
        crm_process_type = {}
        if 'crm_process_type' in select_arr and select_arr['crm_process_type']:
            for item in select_arr['crm_process_type']:
                crm_process_type[item['paras_value']] = item.get('paras_desc', '')
        
        return render_template('sales_contract_review/templates/sales_contract_review/list.html',
                             crm_process_sort=crm_process_sort,
                             crm_process_type=crm_process_type.get(system_id, ''),
                             user_bu=company or '',
                             user_id=user_id or '',
                             is_admin=is_admin,
                             system_admin=system_admin)
    except Exception as e:
        print(f"渲染销售合同评审列表页面错误: {str(e)}")
        import traceback
        traceback.print_exc()
        return redirect(url_for('sales_contract_review.index'))

@sales_contract_review_bp.route('/add')
@login_required
def add():
    """
    销售合同评审新增页
    参考 /sales_contract_review/add.php?company=INHE
    """
    try:
        user_id = get_login_user_id()
        dept_id = get_login_dept_id()
        company = (request.args.get('company') or 'INHE').upper()
        
        # 获取数据模型
        data_model = DataModel()
        user_model = UserModel()
        
        # 获取用户信息
        user_info = user_model.get_user_info(user_id)
        if not user_info:
            user_info = {}
        
        # 获取通用参数
        select_param = {
            'is_used': '1',
            'type': 'project_star,check_opinion,currency,yes_or_no,quote_source,sale_model,auto_flow_user,company,product_brand,product_detail_type,company,funds_come,product_project_type,license_service,license_product,license_type'
        }
        select_arr = data_model.get_common_param(select_param)
        
        # 处理product_detail_types
        product_detail_types = []
        if 'product_detail_type' in select_arr and select_arr['product_detail_type']:
            for v in select_arr['product_detail_type']:
                product_detail_types.append(v.get('paras_value', ''))
        
        # 处理company_names
        company_names = {}
        if 'company' in select_arr and select_arr['company']:
            for v in select_arr['company']:
                company_names[v.get('paras_value', '')] = v.get('paras_desc', '')
        
        # 处理license_service
        license_service = {}
        if 'license_service' in select_arr and select_arr['license_service']:
            for v in select_arr['license_service']:
                license_service[v.get('paras_value', '')] = v.get('paras_desc', '')
        
        # 获取客户列表
        customer_select = data_model.get_customer_list({'user_id': user_id})
        if not customer_select:
            customer_select = {}
        
        # 获取大洲和国家数据
        continent_arr = data_model.get_continent_data()
        country_arr = data_model.get_country_data()
        
        # 确定product_company
        product_company = company
        if company in ["JXINHE", "INHE", "INHEJX", "HKINHE", "INHEIAC", "INHEIMC"]:
            product_company = "JXINHE"
        elif company in ["HKNERGY", "INHENERGY"]:
            product_company = "INHENERGY"
        
        # 获取当前日期
        from datetime import date
        import json
        current_date = date.today().strftime('%Y-%m-%d')
        
        # 将product_detail_types转换为JSON字符串
        product_detail_types_json = json.dumps(product_detail_types)
        
        return render_template('sales_contract_review/templates/sales_contract_review/add.html',
                             company=company,
                             product_company=product_company,
                             user_info=user_info,
                             select_arr=select_arr,
                             product_detail_types=product_detail_types,
                             product_detail_types_json=product_detail_types_json,
                             company_names=company_names,
                             license_service=license_service,
                             customer_select=customer_select,
                             continent_arr=continent_arr or [],
                             country_arr=country_arr or [],
                             user_id=user_id,
                             user_name=get_login_user_name(),
                             dept_id=dept_id,
                             current_date=current_date)
    except Exception as e:
        print(f"渲染销售合同评审新增页错误: {str(e)}")
        import traceback
        traceback.print_exc()
        return redirect(url_for('sales_contract_review.list_page'))

@sales_contract_review_bp.route('/contract_number_list')
@login_required
def contract_number_list():
    """
    合同编号列表弹窗
    用于选择已存在的合同编号
    """
    company = request.args.get('company', '')
    return render_template('sales_contract_review/templates/sales_contract_review/contract_number_list.html',
                         company=company)

@sales_contract_review_bp.route('/next_approver')
@login_required
def next_approver():
    """
    下一审批人选择弹窗（简化版）
    仅支持单选下一步审批人
    """
    try:
        user_id = get_login_user_id()
        dept_id = get_login_dept_id()
        form_id = request.args.get('form_id', '')
        user_bu = request.args.get('user_bu', '')
        
        # 获取流程模型
        from app.common.process_model import ProcessModel
        process_model = ProcessModel()
        
        # 获取流程步骤
        flow_arr = process_model.get_flow_step('sales_contract_review', user_bu)
        
        # 获取下一步（step=1）的审批人信息
        next_step_info = flow_arr.get('1', {}) if flow_arr else {}
        approver_str = next_step_info.get('approver', '')
        
        # 解析审批人ID列表（逗号分隔）
        approver_ids = []
        if approver_str:
            approver_ids = [uid.strip() for uid in approver_str.split(',') if uid.strip()]
        
        # 获取审批人用户信息
        approver_list = []
        if approver_ids:
            from app.utils.db import Database
            placeholders = ','.join(['%s'] * len(approver_ids))
            sql = f"SELECT user_id, USER_NAME FROM user WHERE user_id IN ({placeholders})"
            result = Database.execute_query(sql, tuple(approver_ids))
            approver_list = result if result else []
        
        return render_template('sales_contract_review/templates/sales_contract_review/next_approver.html',
                             approver_list=approver_list,
                             form_id=form_id,
                             user_bu=user_bu)
    except Exception as e:
        print(f"渲染下一审批人弹窗错误: {str(e)}")
        import traceback
        traceback.print_exc()
        return render_template('sales_contract_review/templates/sales_contract_review/next_approver.html',
                             approver_list=[],
                             form_id='',
                             user_bu='')

