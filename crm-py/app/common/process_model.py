"""
流程处理模型
对应原PHP项目的ProcessModel.php
简化版本，仅包含customer_data模块需要的功能
"""
from app.utils.db import Database
from app.utils.response import ResponseCode

class ProcessModel:
    """
    流程处理模型类
    对应原PHP的ProcessModel类
    """
    
    TYPE = 'crm_process_type'
    
    def get_approver(self, param):
        """
        获取审批人信息
        对应原PHP的ProcessModel::getApprover()
        
        Args:
            param (dict): 参数字典，包含step, type, user_bu, role等
            
        Returns:
            list: 审批人信息列表
        """
        where_clauses = ["1=1"]
        params = []
        
        if param.get('step'):
            where_clauses.append("k.step = %s")
            params.append(param['step'])
        
        if param.get('type'):
            where_clauses.append("k.type = %s")
            params.append(param['type'])
        
        if param.get('user_bu'):
            where_clauses.append("k.user_bu = %s")
            params.append(param['user_bu'])
        
        if param.get('role'):
            where_clauses.append("k.role = %s")
            params.append(param['role'])
        
        sql = f"SELECT k.* FROM inhe_flow_manage k WHERE {' AND '.join(where_clauses)}"
        return Database.execute_query(sql, tuple(params))
    
    def get_flow_step(self, form_type, user_bu):
        """
        获取流程步骤
        对应原PHP的ProcessModel::getFlowStep()
        
        Args:
            form_type (str): 表单类型
            user_bu (str): 用户公司
            
        Returns:
            dict: 流程步骤字典，key为step，value为步骤信息
        """
        sql = """
            SELECT * FROM inhe_flow_manage 
            WHERE type = %s AND user_bu = %s 
            ORDER BY step
        """
        result = Database.execute_query(sql, (form_type, user_bu))
        
        flow_dict = {}
        for item in result:
            flow_dict[item['step']] = item
        
        return flow_dict
    
    def get_approve_data(self, param):
        """
        获取审批数据
        对应原PHP的ProcessModel::getApproveData()
        
        Args:
            param (dict): 参数字典，包含form_id, step等
            
        Returns:
            dict: 审批数据
        """
        form_id = param.get('form_id', '')
        step = param.get('step', '')
        
        if not form_id:
            return {}
        
        where_clauses = ["form_id = %s"]
        params = [form_id]
        
        if step:
            where_clauses.append("step = %s")
            params.append(step)
        
        sql = f"SELECT * FROM inhe_flow_opinion WHERE {' AND '.join(where_clauses)} ORDER BY step"
        result = Database.execute_query(sql, tuple(params))
        
        return result[0] if result else {}
    
    def update_flow_data(self, param):
        """
        更新流程数据
        对应原PHP的ProcessModel::updateFlowData()
        
        Args:
            param (dict): 流程参数字典
            
        Returns:
            bool: 更新是否成功
        """
        # 检查是否已存在
        check_sql = "SELECT id FROM inhe_flow_opinion WHERE form_id = %s AND step = %s"
        existing = Database.execute_query(check_sql, (param.get('form_id'), param.get('step')))
        
        if existing:
            # 更新
            update_fields = ['title', 'work_flow', 'common_opinion', 'approve_user', 'status', 'user_bu']
            set_clause = ', '.join([f"{k} = %s" for k in update_fields if k in param])
            params = [param.get(k) for k in update_fields if k in param]
            params.extend([param.get('form_id'), param.get('step')])
            
            sql = f"UPDATE inhe_flow_opinion SET {set_clause} WHERE form_id = %s AND step = %s"
        else:
            # 插入
            fields = ['type', 'form_id', 'title', 'step', 'work_flow', 'common_opinion', 'approve_user', 'status', 'user_bu']
            field_names = [f for f in fields if f in param]
            field_values = ['%s'] * len(field_names)
            params = [param.get(f) for f in field_names]
            
            sql = f"INSERT INTO inhe_flow_opinion ({', '.join(field_names)}) VALUES ({', '.join(field_values)})"
        
        Database.execute_update(sql, tuple(params))
        return True
    
    def update_process_opinion(self, param):
        """
        更新审批意见
        对应原PHP的ProcessModel::updateProcessOpinion()
        
        Args:
            param (dict): 参数字典，包含form_id, step, opinion, operate等
            operate: 'Approve' 或 'Back'，会被映射为 '01' 或 '02'
            
        Returns:
            dict: 返回结果
        """
        form_id = param.get('form_id', '')
        step = param.get('step', '')
        opinion = param.get('opinion', '')
        operate = param.get('operate', '')  # Approve, Back
        
        if not form_id or not step:
            return ResponseCode.failed(False, '参数不完整')
        
        # 将operate参数映射为if_agree值
        # Approve -> '01', Back -> '02'
        if_agree = ''
        if operate == 'Approve':
            if_agree = '01'
        elif operate == 'Back':
            if_agree = '02'
        
        # 更新审批意见
        sql = """
            UPDATE inhe_flow_opinion 
            SET opinion = %s, if_agree = %s, approve_time = NOW()
            WHERE form_id = %s AND step = %s
        """
        Database.execute_update(sql, (opinion, if_agree, form_id, step))
        
        # 根据操作类型更新状态
        if operate == 'Approve':
            # 审批通过，进入下一步或完成
            # 这里需要根据实际流程逻辑处理
            pass
        elif operate == 'Back':
            # 退回
            # 这里需要根据实际流程逻辑处理
            pass
        
        return ResponseCode.success(True)
    
    def find_approve_info(self, param):
        """
        查找审批信息
        对应原PHP的ProcessModel::findApproveInfo()
        
        Args:
            param (dict): 参数字典，包含form_id, step, approve_user等
            
        Returns:
            dict: 审批信息
        """
        where_clauses = []
        params = []
        
        if param.get('form_id'):
            where_clauses.append("form_id = %s")
            params.append(param['form_id'])
        
        if param.get('step'):
            where_clauses.append("step = %s")
            params.append(param['step'])
        
        if param.get('approve_user'):
            where_clauses.append("approve_user = %s")
            params.append(param['approve_user'])
        
        if not where_clauses:
            return {}
        
        sql = f"SELECT * FROM inhe_flow_opinion WHERE {' AND '.join(where_clauses)} AND status = 'U'"
        result = Database.execute_query(sql, tuple(params))
        
        return result[0] if result else {}
    
    def find_back_record(self, param):
        """
        查找退回记录
        对应原PHP的ProcessModel::findBackRecord()
        
        Args:
            param (dict): 参数字典，包含form_id等
            
        Returns:
            list: 退回记录列表
        """
        form_id = param.get('form_id', '')
        if not form_id:
            return []
        
        sql = """
            SELECT * FROM inhe_flow_opinion 
            WHERE form_id = %s AND if_agree = '02'
            ORDER BY step DESC
        """
        return Database.execute_query(sql, (form_id,))
    
    def find_pass_record(self, param):
        """
        查找通过记录
        对应原PHP的ProcessModel::findPassRecord()
        
        Args:
            param (dict): 参数字典，包含form_id等
            
        Returns:
            list: 通过记录列表
        """
        form_id = param.get('form_id', '')
        if not form_id:
            return []
        
        sql = """
            SELECT * FROM inhe_flow_opinion 
            WHERE form_id = %s AND if_agree = '01' AND opinion != ''
            ORDER BY step
        """
        return Database.execute_query(sql, (form_id,))



