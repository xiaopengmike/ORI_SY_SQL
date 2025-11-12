"""
项目明细模型
对应原PHP项目的ActionModel.php中的项目明细操作
对应数据库表: inhe_project_detail
支持多种产品类型：Electric, Soft, Inhenergy, Inhegrid, Cdinhe等
"""
from app.utils.db import Database

class ProjectDetail:
    """
    项目明细模型类
    对应原PHP的ActionModel类中的项目明细相关方法
    """
    
    @staticmethod
    def query_by_form_id(form_id, user_bu=None):
        """
        根据form_id查询项目明细（按类型分组）
        对应原PHP的ActionModel::queryProjectDetail()
        
        Args:
            form_id (str): 表单ID
            user_bu (str): 用户BU，用于确定默认类型
            
        Returns:
            dict: 按类型分组的项目明细字典，如 {'Electric': [...], 'Soft': [...]}
        """
        sql = "SELECT * FROM inhe_project_detail WHERE form_id = %s ORDER BY serial_number"
        result = Database.execute_query(sql, (form_id,))
        
        # 按类型分组
        detail_dict = {}
        for item in result:
            detail_type = item.get('type', '')
            
            # 如果type为空，根据user_bu设置默认类型
            if not detail_type:
                if user_bu == 'witlink':
                    detail_type = 'Soft'
                elif user_bu == 'inhenergy':
                    detail_type = 'Inhenergy'
                elif user_bu == 'inhegrid':
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
        添加项目明细
        对应原PHP的ActionModel::addProjectDetail()
        
        Args:
            param (dict): 项目明细参数字典，包含addProjectDetailCount和product1, price1等字段
            
        Returns:
            bool: 添加是否成功
        """
        form_id = param.get('form_id', '')
        count = int(param.get('addProjectDetailCount', 0))
        
        if count <= 0 or not form_id:
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
                form_id,
                product,
                param.get(f'price{i}', ''),
                i,
                param.get(f'first_price{i}', ''),
                param.get(f'standard{i}', ''),
                param.get(f'supplier{i}', ''),
                param.get(f'type{i}', ''),
                param.get(f'number{i}', 0)
            )
            Database.execute_insert(sql, params)
        
        return True
    
    @staticmethod
    def update_project_detail(param, form_id):
        """
        更新项目明细
        对应原PHP的ActionModel::updateProjectDetailData()
        使用FormModel的updateDetailData方法处理批量更新
        
        Args:
            param (dict): 更新参数字典
            form_id (str): 表单ID
            
        Returns:
            bool: 更新是否成功
        """
        # 这个方法主要通过FormModel的updateDetailData调用
        # 这里只做后续处理：删除空产品名称的记录
        sql = "DELETE FROM inhe_project_detail WHERE form_id = %s AND (product = '' OR product IS NULL)"
        Database.execute_update(sql, (form_id,))
        return True
    
    @staticmethod
    def change_project_detail(param, form_id, related_id):
        """
        变更项目明细（创建新记录）
        对应原PHP的ActionModel::changeProjectDetailData()
        
        Args:
            param (dict): 变更参数字典
            form_id (str): 新表单ID
            related_id (str): 原表单ID
            
        Returns:
            bool: 变更是否成功
        """
        # 这个方法主要通过FormModel的changeDetailData调用
        # 这里只做基础验证
        if not form_id or not related_id:
            return False
        return True
    
    @staticmethod
    def delete_by_form_id(form_id):
        """
        根据form_id删除项目明细
        
        Args:
            form_id (str): 表单ID
            
        Returns:
            bool: 删除是否成功
        """
        sql = "DELETE FROM inhe_project_detail WHERE form_id = %s"
        Database.execute_update(sql, (form_id,))
        return True

