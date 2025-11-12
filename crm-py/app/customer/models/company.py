"""
公司信息模型
对应数据库表: inhe_customer_company
对应原PHP的ActionModel::queryCompany(), addCompany(), updateCompanyData()
"""
from app.utils.db import Database

class Company:
    """
    公司信息模型类
    """
    
    @staticmethod
    def query_by_form_id(form_id):
        """
        根据form_id查询公司信息列表
        对应原PHP的ActionModel::queryCompany()
        
        Args:
            form_id (str): 表单ID
            
        Returns:
            list: 公司信息列表
        """
        sql = "SELECT * FROM inhe_customer_company WHERE form_id = %s"
        return Database.execute_query(sql, (form_id,))
    
    @staticmethod
    def add_company(param):
        """
        添加公司信息
        对应原PHP的ActionModel::addCompany()中的单条添加
        
        Args:
            param (dict): 公司信息参数字典
            
        Returns:
            int: 插入的公司ID
        """
        sql = """
            INSERT INTO inhe_customer_company (
                customer_id, form_id, contract_party, short_name, country, address, contact_name, phone
            ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
        """
        params = (
            param.get('customer_id'),
            param.get('form_id'),
            param.get('contract_party'),
            param.get('short_name'),
            param.get('country'),
            param.get('address'),
            param.get('contact_name'),
            param.get('phone')
        )
        return Database.execute_insert(sql, params)
    
    @staticmethod
    def delete_by_form_id(form_id):
        """
        根据form_id删除所有公司信息
        对应原PHP的ActionModel::delete()
        
        Args:
            form_id (str): 表单ID
            
        Returns:
            bool: 删除是否成功
        """
        sql = "DELETE FROM inhe_customer_company WHERE form_id = %s"
        Database.execute_update(sql, (form_id,))
        return True
    
    @staticmethod
    def delete_empty_by_form_id(form_id):
        """
        删除空合同方的公司信息
        对应原PHP的ActionModel::updateCompanyData()中的清理逻辑
        
        Args:
            form_id (str): 表单ID
            
        Returns:
            bool: 删除是否成功
        """
        sql = "DELETE FROM inhe_customer_company WHERE contract_party = '' AND form_id = %s"
        Database.execute_update(sql, (form_id,))
        return True



