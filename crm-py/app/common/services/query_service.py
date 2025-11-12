"""
列表查询服务
对应原PHP的query_page.php
"""
from app.utils.db import Database
from app.utils.auth import get_login_user_id, get_login_dept_id, get_admin_data_query
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
        
        # 权限控制 - 对应原PHP的query_page.php第152-174行
        admin_data = get_admin_data_query(user_id, dept_id, 'customer_data')
        is_admin = admin_data.get('isAdmin', False)
        
        permission_conditions = ["k.user_id = %s"]
        permission_params = [user_id]
        
        if is_admin:
            # 管理员权限：可查看自己创建的 + 所属公司 + 所属部门 + 指定用户的数据
            user_bu = admin_data.get('userBu', '')
            if user_bu:
                permission_conditions.append(f"k.user_bu IN ({user_bu})")
            
            dept_ids = admin_data.get('deptIds', '')
            if dept_ids:
                permission_conditions.append(f"k.organ_id IN ({dept_ids})")
            
            user_ids = admin_data.get('userIds', '')
            if user_ids:
                permission_conditions.append(f"k.user_id IN ({user_ids})")
        
        # 添加共享数据权限：通过inhe_customer_share共享给当前用户的客户
        permission_conditions.append("k.id IN (SELECT DISTINCT customer_id FROM inhe_customer_share WHERE user_ids = %s)")
        permission_params.append(user_id)
        
        # 组合权限条件
        where_clauses.append(f"({' OR '.join(permission_conditions)})")
        sql_params.extend(permission_params)
        
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
        
        # 查询总数 - 使用相同的WHERE条件（不包含approve_user，因为count不需要flow_opinion join）
        # 注意：count查询的sql_params不包含approve_user参数，所以使用sql_params而不是data_params
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
        try:
            user_id = get_login_user_id()
            dept_id = get_login_dept_id()
            
            # 获取管理员权限信息
            admin_data = get_admin_data_query(user_id, dept_id, 'customer_data')
            is_admin = admin_data.get('isAdmin', False)
            
            # 构建WHERE条件
            where_clauses = ["1=1"]
            sql_params = []
            
            # 客户名称 - 使用k.customer_name（从inhe_customer_share表）
            if param.get('customer_name'):
                where_clauses.append("k.customer_name LIKE %s")
                sql_params.append(f"%{param['customer_name']}%")
            
            # 状态过滤（如果提供）
            if param.get('status'):
                where_clauses.append("k.status = %s")
                sql_params.append(param['status'])
            
            # 权限控制
            # 原PHP逻辑：如果是管理员，可以查看自己创建的、自己公司的、自己部门的、自己用户的客户
            # 如果不是管理员，只能查看自己创建的客户
            # 注意：原PHP代码中，共享列表的权限控制是基于d.user_id（客户创建人）
            # 共享列表应该显示用户创建的、并且已经共享给其他用户的客户
            # 注意：k.user_ids是text类型，可能包含多个用户ID（用逗号分隔），需要使用FIND_IN_SET
            # 注意：处理LEFT JOIN可能返回NULL的情况，移除d.user_id IS NOT NULL检查，允许NULL值参与比较
            # 如果d为NULL（LEFT JOIN失败），d.user_id也会是NULL，NULL = userId 会返回false，这是正确的
            
            # 构建权限条件
            permission_conditions = ["d.user_id = %s"]
            sql_params.append(user_id)
            
            # 如果是管理员，添加额外的权限范围
            if is_admin:
                # 添加公司权限
                user_bu = admin_data.get('userBu', '')
                if user_bu:
                    # user_bu已经是格式化的字符串，如 "'INHE','INHENERGY'"
                    permission_conditions.append(f"d.user_bu IN ({user_bu})")
                
                # 添加部门权限
                dept_ids = admin_data.get('deptIds', '')
                if dept_ids:
                    # dept_ids已经是格式化的字符串
                    permission_conditions.append(f"d.organ_id IN ({dept_ids})")
                
                # 添加用户权限
                user_ids = admin_data.get('userIds', '')
                if user_ids:
                    # user_ids已经是格式化的字符串
                    permission_conditions.append(f"d.user_id IN ({user_ids})")
            
            # 添加共享权限：查看共享给自己的客户（即使不是自己创建的）
            # 使用FIND_IN_SET处理text类型的user_ids字段（可能包含多个用户ID，用逗号分隔）
            permission_conditions.append("FIND_IN_SET(%s, k.user_ids) > 0")
            sql_params.append(user_id)
            
            # 组合权限条件
            where_clauses.append(f"({' OR '.join(permission_conditions)})")
            
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
            
            # 查询总数 - 使用子查询避免GROUP BY导致的多行问题
            count_sql = f"""
                SELECT COUNT(*) AS cnt 
                FROM (
                    SELECT k.customer_id
                    FROM inhe_customer_share k
                    LEFT JOIN inhe_customer_data d ON k.customer_id = d.id
                    WHERE {' AND '.join(where_clauses)}
                    GROUP BY k.customer_id
                ) AS subquery
            """
            count_result = Database.execute_query(count_sql, tuple(sql_params))
            total = count_result[0]['cnt'] if count_result else 0
            
            # 查询数据
            # 注意：k.user_ids是text类型，可能包含多个用户ID（用逗号分隔）
            # 需要使用FIND_IN_SET或LIKE来匹配，但GROUP_CONCAT需要从关联表中获取
            # 原PHP代码中，LEFT JOIN user u ON k.user_ids = u.user_id 可能不准确
            # 因为k.user_ids可能包含多个用户ID，需要特殊处理
            # 这里先使用简单的JOIN，如果k.user_ids是单个用户ID则能正常工作
            # 如果k.user_ids包含多个用户ID，需要额外的处理逻辑
            data_sql = f"""
                SELECT k.customer_id, 
                       k.customer_name,
                       GROUP_CONCAT(DISTINCT u.user_name ORDER BY u.user_name SEPARATOR ',') AS userNameList,
                       uu.user_name AS createUserName
                FROM inhe_customer_share k
                LEFT JOIN inhe_customer_data d ON k.customer_id = d.id
                LEFT JOIN user u ON FIND_IN_SET(u.user_id, k.user_ids) > 0
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
        except Exception as e:
            # 添加错误处理和日志
            import traceback
            error_msg = f"查询客户信息共享列表错误: {str(e)}"
            print(error_msg)
            traceback.print_exc()
            # 返回空结果而不是抛出异常，避免500错误
            return {
                'list': [],
                'total': 0,
                'page': param.get('page', 1),
                'page_size': param.get('page_size', 10)
            }
    
    def _query_customer_flow(self, param):
        """
        查询跟进记录列表
        对应原PHP的query_flow.php
        """
        from app.utils.auth import get_admin_data_query
        
        user_id = get_login_user_id()
        dept_id = get_login_dept_id()
        system_id = "customer_data"
        
        # 获取管理员权限信息
        admin_data = get_admin_data_query(user_id, dept_id, system_id)
        is_admin = admin_data.get('isAdmin', False)
        
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
        
        # 用户工号（精确查询）
        if param.get('user_byid'):
            where_clauses.append("u.user_byid = %s")
            sql_params.append(param['user_byid'].strip())
        
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
        
        # 权限控制 - 参考原PHP代码实现
        # 构建权限条件字符串（使用OR连接，整体用括号包裹）
        permission_parts = []
        permission_params = []
        
        if is_admin:
            # 管理员权限：可查看自己的记录 + 同公司别名 + 同部门 + 指定用户
            permission_parts.append("k.user_id = %s")
            permission_params.append(user_id)
            
            # 同公司别名
            user_bu = admin_data.get('userBu', '')
            if user_bu:
                permission_parts.append(f"k.user_bu IN ({user_bu})")
            
            # 同部门
            dept_ids = admin_data.get('deptIds', '')
            if dept_ids:
                permission_parts.append(f"k.dept_id IN ({dept_ids})")
            
            # 指定用户
            user_ids = admin_data.get('userIds', '')
            if user_ids:
                permission_parts.append(f"k.user_id IN ({user_ids})")
        else:
            # 普通用户权限：只能查看自己的记录
            permission_parts.append("k.user_id = %s")
            permission_params.append(user_id)
        
        # 将权限条件作为一个整体添加到WHERE子句
        if permission_parts:
            permission_condition = "(" + " OR ".join(permission_parts) + ")"
            where_clauses.append(permission_condition)
            sql_params.extend(permission_params)
        
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
    
    def query_project_review_list(self, param):
        """
        查询项目评审列表
        对应原PHP的get_data.php和list.php的查询逻辑
        
        Args:
            param (dict): 查询参数字典
            
        Returns:
            dict: 包含数据和总数的字典
        """
        user_id = get_login_user_id()
        dept_id = get_login_dept_id()
        
        # 获取通用参数
        from app.common.data_model import DataModel
        data_model = DataModel()
        select_param = {
            'is_used': '1',
            'type': 'project_star,crm_process_sort'
        }
        select_arr = data_model.get_common_param(select_param)
        
        # 处理project_star
        project_star = {}
        star_icon = {}
        if 'project_star' in select_arr and select_arr['project_star']:
            for co in select_arr['project_star']:
                project_star[co['paras_value']] = co.get('paras_desc', '')
                star_icon[co['paras_value']] = co.get('extra', '')
        
        # 处理company
        company = {}
        if 'crm_process_sort' in select_arr and select_arr['crm_process_sort']:
            for cc in select_arr['crm_process_sort']:
                company[cc['paras_value']] = cc.get('paras_desc', '')
        
        # 构建WHERE条件
        where_clauses = ["1=1"]
        sql_params = []
        
        # 表单ID
        if param.get('form_id'):
            where_clauses.append("m.form_id LIKE %s")
            sql_params.append(f"%{param['form_id']}%")
        
        # 项目代码
        if param.get('project_code'):
            where_clauses.append("m.project_code LIKE %s")
            sql_params.append(f"%{param['project_code']}%")
        
        # 国家
        if param.get('country'):
            where_clauses.append("p.country = %s")
            sql_params.append(param['country'])
        
        # 状态
        if param.get('status'):
            where_clauses.append("m.status = %s")
            sql_params.append(param['status'])
        
        # 公司
        if param.get('user_bu'):
            where_clauses.append("m.user_bu = %s")
            sql_params.append(param['user_bu'])
        
        # 类型 - 固定为project_review
        where_clauses.append("m.type = %s")
        sql_params.append('project_review')
        
        # 权限控制 - 只能查看自己创建的项目
        where_clauses.append("m.user_id = %s")
        sql_params.append(user_id)
        
        # 排序
        from app.utils.sql_validator import sanitize_field_name
        order_by = "ORDER BY m.id DESC"
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
            order_by = f"ORDER BY {sort_field} {sort_way}, m.id DESC"
        
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
            FROM inhe_project_main m
            LEFT JOIN inhe_project_info p ON p.form_id = m.form_id
            WHERE {' AND '.join(where_clauses)}
        """
        count_result = Database.execute_query(count_sql, tuple(sql_params))
        total = count_result[0]['cnt'] if count_result else 0
        
        # 查询数据
        data_sql = f"""
            SELECT m.*,
                   u.user_name AS create_user_name,
                   d.dept_name AS dept_name,
                   p.country AS country
            FROM inhe_project_main m
            LEFT JOIN user u ON u.user_id = m.user_id
            LEFT JOIN department d ON d.dept_id = m.organ_id
            LEFT JOIN inhe_project_info p ON p.form_id = m.form_id
            WHERE {' AND '.join(where_clauses)}
            {order_by}
            {limit}
        """
        data_result = Database.execute_query(data_sql, tuple(sql_params))
        
        # 添加额外字段
        for item in data_result:
            item['project_star_desc'] = project_star.get(item.get('project_star', ''), '')
            item['star_icon'] = star_icon.get(item.get('project_star', ''), '')
            item['company'] = company.get(item.get('user_bu', ''), '')
        
        return {
            'list': data_result,
            'total': total,
            'page': page,
            'page_size': page_size
        }

