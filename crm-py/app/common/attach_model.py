"""
附件模型
对应原PHP项目的AttachModel.php
"""
from app.utils.db import Database
import json

class AttachModel:
    """
    附件模型类
    对应原PHP的AttachModel类
    """
    
    def get_attach_by_id(self, form_id):
        """
        根据form_id获取附件列表
        对应原PHP的AttachModel::getAttachById()
        
        Args:
            form_id (str): 表单ID
            
        Returns:
            str: JSON格式的附件列表字符串
        """
        sql = "SELECT * FROM inhe_attachment WHERE relatedId = %s"
        result = Database.execute_query(sql, (form_id,))
        return json.dumps(result) if result else '[]'
    
    def update_attach(self, attach_ids, form_id):
        """
        更新附件关联
        对应原PHP的AttachModel::updateAttach()
        
        Args:
            attach_ids (str): 附件ID列表（逗号分隔）
            form_id (str): 表单ID
        """
        if not attach_ids or not form_id:
            return
        
        # 将附件ID列表转换为数组
        attach_id_list = [aid.strip() for aid in attach_ids.split(',') if aid.strip()]
        
        if not attach_id_list:
            return
        
        # 更新附件关联
        placeholders = ','.join(['%s'] * len(attach_id_list))
        sql = f"UPDATE inhe_attachment SET relatedId = %s WHERE id IN ({placeholders})"
        params = [form_id] + attach_id_list
        Database.execute_update(sql, tuple(params))
    
    def change_add_attach(self, attach_ids, form_id):
        """
        变更添加附件关联
        对应原PHP的AttachModel::changeAddAttach()
        
        Args:
            attach_ids (str): 附件ID列表（逗号分隔）
            form_id (str): 表单ID
        """
        # 变更添加附件和更新附件逻辑相同
        self.update_attach(attach_ids, form_id)

