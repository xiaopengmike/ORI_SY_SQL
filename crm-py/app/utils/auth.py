"""
认证工具
对应原PHP项目的auth.inc.php
"""
from functools import wraps
from flask import session, redirect, url_for, request

def login_required(f):
    """
    登录验证装饰器
    对应原PHP项目的include_once("inc/auth.inc.php")
    
    Args:
        f: 被装饰的函数
        
    Returns:
        装饰后的函数
    """
    @wraps(f)
    def decorated_function(*args, **kwargs):
        # 检查session中是否有登录信息
        # 原PHP使用$_SESSION['LOGIN_USER_ID']
        if 'LOGIN_USER_ID' not in session:
            # 如果未登录，重定向到登录页
            from flask import redirect, url_for
            return redirect(url_for('auth.login'))
        return f(*args, **kwargs)
    return decorated_function

def get_login_user_id():
    """
    获取当前登录用户ID
    对应原PHP的$_SESSION['LOGIN_USER_ID']
    
    Returns:
        str: 用户ID
    """
    return session.get('LOGIN_USER_ID', '')

def get_login_user_name():
    """
    获取当前登录用户名称
    对应原PHP的$_SESSION['LOGIN_USER_NAME']
    
    Returns:
        str: 用户名称
    """
    return session.get('LOGIN_USER_NAME', '')

def get_login_dept_id():
    """
    获取当前登录用户部门ID
    对应原PHP的$_SESSION['LOGIN_DEPT_ID']
    
    Returns:
        int: 部门ID
    """
    return session.get('LOGIN_DEPT_ID', 0)

def get_login_dept_name():
    """
    获取当前登录用户部门名称
    对应原PHP的$_SESSION['LOGIN_DEPT_NAME']
    
    Returns:
        str: 部门名称
    """
    return session.get('LOGIN_DEPT_NAME', '')

def get_admin_data_query(user_id, dept_id, system_type):
    """
    获取用户对应模块的查询权限
    对应原PHP的UserModel::getAdminDataQuery()
    
    Args:
        user_id (str): 用户ID
        dept_id (int): 部门ID
        system_type (str): 系统类型，如'customer_data'
        
    Returns:
        dict: 包含isAdmin, systemAdmin, userBu, deptIds, userIds等权限信息
    """
    from app.utils.db import Database
    
    where_clauses = ["1=1"]
    sql_params = []
    
    if user_id:
        where_clauses.append("user_id = %s")
        sql_params.append(user_id)
    elif dept_id:
        where_clauses.append("dept_id = %s")
        sql_params.append(str(dept_id))
    
    if system_type:
        where_clauses.append("type = %s")
        sql_params.append(system_type)
    
    sql = f"SELECT user_bu, dept_ids, user_ids, is_admin, system_admin FROM inhe_system_admin WHERE {' AND '.join(where_clauses)} ORDER BY id DESC"
    result = Database.execute_query(sql, tuple(sql_params))
    
    is_admin = False
    system_admin = False
    user_bu_str = ""
    dept_ids_str = ""
    user_ids_str = ""
    
    if result:
        # 取第一条记录（按id DESC排序，取最新的）
        item = result[0]
        is_admin = bool(item.get('is_admin', 0))
        system_admin = bool(item.get('system_admin', 0))
        
        user_bu = item.get('user_bu', '')
        if user_bu:
            # 将逗号分隔的字符串转换为SQL IN格式
            user_bu_list = [bu.strip() for bu in user_bu.split(',') if bu.strip()]
            if user_bu_list:
                user_bu_str = ','.join([f"'{bu}'" for bu in user_bu_list])
        
        dept_ids = item.get('dept_ids', '')
        if dept_ids:
            dept_ids_list = [dept.strip() for dept in dept_ids.split(',') if dept.strip()]
            if dept_ids_list:
                dept_ids_str = ','.join([f"'{dept}'" for dept in dept_ids_list])
        
        user_ids = item.get('user_ids', '')
        if user_ids:
            user_ids_list = [uid.strip() for uid in user_ids.split(',') if uid.strip()]
            if user_ids_list:
                user_ids_str = ','.join([f"'{uid}'" for uid in user_ids_list])
    
    return {
        'isAdmin': is_admin,
        'systemAdmin': system_admin,
        'userBu': user_bu_str,
        'deptIds': dept_ids_str,
        'userIds': user_ids_str
    }

