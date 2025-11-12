"""
立项申请业务逻辑服务
对应原PHP项目的ActionModel.php中的业务逻辑
"""
from datetime import datetime
from app.models.project_review import ProjectReview
from app.models.project_customer_info import ProjectCustomerInfo
from app.models.project_info import ProjectInfo
from app.models.project_detail import ProjectDetail
from app.models.bidding_strategy import BiddingStrategy
from app.common.form_model import FormModel
from app.utils.auth import get_login_user_id, get_login_user_name, get_login_dept_id
from app.utils.response import ResponseCode
from app.utils.db import Database

class ProjectReviewService:
    """
    立项申请业务逻辑服务类
    对应原PHP的ActionModel类
    """
    
    SYSTEM_ID = "project_review"
    
    def query_by_id(self, param):
        """
        根据ID查询立项申请完整信息
        对应原PHP的ActionModel::queryById()
        
        Args:
            param (dict): 参数字典，包含id（form_id）
            
        Returns:
            dict: 包含main, customer, project, projectWitlink, projectDetail, bidding的字典
        """
        form_id = param.get('id', '')
        if not form_id:
            return {
                'main': {},
                'customer': {},
                'project': {},
                'projectWitlink': {},
                'projectDetail': {},
                'bidding': {}
            }
        
        # 查询主表
        main = ProjectReview.query_by_form_id(form_id)
        if not main:
            return {
                'main': {},
                'customer': {},
                'project': {},
                'projectWitlink': {},
                'projectDetail': {},
                'bidding': {}
            }
        
        user_bu = main.get('user_bu', '')
        
        # 查询客户信息
        customer = ProjectCustomerInfo.query_by_form_id(form_id)
        
        # 查询项目信息
        project = ProjectInfo.query_by_form_id(form_id)
        
        # 查询慧软项目信息（如果是WITLINK公司）
        project_witlink = {}
        if user_bu == 'WITLINK':
            project_witlink = ProjectInfo.query_witlink_by_form_id(form_id)
        
        # 查询项目产品明细
        project_detail = ProjectDetail.query_by_form_id(form_id, user_bu)
        
        # 查询投标策略
        bidding = BiddingStrategy.query_by_form_id(form_id)
        
        return {
            'main': main,
            'customer': customer,
            'project': project,
            'projectWitlink': project_witlink,
            'projectDetail': project_detail,
            'bidding': bidding
        }
    
    def create_project_review(self, param):
        """
        创建立项申请
        对应原PHP的ActionModel::add()
        
        Args:
            param (dict): 立项申请参数字典
            
        Returns:
            bool: 创建是否成功
        """
        try:
            # 生成form_id
            form_model = FormModel()
            form_id_param = {
                'fieldName': 'form_id',
                'tableName': 'inhe_project_main',
                'flowType': 'lxdj',
                'reset': 'day',
                'digit': '2'
            }
            form_id = form_model.create_flow_id(form_id_param)
            if not form_id:
                return False
            
            param['form_id'] = form_id
            
            # 添加主表
            main_id = ProjectReview.add_main(param)
            if main_id <= 0:
                return False
            
            # 添加客户信息
            ProjectCustomerInfo.add_customer_info(param)
            
            # 添加项目信息
            ProjectInfo.add_project_info(param)
            
            # 添加慧软项目信息（如果是WITLINK）
            if param.get('user_bu') == 'WITLINK':
                ProjectInfo.add_witlink_project_info(param)
            
            # 添加项目产品明细
            ProjectDetail.add_project_detail(param)
            
            # 添加投标策略（如果是招标项目）
            if param.get('is_bidding_project') == '01':
                BiddingStrategy.add_bidding_strategy(param)
            
            # 处理附件（如果有）
            attach_arr = param.get('attach', [])
            if attach_arr and isinstance(attach_arr, list):
                # TODO: 实现附件更新逻辑
                pass
            
            return True
        except Exception as e:
            print(f"创建立项申请错误: {str(e)}")
            return False
    
    def update_project_review(self, param):
        """
        更新立项申请
        对应原PHP的ActionModel::update()
        
        Args:
            param (dict): 更新参数字典
            
        Returns:
            bool: 更新是否成功
        """
        try:
            form_id = param.get('form_id', '')
            if not form_id:
                return False
            
            # 更新主表
            ProjectReview.update_main(param, form_id)
            
            # 更新客户信息
            ProjectCustomerInfo.update_customer_info(param, form_id)
            
            # 更新项目信息
            ProjectInfo.update_project_info(param, form_id)
            
            # 更新慧软项目信息（如果是WITLINK）
            if param.get('user_bu') == 'WITLINK':
                ProjectInfo.update_witlink_project_info(param, form_id)
            
            # 更新项目产品明细
            ProjectDetail.update_project_detail(param, form_id)
            
            # 更新投标策略（如果是招标项目）
            if param.get('is_bidding_project') == '01':
                # 先检查是否存在，不存在则新增
                existing = BiddingStrategy.query_by_form_id(form_id)
                if existing:
                    BiddingStrategy.update_bidding_strategy(param, form_id)
                else:
                    BiddingStrategy.add_bidding_strategy(param)
            else:
                # 如果不是招标项目，删除投标策略
                BiddingStrategy.delete_by_form_id(form_id)
            
            # 处理附件（如果有）
            attach_arr = param.get('attach', [])
            if attach_arr and isinstance(attach_arr, list):
                # TODO: 实现附件更新逻辑
                pass
            
            return True
        except Exception as e:
            print(f"更新立项申请错误: {str(e)}")
            return False
    
    def get_project_review_detail(self, form_id):
        """
        获取立项申请详情
        对应原PHP的ActionModel::queryById()
        
        Args:
            form_id (str): 表单ID
            
        Returns:
            dict: 立项申请详情字典
        """
        return self.query_by_id({'id': form_id})
    
    def get_project_review_list(self, param):
        """
        获取立项申请列表
        支持分页、筛选、排序
        支持标签页切换：list(立项申请表), change(立项申请变更), star(星级申请变更)
        
        Args:
            param (dict): 查询参数字典
            
        Returns:
            dict: 包含list和total的字典
        """
        where_clauses = ["1=1"]
        params = []
        
        # 根据type和change_star参数过滤不同类型的数据
        # list标签页: type = 'project_review'
        # change标签页: type = 'project_review_change' and change_star != '1'
        # star标签页: type = 'project_review_change' and change_star = '1'
        if param.get('type'):
            where_clauses.append("k.type = %s")
            params.append(param['type'])
            
            # 如果是变更类型，需要根据change_star区分"立项申请变更"和"星级申请变更"
            if param.get('type') == 'project_review_change':
                if param.get('change_star') == '1':
                    # 星级申请变更
                    where_clauses.append("k.change_star = %s")
                    params.append('1')
                else:
                    # 立项申请变更（排除星级变更）
                    where_clauses.append("(k.change_star IS NULL OR k.change_star != %s)")
                    params.append('1')
        
        # 筛选条件
        if param.get('form_id'):
            where_clauses.append("k.form_id = %s")
            params.append(param['form_id'])
        
        if param.get('project_code'):
            where_clauses.append("k.project_code LIKE %s")
            params.append(f"%{param['project_code']}%")
        
        if param.get('title'):
            where_clauses.append("k.title LIKE %s")
            params.append(f"%{param['title']}%")
        
        if param.get('status'):
            where_clauses.append("k.status = %s")
            params.append(param['status'])
        
        if param.get('user_bu'):
            where_clauses.append("k.user_bu = %s")
            params.append(param['user_bu'])
        
        if param.get('user_id'):
            where_clauses.append("k.user_id = %s")
            params.append(param['user_id'])
        
        # 排序
        order_by = "ORDER BY k.write_time DESC"
        if param.get('sortField') and param.get('sortBy'):
            sort_field = param['sortField']
            sort_by = param['sortBy']
            # 验证字段名防止SQL注入
            from app.utils.sql_validator import sanitize_field_name
            try:
                sanitize_field_name(sort_field)
                order_by = f"ORDER BY k.{sort_field} {sort_by}"
            except:
                pass
        
        # 分页
        page = int(param.get('page', 1))
        page_size = int(param.get('page_size', 10))
        offset = (page - 1) * page_size
        
        # 查询总数
        count_sql = f"""
            SELECT COUNT(*) AS total 
            FROM inhe_project_main k 
            WHERE {' AND '.join(where_clauses)}
        """
        count_result = Database.execute_query(count_sql, tuple(params))
        total = count_result[0]['total'] if count_result else 0
        
        # 查询列表
        sql = f"""
            SELECT k.*, 
                   u.user_name AS createUserName, 
                   d.dept_name AS deptName 
            FROM inhe_project_main k 
            LEFT JOIN user u ON u.user_id = k.user_id 
            LEFT JOIN department d ON d.dept_id = k.organ_id 
            WHERE {' AND '.join(where_clauses)}
            {order_by}
            LIMIT %s OFFSET %s
        """
        params.extend([page_size, offset])
        result = Database.execute_query(sql, tuple(params))
        
        return {
            'list': result,
            'total': total,
            'page': page,
            'page_size': page_size
        }
    
    def delete_project_review(self, form_id):
        """
        删除立项申请
        对应原PHP的ActionModel::delete()
        
        Args:
            form_id (str): 表单ID
            
        Returns:
            bool: 删除是否成功
        """
        try:
            # 删除主表
            ProjectReview.delete_by_form_id(form_id)
            
            # 删除客户信息
            ProjectCustomerInfo.delete_by_form_id(form_id)
            
            # 删除项目信息
            ProjectInfo.delete_by_form_id(form_id)
            
            # 删除项目产品明细
            ProjectDetail.delete_by_form_id(form_id)
            
            # 删除投标策略
            BiddingStrategy.delete_by_form_id(form_id)
            
            # TODO: 删除附件和流程记录
            
            return True
        except Exception as e:
            print(f"删除立项申请错误: {str(e)}")
            return False
    
    def verify_project_code(self, project_code, user_bu, form_id=None):
        """
        验证项目代号是否已存在
        对应原PHP的ActionModel::verifyProjectCode()
        
        Args:
            project_code (str): 项目代号
            user_bu (str): 公司代码
            form_id (str): 表单ID（更新时使用）
            
        Returns:
            dict: 验证结果，code='200'表示已存在，code='404'表示不存在
        """
        exists = ProjectReview.verify_project_code(project_code, user_bu, form_id)
        if exists:
            return {
                'code': '200',
                'msg': '项目代号已存在',
                'data': {'code': '200'}
            }
        else:
            return {
                'code': '404',
                'msg': '项目代号不存在',
                'data': {'code': '404'}
            }
    
    def create_change(self, param):
        """
        创建变更记录（包括星级变更和普通变更）
        对应原PHP的ActionModel::changeAdd()
        
        Args:
            param (dict): 变更参数字典，包含related_id（原表单ID）
            
        Returns:
            bool: 创建是否成功
        """
        try:
            # 生成新的form_id
            form_model = FormModel()
            form_id_param = {
                'fieldName': 'form_id',
                'tableName': 'inhe_project_main',
                'flowType': 'lxdj',
                'reset': 'day',
                'digit': '2'
            }
            form_id = form_model.create_flow_id(form_id_param)
            if not form_id:
                return False
            
            param['form_id'] = form_id
            param['type'] = 'project_review_change'
            
            # 如果是星级变更，只更新主表的星级和备注
            if param.get('change_star') == '1':
                # 添加变更主表
                main_id = self._add_change_main(param)
                if main_id <= 0:
                    return False
                # 星级变更不需要复制其他表数据
                return True
            
            # 普通变更：复制所有数据
            main_id = self._add_change_main(param)
            if main_id <= 0:
                return False
            
            # 复制客户信息
            ProjectCustomerInfo.add_customer_info(param)
            
            # 复制项目信息
            ProjectInfo.add_project_info(param)
            
            # 复制慧软项目信息（如果是WITLINK）
            if param.get('user_bu') == 'WITLINK':
                ProjectInfo.add_witlink_project_info(param)
            
            # 复制项目产品明细
            ProjectDetail.add_project_detail(param)
            
            # 复制投标策略（如果是招标项目）
            if param.get('is_bidding_project') == '01':
                BiddingStrategy.add_bidding_strategy(param)
            
            return True
        except Exception as e:
            print(f"创建变更记录错误: {str(e)}")
            return False
    
    def _add_change_main(self, param):
        """
        添加变更主表数据
        对应原PHP的ActionModel::changeMainData()
        
        Args:
            param (dict): 变更参数字典
            
        Returns:
            int: 插入的ID
        """
        sql = """
            INSERT INTO inhe_project_main (
                organ_id, user_id, write_time, title, form_id, related_id,
                project_code, status, type, is_bidding_project, change_star,
                remark, user_bu, is_collective, past_pcode, create_user_bu,
                from_id, c_past_pcode
            ) VALUES (
                %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s
            )
        """
        params = (
            param.get('organ_id'),
            param.get('user_id'),
            datetime.now().strftime('%Y-%m-%d %H:%M:%S'),
            param.get('title', ''),
            param.get('form_id'),
            param.get('related_id'),
            param.get('project_code'),
            param.get('status'),
            param.get('type'),
            param.get('is_bidding_project'),
            param.get('change_star', '0'),
            param.get('remark'),
            param.get('user_bu'),
            param.get('is_collective'),
            param.get('past_pcode'),
            param.get('create_user_bu'),
            param.get('from_id'),
            param.get('c_past_pcode')
        )
        return Database.execute_insert(sql, params)

