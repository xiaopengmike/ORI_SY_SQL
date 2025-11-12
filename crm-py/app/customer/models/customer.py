"""
客户主表模型
对应原PHP项目的ActionModel.php中的客户数据操作
对应数据库表: inhe_customer_data
"""
from app.utils.db import Database
from datetime import datetime

class Customer:
    """
    客户模型类
    对应原PHP的ActionModel类中的客户相关方法
    """
    
    def __init__(self, data=None):
        """
        初始化客户对象
        
        Args:
            data (dict): 客户数据字典
        """
        if data:
            self.id = data.get('id')
            self.organ_id = data.get('organ_id', 1)
            self.user_id = data.get('user_id', 'admin')
            self.write_time = data.get('write_time')
            self.update_time = data.get('update_time')
            self.title = data.get('title')
            self.form_id = data.get('form_id')
            self.related_id = data.get('related_id')
            self.customer_name = data.get('customer_name')
            self.short_name = data.get('short_name')
            self.continent = data.get('continent')
            self.country = data.get('country')
            self.website = data.get('website')
            self.address = data.get('address')
            self.summarize = data.get('summarize')
            self.user_name = data.get('user_name')
            self.dept_name = data.get('dept_name')
            self.status = data.get('status')
            self.type = data.get('type')
            self.user_bu = data.get('user_bu')
            self.version = data.get('version', 0)
            self.customer_type = data.get('customer_type')
            self.focus = data.get('focus')
            self.project_star = data.get('project_star')
            self.customer_source = data.get('customer_source')
            self.is_vaild = data.get('is_vaild')
            self.change_explain = data.get('change_explain')
            self.create_user_id = data.get('create_user_id')
            self.create_dept_id = data.get('create_dept_id')
            # 关联查询字段
            self.create_user_name = data.get('createUserName')
            self.dept_name_full = data.get('deptName')
    
    @staticmethod
    def query_by_primary_id(customer_id):
        """
        根据主键id查询客户信息
        
        Args:
            customer_id (int): 客户主键ID
            
        Returns:
            dict: 客户信息字典
        """
        sql = """
            SELECT k.*, 
                   u.user_name AS createUserName, 
                   d.dept_name AS deptName, 
                   tc.zh_name AS continentName,
                   yc.zh_name AS countryName, 
                   tc.en_name AS continentEnName,
                   yc.country AS countryEnName 
            FROM inhe_customer_data k 
            LEFT JOIN user u ON u.user_id = k.create_user_id 
            LEFT JOIN department d ON d.dept_id = k.create_dept_id 
            LEFT JOIN continent_code tc ON tc.iso_two = k.continent 
            LEFT JOIN country_code yc ON yc.iso_three = k.country AND yc.continent = k.continent 
            WHERE k.id = %s
        """
        result = Database.execute_query(sql, (customer_id,))
        return result[0] if result else {}
    
    @staticmethod
    def query_by_form_id(form_id):
        """
        根据form_id查询客户信息
        对应原PHP的ActionModel::queryMain()
        
        Args:
            form_id (str): 表单ID
            
        Returns:
            dict: 客户信息字典
        """
        sql = """
            SELECT k.*, 
                   u.user_name AS createUserName, 
                   d.dept_name AS deptName, 
                   tc.zh_name AS continentName,
                   yc.zh_name AS countryName, 
                   tc.en_name AS continentEnName,
                   yc.country AS countryEnName 
            FROM inhe_customer_data k 
            LEFT JOIN user u ON u.user_id = k.create_user_id 
            LEFT JOIN department d ON d.dept_id = k.create_dept_id 
            LEFT JOIN continent_code tc ON tc.iso_two = k.continent 
            LEFT JOIN country_code yc ON yc.iso_three = k.country AND yc.continent = k.continent 
            WHERE k.form_id = %s
        """
        result = Database.execute_query(sql, (form_id,))
        return result[0] if result else {}
    
    @staticmethod
    def add_main(param):
        """
        添加客户主表数据
        对应原PHP的ActionModel::addMain()
        
        Args:
            param (dict): 客户参数字典
            
        Returns:
            int: 插入的客户ID
        """
        sql = """
            INSERT INTO inhe_customer_data (
                organ_id, user_id, create_dept_id, create_user_id, write_time,
                title, form_id, customer_name, short_name, continent, country,
                website, address, summarize, user_name, dept_name, status, type,
                customer_type, customer_source, is_vaild, change_explain, focus, user_bu
            ) VALUES (
                %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s
            )
        """
        params = (
            param.get('organ_id'),
            param.get('user_id'),
            param.get('create_dept_id'),
            param.get('create_user_id'),
            param.get('write_time', datetime.now().strftime('%Y-%m-%d %H:%M:%S')),
            param.get('title'),
            param.get('form_id'),
            param.get('customer_name'),
            param.get('short_name'),
            param.get('continent'),
            param.get('country'),
            param.get('website'),
            param.get('address'),
            param.get('summarize'),
            param.get('user_name'),
            param.get('dept_name'),
            param.get('status'),
            param.get('type'),
            param.get('customer_type'),
            param.get('customer_source'),
            param.get('is_vaild'),
            param.get('change_explain'),
            param.get('focus'),
            param.get('user_bu')
        )
        return Database.execute_insert(sql, params)
    
    @staticmethod
    def update_main(param, form_id):
        """
        更新客户主表数据
        对应原PHP的ActionModel::updateMainData()
        
        Args:
            param (dict): 更新参数字典
            form_id (str): 表单ID
            
        Returns:
            bool: 更新是否成功
        """
        sql = """
            UPDATE inhe_customer_data 
            SET update_time = %s,
                title = %s,
                customer_name = %s,
                short_name = %s,
                continent = %s,
                country = %s,
                website = %s,
                address = %s,
                summarize = %s,
                user_name = %s,
                dept_name = %s,
                customer_type = %s,
                customer_source = %s,
                focus = %s,
                is_vaild = %s,
                change_explain = %s,
                status = %s
            WHERE form_id = %s
        """
        params = (
            param.get('update_time', datetime.now().strftime('%Y-%m-%d %H:%M:%S')),
            param.get('title'),
            param.get('customer_name'),
            param.get('short_name'),
            param.get('continent'),
            param.get('country'),
            param.get('website'),
            param.get('address'),
            param.get('summarize'),
            param.get('user_name'),
            param.get('dept_name'),
            param.get('customer_type'),
            param.get('customer_source'),
            param.get('focus'),
            param.get('is_vaild'),
            param.get('change_explain'),
            param.get('status'),
            form_id
        )
        Database.execute_update(sql, params)
        return True
    
    @staticmethod
    def delete_by_form_id(form_id):
        """
        根据form_id删除客户数据
        对应原PHP的ActionModel::delete()
        
        Args:
            form_id (str): 表单ID
            
        Returns:
            bool: 删除是否成功
        """
        sql = "DELETE FROM inhe_customer_data WHERE form_id = %s"
        Database.execute_update(sql, (form_id,))
        return True
    
    @staticmethod
    def verify_not_exist(param, field='customer_name'):
        """
        验证字段值是否不存在
        对应原PHP的ActionModel::verifyNotExist()
        
        Args:
            param (dict): 参数字典
            field (str): 要验证的字段名
            
        Returns:
            bool: True表示不存在（可以添加），False表示已存在
        """
        form_id = param.get('form_id', '')
        field_value = param.get(field, '')
        
        if not field_value:
            return True
        
        # 修复Bug #2: SQL注入风险 - 验证字段名
        from app.utils.sql_validator import sanitize_field_name
        sanitize_field_name(field)
        
        sql = f"SELECT COUNT(*) AS cnt FROM inhe_customer_data WHERE `{field}` = %s"
        params = [field_value]
        
        if form_id:
            sql += " AND `form_id` != %s"
            params.append(form_id)
        
        result = Database.execute_query(sql, tuple(params))
        return result[0]['cnt'] == 0 if result else True

