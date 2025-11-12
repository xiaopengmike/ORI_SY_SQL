"""
项目产品明细模型
对应原PHP项目的ActionModel.php中的项目产品明细操作
对应数据库表: inhe_project_detail
"""
from app.utils.db import Database

class ProjectDetail:
    """
    项目产品明细模型类
    对应原PHP的ActionModel类中的项目产品明细相关方法
    """
    
    @staticmethod
    def query_by_form_id(form_id, user_bu=None):
        """
        根据form_id查询项目产品明细
        对应原PHP的ActionModel::queryProjectDetail()
        
        Args:
            form_id (str): 表单ID
            user_bu (str): 公司代码（用于分组）
            
        Returns:
            dict: 按type分组的项目产品明细字典
        """
        sql = "SELECT * FROM inhe_project_detail WHERE form_id = %s ORDER BY serial_number"
        result = Database.execute_query(sql, (form_id,))
        
        # 按type分组
        detail_dict = {}
        for item in result:
            detail_type = item.get('type', '')
            if not detail_type:
                # 如果type为空，根据user_bu设置默认type
                if user_bu == 'WITLINK':
                    detail_type = 'Soft'
                elif user_bu == 'INHENERGY' or user_bu == 'HKNERGY':
                    detail_type = 'Inhenergy'
                elif user_bu == 'INHEGRID':
                    detail_type = 'Inhegrid'
                else:
                    detail_type = 'Electric'
            
            if detail_type not in detail_dict:
                detail_dict[detail_type] = []
            detail_dict[detail_type].append(item)
        
        return detail_dict
    
    @staticmethod
    def add_project_detail(param):
        """
        添加项目产品明细
        对应原PHP的ActionModel::addProjectDetail()
        
        Args:
            param (dict): 产品明细参数字典，包含addProjectDetailCount和product1-N等
            
        Returns:
            bool: 添加是否成功
        """
        count = param.get('addProjectDetailCount', 0)
        if count <= 0:
            return True
        
        for i in range(1, count + 1):
            product = param.get(f'product{i}', '')
            if not product:
                continue
            
            sql = """
                INSERT INTO inhe_project_detail (
                    form_id, product, price, serial_number, first_price,
                    standard, supplier, type, number
                ) VALUES (
                    %s, %s, %s, %s, %s, %s, %s, %s, %s
                )
            """
            params = (
                param.get('form_id'),
                product,
                param.get(f'price{i}', ''),
                i,
                param.get(f'first_price{i}', ''),
                param.get(f'standard{i}', ''),
                param.get(f'supplier{i}', ''),
                param.get(f'type{i}', ''),
                param.get(f'number{i}', '')
            )
            Database.execute_insert(sql, params)
        
        return True
    
    @staticmethod
    def update_project_detail(param, form_id):
        """
        更新项目产品明细
        对应原PHP的ActionModel::updateProjectDetailData()
        
        Args:
            param (dict): 更新参数字典
            form_id (str): 表单ID
            
        Returns:
            bool: 更新是否成功
        """
        # 先删除旧的产品明细
        delete_sql = "DELETE FROM inhe_project_detail WHERE form_id = %s"
        Database.execute_update(delete_sql, (form_id,))
        
        # 重新插入产品明细
        ProjectDetail.add_project_detail(param)
        
        # 删除product为空或null的记录
        delete_empty_sql = "DELETE FROM inhe_project_detail WHERE form_id = %s AND (product = '' OR product IS NULL)"
        Database.execute_update(delete_empty_sql, (form_id,))
        
        return True
    
    @staticmethod
    def delete_by_form_id(form_id):
        """
        根据form_id删除项目产品明细
        
        Args:
            form_id (str): 表单ID
            
        Returns:
            bool: 删除是否成功
        """
        sql = "DELETE FROM inhe_project_detail WHERE form_id = %s"
        Database.execute_update(sql, (form_id,))
        return True

