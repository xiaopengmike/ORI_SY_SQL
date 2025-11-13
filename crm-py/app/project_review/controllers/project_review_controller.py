"""
项目评审控制器
对应原PHP项目的页面文件（index.php, list.php, add.php, detail.php等）
"""
from flask import Blueprint, render_template, request, session, redirect, url_for
from app.utils.auth import login_required, get_login_user_id, get_login_user_name, get_login_dept_id
from app.project_review.services.project_review_service import ProjectReviewService
from app.common.data_model import DataModel
from app.common.process_model import ProcessModel
from app.common.user_model import UserModel
from app.common.attach_model import AttachModel

project_review_bp = Blueprint('project_review', __name__)

@project_review_bp.route('/')
@login_required
def index():
    """
    项目评审列表首页
    对应原PHP的index.php
    重定向到list页面
    """
    return redirect(url_for('project_review.list_page'))

@project_review_bp.route('/list')
@login_required
def list_page():
    """
    项目评审列表查询页
    对应原PHP的list.php
    """
    try:
        # 获取用户信息
        user_id = get_login_user_id()
        dept_id = get_login_dept_id()
        
        # 获取系统类型
        system_id = 'project_review'
        
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
        
        return render_template('project_review/templates/project_review/list.html',
                             crm_process_sort=crm_process_sort,
                             crm_process_type=crm_process_type.get(system_id, ''),
                             user_bu=company or '',
                             user_id=user_id or '',
                             is_admin=is_admin,
                             system_admin=system_admin)
    except Exception as e:
        print(f"渲染项目评审列表页面错误: {str(e)}")
        import traceback
        traceback.print_exc()
        return redirect(url_for('project_review.index'))

@project_review_bp.route('/add')
@login_required
def add():
    """
    新增项目评审页面
    对应原PHP的add.php
    根据公司类型重定向到专用页面
    """
    user_id = get_login_user_id()
    dept_id = get_login_dept_id()
    
    # 获取用户公司
    user_model = UserModel()
    company = user_model.get_user_bu(dept_id)
    
    # 根据公司类型重定向
    if company in ['INHENERGY', 'HKNERGY']:
        return redirect(url_for('project_review.add_inhenergy', company=company))
    elif company == 'WITLINK':
        return redirect(url_for('project_review.add_witlink', company='WITLINK'))
    else:
        # 通用新增页
        return _render_add_page(company, user_id, dept_id)

@project_review_bp.route('/add_inhenergy')
@login_required
def add_inhenergy():
    """
    INHENERGY公司新增页
    对应原PHP的add_inhenergy.php
    """
    user_id = get_login_user_id()
    dept_id = get_login_dept_id()
    company = request.args.get('company', 'INHENERGY')
    return _render_add_page(company, user_id, dept_id, is_inhenergy=True)

@project_review_bp.route('/add_witlink')
@login_required
def add_witlink():
    """
    WITLINK公司新增页
    对应原PHP的add_witlink.php
    """
    user_id = get_login_user_id()
    dept_id = get_login_dept_id()
    company = request.args.get('company', 'WITLINK')
    return _render_add_page(company, user_id, dept_id, is_witlink=True)

