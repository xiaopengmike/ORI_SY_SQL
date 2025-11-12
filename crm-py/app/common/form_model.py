"""
表单处理模型
对应原PHP项目的FormModel.php
"""
from datetime import datetime
from app.utils.db import Database
from app.utils.auth import get_login_user_id
from app.common.process_model import ProcessModel
from app.utils.response import ResponseCode
from app.utils.sql_validator import sanitize_table_name, sanitize_field_name

class FormModel:
    """
    表单处理模型类
    对应原PHP的FormModel类
    """
    
    # 常量定义
    USER_ID = "user_id"
    ORGAN_ID = "organ_id"
    WRITE_TIME = "write_time"
    UPDATE_TIME = "update_time"
    HANDLING = 'U'  # 未处理
    UN_SUBMIT = 'T'  # 待提交
    UNDER_APPROVE = 'P'  # 审核中
    AGREE = '01'  # 同意
    DISAGREE = '02'  # 不同意
    
    # 表单操作类型映射
    FORM_ACTION_TYPE = {
        'update_approver': 'updateFormFlow',
        'customer_share': 'addCustomerShare',
        'change_approve_user': 'updateFlowOpinionApprover',
        'urgent_notify': 'urgentNotify',
    }
    
    def create_flow_id(self, param):
        """
        生成表单ID（流程单号）
        对应原PHP的FormModel::createFlowId()
        
        Args:
            param (dict): 参数字典，包含fieldName, tableName, flowType, digit, reset等
            
        Returns:
            str: 生成的表单ID
        """
        field_name = param.get('fieldName', 'form_id')
        table_name = param.get('tableName', '')
        flow_type = param.get('flowType', '')
        digit = int(param.get('digit', 4))
        reset = param.get('reset', 'day')  # day, month, year
        
        if not table_name or not flow_type:
            return ''
        
        # 获取当前日期前缀
        if reset == 'day':
            prefix = datetime.now().strftime('%Y%m%d')
        elif reset == 'month':
            prefix = datetime.now().strftime('%Y%m')
        elif reset == 'year':
            prefix = datetime.now().strftime('%Y')
        else:
            prefix = datetime.now().strftime('%Y%m%d')
        
        # 修复Bug #2: SQL注入风险 - 验证表名和字段名
        date_prefix = prefix
        # 验证表名和字段名
        sanitize_table_name(table_name)
        sanitize_field_name(field_name)
        sql = f"SELECT MAX(`{field_name}`) AS max_id FROM `{table_name}` WHERE `{field_name}` LIKE %s"
        result = Database.execute_query(sql, (f'{flow_type}-{date_prefix}%',))
        
        max_id = result[0]['max_id'] if result and result[0].get('max_id') else None
        
        if max_id:
            # 提取序号并加1 - 修复Bug #8: 更精确的异常处理
            try:
                seq = int(max_id.split('-')[-1])
                seq += 1
            except (ValueError, IndexError, AttributeError):
                # 如果解析失败，从1开始
                seq = 1
        else:
            seq = 1
        
        # 生成新的表单ID
        form_id = f'{flow_type}-{date_prefix}-{str(seq).zfill(digit)}'
        return form_id
    
    def make_form_title(self, param):
        """
        生成表单标题
        对应原PHP的FormModel::makeFormTitle()
        
        Args:
            param (dict): 参数字典
            
        Returns:
            str: 表单标题
        """
        # 简单实现，可以根据实际需求调整
        customer_name = param.get('customer_name', '')
        form_id = param.get('form_id', '')
        if customer_name:
            return f'{customer_name}-{form_id}'
        return form_id
    
    def submit(self, param):
        """
        提交表单
        对应原PHP的FormModel::submit()
        
        Args:
            param (dict): 参数字典
            
        Returns:
            dict: 返回结果，包含action和param
        """
        param['status'] = self.UN_SUBMIT
        process = ProcessModel()
        response = ResponseCode()
        
        form_type = param.get('form_type', '')
        if form_type in self.FORM_ACTION_TYPE:
            # 特殊表单类型处理
            form_action = self.FORM_ACTION_TYPE[form_type]
            # 这里需要根据实际情况调用ProcessModel的相应方法
            # process.form_action(param)
            return response.success(param.get('form_id'))
        else:
            # 普通表单处理
            if param.get('form_id'):
                action = 'changeUpdate' if param.get('action') == 'Change' else 'update'
                return {
                    'action': action,
                    'param': param
                }
            else:
                # 生成新的表单ID
                param['form_id'] = self.create_flow_id(param)
                param['title'] = self.make_form_title(param)
                action = 'changeAdd' if param.get('action') == 'Change' else 'add'
                return {
                    'action': action,
                    'param': param
                }
    
    def save(self, param):
        """
        保存表单（不提交）
        对应原PHP的FormModel::save()
        
        Args:
            param (dict): 参数字典
            
        Returns:
            dict: 返回结果，包含action和param
        """
        param['status'] = self.UN_SUBMIT
        
        if param.get('form_id'):
            action = 'changeUpdate' if param.get('action') in ['Change', 'ChangeSave'] else 'update'
            return {
                'action': action,
                'param': param
            }
        else:
            # 生成新的表单ID
            param['form_id'] = self.create_flow_id(param)
            param['title'] = self.make_form_title(param)
            action = 'changeAdd' if param.get('action') in ['Change', 'ChangeSave'] else 'add'
            return {
                'action': action,
                'param': param
            }
    
    def make_process_data(self, param, flag):
        """
        创建流程数据
        对应原PHP的FormModel::makeProcessData()
        
        Args:
            param (dict): 参数字典
            flag (bool): 操作是否成功
            
        Returns:
            dict: 返回结果
        """
        if not flag:
            return ResponseCode.failed(False, '操作失败')
        
        process = ProcessModel()
        user_id = get_login_user_id()
        
        # 获取下一审批人
        app_param = {
            'step': param.get('step', 1),
            'type': param.get('type'),
            'user_bu': param.get('user_bu')
        }
        approver_arr = process.get_approver(app_param)
        
        if not approver_arr:
            return ResponseCode.failed(False, '未找到审批人')
        
        approver_info = approver_arr[0]
        
        # 创建流程审批记录
        flow_param = {
            'type': param.get('type'),
            'form_id': param.get('form_id'),
            'title': param.get('title'),
            'step': param.get('step', 1),
            'work_flow': approver_info.get('role'),
            'common_opinion': approver_info.get('common_opinion'),
            'approve_user': user_id,
            'status': self.HANDLING,
            'user_bu': param.get('user_bu')
        }
        process.update_flow_data(flow_param)
        
        return ResponseCode.success(param.get('form_id'))
    
    def update_main_data(self, param_insert, condition, table_name, attach=None, related_id=None):
        """
        更新主表数据
        对应原PHP的FormModel::updateMainData()
        
        Args:
            param_insert (dict): 要更新的字段字典
            condition (dict): 条件字典
            table_name (str): 表名
            attach (list): 附件ID列表
            related_id (str): 关联ID
            
        Returns:
            bool: 更新是否成功
        """
        if not condition:
            return False
        
        # 修复Bug #2: SQL注入风险 - 验证表名和字段名
        sanitize_table_name(table_name)
        # 验证所有字段名
        for field in list(param_insert.keys()) + list(condition.keys()):
            sanitize_field_name(field)
        
        # 构建UPDATE SQL - 使用反引号包裹字段名
        set_clause = ', '.join([f"`{k}` = %s" for k in param_insert.keys()])
        where_clause = ' AND '.join([f"`{k}` = %s" for k in condition.keys()])
        
        sql = f"UPDATE `{table_name}` SET {set_clause} WHERE {where_clause}"
        params = list(param_insert.values()) + list(condition.values())
        
        Database.execute_update(sql, tuple(params))
        return True
    
    def update_detail_data(self, normal_field, batch_field, form_data, condition, table_name, count):
        """
        更新明细表数据
        对应原PHP的FormModel::updateDetailData()
        
        Args:
            normal_field (dict): 普通字段映射
            batch_field (dict): 批量字段映射
            form_data (dict): 表单数据
            condition (dict): 条件字典
            table_name (str): 表名
            count (int): 明细行数
            
        Returns:
            bool: 更新是否成功
        """
        # 修复Bug #2: SQL注入风险 - 验证表名
        sanitize_table_name(table_name)
        
        # 先删除旧数据
        if condition:
            # 验证条件字段名
            for field in condition.keys():
                sanitize_field_name(field)
            where_clause = ' AND '.join([f"`{k}` = %s" for k in condition.keys()])
            delete_sql = f"DELETE FROM `{table_name}` WHERE {where_clause}"
            Database.execute_update(delete_sql, tuple(condition.values()))
        
        # 插入新数据
        if count <= 0:
            return True
        
        # 验证所有字段名
        all_fields = list(normal_field.keys()) + list(batch_field.keys())
        for field in all_fields:
            sanitize_field_name(field)
        
        for i in range(1, count + 1):
            field_names = []
            field_values = []
            params = []
            
            # 普通字段
            for n_key, n_val in normal_field.items():
                field_names.append(f"`{n_key}`")
                field_values.append('%s')
                params.append(form_data.get(n_val))
            
            # 批量字段
            for b_key, b_val in batch_field.items():
                field_names.append(f"`{b_key}`")
                field_values.append('%s')
                field_value = form_data.get(f"{b_val}{i}", '')
                params.append(field_value)
            
            insert_sql = f"INSERT INTO `{table_name}` ({', '.join(field_names)}) VALUES ({', '.join(field_values)})"
            Database.execute_insert(insert_sql, tuple(params))
        
        return True
    
    def change_main_data(self, param_insert, condition, table_name, attach=None, related_id=None):
        """
        变更主表数据（插入新记录）
        对应原PHP的FormModel::changeMainData()
        
        Args:
            param_insert (dict): 要插入的字段字典
            condition (dict): 条件字典（用于查询原记录）
            table_name (str): 表名
            attach (list): 附件ID列表
            related_id (str): 关联ID
            
        Returns:
            bool: 插入是否成功
        """
        # 修复Bug #2: SQL注入风险 - 验证表名和字段名
        sanitize_table_name(table_name)
        # 验证所有字段名
        for field in param_insert.keys():
            sanitize_field_name(field)
        
        # 构建INSERT SQL - 使用反引号包裹字段名
        field_names = [f"`{k}`" for k in param_insert.keys()]
        field_values = ['%s'] * len(field_names)
        params = list(param_insert.values())
        
        insert_sql = f"INSERT INTO `{table_name}` ({', '.join(field_names)}) VALUES ({', '.join(field_values)})"
        Database.execute_insert(insert_sql, tuple(params))
        return True
    
    def change_detail_data(self, normal_field, batch_field, form_data, condition, table_name, count):
        """
        变更明细表数据（基于related_id插入新记录）
        对应原PHP的FormModel::changeDetailData()
        
        Args:
            normal_field (dict): 普通字段映射
            batch_field (dict): 批量字段映射
            form_data (dict): 表单数据
            condition (dict): 条件字典（用于查询原记录）
            table_name (str): 表名
            count (int): 明细行数
            
        Returns:
            bool: 插入是否成功
        """
        # 修复Bug #2: SQL注入风险 - 验证表名和字段名
        sanitize_table_name(table_name)
        # 验证所有字段名
        all_fields = list(normal_field.keys()) + list(batch_field.keys())
        for field in all_fields:
            sanitize_field_name(field)
        
        # 基于原记录插入新记录（使用新的form_id）
        if count <= 0:
            return True
        
        for i in range(1, count + 1):
            field_names = []
            field_values = []
            params = []
            
            # 普通字段
            for n_key, n_val in normal_field.items():
                field_names.append(f"`{n_key}`")
                field_values.append('%s')
                params.append(form_data.get(n_val))
            
            # 批量字段
            for b_key, b_val in batch_field.items():
                field_names.append(f"`{b_key}`")
                field_values.append('%s')
                field_value = form_data.get(f"{b_val}{i}", '')
                params.append(field_value)
            
            insert_sql = f"INSERT INTO `{table_name}` ({', '.join(field_names)}) VALUES ({', '.join(field_values)})"
            Database.execute_insert(insert_sql, tuple(params))
        
        return True
    
    def verify_not_exist(self, table_name, condition, neq_condition=None):
        """
        验证记录是否存在
        对应原PHP的FormModel::verifyNotExist()
        
        Args:
            table_name (str): 表名
            condition (dict): 条件字典
            neq_condition (dict): 不等于条件字典（用于排除自己）
            
        Returns:
            bool: True表示不存在，False表示已存在
        """
        # 修复Bug #2: SQL注入风险 - 验证表名和字段名
        sanitize_table_name(table_name)
        # 验证所有字段名
        for field in condition.keys():
            sanitize_field_name(field)
        if neq_condition:
            for field in neq_condition.keys():
                sanitize_field_name(field)
        
        where_clause = ' AND '.join([f"`{k}` = %s" for k in condition.keys()])
        params = list(condition.values())
        
        if neq_condition:
            where_clause += ' AND ' + ' AND '.join([f"`{k}` != %s" for k in neq_condition.keys()])
            params.extend(list(neq_condition.values()))
        
        sql = f"SELECT COUNT(*) AS cnt FROM `{table_name}` WHERE {where_clause}"
        result = Database.execute_query(sql, tuple(params))
        
        return result[0]['cnt'] == 0 if result else True

