"""
通用列表控制器
提供通用的选择弹窗功能
"""
from flask import Blueprint, render_template, request
from app.utils.auth import login_required, get_login_user_id, get_login_dept_id
from app.utils.db import Database

common_list_bp = Blueprint('common_list', __name__)

@common_list_bp.route('/list/single_type_list')
@login_required
def single_type_list():
    """
    单选人员列表弹窗
    用于业务归属等字段选择人员
    """
    dom_id = request.args.get('domId', '')
    return render_template('common/templates/common/single_type_list.html',
                         dom_id=dom_id)

