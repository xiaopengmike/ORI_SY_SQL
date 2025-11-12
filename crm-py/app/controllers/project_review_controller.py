"""
立项申请控制器
对应原PHP项目的页面文件（index.php, list.php, add.php, detail.php等）
"""
from flask import Blueprint, render_template, request, session, redirect, url_for, jsonify
from app.utils.auth import login_required, get_login_user_id, get_login_user_name, get_login_dept_id
from app.services.project_review_service import ProjectReviewService
from app.common.data_model import DataModel
from app.common.process_model import ProcessModel
from app.utils.common import get_param_to_dict
from app.utils.response import ResponseCode
from datetime import datetime

project_review_bp = Blueprint('project_review', __name__)

@project_review_bp.route('/')
@login_required
def index():
    """
    立项申请列表首页
    对应原PHP的index.php
    重定向到list页面
    """
    return redirect(url_for('project_review.list_page'))

@project_review_bp.route('/list')
@login_required
def list_page():
    """
    立项申请列表查询页
    对应原PHP的list.php, list_change.php, list_star.php
    支持tab参数：list(立项申请表), change(立项申请变更), star(星级申请变更)
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
            'type': 'project_star,project_type,yes_or_no'
        }
        select_arr = data_model.get_common_param(select_param)
        
        # 获取用户公司
        user_bu = session.get('LOGIN_USER_BU', '')
        
        # 处理project_star
        project_star = {}
        if 'project_star' in select_arr and select_arr['project_star']:
            for item in select_arr['project_star']:
                project_star[item['paras_value']] = item['paras_desc']
        
        # 处理project_type
        project_type = {}
        if 'project_type' in select_arr and select_arr['project_type']:
            for item in select_arr['project_type']:
                project_type[item['paras_value']] = item['paras_desc']
        
        # 处理yes_or_no
        yes_or_no = {}
        if 'yes_or_no' in select_arr and select_arr['yes_or_no']:
            for item in select_arr['yes_or_no']:
                yes_or_no[item['paras_value']] = item['paras_desc']
        
        return render_template('project_review/list.html',
                             select_arr=select_arr,
                             project_star=project_star,
                             project_type=project_type,
                             yes_or_no=yes_or_no,
                             user_bu=user_bu,
                             user_id=user_id,
                             current_tab=tab)
    except Exception as e:
        return f'<html><body><h1>错误</h1><p>{str(e)}</p></body></html>', 500

@project_review_bp.route('/add')
@login_required
def add():
    """
    新增立项申请页面
    对应原PHP的add.php
    """
    try:
        user_id = get_login_user_id()
        dept_id = get_login_dept_id()
        user_name = get_login_user_name()
        
        # 获取数据模型
        data_model = DataModel()
        
        # 获取通用参数
        select_param = {
            'is_used': '1',
            'type': 'yes_or_no,project_type,project_level,product_detail_type'
        }
        select_arr = data_model.get_common_param(select_param)
        
        # 处理product_detail_type
        product_detail_types = []
        if 'product_detail_type' in select_arr and select_arr['product_detail_type']:
            for item in select_arr['product_detail_type']:
                product_detail_types.append(item['paras_value'])
        
        # 获取客户列表
        customer_param = {'user_id': user_id}
        customer_select = data_model.get_customer_list(customer_param)
        
        # 获取临时项目代号
        project_code_param = {'user_id': user_id}
        project_code_select = data_model.get_temp_code(project_code_param)
        
        # 获取用户公司
        user_bu = session.get('LOGIN_USER_BU', '')
        
        # 判断是否为项目经理
        if_project_manager = data_model.verify_project_manager(user_id)
        
        return render_template('project_review/add.html',
                             select_arr=select_arr,
                             product_detail_types=product_detail_types,
                             customer_select=customer_select,
                             project_code_select=project_code_select,
                             user_bu=user_bu,
                             user_id=user_id,
                             user_name=user_name,
                             dept_id=dept_id,
                             if_project_manager=if_project_manager,
                             current_time=datetime.now().strftime('%Y-%m-%d %H:%M:%S'))
    except Exception as e:
        return f'<html><body><h1>错误</h1><p>{str(e)}</p></body></html>', 500

@project_review_bp.route('/detail')
@login_required
def detail():
    """
    立项申请详情页
    对应原PHP的detail.php
    """
    try:
        form_id = request.args.get('id', '')
        if not form_id:
            return redirect(url_for('project_review.list_page'))
        
        user_id = get_login_user_id()
        step = request.args.get('step', '')
        operate = request.args.get('operate', 'detail')
        
        # 查询立项申请数据
        service = ProjectReviewService()
        data = service.get_project_review_detail(form_id)
        
        if not data or not data.get('main'):
            return redirect(url_for('project_review.list_page'))
        
        main = data['main']
        customer = data.get('customer', {})
        project = data.get('project', {})
        project_witlink = data.get('projectWitlink', {})
        project_detail = data.get('projectDetail', {})
        bidding = data.get('bidding', {})
        
        # 获取数据模型
        data_model = DataModel()
        
        # 获取通用参数
        select_param = {
            'is_used': '1',
            'type': 'project_star,check_opinion,project_type,yes_or_no,product_detail_type'
        }
        select_arr = data_model.get_common_param(select_param)
        
        # 处理product_detail_type
        product_detail_types = []
        if 'product_detail_type' in select_arr and select_arr['product_detail_type']:
            for item in select_arr['product_detail_type']:
                product_detail_types.append(item['paras_value'])
        
        # 处理project_star
        project_star = {}
        if 'project_star' in select_arr and select_arr['project_star']:
            for item in select_arr['project_star']:
                project_star[item['paras_value']] = item['paras_desc']
        
        # 处理check_opinion
        check_opinion = {}
        if 'check_opinion' in select_arr and select_arr['check_opinion']:
            for item in select_arr['check_opinion']:
                check_opinion[item['paras_value']] = item
        
        # 处理project_type
        project_type = {}
        if 'project_type' in select_arr and select_arr['project_type']:
            for item in select_arr['project_type']:
                project_type[item['paras_value']] = item['paras_desc']
        
        # 处理yes_or_no
        yes_or_no = {}
        if 'yes_or_no' in select_arr and select_arr['yes_or_no']:
            for item in select_arr['yes_or_no']:
                yes_or_no[item['paras_value']] = item['paras_desc']
        
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
        cur_operate = ''
        if step and flow_arr:
            cur_operate = flow_arr.get(step, {}).get('operate', '')
        
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
        
        # 获取退回和通过记录
        back_record = process.find_back_record({'form_id': form_id})
        pass_record = process.find_pass_record({'form_id': form_id})
        
        # 获取变更记录
        change_data = data_model.get_new_change(form_id, 'project_review')
        
        # 获取客户列表（用于修改时选择）
        customer_param = {'user_id': user_id}
        customer_select = data_model.get_customer_list(customer_param)
        
        return render_template('project_review/detail.html',
                             main=main,
                             customer=customer,
                             project=project,
                             project_witlink=project_witlink,
                             project_detail=project_detail,
                             bidding=bidding,
                             select_arr=select_arr,
                             product_detail_types=product_detail_types,
                             project_star=project_star,
                             check_opinion=check_opinion,
                             project_type=project_type,
                             yes_or_no=yes_or_no,
                             approve_data=approve_data,
                             flow_arr=flow_arr,
                             step=step,
                             operate=operate,
                             cur_operate=cur_operate,
                             approve_info=approve_info,
                             back_record=back_record,
                             pass_record=pass_record,
                             change_data=change_data,
                             customer_select=customer_select)
    except Exception as e:
        return f'<html><body><h1>错误</h1><p>{str(e)}</p></body></html>', 500

@project_review_bp.route('/change')
@login_required
def change():
    """
    变更立项申请页面（包括星级变更和普通变更）
    对应原PHP的change.php
    """
    try:
        form_id = request.args.get('id', '')
        change_star = request.args.get('changeStar', '')
        
        if not form_id:
            return redirect(url_for('project_review.list_page'))
        
        user_id = get_login_user_id()
        dept_id = get_login_dept_id()
        user_name = get_login_user_name()
        
        # 查询立项申请数据
        service = ProjectReviewService()
        data = service.get_project_review_detail(form_id)
        
        if not data or not data.get('main'):
            return redirect(url_for('project_review.list_page'))
        
        main = data['main']
        customer = data.get('customer', {})
        project = data.get('project', {})
        project_witlink = data.get('projectWitlink', {})
        project_detail = data.get('projectDetail', {})
        bidding = data.get('bidding', {})
        
        # 获取数据模型
        data_model = DataModel()
        
        # 获取通用参数
        select_param = {
            'is_used': '1',
            'type': 'yes_or_no,project_type,project_level,product_detail_type,project_star'
        }
        select_arr = data_model.get_common_param(select_param)
        
        # 处理product_detail_type
        product_detail_types = []
        if 'product_detail_type' in select_arr and select_arr['product_detail_type']:
            for item in select_arr['product_detail_type']:
                product_detail_types.append(item['paras_value'])
        
        # 获取客户列表
        customer_param = {'user_id': user_id}
        customer_select = data_model.get_customer_list(customer_param)
        
        # 获取用户公司
        user_bu = session.get('LOGIN_USER_BU', '')
        
        return render_template('project_review/change.html',
                             main=main,
                             customer=customer,
                             project=project,
                             project_witlink=project_witlink,
                             project_detail=project_detail,
                             bidding=bidding,
                             select_arr=select_arr,
                             product_detail_types=product_detail_types,
                             customer_select=customer_select,
                             user_bu=user_bu,
                             user_id=user_id,
                             user_name=user_name,
                             dept_id=dept_id,
                             change_star=change_star,
                             related_id=form_id)
    except Exception as e:
        return f'<html><body><h1>错误</h1><p>{str(e)}</p></body></html>', 500

@project_review_bp.route('/modify')
@login_required
def modify():
    """
    修改立项申请页面
    对应原PHP的modify.php
    """
    try:
        form_id = request.args.get('id', '')
        if not form_id:
            return redirect(url_for('project_review.list_page'))
        
        user_id = get_login_user_id()
        dept_id = get_login_dept_id()
        user_name = get_login_user_name()
        
        # 查询立项申请数据
        service = ProjectReviewService()
        data = service.get_project_review_detail(form_id)
        
        if not data or not data.get('main'):
            return redirect(url_for('project_review.list_page'))
        
        main = data['main']
        customer = data.get('customer', {})
        project = data.get('project', {})
        project_witlink = data.get('projectWitlink', {})
        project_detail = data.get('projectDetail', {})
        bidding = data.get('bidding', {})
        
        # 获取数据模型
        data_model = DataModel()
        
        # 获取通用参数
        select_param = {
            'is_used': '1',
            'type': 'yes_or_no,project_type,project_level,product_detail_type'
        }
        select_arr = data_model.get_common_param(select_param)
        
        # 处理product_detail_type
        product_detail_types = []
        if 'product_detail_type' in select_arr and select_arr['product_detail_type']:
            for item in select_arr['product_detail_type']:
                product_detail_types.append(item['paras_value'])
        
        # 获取客户列表
        customer_param = {'user_id': user_id}
        customer_select = data_model.get_customer_list(customer_param)
        
        # 获取用户公司
        user_bu = session.get('LOGIN_USER_BU', '')
        
        return render_template('project_review/modify.html',
                             main=main,
                             customer=customer,
                             project=project,
                             project_witlink=project_witlink,
                             project_detail=project_detail,
                             bidding=bidding,
                             select_arr=select_arr,
                             product_detail_types=product_detail_types,
                             customer_select=customer_select,
                             user_bu=user_bu,
                             user_id=user_id,
                             user_name=user_name,
                             dept_id=dept_id)
    except Exception as e:
        return f'<html><body><h1>错误</h1><p>{str(e)}</p></body></html>', 500

@project_review_bp.route('/query_page', methods=['GET', 'POST'])
@login_required
def query_page():
    """
    查询接口（AJAX）
    对应原PHP的query_page.php
    支持标签页切换：根据type和change_star参数过滤不同类型的数据
    """
    try:
        params = get_param_to_dict(request)
        
        # 设置默认值
        params.setdefault('page', 1)
        params.setdefault('page_size', 10)
        
        # 添加用户信息
        params['user_id'] = get_login_user_id()
        params['user_bu'] = session.get('LOGIN_USER_BU', '')
        
        # 根据type和change_star参数设置过滤条件
        # list标签页: type = 'project_review'
        # change标签页: type = 'project_review_change' and change_star != '1'
        # star标签页: type = 'project_review_change' and change_star = '1'
        params['type'] = params.get('type', 'project_review')
        params['change_star'] = params.get('change_star', '')
        
        service = ProjectReviewService()
        result = service.get_project_review_list(params)
        
        return jsonify(ResponseCode.success(result))
    except Exception as e:
        return jsonify(ResponseCode.failed(False, str(e)))

@project_review_bp.route('/action', methods=['GET', 'POST'])
@login_required
def action():
    """
    统一操作接口（AJAX）
    对应原PHP的action.php
    """
    try:
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
        params['dept_name'] = session.get('LOGIN_DEPT_NAME', '')
        params['user_bu'] = session.get('LOGIN_USER_BU', '')
        params['create_user_bu'] = session.get('LOGIN_USER_BU', '')
        params['type'] = 'project_review'
        params['status'] = 'T'  # 待提交
        
        service = ProjectReviewService()
        result = None
        
        # 根据action类型执行相应操作
        if action_type == 'Add':
            result = service.create_project_review(params)
            result = ResponseCode.success(params.get('form_id')) if result else ResponseCode.failed(False, '添加失败')
        
        elif action_type == 'Submit':
            # 提交时更新状态为审核中
            params['status'] = 'P'
            result = service.update_project_review(params)
            result = ResponseCode.success(params.get('form_id')) if result else ResponseCode.failed(False, '提交失败')
        
        elif action_type == 'Save':
            result = service.update_project_review(params)
            result = ResponseCode.success(params.get('form_id')) if result else ResponseCode.failed(False, '保存失败')
        
        elif action_type == 'Modify':
            result = service.update_project_review(params)
            result = ResponseCode.success(params.get('form_id')) if result else ResponseCode.failed(False, '修改失败')
        
        elif action_type == 'Change':
            # 变更提交（生成新的form_id，复制数据，状态为P）
            params['status'] = 'P'
            result = service.create_change(params)
            result = ResponseCode.success(params.get('form_id')) if result else ResponseCode.failed(False, '变更提交失败')
        
        elif action_type == 'ChangeSave':
            # 变更保存（生成新的form_id，复制数据，状态为T）
            params['status'] = 'T'
            result = service.create_change(params)
            result = ResponseCode.success(params.get('form_id')) if result else ResponseCode.failed(False, '变更保存失败')
        
        elif action_type == 'Delete':
            form_id = params.get('id') or params.get('form_id', '')
            if not form_id:
                return jsonify(ResponseCode.failed(False, '缺少form_id参数'))
            result = service.delete_project_review(form_id)
            result = ResponseCode.success(True) if result else ResponseCode.failed(False, '删除失败')
        
        elif action_type == 'Retrieve':
            # 获取数据
            data_type = params.get('dataType', '')
            if data_type == 'getCustomerDetail':
                # 获取客户详情
                from app.models.customer import Customer
                customer_id = params.get('id', '')
                customer = Customer.query_by_primary_id(int(customer_id)) if customer_id.isdigit() else {}
                return jsonify(ResponseCode.success(customer))
            elif data_type == 'verifyProjectCode':
                # 验证项目代号
                project_code = params.get('projectCode', '')
                user_bu = params.get('user_bu', '')
                form_id = params.get('form_id', '')
                result = service.verify_project_code(project_code, user_bu, form_id)
                return jsonify(result)
            else:
                return jsonify(ResponseCode.failed(False, f'不支持的数据类型: {data_type}'))
        
        else:
            result = ResponseCode.failed(False, f'不支持的操作类型: {action_type}')
        
        return jsonify(result)
    except Exception as e:
        return jsonify(ResponseCode.failed(False, str(e)))

