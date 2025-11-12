"""
Flask应用初始化
对应原PHP项目的入口文件
"""
from flask import Flask
from app.config import Config

def create_app(config_class=Config):
    """
    创建Flask应用实例
    
    Args:
        config_class: 配置类
        
    Returns:
        Flask应用实例
    """
    # 修复Bug #6: 在创建Flask实例时设置模板和静态文件路径
    import os
    template_dir = os.path.join(os.path.dirname(__file__), 'templates')
    static_dir = os.path.join(os.path.dirname(__file__), 'static')
    
    app = Flask(__name__, 
                template_folder=template_dir,
                static_folder=static_dir)
    app.config.from_object(config_class)
    
    # 注册蓝图
    from app.controllers.auth_controller import auth_bp
    from app.controllers.customer_controller import customer_bp
    from app.controllers.action_controller import action_bp
    from app.controllers.project_code_controller import project_code_bp
    from app.controllers.project_review_controller import project_review_bp
    
    app.register_blueprint(auth_bp)
    app.register_blueprint(customer_bp, url_prefix='/customer')
    app.register_blueprint(action_bp, url_prefix='/customer')
    app.register_blueprint(project_code_bp, url_prefix='/project_code')
    app.register_blueprint(project_review_bp, url_prefix='/project_review')
    
    # 添加根路径路由，重定向到客户列表
    @app.route('/')
    def index():
        from flask import redirect, url_for
        return redirect(url_for('customer.index'))
    
    return app

