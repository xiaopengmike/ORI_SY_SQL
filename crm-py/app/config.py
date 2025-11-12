"""
应用配置文件
对应原PHP项目的config.php
"""
import os
import configparser

class Config:
    """应用配置类"""
    
    # Flask配置
    SECRET_KEY = os.environ.get('SECRET_KEY') or 'dev-secret-key-change-in-production'
    DEBUG = True
    
    # 读取配置文件 - 修复Bug #5: 添加文件存在性检查和错误处理
    config = configparser.ConfigParser()
    config_path = os.path.join(os.path.dirname(os.path.dirname(__file__)), 'config.ini')
    
    # 检查配置文件是否存在
    if os.path.exists(config_path):
        config.read(config_path, encoding='utf-8')
    else:
        # 如果配置文件不存在，使用默认值
        print(f"警告: 配置文件 {config_path} 不存在，使用默认配置")
        config.read_string('[database]\n')
    
    # 数据库配置
    DB_HOST = config.get('database', 'host', fallback='192.168.4.89')
    DB_PORT = config.getint('database', 'port', fallback=3306)
    DB_USER = config.get('database', 'user', fallback='inhe1329')
    DB_PASSWORD = config.get('database', 'password', fallback='inhe1329')
    DB_NAME = config.get('database', 'database', fallback='td_oa_no_data')
    DB_CHARSET = config.get('database', 'charset', fallback='gbk')