def _render_add_page(company, user_id, dept_id, is_inhenergy=False, is_witlink=False):
    """
    渲染新增页面的通用函数
    
    Args:
        company: 公司代码
        user_id: 用户ID
        dept_id: 部门ID
        is_inhenergy: 是否为INHENERGY公司
        is_witlink: 是否为WITLINK公司
    """
    # 获取数据模型
    data_model = DataModel()
    user_model = UserModel()
    
    # 获取通用参数
    select_param = {
        'is_used': '1',
        'type': 'yes_or_no,project_type,project_level,product_detail_type'
    }
    select_arr = data_model.get_common_param(select_param)
    
    # 处理product_detail_types
    product_detail_types = []
    if 'product_detail_type' in select_arr and select_arr['product_detail_type']:
        for v in select_arr['product_detail_type']:
            product_detail_types.append(v.get('paras_value', ''))
    
    # 获取用户信息
    user_info = user_model.get_user_info(user_id)
    if not user_info:
        user_info = {}
    
    # 获取客户列表
    customer_select = data_model.get_customer_list({'user_id': user_id})
    if not customer_select:
        customer_select = {}
    
    # 获取临时项目代码
    project_code_select = data_model.get_temp_code({'user_id': user_id})
    if not project_code_select:
        project_code_select = []
    
    # 判断是否为项目管理员
    if_project_manager = data_model.verify_project_manager(user_id)
    
    template_name = 'project_review/templates/project_review/add.html'
    if is_inhenergy:
        template_name = 'project_review/templates/project_review/add_inhenergy.html'
    elif is_witlink:
        template_name = 'project_review/templates/project_review/add_witlink.html'
    
    # 初始化空数据对象（用于新增页面）
    customer_data = {}
    project = {}
    main = {}
    bidding = {}
    
    return render_template(template_name,
                         select_arr=select_arr,
                         product_detail_types=product_detail_types,
                         customer_select=customer_select,
                         project_code_select=project_code_select,
                         company=company,
                         user_info=user_info,
                         if_project_manager=if_project_manager,
                         user_id=user_id,
                         user_name=get_login_user_name(),
                         dept_id=dept_id,
                         customer_data=customer_data,
                         project=project,
                         main=main,
                         bidding=bidding)

@project_review_bp.route('/get_project_code')
@login_required
def get_project_code():
    """
    获取项目代码页面
    对应原PHP的get_project_code.php
    """
    user_id = get_login_user_id()
    dept_id = get_login_dept_id()
    company = request.args.get('company', '')
    
    # 获取数据模型
    data_model = DataModel()
    user_model = UserModel()
    
    if not company:
        company = user_model.get_user_bu(dept_id)
    
    # 获取临时项目代码
    project_code_select = data_model.get_temp_code({'user_id': user_id})
    if not project_code_select:
        project_code_select = []
    
    return render_template('project_review/templates/project_review/get_project_code.html',
                         company=company,
                         project_code_select=project_code_select)

@project_review_bp.route('/project_list')
@login_required
def project_list():
    """
    项目列表弹窗
    用于销售合同评审等模块选择项目代码
    复用 get_project_code.html 模板
    """
    user_id = get_login_user_id()
    dept_id = get_login_dept_id()
    company = request.args.get('company', '')
    
    # 获取数据模型
    data_model = DataModel()
    user_model = UserModel()
    
    if not company:
        company = user_model.get_user_bu(dept_id)
    
    # 获取临时项目代码
    project_code_select = data_model.get_temp_code({'user_id': user_id})
    if not project_code_select:
        project_code_select = []
    
    return render_template('project_review/templates/project_review/get_project_code.html',
                         company=company,
                         project_code_select=project_code_select)

@project_review_bp.route('/detail')
@login_required
def detail_query():
    """
    处理访问 /project_review/detail?id=xxx 的情况
    对应原PHP的detail.php?id=xxx格式
    """
    project_id = request.args.get('id', '')
    step = request.args.get('step', '')
    operate = request.args.get('operate', 'detail')
    
    if not project_id:
        return redirect(url_for('project_review.index'))
    
    # 查询项目数据
    service = ProjectReviewService()
    param = {'id': project_id}
    data = service.query_by_id(param)
    
    if not data or not data.get('main'):
        return redirect(url_for('project_review.index'))
    
    main = data['main']
    user_bu = main.get('user_bu', '')
    
    # 根据公司类型重定向
    if user_bu in ['INHENERGY', 'HKNERGY'] and main.get('is_bidding_project'):
        return redirect(url_for('project_review.detail_inhenergy', id=project_id, step=step, operate=operate))
    elif user_bu == 'WITLINK':
        return redirect(url_for('project_review.detail_witlink', id=project_id, step=step, operate=operate))
    else:
        return _render_detail_page(data, step, operate, project_id)

