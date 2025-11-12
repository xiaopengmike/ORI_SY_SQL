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
        where_clauses = ["1=1"]
        params = []
        
        if param.get('user_id'):
            where_clauses.append("(k.user_id = %s OR k.share_user LIKE %s)")
            params.extend([param['user_id'], f"%{param['user_id']}%"])
        
        sql = f"""
            SELECT k.id, k.customer_name 
            FROM inhe_customer_data k 
            WHERE {' AND '.join(where_clauses)}
            ORDER BY k.customer_name
        """
        result = Database.execute_query(sql, tuple(params))
        
        # 转换为字典格式
        customer_dict = {}
        for item in result:
            customer_dict[str(item['id'])] = item.get('customer_name', '')
        
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



