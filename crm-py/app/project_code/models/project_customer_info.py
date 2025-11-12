"""
项目客户信息模型
对应原PHP项目的ActionModel.php中的客户信息操作
对应数据库表: inhe_customer_info
"""
from app.utils.db import Database

class ProjectCustomerInfo:
    """
    项目客户信息模型类
    对应原PHP的ActionModel类中的客户信息相关方法
    """
    
    @staticmethod
    def query_by_form_id(form_id):
        """
        根据form_id查询项目客户信息
        对应原PHP的ActionModel::queryCustomer()
        
        Args:
            form_id (str): 表单ID
            
        Returns:
            dict: 客户信息字典，按type分组（name1, name2, name3等）
        """
        sql = "SELECT * FROM inhe_customer_info WHERE form_id = %s"
        result = Database.execute_query(sql, (form_id,))
        
        # 转换为按type分组的字典格式
        customer_data = {}
        for item in result:
            type_num = item.get('type', '')
            if type_num:
                customer_data[f"name{type_num}"] = item.get('name', '')
                customer_data[f"customer_name{type_num}"] = item.get('customer_name', '')
                customer_data[f"customer_id{type_num}"] = item.get('customer_id', '')
                customer_data[f"country{type_num}"] = item.get('country', '')
                customer_data[f"contact{type_num}"] = item.get('contact', '')
                customer_data[f"phone{type_num}"] = item.get('phone', '')
                customer_data[f"email{type_num}"] = item.get('email', '')
                customer_data[f"introduction{type_num}"] = item.get('introduction', '')
                customer_data['form_id'] = item.get('form_id', '')
        
        return customer_data
    
    @staticmethod
    def add_customer_info(param):
        """
        添加项目客户信息
        对应原PHP的ActionModel::addClientInfo()
        
        Args:
            param (dict): 客户信息参数字典，包含name1-3, customer_id1-3等
            
        Returns:
            bool: 添加是否成功
        """
        # 插入3个客户信息（type=1,2,3）
        for i in range(1, 4):
            sql = """
                INSERT INTO inhe_customer_info (
                    form_id, name, customer_id, country, contact, phone, email, introduction, type
                ) VALUES (
                    %s, %s, %s, %s, %s, %s, %s, %s, %s
                )
            """
            params = (
                param.get('form_id'),
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
        
        # 更新customer_name（从inhe_customer_data表关联）
        update_sql = """
            UPDATE inhe_customer_info a 
            LEFT JOIN inhe_customer_data b ON a.name = b.id 
            SET a.customer_name = b.customer_name 
            WHERE (a.customer_name = '' OR a.customer_name IS NULL) 
            AND a.form_id = %s
        """
        Database.execute_update(update_sql, (param.get('form_id'),))
        
        return True
    
    @staticmethod
    def update_customer_info(param, form_id):
        """
        更新项目客户信息
        对应原PHP的ActionModel::updateClientData()
        
        Args:
            param (dict): 更新参数字典
            form_id (str): 表单ID
            
        Returns:
            bool: 更新是否成功
        """
        # 先删除旧的客户信息
        delete_sql = "DELETE FROM inhe_customer_info WHERE form_id = %s"
        Database.execute_update(delete_sql, (form_id,))
        
        # 重新插入客户信息
        ProjectCustomerInfo.add_customer_info(param)
        
        # 删除type=0的记录（如果有）
        delete_zero_sql = "DELETE FROM inhe_customer_info WHERE type = '0'"
        Database.execute_update(delete_zero_sql)
        
        # 更新customer_name
        update_sql = """
            UPDATE inhe_customer_info a 
            LEFT JOIN inhe_customer_data b ON a.name = b.id 
            SET a.customer_name = b.customer_name 
            WHERE (a.customer_name = '' OR a.customer_name IS NULL) 
            AND a.form_id = %s
        """
        Database.execute_update(update_sql, (form_id,))
        
        return True
    
    @staticmethod
    def delete_by_form_id(form_id):
        """
        根据form_id删除项目客户信息
        
        Args:
            form_id (str): 表单ID
            
        Returns:
            bool: 删除是否成功
        """
        sql = "DELETE FROM inhe_customer_info WHERE form_id = %s"
        Database.execute_update(sql, (form_id,))
        return True

