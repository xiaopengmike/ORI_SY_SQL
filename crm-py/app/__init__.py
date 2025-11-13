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
    # 注意：模板现在分散在各个模块中，将template_folder设置为app目录
    # 这样可以通过相对路径访问所有模块的模板
    import os
    base_dir = os.path.dirname(__file__)
    # 将template_folder设置为app目录，模板路径使用相对路径
    template_dir = base_dir
    static_dir = os.path.join(base_dir, 'static')
    
    app = Flask(__name__, 
                template_folder=template_dir,
                static_folder=static_dir)
    app.config.from_object(config_class)
    
    # 注册蓝图
    from app.common.controllers.auth_controller import auth_bp
    from app.customer.controllers.customer_controller import customer_bp
    from app.common.controllers.action_controller import action_bp
    from app.common.controllers.common_list_controller import common_list_bp
    from app.project_review.controllers.project_review_controller import project_review_bp
    from app.project_review.controllers.project_review_action_controller import project_review_action_bp
    from app.sales_contract_review.controllers.sales_contract_review_controller import sales_contract_review_bp
    from app.sales_contract_review.controllers.sales_contract_review_action_controller import sales_contract_review_action_bp
    
    app.register_blueprint(auth_bp)
    app.register_blueprint(customer_bp, url_prefix='/customer')
    app.register_blueprint(action_bp, url_prefix='/customer')
    app.register_blueprint(common_list_bp, url_prefix='/common')
    app.register_blueprint(project_review_bp, url_prefix='/project_review')
    app.register_blueprint(project_review_action_bp, url_prefix='/project_review')
    app.register_blueprint(sales_contract_review_bp, url_prefix='/sales_contract_review')
    app.register_blueprint(sales_contract_review_action_bp, url_prefix='/sales_contract_review')
    
    # 添加根路径路由，重定向到客户列表
    @app.route('/')
    def index():
        from flask import redirect, url_for
        return redirect(url_for('customer.index'))
    
    return app

