"""
项目代号业务逻辑服务
对应原PHP项目的ActionModel.php中的业务逻辑
"""
from datetime import datetime
from app.models.project_code import ProjectCode
from app.utils.auth import get_login_user_id, get_login_user_name, get_login_dept_id
from app.utils.response import ResponseCode

class ProjectCodeService:
    """
    项目代号业务逻辑服务类
    对应原PHP的ActionModel类
    """
    
    SYSTEM_ID = "temp_code"
    
    # 项目范围到代码前缀的映射
    NAME_RULE = {
        'D': 'A',  # 国内项目 -> A
        'F': 'B',  # 国外项目 -> B
    }
    
    def query_list(self, param, user_id=None, dept_id=None):
        """
        查询项目代号列表
        对应原PHP的get_data.php和query_page.php
        
        Args:
            param (dict): 查询参数字典
            user_id (str): 当前用户ID（可选）
            dept_id (int): 当前用户部门ID（可选）
            
        Returns:
            dict: 包含code, msg, count, data的字典
        """
        if user_id is None:
            user_id = get_login_user_id()
        if dept_id is None:
            dept_id = get_login_dept_id()
        
        data, total_count = ProjectCode.query_list(param, user_id, dept_id)
        
        # 获取公司名称映射
        from app.common.data_model import DataModel
        data_model = DataModel()
        select_param = {
            'is_used': '1',
            'type': 'company'
        }
        select_arr = data_model.get_common_param(select_param)
        company_map = {}
        if 'company' in select_arr:
            for item in select_arr['company']:
                company_map[item['paras_value']] = item['paras_desc']
        
        # 处理数据，添加公司名称
        for item in data:
            company_code = item.get('company', '')
            if company_code and company_code in company_map:
                item['company_name'] = company_map[company_code]
            else:
                item['company_name'] = company_code
        
        return {
            "code": 0,
            "msg": "",
            "count": total_count,
            "data": data
        }
    
    def query_by_id(self, project_code_id):
        """
        根据ID查询项目代号信息
        
        Args:
            project_code_id (int): 项目代号主键ID
            
        Returns:
            dict: 项目代号信息字典
        """
        return ProjectCode.query_by_id(project_code_id)
    
    def make_temp_code(self, scope_code):
        """
        生成临时项目代号
        对应原PHP的ActionModel::makeTempCode()
        
        Args:
            scope_code (str): 项目范围代码 ('F' 或 'D')
            
        Returns:
            str: 生成的项目代号，格式如 B20240001 (无连字符)
        """
        from app.utils.db import Database
        from app.utils.sql_validator import sanitize_field_name, sanitize_table_name
        
        # 获取对应的流程类型
        flow_type = self.NAME_RULE.get(scope_code, 'B')
        
        # 按年重置，格式如 B20240001
        year_prefix = datetime.now().strftime('%Y')
        
        # 验证表名和字段名
        sanitize_table_name('inhe_temp_code')
        sanitize_field_name('temp_code')
        
        # 查询当前年份的最大代号（格式：B2024xxxx）
        # 使用LIKE查询匹配 flow_type + year_prefix 开头的代码
        pattern = f'{flow_type}{year_prefix}%'
        sql = "SELECT MAX(temp_code) AS max_id FROM inhe_temp_code WHERE temp_code LIKE %s"
        result = Database.execute_query(sql, (pattern,))
        
        max_id = result[0]['max_id'] if result and result[0].get('max_id') else None
        
        if max_id:
            # 提取序号并加1
            # max_id格式: B20240001，需要提取后4位数字
            try:
                # 去掉前缀（flow_type + year_prefix），获取序号部分
                prefix_len = len(flow_type) + len(year_prefix)
                if len(max_id) > prefix_len:
                    seq_str = max_id[prefix_len:]
                    seq = int(seq_str)
                    seq += 1
                else:
                    seq = 1
            except (ValueError, IndexError, AttributeError):
                # 如果解析失败，从1开始
                seq = 1
        else:
            seq = 1
        
        # 生成新的项目代号，格式: B20240001 (无连字符)
        new_code = f'{flow_type}{year_prefix}{str(seq).zfill(4)}'
        return new_code
    
    def add(self, param):
        """
        添加项目代号
        对应原PHP的ActionModel::add()
        
        Args:
            param (dict): 参数字典
            
        Returns:
            dict: 返回结果
        """
        # 生成项目代号
        scope_code = param.get('scope_code', 'F')
        new_code = self.make_temp_code(scope_code)
        
        # 验证代号是否已存在
        if ProjectCode.verify_code_exists(new_code):
            # 如果已存在，尝试重新生成（最多尝试10次）
            for i in range(10):
                new_code = self.make_temp_code(scope_code)
                if not ProjectCode.verify_code_exists(new_code):
                    break
            else:
                return ResponseCode.failed(False, '生成项目代号失败，请稍后重试')
        
        # 准备插入参数
        user_id = param.get('user_id') or get_login_user_id()
        user_name = param.get('user_name') or get_login_user_name()
        dept_id = param.get('dept_id') or get_login_dept_id()
        
        # 获取用户公司
        from app.utils.db import Database
        company_sql = "SELECT user_bu FROM department WHERE dept_id = %s"
        company_result = Database.execute_query(company_sql, (dept_id,))
        company = company_result[0]['user_bu'] if company_result else ''
        
        insert_param = {
            'temp_code': new_code,
            'project_name': param.get('project_name'),
            'location': param.get('location'),
            'scope_code': scope_code,
            'remark': param.get('remark', ''),
            'dept_id': dept_id,
            'user_id': user_id,
            'user_name': user_name,
            'create_time': datetime.now().strftime('%Y-%m-%d %H:%M:%S'),
            'company': company
        }
        
        # 插入数据
        project_code_id = ProjectCode.add(insert_param)
        
        if project_code_id > 0:
            return ResponseCode.success(True)
        else:
            return ResponseCode.failed(False, '添加项目代号失败')
    
    def submit(self, param):
        """
        提交项目代号（添加）
        对应原PHP的ActionModel::submit()
        
        Args:
            param (dict): 参数字典
            
        Returns:
            dict: 返回结果
        """
        return self.add(param)
    
    def verify_code_exists(self, temp_code):
        """
        验证项目代号是否已存在
        
        Args:
            temp_code (str): 项目代号
            
        Returns:
            bool: True表示已存在，False表示不存在
        """
        return ProjectCode.verify_code_exists(temp_code)

