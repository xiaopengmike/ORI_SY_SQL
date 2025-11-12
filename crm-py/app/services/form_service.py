"""
表单处理服务
辅助FormModel处理表单相关业务
"""
from app.common.form_model import FormModel

class FormService:
    """
    表单处理服务类
    """
    
    def __init__(self):
        self.form_model = FormModel()
    
    def create_form_id(self, param):
        """
        创建表单ID
        
        Args:
            param (dict): 参数字典
            
        Returns:
            str: 表单ID
        """
        return self.form_model.create_flow_id(param)
    
    def make_form_title(self, param):
        """
        生成表单标题
        
        Args:
            param (dict): 参数字典
            
        Returns:
            str: 表单标题
        """
        return self.form_model.make_form_title(param)



