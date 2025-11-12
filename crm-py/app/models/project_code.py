"""
项目代号模型
对应原PHP项目的ActionModel.php中的项目代号数据操作
对应数据库表: inhe_temp_code
"""
from app.utils.db import Database
from datetime import datetime

class ProjectCode:
    """
    项目代号模型类
    对应原PHP的ActionModel类中的项目代号相关方法
    """
    
    @staticmethod
    def query_list(param, user_id, dept_id):
        """
        查询项目代号列表（带权限过滤）
        对应原PHP的get_data.php和query_page.php
        
        Args:
            param (dict): 查询参数字典
            user_id (str): 当前用户ID
            dept_id (int): 当前用户部门ID
            
        Returns:
            tuple: (数据列表, 总记录数)
        """
        from app.utils.auth import get_admin_data_query
        
        where_clauses = ["1=1"]
        params = []
        
        # 权限过滤
        admin_data = get_admin_data_query(user_id, dept_id, 'temp_code')
        is_admin = admin_data.get('isAdmin', False)
        
        if is_admin:
            # 管理员：根据权限范围过滤
            conditions = []
            if admin_data.get('userBu'):
                conditions.append(f"m.company IN ({admin_data['userBu']})")
            if admin_data.get('deptIds'):
                conditions.append(f"m.dept_id IN ({admin_data['deptIds']})")
            if admin_data.get('userIds'):
                conditions.append(f"m.user_id IN ({admin_data['userIds']})")
            
            if conditions:
                where_clauses.append(f"({' OR '.join(conditions)})")
        else:
            # 普通用户：只能看自己的
            where_clauses.append("m.user_id = %s")
            params.append(user_id)
        
        # 查询条件
        if param.get('temp_code'):
            where_clauses.append("m.temp_code LIKE %s")
            params.append(f"%{param['temp_code'].strip()}%")
        
        if param.get('scope_code'):
            where_clauses.append("m.scope_code = %s")
            params.append(param['scope_code'])
        
        # 排序
        order_by = "ORDER BY m.id DESC"
        if param.get('sort_field') and param.get('sort_way'):
            sort_field = param['sort_field']
            sort_way = param['sort_way']
            # 验证字段名防止SQL注入
            from app.utils.sql_validator import sanitize_field_name
            sanitize_field_name(sort_field)
            order_by = f"ORDER BY m.{sort_field} {sort_way}"
        
        # 分页
        limit_clause = ""
        if param.get('page') and param.get('limit'):
            page = int(param['page'])
            limit = int(param['limit'])
            offset = (page - 1) * limit
            limit_clause = f"LIMIT {offset}, {limit}"
        
        # 查询数据
        sql = f"""
            SELECT m.*, d.dept_name AS realDeptName, u.user_name AS createUserName
            FROM inhe_temp_code m
            LEFT JOIN department d ON d.dept_id = m.dept_id
            LEFT JOIN user u ON u.user_id = m.user_id
            WHERE {' AND '.join(where_clauses)}
            {order_by}
            {limit_clause}
        """
        result = Database.execute_query(sql, tuple(params))
        
        # 查询总数
        count_sql = f"""
            SELECT COUNT(*) AS total_count
            FROM inhe_temp_code m
            WHERE {' AND '.join(where_clauses)}
        """
        count_result = Database.execute_query(count_sql, tuple(params))
        total_count = count_result[0]['total_count'] if count_result else 0
        
        return result, total_count
    
    @staticmethod
    def query_by_id(project_code_id):
        """
        根据ID查询项目代号信息
        
        Args:
            project_code_id (int): 项目代号主键ID
            
        Returns:
            dict: 项目代号信息字典
        """
        sql = """
            SELECT m.*, d.dept_name AS realDeptName
            FROM inhe_temp_code m
            LEFT JOIN department d ON d.dept_id = m.dept_id
            WHERE m.id = %s
        """
        result = Database.execute_query(sql, (project_code_id,))
        return result[0] if result else {}
    
    @staticmethod
    def add(param):
        """
        添加项目代号
        对应原PHP的ActionModel::add()
        
        Args:
            param (dict): 项目代号参数字典
            
        Returns:
            int: 插入的项目代号ID
        """
        sql = """
            INSERT INTO inhe_temp_code (
                temp_code, project_name, location, scope_code, remark,
                dept_id, user_id, user_name, create_time, company
            ) VALUES (
                %s, %s, %s, %s, %s, %s, %s, %s, %s, %s
            )
        """
        params = (
            param.get('temp_code'),
            param.get('project_name'),
            param.get('location'),
            param.get('scope_code'),
            param.get('remark', ''),
            param.get('dept_id'),
            param.get('user_id'),
            param.get('user_name'),
            param.get('create_time', datetime.now().strftime('%Y-%m-%d %H:%M:%S')),
            param.get('company')
        )
        return Database.execute_insert(sql, params)
    
    @staticmethod
    def verify_code_exists(temp_code):
        """
        验证项目代号是否已存在
        对应原PHP的ActionModel中验证逻辑
        
        Args:
            temp_code (str): 项目代号
            
        Returns:
            bool: True表示已存在，False表示不存在
        """
        if not temp_code:
            return False
        
        sql = "SELECT COUNT(*) AS cnt FROM inhe_temp_code WHERE temp_code = %s"
        result = Database.execute_query(sql, (temp_code,))
        return result[0]['cnt'] > 0 if result else False

