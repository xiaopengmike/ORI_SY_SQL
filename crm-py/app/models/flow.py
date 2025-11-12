"""
流程记录模型
对应数据库表: inhe_customer_data_flow
对应原PHP的ActionModel::queryFlow()
"""
from app.utils.db import Database

class Flow:
    """
    流程记录模型类
    """
    
    @staticmethod
    def query_by_form_id(form_id, related_id=None):
        """
        根据form_id查询流程记录
        对应原PHP的ActionModel::queryFlow()
        
        Args:
            form_id (str): 表单ID
            related_id (str): 关联ID（如果提供，使用related_id查询）
            
        Returns:
            list: 流程记录列表
        """
        query_id = related_id if related_id else form_id
        sql = "SELECT * FROM inhe_customer_data_flow WHERE form_id = %s"
        return Database.execute_query(sql, (query_id,))
    
    @staticmethod
    def add_flow(param):
        """
        添加流程记录
        
        Args:
            param (dict): 流程记录参数字典
            
        Returns:
            int: 插入的流程ID
        """
        sql = """
            INSERT INTO inhe_customer_data_flow (
                form_id, customer_id, flow_date, create_time, remark, user_id, user_name
            ) VALUES (%s, %s, %s, %s, %s, %s, %s)
        """
        params = (
            param.get('form_id'),
            param.get('customer_id'),
            param.get('flow_date'),
            param.get('create_time'),
            param.get('remark'),
            param.get('user_id'),
            param.get('user_name')
        )
        return Database.execute_insert(sql, params)



