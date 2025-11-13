"""
SQL安全验证工具
用于验证表名和字段名，防止SQL注入
"""
import re

# 允许的表名白名单
ALLOWED_TABLES = {
    'inhe_customer_data',
    'inhe_customer_contact',
    'inhe_customer_company',
    'inhe_customer_data_flow',
    'inhe_customer_share',
    'inhe_flow_opinion',
    'inhe_flow_manage',
    'inhe_oa_paras',
    'continent_code',
    'country_code',
    'user',
    'department',
}

# 允许的字段名模式（字母、数字、下划线）
FIELD_NAME_PATTERN = re.compile(r'^[a-zA-Z_][a-zA-Z0-9_]*$')

def validate_table_name(table_name):
    """
    验证表名是否在白名单中
    
    Args:
        table_name (str): 表名
        
    Returns:
        bool: True表示有效，False表示无效
    """
    if not table_name:
        return False
    return table_name in ALLOWED_TABLES

def validate_field_name(field_name):
    """
    验证字段名是否符合规范
    
    Args:
        field_name (str): 字段名
        
    Returns:
        bool: True表示有效，False表示无效
    """
    if not field_name:
        return False
    return bool(FIELD_NAME_PATTERN.match(field_name))

def sanitize_table_name(table_name):
    """
    清理并验证表名
    
    Args:
        table_name (str): 表名
        
    Returns:
        str: 验证后的表名，如果无效则抛出异常
    """
    if not validate_table_name(table_name):
        raise ValueError(f"无效的表名: {table_name}")
    return table_name

def sanitize_field_name(field_name):
    """
    清理并验证字段名
    
    Args:
        field_name (str): 字段名
        
    Returns:
        str: 验证后的字段名，如果无效则抛出异常
    """
    if not validate_field_name(field_name):
        raise ValueError(f"无效的字段名: {field_name}")
    return field_name





