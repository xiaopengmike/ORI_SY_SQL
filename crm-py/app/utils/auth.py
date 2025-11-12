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

