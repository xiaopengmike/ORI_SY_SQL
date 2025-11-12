"""
联系人模型
对应数据库表: inhe_customer_contact
对应原PHP的ActionModel::queryContacts(), addContacts(), updateContactData()
"""
from app.utils.db import Database

class Contact:
    """
    联系人模型类
    """
    
    @staticmethod
    def query_by_form_id(form_id):
        """
        根据form_id查询联系人列表
        对应原PHP的ActionModel::queryContacts()
        
        Args:
            form_id (str): 表单ID
            
        Returns:
            list: 联系人列表
        """
        sql = "SELECT * FROM inhe_customer_contact WHERE form_id = %s"
        return Database.execute_query(sql, (form_id,))
    
    @staticmethod
    def add_contact(param):
        """
        添加联系人
        对应原PHP的ActionModel::addContacts()中的单条添加
        
        Args:
            param (dict): 联系人参数字典
            
        Returns:
            int: 插入的联系人ID
        """
        sql = """
            INSERT INTO inhe_customer_contact (
                customer_id, form_id, name, phone_one, phone_two, email, position, address, remark
            ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)
        """
        params = (
            param.get('customer_id'),
            param.get('form_id'),
            param.get('name'),
            param.get('phone_one'),
            param.get('phone_two'),
            param.get('email'),
            param.get('position'),
            param.get('address'),
            param.get('remark')
        )
        return Database.execute_insert(sql, params)
    
    @staticmethod
    def delete_by_form_id(form_id):
        """
        根据form_id删除所有联系人
        对应原PHP的ActionModel::delete()
        
        Args:
            form_id (str): 表单ID
            
        Returns:
            bool: 删除是否成功
        """
        sql = "DELETE FROM inhe_customer_contact WHERE form_id = %s"
        Database.execute_update(sql, (form_id,))
        return True
    
    @staticmethod
    def delete_empty_by_form_id(form_id):
        """
        删除空名称的联系人
        对应原PHP的ActionModel::updateContactData()中的清理逻辑
        
        Args:
            form_id (str): 表单ID
            
        Returns:
            bool: 删除是否成功
        """
        sql = "DELETE FROM inhe_customer_contact WHERE name = '' AND form_id = %s"
        Database.execute_update(sql, (form_id,))
        return True



