"""
项目主表模型
对应原PHP项目的ActionModel.php中的项目主表操作
对应数据库表: inhe_project_main
"""
from app.utils.db import Database
from datetime import datetime

class ProjectMain:
    """
    项目主表模型类
    对应原PHP的ActionModel类中的项目主表相关方法
    """
    
    @staticmethod
    def query_by_form_id(form_id):
        """
        根据form_id查询项目主表信息
        对应原PHP的ActionModel::queryMain()
        
        Args:
            form_id (str): 表单ID
            
        Returns:
            dict: 项目主表信息字典
        """
        sql = """
            SELECT k.*, 
                   u.user_name AS createUserName, 
                   d.dept_name AS deptName 
            FROM inhe_project_main k 
            LEFT JOIN user u ON u.user_id = k.user_id 
            LEFT JOIN department d ON d.dept_id = k.organ_id 
            WHERE k.form_id = %s
        """
        result = Database.execute_query(sql, (form_id,))
        return result[0] if result else {}
    
    @staticmethod
    def query_by_id(project_id):
        """
        根据主键id查询项目主表信息
        
        Args:
            project_id (int): 项目主键ID
            
        Returns:
            dict: 项目主表信息字典
        """
        sql = """
            SELECT k.*, 
                   u.user_name AS createUserName, 
                   d.dept_name AS deptName 
            FROM inhe_project_main k 
            LEFT JOIN user u ON u.user_id = k.user_id 
            LEFT JOIN department d ON d.dept_id = k.organ_id 
            WHERE k.id = %s
        """
        result = Database.execute_query(sql, (project_id,))
        return result[0] if result else {}
    
    @staticmethod
    def add_main(param):
        """
        添加项目主表数据
        对应原PHP的ActionModel::addMain()
        
        Args:
            param (dict): 项目参数字典
            
        Returns:
            int: 插入的项目ID
        """
        sql = """
            INSERT INTO inhe_project_main (
                title, form_id, project_code, remark, organ_id, user_id,
                user_name, dept_name, write_time, status, type, is_bidding_project,
                change_star, create_user_bu, is_collective, past_pcode, c_past_pcode, user_bu
            ) VALUES (
                %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s
            )
        """
        params = (
            param.get('title'),
            param.get('form_id'),
            param.get('project_code'),
            param.get('remark'),
            param.get('organ_id'),
            param.get('user_id'),
            param.get('user_name'),
            param.get('dept_name'),
            param.get('write_time', datetime.now().strftime('%Y-%m-%d %H:%M:%S')),
            param.get('status'),
            param.get('type'),
            param.get('is_bidding_project'),
            param.get('change_star'),
            param.get('create_user_bu'),
            param.get('is_collective'),
            param.get('past_pcode'),
            param.get('c_past_pcode'),
            param.get('user_bu')
        )
        return Database.execute_insert(sql, params)
    
    @staticmethod
    def update_main(param, form_id):
        """
        更新项目主表数据
        对应原PHP的ActionModel::updateMainData()
        
        Args:
            param (dict): 更新参数字典
            form_id (str): 表单ID
            
        Returns:
            bool: 更新是否成功
        """
        sql = """
            UPDATE inhe_project_main 
            SET user_id = %s,
                organ_id = %s,
                user_name = %s,
                dept_name = %s,
                update_time = %s,
                title = %s,
                remark = %s,
                project_code = %s,
                status = %s,
                type = %s,
                is_bidding_project = %s,
                change_star = %s,
                user_bu = %s,
                is_collective = %s,
                past_pcode = %s,
                c_past_pcode = %s,
                create_user_bu = %s
            WHERE form_id = %s
        """
        params = (
            param.get('user_id'),
            param.get('organ_id'),
            param.get('user_name'),
            param.get('dept_name'),
            param.get('update_time', datetime.now().strftime('%Y-%m-%d %H:%M:%S')),
            param.get('title'),
            param.get('remark'),
            param.get('project_code'),
            param.get('status'),
            param.get('type'),
            param.get('is_bidding_project'),
            param.get('change_star'),
            param.get('user_bu'),
            param.get('is_collective'),
            param.get('past_pcode'),
            param.get('c_past_pcode'),
            param.get('create_user_bu'),
            form_id
        )
        Database.execute_update(sql, params)
        return True
    
    @staticmethod
    def delete_by_form_id(form_id):
        """
        根据form_id删除项目主表数据
        对应原PHP的ActionModel::delete()
        
        Args:
            form_id (str): 表单ID
            
        Returns:
            bool: 删除是否成功
        """
        sql = "DELETE FROM inhe_project_main WHERE form_id = %s"
        Database.execute_update(sql, (form_id,))
        return True
    
    @staticmethod
    def update_main_data(param_insert, condition):
        """
        通用更新方法（用于FormModel调用）
        对应原PHP的FormModel::updateMainData()
        
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
        sql = f"UPDATE inhe_project_main SET {', '.join(update_fields)} WHERE form_id = %s"
        Database.execute_update(sql, tuple(params))
        return True
    
    @staticmethod
    def change_main_data(param_insert, condition):
        """
        变更主表数据（创建新记录，关联原记录）
        对应原PHP的FormModel::changeMainData()
        
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
            INSERT INTO inhe_project_main ({', '.join(fields)})
            VALUES ({', '.join(placeholders)})
        """
        Database.execute_insert(sql, tuple(values))
        return True

