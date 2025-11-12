"""
列表查询服务
对应原PHP的query_page.php
"""
from app.utils.db import Database
from app.utils.auth import get_login_user_id, get_login_dept_id
from app.common.data_model import DataModel

class QueryService:
    """
    列表查询服务类
    """
    
    def query_customer_list(self, param):
        """
        查询客户列表
        对应原PHP的query_page.php
        支持多种查询类型：customer_data, customer_data_change, customer_share, customer_flow
        
        Args:
            param (dict): 查询参数字典
            
        Returns:
            dict: 包含数据和总数的字典
        """
        # 获取查询类型
        query_type = param.get('type', 'customer_data')
        
        # 根据类型调用不同的查询方法
        if query_type == 'customer_share':
            return self._query_customer_share(param)
        elif query_type == 'customer_flow':
            return self._query_customer_flow(param)
        elif query_type == 'customer_data_change':
            return self._query_customer_change(param)
        else:
            # 默认查询普通客户列表
            return self._query_customer_data(param)
    
    def _query_customer_data(self, param):
        """
        查询普通客户列表
        """
        user_id = get_login_user_id()
        dept_id = get_login_dept_id()
        
        # 构建WHERE条件
        where_clauses = ["1=1"]
        sql_params = []
        
        # 表单ID
        if param.get('form_id'):
            where_clauses.append("k.form_id LIKE %s")
            sql_params.append(f"%{param['form_id']}%")
        
        # 客户名称
        if param.get('customer_name'):
            where_clauses.append("k.customer_name LIKE %s")
            sql_params.append(f"%{param['customer_name']}%")
        
        # 简称
        if param.get('short_name'):
            where_clauses.append("k.short_name LIKE %s")
            sql_params.append(f"%{param['short_name']}%")
        
        # 大洲
        if param.get('continent'):
            where_clauses.append("k.continent = %s")
            sql_params.append(param['continent'])
        
        # 国家
        if param.get('country'):
            where_clauses.append("k.country = %s")
            sql_params.append(param['country'])
        
        # 创建人
        if param.get('create_user_name'):
            where_clauses.append("u.user_name LIKE %s")
            sql_params.append(f"%{param['create_user_name']}%")
        
        # 状态
        if param.get('status'):
            where_clauses.append("k.status = %s")
            sql_params.append(param['status'])
        
        # 类型 - 默认使用customer_data
        type_value = param.get('type', 'customer_data')
        where_clauses.append("k.type = %s")
        sql_params.append(type_value)
        
        # 公司
        if param.get('user_bu'):
            where_clauses.append("k.user_bu = %s")
            sql_params.append(param['user_bu'])
        
        # 联系人查询
        if param.get('contact'):
            contact_where = """
                EXISTS(
                    SELECT * FROM inhe_customer_contact cc 
                    JOIN inhe_customer_data cd ON cc.form_id = cd.form_id 
                    WHERE (cd.form_id = k.form_id OR cd.related_id = k.form_id) 
                    AND CONCAT(cc.name, cc.phone_one, cc.phone_two, cc.email) REGEXP %s
                )
            """
            where_clauses.append(contact_where)
            sql_params.append(param['contact'])
        
        # 是否有效
        if param.get('is_vaild'):
            is_vaild_where = """
                ((k.is_vaild = %s AND k.version = 0)
                OR EXISTS(
                    SELECT * FROM inhe_customer_data 
                    WHERE related_id = k.form_id AND version = k.version AND is_vaild = %s
                ))
            """
            where_clauses.append(is_vaild_where)
            sql_params.append(param['is_vaild'])
            sql_params.append(param['is_vaild'])
        
        # 跟进天数 - 添加异常处理
        if param.get('flow_date'):
            from datetime import datetime, timedelta
            try:
                flow_days = int(param['flow_date'])
                flow_date = (datetime.now() - timedelta(days=flow_days)).strftime('%Y-%m-%d')
            except (ValueError, TypeError):
                # 如果转换失败，跳过这个条件
                flow_date = None
            if flow_date:  # 只有在flow_date有效时才添加条件
                flow_where = """
                    (EXISTS(
                        SELECT * FROM inhe_customer_data_flow 
                        WHERE form_id = k.form_id 
                        GROUP BY form_id 
                        HAVING MAX(flow_date) < %s
                    ) OR NOT EXISTS(
                        SELECT * FROM inhe_customer_data_flow 
                        WHERE form_id = k.form_id
                    ))
                """
                where_clauses.append(flow_where)
                sql_params.append(flow_date)
        
        # 权限控制（简化处理）
        # 原PHP有复杂的权限判断，这里简化为基础权限
        where_clauses.append("(k.user_id = %s OR k.id IN (SELECT DISTINCT customer_id FROM inhe_customer_share WHERE user_ids = %s))")
        sql_params.append(user_id)
        sql_params.append(user_id)
        
        # 排序 - 修复Bug #2: SQL注入风险
        from app.utils.sql_validator import sanitize_field_name
        order_by = "ORDER BY k.id DESC"
        if param.get('sort_field') and param.get('sort_way'):
            sort_field = param['sort_field']
            sort_way = param['sort_way'].upper()
            # 验证排序字段名
            if '.' in sort_field:
                # 处理带表别名的字段，如 k.customer_name
                table_alias, field = sort_field.split('.', 1)
                sanitize_field_name(field)
                sort_field = f"{table_alias}.`{field}`"
            else:
                sanitize_field_name(sort_field)
                sort_field = f"`{sort_field}`"
            # 验证排序方式
            if sort_way not in ['ASC', 'DESC']:
                sort_way = 'ASC'
            order_by = f"ORDER BY {sort_field} {sort_way}, k.id DESC"
        
        # 分页 - 添加异常处理
        try:
            page = max(1, int(param.get('page', 1)))  # 确保页码至少为1
        except (ValueError, TypeError):
            page = 1
        try:
            page_size = max(1, min(100, int(param.get('page_size', 10))))  # 限制每页最多100条
        except (ValueError, TypeError):
            page_size = 10
        limit = f"LIMIT {(page - 1) * page_size}, {page_size}"
        
        # 查询总数 - 使用相同的参数列表（不包含approve_user，因为count不需要flow_opinion join）
        count_sql = f"""
            SELECT COUNT(*) AS cnt 
            FROM inhe_customer_data k
            LEFT JOIN user u ON u.user_id = k.create_user_id
            WHERE {' AND '.join(where_clauses)}
        """
        count_result = Database.execute_query(count_sql, tuple(sql_params))
        total = count_result[0]['cnt'] if count_result else 0
        
        # 查询数据 - 需要额外的user_id参数用于flow_opinion的LEFT JOIN
        # 注意：approve_user的%s在WHERE之前，所以参数顺序应该是：先approve_user，然后是WHERE参数
        # 但为了保持代码清晰，我们将approve_user放在WHERE子句之后，需要调整SQL结构
        # 或者：将approve_user参数放在sql_params之前
        data_params = [user_id] + list(sql_params)
        
        data_sql = f"""
            SELECT k.*,
                   o.approve_user, o.step,
                   u.user_name AS create_user_name,
                   d.dept_name AS dept_name,
                   IFNULL(TIMESTAMPDIFF(DAY, (
                       SELECT MAX(flow_date) FROM inhe_customer_data_flow 
                       WHERE form_id = k.form_id
                   ), NOW()), 9999) AS flow_date,
                   tc.zh_name AS continent_name,
                   yc.zh_name AS country_name,
                   tc.en_name AS continent_en_name,
                   yc.country AS country_en_name
            FROM inhe_customer_data k
            LEFT JOIN user u ON u.user_id = k.create_user_id
            LEFT JOIN department d ON d.dept_id = k.create_dept_id
            LEFT JOIN continent_code tc ON tc.iso_two = k.continent
            LEFT JOIN country_code yc ON yc.iso_three = k.country AND yc.continent = k.continent
            LEFT JOIN inhe_flow_opinion o ON o.form_id = k.form_id AND o.status = 'U' AND o.approve_user = %s
            WHERE {' AND '.join(where_clauses)}
            {order_by}
            {limit}
        """
        data_result = Database.execute_query(data_sql, tuple(data_params))
        
        return {
            'list': data_result,
            'total': total,
            'page': page,
            'page_size': page_size
        }
    
    def _query_customer_change(self, param):
        """
        查询客户信息登记变更列表
        对应原PHP的list_change.php和query_page.php (type='customer_data_change')
        """
        user_id = get_login_user_id()
        dept_id = get_login_dept_id()
        
        # 构建WHERE条件
        where_clauses = ["1=1"]
        sql_params = []
        
        # 表单ID
        if param.get('form_id'):
            where_clauses.append("k.form_id LIKE %s")
            sql_params.append(f"%{param['form_id']}%")
        
        # 客户名称
        if param.get('customer_name'):
            where_clauses.append("k.customer_name LIKE %s")
            sql_params.append(f"%{param['customer_name']}%")
        
        # 状态
        if param.get('status'):
            where_clauses.append("k.status = %s")
            sql_params.append(param['status'])
        
        # 类型 - 固定为customer_data_change
        where_clauses.append("k.type = %s")
        sql_params.append('customer_data_change')
        
        # 公司
        if param.get('user_bu'):
            where_clauses.append("k.user_bu = %s")
            sql_params.append(param['user_bu'])
        
        # 权限控制
        where_clauses.append("(k.user_id = %s OR k.id IN (SELECT DISTINCT customer_id FROM inhe_customer_share WHERE user_ids = %s))")
        sql_params.append(user_id)
        sql_params.append(user_id)
        
        # 排序
        from app.utils.sql_validator import sanitize_field_name
        order_by = "ORDER BY k.id DESC"
        if param.get('sort_field') and param.get('sort_way'):
            sort_field = param['sort_field']
            sort_way = param['sort_way'].upper()
            if '.' in sort_field:
                table_alias, field = sort_field.split('.', 1)
                sanitize_field_name(field)
                sort_field = f"{table_alias}.`{field}`"
            else:
                sanitize_field_name(sort_field)
                sort_field = f"`{sort_field}`"
            if sort_way not in ['ASC', 'DESC']:
                sort_way = 'ASC'
            order_by = f"ORDER BY {sort_field} {sort_way}, k.id DESC"
        
        # 分页
        try:
            page = max(1, int(param.get('page', 1)))
        except (ValueError, TypeError):
            page = 1
        try:
            page_size = max(1, min(100, int(param.get('page_size', 10))))
        except (ValueError, TypeError):
            page_size = 10
        limit = f"LIMIT {(page - 1) * page_size}, {page_size}"
        
        # 查询总数
        count_sql = f"""
            SELECT COUNT(*) AS cnt 
            FROM inhe_customer_data k
            WHERE {' AND '.join(where_clauses)}
        """
        count_result = Database.execute_query(count_sql, tuple(sql_params))
        total = count_result[0]['cnt'] if count_result else 0
        
        # 查询数据
        data_params = [user_id] + list(sql_params)
        data_sql = f"""
            SELECT k.*,
                   o.approve_user, o.step,
                   u.user_name AS create_user_name,
                   d.dept_name AS dept_name,
                   tc.zh_name AS continent_name,
                   yc.zh_name AS country_name
            FROM inhe_customer_data k
            LEFT JOIN user u ON u.user_id = k.create_user_id
            LEFT JOIN department d ON d.dept_id = k.create_dept_id
            LEFT JOIN continent_code tc ON tc.iso_two = k.continent
            LEFT JOIN country_code yc ON yc.iso_three = k.country AND yc.continent = k.continent
            LEFT JOIN inhe_flow_opinion o ON o.form_id = k.form_id AND o.status = 'U' AND o.approve_user = %s
            WHERE {' AND '.join(where_clauses)}
            {order_by}
            {limit}
        """
        data_result = Database.execute_query(data_sql, tuple(data_params))
        
        return {
            'list': data_result,
            'total': total,
            'page': page,
            'page_size': page_size
        }
    
    def _query_customer_share(self, param):
        """
        查询客户信息共享列表
        对应原PHP的query_share.php
        """
        user_id = get_login_user_id()
        dept_id = get_login_dept_id()
        
        # 构建WHERE条件
        where_clauses = ["1=1"]
        sql_params = []
        
        # 客户名称
        if param.get('customer_name'):
            where_clauses.append("d.customer_name LIKE %s")
            sql_params.append(f"%{param['customer_name']}%")
        
        # 状态
        if param.get('status'):
            where_clauses.append("d.status = %s")
            sql_params.append(param['status'])
        
        # 权限控制 - 只查询当前用户共享的客户
        where_clauses.append("(d.user_id = %s")
        sql_params.append(user_id)
        # 添加共享权限
        where_clauses.append("OR d.id IN (SELECT DISTINCT customer_id FROM inhe_customer_share WHERE user_ids = %s))")
        sql_params.append(user_id)
        
        # 排序
        from app.utils.sql_validator import sanitize_field_name
        order_by = "ORDER BY MAX(k.id) DESC"
        if param.get('sort_field') and param.get('sort_way'):
            sort_field = param['sort_field']
            sort_way = param['sort_way'].upper()
            if '.' in sort_field:
                table_alias, field = sort_field.split('.', 1)
                sanitize_field_name(field)
                sort_field = f"{table_alias}.`{field}`"
            else:
                sanitize_field_name(sort_field)
                sort_field = f"`{sort_field}`"
            if sort_way not in ['ASC', 'DESC']:
                sort_way = 'ASC'
            order_by = f"ORDER BY {sort_field} {sort_way}, MAX(k.id) DESC"
        
        # 分页
        try:
            page = max(1, int(param.get('page', 1)))
        except (ValueError, TypeError):
            page = 1
        try:
            page_size = max(1, min(100, int(param.get('page_size', 10))))
        except (ValueError, TypeError):
            page_size = 10
        limit = f"LIMIT {(page - 1) * page_size}, {page_size}"
        
        # 查询总数
        count_sql = f"""
            SELECT COUNT(DISTINCT k.customer_id) AS cnt 
            FROM inhe_customer_share k
            LEFT JOIN inhe_customer_data d ON k.customer_id = d.id
            WHERE {' AND '.join(where_clauses)}
            GROUP BY k.customer_id
        """
        count_result = Database.execute_query(count_sql, tuple(sql_params))
        total = count_result[0]['cnt'] if count_result else 0
        
        # 查询数据
        data_sql = f"""
            SELECT k.customer_id, 
                   k.customer_name,
                   GROUP_CONCAT(DISTINCT u.user_name) AS userNameList,
                   uu.user_name AS createUserName
            FROM inhe_customer_share k
            LEFT JOIN inhe_customer_data d ON k.customer_id = d.id
            LEFT JOIN user u ON k.user_ids = u.user_id
            LEFT JOIN user uu ON d.user_id = uu.user_id
            WHERE {' AND '.join(where_clauses)}
            GROUP BY k.customer_id, k.customer_name, uu.user_name
            {order_by}
            {limit}
        """
        data_result = Database.execute_query(data_sql, tuple(sql_params))
        
        return {
            'list': data_result,
            'total': total,
            'page': page,
            'page_size': page_size
        }
    
    def _query_customer_flow(self, param):
        """
        查询跟进记录列表
        对应原PHP的query_flow.php
        """
        user_id = get_login_user_id()
        dept_id = get_login_dept_id()
        
        # 构建WHERE条件
        where_clauses = ["1=1"]
        sql_params = []
        
        # 客户名称
        if param.get('customer_name'):
            where_clauses.append("d.customer_name LIKE %s")
            sql_params.append(f"%{param['customer_name']}%")
        
        # 跟进人员
        if param.get('user_name'):
            where_clauses.append("k.user_name LIKE %s")
            sql_params.append(f"%{param['user_name']}%")
        
        # 是否有意向订单
        if param.get('order_will'):
            where_clauses.append("k.order_will = %s")
            sql_params.append(param['order_will'])
        
        # 时间范围
        if param.get('starting_time'):
            where_clauses.append("k.flow_date >= %s")
            sql_params.append(param['starting_time'])
        
        if param.get('ending_time'):
            from datetime import datetime, timedelta
            try:
                end_date = datetime.strptime(param['ending_time'], '%Y-%m-%d')
                end_date = (end_date + timedelta(days=1)).strftime('%Y-%m-%d')
                where_clauses.append("k.flow_date < %s")
                sql_params.append(end_date)
            except (ValueError, TypeError):
                pass
        
        # 权限控制
        where_clauses.append("(k.user_id = %s")
        sql_params.append(user_id)
        # 可以添加更多权限判断
        where_clauses.append(")")
        
        # 排序
        from app.utils.sql_validator import sanitize_field_name
        order_by = "ORDER BY k.id DESC"
        if param.get('sort_field') and param.get('sort_way'):
            sort_field = param['sort_field']
            sort_way = param['sort_way'].upper()
            if '.' in sort_field:
                table_alias, field = sort_field.split('.', 1)
                sanitize_field_name(field)
                sort_field = f"{table_alias}.`{field}`"
            else:
                sanitize_field_name(sort_field)
                sort_field = f"`{sort_field}`"
            if sort_way not in ['ASC', 'DESC']:
                sort_way = 'ASC'
            order_by = f"ORDER BY {sort_field} {sort_way}, k.id DESC"
        
        # 分页
        try:
            page = max(1, int(param.get('page', 1)))
        except (ValueError, TypeError):
            page = 1
        try:
            page_size = max(1, min(100, int(param.get('page_size', 10))))
        except (ValueError, TypeError):
            page_size = 10
        limit = f"LIMIT {(page - 1) * page_size}, {page_size}"
        
        # 查询总数
        count_sql = f"""
            SELECT COUNT(*) AS cnt 
            FROM inhe_customer_data_flow k
            LEFT JOIN inhe_customer_data d ON k.form_id = d.form_id
            LEFT JOIN user u ON u.user_id = k.user_id
            WHERE {' AND '.join(where_clauses)}
        """
        count_result = Database.execute_query(count_sql, tuple(sql_params))
        total = count_result[0]['cnt'] if count_result else 0
        
        # 查询数据
        data_sql = f"""
            SELECT d.customer_name,
                   k.*,
                   u.user_name AS user_name
            FROM inhe_customer_data_flow k
            LEFT JOIN inhe_customer_data d ON k.form_id = d.form_id
            LEFT JOIN user u ON u.user_id = k.user_id
            WHERE {' AND '.join(where_clauses)}
            {order_by}
            {limit}
        """
        data_result = Database.execute_query(data_sql, tuple(sql_params))
        
        return {
            'list': data_result,
            'total': total,
            'page': page,
            'page_size': page_size
        }

