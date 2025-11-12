"""
响应工具类
对应原PHP项目的ResponseCode.php
"""
import json

class ResponseCode:
    """
    响应码类
    对应原PHP项目的ResponseCode类
    """
    
    @staticmethod
    def success(data=None, msg='success'):
        """
        成功响应
        对应原PHP的$response->success()
        
        Args:
            data: 返回的数据
            msg: 消息
            
        Returns:
            dict: 响应字典
        """
        return {
            'code': '10000',  # 修改为10000以匹配前端JavaScript期望
            'status': 'success',
            'msg': msg,
            'data': data
        }
    
    @staticmethod
    def failed(data=None, msg='failed'):
        """
        失败响应
        对应原PHP的$response->failed()
        
        Args:
            data: 返回的数据
            msg: 消息
            
        Returns:
            dict: 响应字典
        """
        return {
            'code': '0',
            'status': 'failed',
            'msg': msg,
            'data': data
        }
    
    @staticmethod
    def to_json(data):
        """
        转换为JSON字符串
        对应原PHP的json_encode()
        
        Args:
            data: 要转换的数据
            
        Returns:
            str: JSON字符串
        """
        return json.dumps(data, ensure_ascii=False)



