"""
用户模型
对应原PHP项目的UserModel.php
"""
from app.utils.db import Database

class UserModel:
    """
    用户模型类
    对应原PHP的UserModel类
    """
    
    def get_user_bu(self, dept_id):
        """
        根据部门ID获取用户BU（公司）
        对应原PHP的UserModel::getUserBu()
        
        Args:
            dept_id (str): 部门ID
            
        Returns:
            str: 用户BU（公司代码）
        """
        if not dept_id:
            return ''
        
        sql = "SELECT user_bu FROM department WHERE dept_id = %s"
        result = Database.execute_query(sql, (dept_id,))
        return result[0].get('user_bu', '') if result else ''
    
    def get_user_info(self, user_id):
        """
        获取指定用户信息
        对应原PHP的UserModel::getUserInfo()
        
        Args:
            user_id (str): 用户ID
            
        Returns:
            dict: 用户信息字典
        """
        if not user_id:
            return {}
        
        sql = """
            SELECT u.*, 
                   d.dept_name AS deptName, 
                   c.id AS companyId, 
                   c.company AS companyName, 
                   c.user_bu AS company_user_bu 
            FROM user u 
            LEFT JOIN department d ON d.dept_id = u.dept_id 
            LEFT JOIN company c ON c.user_bu = d.user_bu 
            WHERE u.user_id = %s
        """
        result = Database.execute_query(sql, (user_id,))
        return result[0] if result else {}

