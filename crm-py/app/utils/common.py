"""
通用工具函数
对应原PHP项目的utility_all.php等工具文件
"""
import re
from datetime import datetime

def get_param_to_dict(request):
    """
    获取请求参数（GET和POST合并）
    对应原PHP的getParamToArray()
    
    Args:
        request: Flask request对象
        
    Returns:
        dict: 参数字典
    """
    # 合并GET和POST参数，POST优先
    params = {}
    if request.method == 'GET':
        params.update(request.args.to_dict())
    if request.method == 'POST':
        params.update(request.form.to_dict())
        # 如果POST是JSON格式
        if request.is_json:
            params.update(request.get_json() or {})
    return params

def array_iconv(data, in_charset='gbk', out_charset='utf-8', return_type='json'):
    """
    数组编码转换
    对应原PHP的array_iconv()
    
    Args:
        data: 要转换的数据
        in_charset: 输入编码
        out_charset: 输出编码
        return_type: 返回类型（'json'或'array'）
        
    Returns:
        str或dict: 转换后的数据
    """
    import json
    # Python中字符串已经是unicode，这里主要处理字典的编码
    if return_type == 'json':
        return json.dumps(data, ensure_ascii=False)
    return data

def escape_sql_string(value):
    """
    SQL字符串转义
    对应原PHP的mysql_real_escape_string()
    
    Args:
        value: 要转义的值
        
    Returns:
        str: 转义后的字符串
    """
    if value is None:
        return ''
    # 使用pymysql的参数化查询，这里仅作简单处理
    return str(value).replace("'", "''").replace("\\", "\\\\")

def format_datetime(dt=None, format_str='%Y-%m-%d %H:%M:%S'):
    """
    格式化日期时间
    对应原PHP的date()
    
    Args:
        dt: datetime对象，None则使用当前时间
        format_str: 格式字符串
        
    Returns:
        str: 格式化后的日期时间字符串
    """
    if dt is None:
        dt = datetime.now()
    return dt.strftime(format_str)

def if_empty(value):
    """
    判断是否为空
    对应原PHP的ifEmpty()
    
    Args:
        value: 要判断的值
        
    Returns:
        bool: True表示为空，False表示不为空
    """
    if value is None:
        return True
    if isinstance(value, str):
        return value.strip() == ''
    if isinstance(value, (list, dict)):
        return len(value) == 0
    return False





