"""
认证控制器
处理登录相关功能
"""
from flask import Blueprint, render_template, request, session, redirect, url_for, flash
from app.utils.db import Database

auth_bp = Blueprint('auth', __name__)

@auth_bp.route('/login', methods=['GET', 'POST'])
def login():
    """
    登录页面
    """
    if request.method == 'POST':
        user_id = request.form.get('user_id', '').strip()
        password = request.form.get('password', '').strip()
        
        if not user_id or not password:
            flash('请输入用户名和密码')
            return render_template('auth/login.html')
        
        # 简化登录验证：直接查询数据库
        # 实际项目中应该验证密码
        sql = "SELECT u.user_id, u.user_name, u.dept_id, d.dept_name " \
              "FROM user u " \
              "LEFT JOIN department d ON d.dept_id = u.dept_id " \
              "WHERE u.user_id = %s LIMIT 1"
        result = Database.execute_query(sql, (user_id,))
        
        if result:
            user = result[0]
            # 设置session
            session['LOGIN_USER_ID'] = user['user_id']
            session['LOGIN_USER_NAME'] = user.get('user_name', user_id)
            session['LOGIN_DEPT_ID'] = user.get('dept_id', 1)
            session['LOGIN_DEPT_NAME'] = user.get('dept_name', '')
            
            # 获取用户公司（简化处理）
            session['LOGIN_USER_BU'] = 'INHENERGY'  # 默认值，实际应从数据库获取
            
            return redirect(url_for('customer.index'))
        else:
            flash('用户名或密码错误')
    
    return render_template('auth/login.html')

@auth_bp.route('/logout')
def logout():
    """
    登出
    """
    session.clear()
    return redirect(url_for('auth.login'))