@project_review_bp.route('/detail_inhenergy')
@login_required
def detail_inhenergy():
    """
    INHENERGY详情页
    对应原PHP的detail_inhenergy.php
    """
    project_id = request.args.get('id', '')
    step = request.args.get('step', '')
    operate = request.args.get('operate', 'detail')
    
    if not project_id:
        return redirect(url_for('project_review.index'))
    
    service = ProjectReviewService()
    param = {'id': project_id}
    data = service.query_by_id(param)
    
    if not data or not data.get('main'):
        return redirect(url_for('project_review.index'))
    
    return _render_detail_page(data, step, operate, project_id, is_inhenergy=True)

@project_review_bp.route('/detail_witlink')
@login_required
def detail_witlink():
    """
    WITLINK详情页
    对应原PHP的detail_witlink.php
    """
    project_id = request.args.get('id', '')
    step = request.args.get('step', '')
    operate = request.args.get('operate', 'detail')
    
    if not project_id:
        return redirect(url_for('project_review.index'))
    
    service = ProjectReviewService()
    param = {'id': project_id}
    data = service.query_by_id(param)
    
    if not data or not data.get('main'):
        return redirect(url_for('project_review.index'))
    
    return _render_detail_page(data, step, operate, project_id, is_witlink=True)

def _render_detail_page(data, step, operate, project_id, is_inhenergy=False, is_witlink=False):
    """
    渲染详情页面的通用函数
    """
    main = data['main']
    customer_data = data.get('customer', {})
    project = data.get('project', {})
    project_witlink = data.get('projectWitlink', {})
    project_detail = data.get('projectDetail', {})
    bidding = data.get('bidding', {})
    
    form_id = main.get('form_id', '')
    user_id = get_login_user_id()
    
    # 获取流程信息
    process = ProcessModel()
    approve_param = {'form_id': form_id}
    approve_data = process.get_approve_data(approve_param)
    
    # 获取流程步骤
    system_id = 'project_review'
    if 'change' in main.get('type', ''):
        system_id = 'project_review_change'
    flow_arr = process.get_flow_step(system_id, main.get('user_bu', ''))
    
    # 获取当前操作
    cur_operate = flow_arr.get(step, {}).get('operate', '') if step and flow_arr else ''
    
    # 获取审批信息
    condition_param = {
        'step': step,
        'form_id': form_id,
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
        'type': 'project_star,check_opinion,project_type,yes_or_no,product_detail_type'
    }
    select_arr = data_model.get_common_param(select_param)
    
    # 处理project_star
    project_star = {}
    if 'project_star' in select_arr and select_arr['project_star']:
        for ps in select_arr['project_star']:
            project_star[ps['paras_value']] = ps
    
    # 处理check_opinion
    check_arr = {}
    if 'check_opinion' in select_arr and select_arr['check_opinion']:
        for co in select_arr['check_opinion']:
            check_arr[co['paras_value']] = co
    
    # 处理project_type
    project_type = {}
    if 'project_type' in select_arr and select_arr['project_type']:
        for v in select_arr['project_type']:
            project_type[v['paras_value']] = v.get('paras_desc', '')
    
    # 获取变更记录
    change_data = data_model.get_new_change(form_id, 'project_review')
    
    # 获取附件
    attach_model = AttachModel()
    attach_obj = attach_model.get_attach_by_id(form_id)
    
    # 获取退回和通过记录
    back_record = process.find_back_record({'form_id': form_id})
    pass_record = process.find_pass_record({'form_id': form_id})
    
    # 获取客户列表
    customer_select = data_model.get_customer_list2()
    if not customer_select:
        customer_select = {}
    
    # 获取opinion_extra
    select_param_extra = {
        'is_used': '1',
        'type': 'opinion_extra',
        'user_bu': main.get('user_bu', ''),
        'paras_group': 'project_review'
    }
    opinion_extra = data_model.get_common_param(select_param_extra)
    opinion_extra_arr = {}
    if 'opinion_extra' in opinion_extra and opinion_extra['opinion_extra']:
        for ps in opinion_extra['opinion_extra']:
            type_desc = ps.get('type_desc', '')
            if type_desc not in opinion_extra_arr:
                opinion_extra_arr[type_desc] = {}
            opinion_extra_arr[type_desc][ps['paras_value']] = ps
    
    template_name = 'project_review/templates/project_review/detail.html'
    if is_inhenergy:
        template_name = 'project_review/templates/project_review/detail_inhenergy.html'
    elif is_witlink:
        template_name = 'project_review/templates/project_review/detail_witlink.html'
    
    return render_template(template_name,
                         main=main,
                         customer_data=customer_data,
                         project=project,
                         project_witlink=project_witlink,
                         project_detail=project_detail,
                         bidding=bidding,
                         approve_data=approve_data,
                         flow_arr=flow_arr,
                         step=step,
                         operate=operate,
                         cur_operate=cur_operate,
                         approve_info=approve_info,
                         select_arr=select_arr,
                         project_star=project_star,
                         check_arr=check_arr,
                         project_type=project_type,
                         change_data=change_data,
                         attach_obj=attach_obj,
                         back_record=back_record,
                         pass_record=pass_record,
                         customer_select=customer_select,
                         opinion_extra_arr=opinion_extra_arr)

