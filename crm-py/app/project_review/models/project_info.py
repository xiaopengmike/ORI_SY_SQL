"""
项目信息模型
对应原PHP项目的ActionModel.php中的项目信息操作
对应数据库表: inhe_project_info
"""
from app.utils.db import Database

class ProjectInfo:
    """
    项目信息模型类
    对应原PHP的ActionModel类中的项目信息相关方法
    """
    
    @staticmethod
    def query_by_form_id(form_id):
        """
        根据form_id查询项目信息
        对应原PHP的ActionModel::queryProject()
        
        Args:
            form_id (str): 表单ID
            
        Returns:
            dict: 项目信息字典
        """
        sql = "SELECT * FROM inhe_project_info WHERE form_id = %s"
        result = Database.execute_query(sql, (form_id,))
        return result[0] if result else {}
    
    @staticmethod
    def add_project_info(param):
        """
        添加项目信息
        对应原PHP的ActionModel::addProjectInfo()
        
        Args:
            param (dict): 项目信息参数字典
            
        Returns:
            bool: 添加是否成功
        """
        sql = """
            INSERT INTO inhe_project_info (
                form_id, project_code, temp_project_code, country, scope, project_type,
                prototype, potential, background, explain_user, explain_project,
                cooperate_history, service
            ) VALUES (
                %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s
            )
        """
        params = (
            param.get('form_id'),
            param.get('project_code'),
            param.get('temp_project_code'),
            param.get('country'),
            param.get('scope'),
            param.get('project_type'),
            param.get('prototype'),
            param.get('potential'),
            param.get('background'),
            param.get('explain_user'),
            param.get('explain_project'),
            param.get('cooperate_history'),
            param.get('service')
        )
        Database.execute_insert(sql, params)
        return True
    
    @staticmethod
    def update_project_info(param, form_id):
        """
        更新项目信息
        对应原PHP的ActionModel::updateProjectMainData()
        
        Args:
            param (dict): 更新参数字典
            form_id (str): 表单ID
            
        Returns:
            bool: 更新是否成功
        """
        sql = """
            UPDATE inhe_project_info 
            SET project_code = %s,
                temp_project_code = %s,
                country = %s,
                scope = %s,
                project_type = %s,
                prototype = %s,
                potential = %s,
                background = %s,
                explain_user = %s,
                explain_project = %s,
                cooperate_history = %s,
                service = %s
            WHERE form_id = %s
        """
        params = (
            param.get('project_code'),
            param.get('temp_project_code'),
            param.get('country'),
            param.get('scope'),
            param.get('project_type'),
            param.get('prototype'),
            param.get('potential'),
            param.get('background'),
            param.get('explain_user'),
            param.get('explain_project'),
            param.get('cooperate_history'),
            param.get('service'),
            form_id
        )
        Database.execute_update(sql, params)
        return True
    
    @staticmethod
    def change_project_info(param, form_id, related_id):
        """
        变更项目信息（创建新记录）
        对应原PHP的ActionModel::changeProjectMainData()
        
        Args:
            param (dict): 变更参数字典
            form_id (str): 新表单ID
            related_id (str): 原表单ID
            
        Returns:
            bool: 变更是否成功
        """
        # 这个方法主要通过FormModel的changeMainData调用
        # 这里只做基础验证
        if not form_id or not related_id:
            return False
        return True
    
    @staticmethod
    def delete_by_form_id(form_id):
        """
        根据form_id删除项目信息
        
        Args:
            form_id (str): 表单ID
            
        Returns:
            bool: 删除是否成功
        """
        sql = "DELETE FROM inhe_project_info WHERE form_id = %s"
        Database.execute_update(sql, (form_id,))
        return True
    
    @staticmethod
    def update_main_data(param_insert, condition):
        """
        通用更新方法（用于FormModel调用）
        
        Args:
            param_insert (dict): 更新参数字典
            condition (dict): 条件字典
            
        Returns:
            bool: 更新是否成功
        """
        form_id = condition.get('form_id', '')
        if not form_id:
            return False
        
        # 构建更新字段
        update_fields = []
        params = []
        
        for key, value in param_insert.items():
            if value is not None:
                update_fields.append(f"{key} = %s")
                params.append(value)
        
        if not update_fields:
            return False
        
        params.append(form_id)
        sql = f"UPDATE inhe_project_info SET {', '.join(update_fields)} WHERE form_id = %s"
        Database.execute_update(sql, tuple(params))
        return True
    
    @staticmethod
    def change_main_data(param_insert, condition):
        """
        变更主表数据（创建新记录）
        
        Args:
            param_insert (dict): 插入参数字典
            condition (dict): 条件字典（related_id）
            
        Returns:
            bool: 变更是否成功
        """
        related_id = condition.get('form_id', '')
        if not related_id:
            return False
        
        # 构建插入字段和值
        fields = list(param_insert.keys())
        placeholders = ['%s'] * len(fields)
        values = [param_insert[key] for key in fields]
        
        sql = f"""
            INSERT INTO inhe_project_info ({', '.join(fields)})
            VALUES ({', '.join(placeholders)})
        """
        Database.execute_insert(sql, tuple(values))
        return True

