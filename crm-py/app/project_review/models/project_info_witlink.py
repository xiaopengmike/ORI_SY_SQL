"""
WITLINK项目信息模型
对应原PHP项目的ActionModel.php中的WITLINK项目信息操作
对应数据库表: inhe_project_info_witlink
"""
from app.utils.db import Database

class ProjectInfoWitlink:
    """
    WITLINK项目信息模型类
    对应原PHP的ActionModel类中的WITLINK项目信息相关方法
    """
    
    @staticmethod
    def query_by_form_id(form_id):
        """
        根据form_id查询WITLINK项目信息
        对应原PHP的ActionModel::queryWitlinkProject()
        
        Args:
            form_id (str): 表单ID
            
        Returns:
            dict: WITLINK项目信息字典
        """
        sql = "SELECT * FROM inhe_project_info_witlink WHERE form_id = %s"
        result = Database.execute_query(sql, (form_id,))
        return result[0] if result else {}
    
    @staticmethod
    def add_project_info_witlink(param):
        """
        添加WITLINK项目信息
        对应原PHP的ActionModel::addProjectInfoWitlink()
        
        Args:
            param (dict): WITLINK项目信息参数字典
            
        Returns:
            bool: 添加是否成功
        """
        # 如果不是WITLINK公司，直接返回
        if param.get('user_bu', '').upper() != 'WITLINK':
            return True
        
        sql = """
            INSERT INTO inhe_project_info_witlink (
                form_id, customer_type, num_users, users_five_years_later, replacement_reason,
                old_system_situation, purchase_method, customer_it_leader, leader_phone, leader_email,
                supply_server_database, database_type, database_config, database_num,
                server_model, server_config, server_num, central_type, distributed, cloud_deployment,
                os_language, data_migration, compatible_equipment, compatible_module,
                integrated_software, custom_api, other_requirements, demand_change,
                implement_scene, implement_long, implement_agent, implement_scene_duration,
                implement_scene_person, implement_long_duration, implement_long_person,
                implement_agent_duration, implement_agent_person, cooperate_history,
                maintain_obligation, maintain_mode, maintain_years, service_level
            ) VALUES (
                %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,
                %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s
            )
        """
        params = (
            param.get('form_id'),
            param.get('customer_type'),
            param.get('num_users'),
            param.get('users_five_years_later'),
            param.get('replacement_reason'),
            param.get('old_system_situation'),
            param.get('purchase_method'),
            param.get('customer_it_leader'),
            param.get('leader_phone'),
            param.get('leader_email'),
            param.get('supply_server_database'),
            param.get('database_type'),
            param.get('database_config'),
            param.get('database_num'),
            param.get('server_model'),
            param.get('server_config'),
            param.get('server_num'),
            param.get('central_type'),
            param.get('distributed'),
            param.get('cloud_deployment'),
            param.get('os_language'),
            param.get('data_migration'),
            param.get('compatible_equipment'),
            param.get('compatible_module'),
            param.get('integrated_software'),
            param.get('custom_api'),
            param.get('other_requirements'),
            param.get('demand_change'),
            param.get('implement_scene'),
            param.get('implement_long'),
            param.get('implement_agent'),
            param.get('implement_scene_duration'),
            param.get('implement_scene_person'),
            param.get('implement_long_duration'),
            param.get('implement_long_person'),
            param.get('implement_agent_duration'),
            param.get('implement_agent_person'),
            param.get('cooperate_history'),
            param.get('maintain_obligation'),
            param.get('maintain_mode'),
            param.get('maintain_years'),
            param.get('service_level')
        )
        Database.execute_insert(sql, params)
        return True
    
    @staticmethod
    def update_project_info_witlink(param, form_id):
        """
        更新WITLINK项目信息
        对应原PHP的ActionModel::updateProjectWitlinkMainData()
        
        Args:
            param (dict): 更新参数字典
            form_id (str): 表单ID
            
        Returns:
            bool: 更新是否成功
        """
        # 如果不是WITLINK公司，直接返回
        if param.get('user_bu', '').upper() != 'WITLINK':
            return True
        
        sql = """
            UPDATE inhe_project_info_witlink 
            SET customer_type = %s,
                num_users = %s,
                users_five_years_later = %s,
                replacement_reason = %s,
                old_system_situation = %s,
                purchase_method = %s,
                customer_it_leader = %s,
                leader_phone = %s,
                leader_email = %s,
                supply_server_database = %s,
                database_type = %s,
                database_config = %s,
                database_num = %s,
                server_model = %s,
                server_config = %s,
                server_num = %s,
                central_type = %s,
                distributed = %s,
                cloud_deployment = %s,
                os_language = %s,
                data_migration = %s,
                compatible_equipment = %s,
                compatible_module = %s,
                integrated_software = %s,
                custom_api = %s,
                other_requirements = %s,
                demand_change = %s,
                maintain_obligation = %s,
                maintain_mode = %s,
                maintain_years = %s,
                service_level = %s,
                implement_scene = %s,
                implement_long = %s,
                implement_agent = %s,
                implement_scene_duration = %s,
                implement_scene_person = %s,
                implement_long_duration = %s,
                implement_long_person = %s,
                implement_agent_duration = %s,
                implement_agent_person = %s,
                cooperate_history = %s
            WHERE form_id = %s
        """
        params = (
            param.get('customer_type'),
            param.get('num_users'),
            param.get('users_five_years_later'),
            param.get('replacement_reason'),
            param.get('old_system_situation'),
            param.get('purchase_method'),
            param.get('customer_it_leader'),
            param.get('leader_phone'),
            param.get('leader_email'),
            param.get('supply_server_database'),
            param.get('database_type'),
            param.get('database_config'),
            param.get('database_num'),
            param.get('server_model'),
            param.get('server_config'),
            param.get('server_num'),
            param.get('central_type'),
            param.get('distributed'),
            param.get('cloud_deployment'),
            param.get('os_language'),
            param.get('data_migration'),
            param.get('compatible_equipment'),
            param.get('compatible_module'),
            param.get('integrated_software'),
            param.get('custom_api'),
            param.get('other_requirements'),
            param.get('demand_change'),
            param.get('maintain_obligation'),
            param.get('maintain_mode'),
            param.get('maintain_years'),
            param.get('service_level'),
            param.get('implement_scene'),
            param.get('implement_long'),
            param.get('implement_agent'),
            param.get('implement_scene_duration'),
            param.get('implement_scene_person'),
            param.get('implement_long_duration'),
            param.get('implement_long_person'),
            param.get('implement_agent_duration'),
            param.get('implement_agent_person'),
            param.get('cooperate_history'),
            form_id
        )
        Database.execute_update(sql, params)
        return True
    
    @staticmethod
    def change_project_info_witlink(param, form_id, related_id):
        """
        变更WITLINK项目信息（创建新记录）
        对应原PHP的ActionModel::changeProjectWitlinkMainData()
        
        Args:
            param (dict): 变更参数字典
            form_id (str): 新表单ID
            related_id (str): 原表单ID
            
        Returns:
            bool: 变更是否成功
        """
        # 如果不是WITLINK公司，直接返回
        if param.get('user_bu', '').upper() != 'WITLINK':
            return True
        
        # 这个方法主要通过FormModel的changeMainData调用
        # 这里只做基础验证
        if not form_id or not related_id:
            return False
        return True
    
    @staticmethod
    def delete_by_form_id(form_id):
        """
        根据form_id删除WITLINK项目信息
        
        Args:
            form_id (str): 表单ID
            
        Returns:
            bool: 删除是否成功
        """
        sql = "DELETE FROM inhe_project_info_witlink WHERE form_id = %s"
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
        sql = f"UPDATE inhe_project_info_witlink SET {', '.join(update_fields)} WHERE form_id = %s"
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
            INSERT INTO inhe_project_info_witlink ({', '.join(fields)})
            VALUES ({', '.join(placeholders)})
        """
        Database.execute_insert(sql, tuple(values))
        return True