@project_review_bp.route('/modify/<form_id>')
@login_required
def modify(form_id):
    """
    修改项目评审页面
    对应原PHP的modify.php
    """
    service = ProjectReviewService()
    param = {'id': form_id}
    data = service.query_by_id(param)
    
    if not data or not data.get('main'):
        return redirect(url_for('project_review.index'))
    
    main = data['main']
    user_bu = main.get('user_bu', '')
    
    # 根据公司类型重定向
    if user_bu in ['INHENERGY', 'HKNERGY']:
        return redirect(url_for('project_review.modify_inhenergy', form_id=form_id))
    elif user_bu == 'WITLINK':
        return redirect(url_for('project_review.modify_witlink', form_id=form_id))
    else:
        return _render_modify_page(data, form_id)

@project_review_bp.route('/modify_inhenergy/<form_id>')
@login_required
def modify_inhenergy(form_id):
    """
    INHENERGY修改页
    对应原PHP的modify_inhenergy.php
    """
    service = ProjectReviewService()
    param = {'id': form_id}
    data = service.query_by_id(param)
    
    if not data or not data.get('main'):
        return redirect(url_for('project_review.index'))
    
    return _render_modify_page(data, form_id, is_inhenergy=True)

@project_review_bp.route('/modify_witlink/<form_id>')
@login_required
def modify_witlink(form_id):
    """
    WITLINK修改页
    对应原PHP的modify_witlink.php
    """
    service = ProjectReviewService()
    param = {'id': form_id}
    data = service.query_by_id(param)
    
    if not data or not data.get('main'):
        return redirect(url_for('project_review.index'))
    
    return _render_modify_page(data, form_id, is_witlink=True)

def _render_modify_page(data, form_id, is_inhenergy=False, is_witlink=False):
    """
    渲染修改页面的通用函数
    """
    main = data['main']
    customer_data = data.get('customer', {})
    project = data.get('project', {})
    project_witlink = data.get('projectWitlink', {})
    project_detail = data.get('projectDetail', {})
    bidding = data.get('bidding', {})
    
    # 获取数据模型
    data_model = DataModel()
    user_model = UserModel()
    user_id = get_login_user_id()
    
    # 获取通用参数
    select_param = {
        'is_used': '1',
        'type': 'yes_or_no,project_type,project_level,product_detail_type'
    }
    select_arr = data_model.get_common_param(select_param)
    
    # 处理product_detail_types
    product_detail_types = []
    if 'product_detail_type' in select_arr and select_arr['product_detail_type']:
        for v in select_arr['product_detail_type']:
            product_detail_types.append(v.get('paras_value', ''))
    
    # 获取客户列表
    customer_select = data_model.get_customer_list({'user_id': user_id})
    if not customer_select:
        customer_select = {}
    
    template_name = 'project_review/templates/project_review/modify.html'
    if is_inhenergy:
        template_name = 'project_review/templates/project_review/modify_inhenergy.html'
    elif is_witlink:
        template_name = 'project_review/templates/project_review/modify_witlink.html'
    
    return render_template(template_name,
                         main=main,
                         customer_data=customer_data,
                         project=project,
                         project_witlink=project_witlink,
                         project_detail=project_detail,
                         bidding=bidding,
                         select_arr=select_arr,
                         product_detail_types=product_detail_types,
                         customer_select=customer_select,
                         company=main.get('user_bu', ''))

