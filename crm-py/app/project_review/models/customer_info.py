"""
项目评审客户信息模型
对应原PHP项目的ActionModel.php中的客户信息操作
对应数据库表: inhe_customer_info
注意：这是项目评审模块中的客户信息，不是customer模块的客户数据
"""
from app.utils.db import Database

class CustomerInfo:
    """
    项目评审客户信息模型类
    对应原PHP的ActionModel类中的客户信息相关方法
    支持3种类型：1=代理商，2=招标方，3=买方
    """
    
    @staticmethod
    def query_by_form_id(form_id):
        """
        根据form_id查询客户信息（支持3种类型）
        对应原PHP的ActionModel::queryCustomer()
        
        Args:
            form_id (str): 表单ID
            
        Returns:
            dict: 客户信息字典，包含name1, name2, name3等字段
        """
        sql = "SELECT * FROM inhe_customer_info WHERE form_id = %s"
        result = Database.execute_query(sql, (form_id,))
        
        # 将结果转换为按类型组织的字典
        customer_data = {}
        for item in result:
            type_suffix = item.get('type', '')
            if type_suffix:
                customer_data[f"name{type_suffix}"] = item.get('name', '')
                customer_data[f"customer_name{type_suffix}"] = item.get('customer_name', '')
                customer_data[f"customer_id{type_suffix}"] = item.get('customer_id', '')
                customer_data[f"country{type_suffix}"] = item.get('country', '')
                customer_data[f"contact{type_suffix}"] = item.get('contact', '')
                customer_data[f"phone{type_suffix}"] = item.get('phone', '')
                customer_data[f"email{type_suffix}"] = item.get('email', '')
                customer_data[f"introduction{type_suffix}"] = item.get('introduction', '')
        
        if result:
            customer_data['form_id'] = form_id
        
        return customer_data
    
    @staticmethod
    def add_customer_info(param):
        """
        添加客户信息（3种类型）
        对应原PHP的ActionModel::addClientInfo()
        
        Args:
            param (dict): 客户信息参数字典
            
        Returns:
            bool: 添加是否成功
        """
        form_id = param.get('form_id', '')
        if not form_id:
            return False
        
        # 添加3种类型的客户信息
        for i in range(1, 4):
            sql = """
                INSERT INTO inhe_customer_info (
                    form_id, name, customer_id, country, contact, phone, email, introduction, type
                ) VALUES (
                    %s, %s, %s, %s, %s, %s, %s, %s, %s
                )
            """
            params = (
                form_id,
                param.get(f'name{i}', ''),
                param.get(f'customer_id{i}', ''),
                param.get(f'country{i}', ''),
                param.get(f'contact{i}', ''),
                param.get(f'phone{i}', ''),
                param.get(f'email{i}', ''),
                param.get(f'introduction{i}', ''),
                str(i)
            )
            Database.execute_insert(sql, params)
        
        # 更新客户名称（从inhe_customer_data表关联）
        update_sql = """
            UPDATE inhe_customer_info a 
            LEFT JOIN inhe_customer_data b ON a.name = b.id 
            SET a.customer_name = b.customer_name 
            WHERE a.customer_name = '' OR a.customer_name IS NULL
        """
        Database.execute_update(update_sql)
        
        return True
    
    @staticmethod
    def update_customer_info(param, form_id):
        """
        更新客户信息
        对应原PHP的ActionModel::updateClientData()
        使用FormModel的updateDetailData方法处理批量更新
        
        Args:
            param (dict): 更新参数字典
            form_id (str): 表单ID
            
        Returns:
            bool: 更新是否成功
        """
        # 这个方法主要通过FormModel的updateDetailData调用
        # 这里只做基础验证
        if not form_id:
            return False
        
        # 删除type=0的记录
        delete_sql = "DELETE FROM inhe_customer_info WHERE type = '0'"
        Database.execute_update(delete_sql)
        
        # 更新客户名称（从inhe_customer_data表关联）
        update_sql = """
            UPDATE inhe_customer_info a 
            LEFT JOIN inhe_customer_data b ON a.name = b.id 
            SET a.customer_name = b.customer_name 
            WHERE a.customer_name = '' OR a.customer_name IS NULL
        """
        Database.execute_update(update_sql)
        
        return True
    
    @staticmethod
    def change_customer_info(param, form_id, related_id):
        """
        变更客户信息（创建新记录）
        对应原PHP的ActionModel::changeClientData()
        
        Args:
            param (dict): 变更参数字典
            form_id (str): 新表单ID
            related_id (str): 原表单ID
            
        Returns:
            bool: 变更是否成功
        """
        if not form_id or not related_id:
            return False
        
        # 这个方法主要通过FormModel的changeDetailData调用
        # 这里只做基础验证和后续处理
        
        # 删除type=0的记录
        delete_sql = "DELETE FROM inhe_customer_info WHERE type = '0'"
        Database.execute_update(delete_sql)
        
        # 更新客户名称（从inhe_customer_data表关联）
        update_sql = """
            UPDATE inhe_customer_info a 
            LEFT JOIN inhe_customer_data b ON a.name = b.id 
            SET a.customer_name = b.customer_name 
            WHERE a.customer_name = '' OR a.customer_name IS NULL
        """
        Database.execute_update(update_sql)
        
        return True
    
    @staticmethod
    def delete_by_form_id(form_id):
        """
        根据form_id删除客户信息
        
        Args:
            form_id (str): 表单ID
            
        Returns:
            bool: 删除是否成功
        """
        sql = "DELETE FROM inhe_customer_info WHERE form_id = %s"
        Database.execute_update(sql, (form_id,))
        return True

