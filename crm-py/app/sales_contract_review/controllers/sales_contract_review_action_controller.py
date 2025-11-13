"""
销售合同评审操作控制器（AJAX接口）
对应原PHP项目的query_page.php
"""
from flask import Blueprint, request, jsonify
from app.utils.auth import login_required
from app.utils.common import get_param_to_dict
from app.common.services.query_service import QueryService
from app.utils.response import ResponseCode

sales_contract_review_action_bp = Blueprint('sales_contract_review_action', __name__)

@sales_contract_review_action_bp.route('/query', methods=['GET', 'POST'])
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
        params.setdefault('type', 'sales_contract_review')
        
        query_service = QueryService()
        result = query_service.query_sales_contract_review_list(params)
        
        return jsonify(ResponseCode.success(result))
    except Exception as e:
        print(f"查询销售合同评审列表错误: {str(e)}")
        import traceback
        traceback.print_exc()
        return jsonify(ResponseCode.failed(False, f'查询失败: {str(e)}'))