@project_review_bp.route('/change/<form_id>')
@login_required
def change(form_id):
    """
    变更项目评审页面
    对应原PHP的change.php
    """
    service = ProjectReviewService()
    param = {'id': form_id}
    data = service.query_by_id(param)
    
    if not data or not data.get('main'):
        return redirect(url_for('project_review.index'))
    
    main = data['main']
    user_bu = main.get('user_bu', '')
    
    # 根据公司类型重定向
    if user_bu in ['INHENERGY', 'HKNERGY']:
        return redirect(url_for('project_review.change_inhenergy', form_id=form_id))
    elif user_bu == 'WITLINK':
        return redirect(url_for('project_review.change_witlink', form_id=form_id))
    else:
        return _render_change_page(data, form_id)

@project_review_bp.route('/change_inhenergy/<form_id>')
@login_required
def change_inhenergy(form_id):
    """
    INHENERGY变更页
    对应原PHP的change_inhenergy.php
    """
    service = ProjectReviewService()
    param = {'id': form_id}
    data = service.query_by_id(param)
    
    if not data or not data.get('main'):
        return redirect(url_for('project_review.index'))
    
    return _render_change_page(data, form_id, is_inhenergy=True)

@project_review_bp.route('/change_witlink/<form_id>')
@login_required
def change_witlink(form_id):
    """
    WITLINK变更页
    对应原PHP的change_witlink.php
    """
    service = ProjectReviewService()
    param = {'id': form_id}
    data = service.query_by_id(param)
    
    if not data or not data.get('main'):
        return redirect(url_for('project_review.index'))
    
    return _render_change_page(data, form_id, is_witlink=True)

def _render_change_page(data, form_id, is_inhenergy=False, is_witlink=False):
    """
    渲染变更页面的通用函数
    """
    main = data['main']
    customer_data = data.get('customer', {})
    project = data.get('project', {})
    project_witlink = data.get('projectWitlink', {})
    project_detail = data.get('projectDetail', {})
    bidding = data.get('bidding', {})
    
    # 获取数据模型
    data_model = DataModel()
    user_model = UserModel()
    user_id = get_login_user_id()
    
    # 获取通用参数
    select_param = {
        'is_used': '1',
        'type': 'yes_or_no,project_type,project_level,product_detail_type'
    }
    select_arr = data_model.get_common_param(select_param)
    
    # 处理product_detail_types
    product_detail_types = []
    if 'product_detail_type' in select_arr and select_arr['product_detail_type']:
        for v in select_arr['product_detail_type']:
            product_detail_types.append(v.get('paras_value', ''))
    
    # 获取客户列表
    customer_select = data_model.get_customer_list({'user_id': user_id})
    if not customer_select:
        customer_select = {}
    
    template_name = 'project_review/templates/project_review/change.html'
    if is_inhenergy:
        template_name = 'project_review/templates/project_review/change_inhenergy.html'
    elif is_witlink:
        template_name = 'project_review/templates/project_review/change_witlink.html'
    
    return render_template(template_name,
                         main=main,
                         customer_data=customer_data,
                         project=project,
                         project_witlink=project_witlink,
                         project_detail=project_detail,
                         bidding=bidding,
                         select_arr=select_arr,
                         product_detail_types=product_detail_types,
                         customer_select=customer_select,
                         company=main.get('user_bu', ''),
                         related_id=form_id,
                         user_name=get_login_user_name(),
                         dept_name=main.get('deptName', ''))
