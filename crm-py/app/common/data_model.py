"""
数据查询模型
对应原PHP项目的DataModel.php
"""
from app.utils.db import Database
from app.utils.common import get_param_to_dict

class DataModel:
    """
    数据查询模型类
    对应原PHP的DataModel类
    """
    
    def get_param_to_dict(self, request):
        """
        获取请求参数
        对应原PHP的DataModel::getParamToArray()
        
        Args:
            request: Flask request对象
            
        Returns:
            dict: 参数字典
        """
        return get_param_to_dict(request)
    
    def get_continent_data(self):
        """
        获取大洲数据
        对应原PHP的DataModel::getContinentData()
        
        Returns:
            list: 大洲数据列表
        """
        sql = "SELECT * FROM continent_code ORDER BY iso_two"
        return Database.execute_query(sql)
    
    def get_country_data(self, continent=None, zh_name=None):
        """
        获取国家数据
        对应原PHP的DataModel::getCountryData()
        
        Args:
            continent (str): 大洲代码
            zh_name (str): 中文名称（模糊查询）
            
        Returns:
            list: 国家数据列表
        """
        where_clauses = ["1=1"]
        params = []
        
        if continent:
            where_clauses.append("k.continent = %s")
            params.append(continent)
        
        if zh_name:
            where_clauses.append("k.zh_name LIKE %s")
            params.append(f'%{zh_name}%')
        
        sql = f"SELECT DISTINCT k.* FROM country_code k WHERE {' AND '.join(where_clauses)} ORDER BY k.continent, k.iso_three"
        return Database.execute_query(sql, tuple(params))
    
    def get_common_param(self, param):
        """
        获取通用参数数据
        对应原PHP的DataModel::getCommonParam()
        
        Args:
            param (dict): 参数字典，包含type, is_used, paras_group等
            
        Returns:
            dict: 按type分组的参数字典
        """
        where_clauses = ["1=1"]
        params = []
        
        if param.get('paras_group'):
            where_clauses.append("f.paras_group = %s")
            params.append(param['paras_group'])
        
        if param.get('type'):
            # 支持多个type，用逗号分隔
            type_list = [t.strip() for t in param['type'].split(',')]
            placeholders = ','.join(['%s'] * len(type_list))
            where_clauses.append(f"f.type IN ({placeholders})")
            params.extend(type_list)
        
        if param.get('type_desc'):
            where_clauses.append("f.type_desc LIKE %s")
            params.append(f"%{param['type_desc']}%")
        
        if param.get('bz'):
            where_clauses.append("f.bz = %s")
            params.append(param['bz'])
        
        if param.get('paras_desc'):
            where_clauses.append("f.paras_desc = %s")
            params.append(param['paras_desc'])
        
        if param.get('paras_value'):
            where_clauses.append("f.paras_value = %s")
            params.append(param['paras_value'])
        
        if param.get('is_used'):
            where_clauses.append("f.is_used = %s")
            params.append(param['is_used'])
        
        if param.get('user_bu'):
            where_clauses.append("f.user_bu = %s")
            params.append(param['user_bu'])
        
        sql = f"SELECT f.* FROM inhe_oa_paras f WHERE {' AND '.join(where_clauses)} ORDER BY sort_code"
        result_list = Database.execute_query(sql, tuple(params))
        
        # 按type分组
        result_dict = {}
        for item in result_list:
            type_key = item.get('type', '')
            if type_key not in result_dict:
                result_dict[type_key] = []
            result_dict[type_key].append(item)
        
        return result_dict
    
    def get_new_change(self, form_id, form_type):
        """
        获取最新变更记录
        对应原PHP的DataModel::getNewChange()
        
        Args:
            form_id (str): 表单ID
            form_type (str): 表单类型
            
        Returns:
            dict: 变更记录
        """
        sql = """
            SELECT * FROM inhe_customer_data 
            WHERE related_id = %s AND type = %s 
            ORDER BY version DESC 
            LIMIT 1
        """
        result = Database.execute_query(sql, (form_id, f"{form_type}_change"))
        return result[0] if result else {}
    
    def get_last_change(self, form_id, version, change_type):
        """
        获取上一次变更记录
        对应原PHP的DataModel::getLastChange()
        
        Args:
            form_id (str): 表单ID
            version (int): 版本号
            change_type (str): 变更类型
            
        Returns:
            dict: 变更记录
        """
        sql = """
            SELECT * FROM inhe_customer_data 
            WHERE related_id = %s AND type = %s AND version = %s
            LIMIT 1
        """
        result = Database.execute_query(sql, (form_id, change_type, version))
        return result[0] if result else {}
    
    def get_customer_list(self, param):
        """
        获取客户列表（用于下拉选择）
        对应原PHP的DataModel::getCustomerList()
        
        Args:
            param (dict): 参数字典，包含user_id等
            
        Returns:
            dict: 客户ID到客户名称的字典映射
        """
        user_id = param.get('user_id', '')
        if not user_id:
            return {}
        
        customer_dict = {}
        
        # 1. 查询用户自己创建的客户
        sql1 = """
            SELECT k.id, k.customer_name, k.version, k.form_id 
            FROM inhe_customer_data k 
            WHERE 1=1 
            AND k.status IN ('R', 'Y') 
            AND k.user_id = %s 
            AND k.type = 'customer_data'
            ORDER BY k.id DESC
        """
        result1 = Database.execute_query(sql1, (user_id,))
        
        for item in result1:
            customer_id = item['id']
            customer_name = item['customer_name']
            version = item.get('version', 0)
            form_id = item.get('form_id', '')
            
            # 如果有版本号，查询变更记录
            if version > 0:
                change_sql = """
                    SELECT k.id, k.customer_name, k.version 
                    FROM inhe_customer_data k 
                    WHERE 1=1 
                    AND k.type = 'customer_data_change' 
                    AND k.status IN ('R', 'Y') 
                    AND k.version = %s 
                    AND k.related_id = %s
                """
                change_result = Database.execute_query(change_sql, (version, form_id))
                if change_result:
                    item = change_result[0]
                    customer_id = item['id']
                    customer_name = item['customer_name']
            
            customer_dict[str(customer_id)] = customer_name
        
        # 2. 查询分享给该用户的客户
        sql2 = """
            SELECT DISTINCT k.id, k.customer_name, k.version, k.form_id 
            FROM inhe_customer_share a 
            JOIN inhe_customer_data k ON a.customer_id = k.id 
            WHERE a.user_ids = %s
        """
        result2 = Database.execute_query(sql2, (user_id,))
        
        for item in result2:
            customer_id = item['id']
            customer_name = item['customer_name']
            version = item.get('version', 0)
            form_id = item.get('form_id', '')
            
            # 如果有版本号，查询变更记录
            if version > 0:
                change_sql = """
                    SELECT k.id, k.customer_name, k.version 
                    FROM inhe_customer_data k 
                    WHERE 1=1 
                    AND k.type = 'customer_data_change' 
                    AND k.status IN ('R', 'Y') 
                    AND k.version = %s 
                    AND k.related_id = %s
                """
                change_result = Database.execute_query(change_sql, (version, form_id))
                if change_result:
                    item = change_result[0]
                    customer_id = item['id']
                    customer_name = item['customer_name']
            
            customer_dict[str(customer_id)] = customer_name
        
        return customer_dict
    
    def verify_project_manager(self, user_id):
        """
        验证用户是否为项目经理
        对应原PHP的DataModel::verifyProjectManger()
        
        Args:
            user_id (str): 用户ID
            
        Returns:
            bool: True表示是项目经理，False表示不是
        """
        sql = """
            SELECT COUNT(*) AS cnt 
            FROM inhe_oa_paras 
            WHERE type = 'project_manager' 
            AND paras_value = %s 
            AND is_used = '1'
        """
        result = Database.execute_query(sql, (user_id,))
        return result[0]['cnt'] > 0 if result else False
    
    def get_temp_code(self, param):
        """
        获取临时项目代码列表
        对应原PHP的DataModel::getTempCode()
        
        Args:
            param (dict): 参数字典，包含user_id, temp_code等
            
        Returns:
            list: 临时项目代码列表
        """
        where_clauses = ["1=1"]
        params = []
        
        if param.get('temp_code'):
            where_clauses.append("m.temp_code = %s")
            params.append(param['temp_code'])
        
        if param.get('user_id'):
            where_clauses.append("m.user_id = %s")
            params.append(param['user_id'])
        
        sql = f"""
            SELECT DISTINCT m.*, d.dept_name AS realDeptName, c.company AS company_desc 
            FROM inhe_temp_code m 
            LEFT JOIN department d ON d.dept_id = m.dept_id 
            LEFT JOIN company c ON c.user_bu = m.company 
            WHERE {' AND '.join(where_clauses)}
        """
        result = Database.execute_query(sql, tuple(params))
        
        # 处理部门名称（如果有realDeptName则使用，否则使用原dept_name）
        for item in result:
            if item.get('realDeptName'):
                item['dept_name'] = item['realDeptName']
        
        return result
    
    def get_customer_list2(self):
        """
        获取客户列表（所有客户，用于详情页选择）
        对应原PHP的DataModel::getCustomerList2()
        
        Returns:
            dict: 客户ID到客户名称的字典映射
        """
        sql = """
            SELECT k.id, k.customer_name 
            FROM inhe_customer_data k 
            ORDER BY k.customer_name
        """
        result = Database.execute_query(sql)
        
        # 转换为字典格式
        customer_dict = {}
        for item in result:
            customer_dict[str(item['id'])] = item.get('customer_name', '')
        
        return customer_dict



