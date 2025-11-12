"""
客户分享模型
对应数据库表: inhe_customer_share
对应原PHP的ActionModel::queryShare()
"""
from app.utils.db import Database

class Share:
    """
    客户分享模型类
    """
    
    @staticmethod
    def query_by_customer_id(customer_id):
        """
        根据customer_id查询分享的用户列表
        对应原PHP的ActionModel::queryShare()
        
        Args:
            customer_id (int): 客户ID
            
        Returns:
            str: 分享的用户名称，用逗号分隔
        """
        sql = """
            SELECT u.user_name 
            FROM inhe_customer_share k 
            LEFT JOIN user u ON u.user_id = k.user_ids 
            WHERE k.customer_id = %s
        """
        result = Database.execute_query(sql, (customer_id,))
        user_names = [item['user_name'] for item in result if item.get('user_name')]
        return ','.join(user_names) if user_names else ''



