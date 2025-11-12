"""
项目评审业务逻辑服务
对应原PHP项目的ActionModel.php中的业务逻辑
"""
from datetime import datetime
from app.project_review.models.project_main import ProjectMain
from app.project_review.models.customer_info import CustomerInfo
from app.project_review.models.project_info import ProjectInfo
from app.project_review.models.project_info_witlink import ProjectInfoWitlink
from app.project_review.models.project_detail import ProjectDetail
from app.project_review.models.bidding_strategy import BiddingStrategy
from app.common.form_model import FormModel
from app.common.attach_model import AttachModel
from app.common.process_model import ProcessModel
from app.utils.auth import get_login_user_id, get_login_user_name, get_login_dept_id
from app.utils.response import ResponseCode

class ProjectReviewService:
    """
    项目评审业务逻辑服务类
    对应原PHP的ActionModel类
    """
    
    SYSTEM_ID = "project_review"
    
    def query_by_id(self, param):
        """
        根据ID查询项目完整信息
        对应原PHP的ActionModel::queryById()
        
        Args:
            param (dict): 参数字典，包含id（主键）或form_id
            
        Returns:
            dict: 包含main, customer, project, projectWitlink, projectDetail, bidding的字典
        """
        # 判断是主键id还是form_id
        project_id = param.get('id')
        form_id = param.get('form_id', '')
        
        # 查询主表
        if project_id:
            try:
                project_id = int(project_id)
                main = ProjectMain.query_by_id(project_id)
            except (ValueError, TypeError):
                # 如果不是数字，当作form_id处理
                form_id = project_id
                main = ProjectMain.query_by_form_id(form_id)
        else:
            main = ProjectMain.query_by_form_id(form_id)
        
        if not main:
            return {
                'main': {},
                'customer': {},
                'project': {},
                'projectWitlink': {},
                'projectDetail': {},
                'bidding': {}
            }
        
        # 获取form_id（用于查询关联数据）
        form_id = main.get('form_id', '')
        user_bu = main.get('user_bu', '')
        
        # 查询客户信息
        customer = CustomerInfo.query_by_form_id(form_id) if form_id else {}
        
        # 查询项目信息
        project = ProjectInfo.query_by_form_id(form_id) if form_id else {}
        
        # 查询WITLINK项目信息
        project_witlink = ProjectInfoWitlink.query_by_form_id(form_id) if form_id else {}
        
        # 查询项目明细
        project_detail = ProjectDetail.query_by_form_id(form_id, user_bu) if form_id else {}
        
        # 查询投标策略
        bidding = BiddingStrategy.query_by_form_id(form_id) if form_id else {}
        
        return {
            'main': main,
            'customer': customer,
            'project': project,
            'projectWitlink': project_witlink,
            'projectDetail': project_detail,
            'bidding': bidding
        }
    
    def add(self, param):
        """
        添加项目数据
        对应原PHP的ActionModel::add()
        
        Args:
            param (dict): 参数字典
            
        Returns:
            bool: 添加是否成功
        """
        # 添加主表
        project_id = self._add_main(param)
        if project_id <= 0:
            return False
        
        # 添加客户信息
        self._add_customer_info(param)
        
        # 添加项目信息
        self._add_project_info(param)
        
        # 添加WITLINK项目信息
        self._add_project_info_witlink(param)
        
        # 添加项目明细
        self._add_project_detail(param)
        
        # 添加投标策略
        self._add_bidding_strategy(param)
        
        # 处理附件
        self._update_attach(param)
        
        return True
    
    def _add_main(self, param):
        """
        添加项目主表数据
        对应原PHP的ActionModel::addMain()
        
        Args:
            param (dict): 参数字典
            
        Returns:
            int: 插入的项目ID
        """
        user_id = get_login_user_id()
        dept_id = get_login_dept_id()
        user_name = get_login_user_name()
        
        main_param = {
            'title': param.get('title'),
            'form_id': param.get('form_id'),
            'project_code': param.get('project_code'),
            'remark': param.get('remark'),
            'organ_id': dept_id,
            'user_id': user_id,
            'user_name': user_name,
            'dept_name': param.get('deptName'),
            'write_time': datetime.now().strftime('%Y-%m-%d %H:%M:%S'),
            'status': param.get('status'),
            'type': param.get('type'),
            'is_bidding_project': param.get('is_bidding_project'),
            'change_star': param.get('change_star'),
            'create_user_bu': param.get('create_user_bu'),
            'is_collective': param.get('is_collective'),
            'past_pcode': param.get('past_pcode'),
            'c_past_pcode': param.get('c_past_pcode'),
            'user_bu': param.get('user_bu')
        }
        return ProjectMain.add_main(main_param)
    
    def _add_customer_info(self, param):
        """
        添加客户信息
        对应原PHP的ActionModel::addClientInfo()
        
        Args:
            param (dict): 参数字典
        """
        CustomerInfo.add_customer_info(param)
    
    def _add_project_info(self, param):
        """
        添加项目信息
        对应原PHP的ActionModel::addProjectInfo()
        
        Args:
            param (dict): 参数字典
        """
        ProjectInfo.add_project_info(param)
    
    def _add_project_info_witlink(self, param):
        """
        添加WITLINK项目信息
        对应原PHP的ActionModel::addProjectInfoWitlink()
        
        Args:
            param (dict): 参数字典
        """
        ProjectInfoWitlink.add_project_info_witlink(param)
    
    def _add_project_detail(self, param):
        """
        添加项目明细
        对应原PHP的ActionModel::addProjectDetail()
        
        Args:
            param (dict): 参数字典
        """
        ProjectDetail.add_project_detail(param)
    
    def _add_bidding_strategy(self, param):
        """
        添加投标策略
        对应原PHP的ActionModel::addBiddingStrategy()
        
        Args:
            param (dict): 参数字典
        """
        BiddingStrategy.add_bidding_strategy(param)
    
    def _update_attach(self, param):
        """
        更新附件
        对应原PHP的ActionModel::add()中的附件处理
        
        Args:
            param (dict): 参数字典
        """
        attach_arr = param.get('attach', [])
        if isinstance(attach_arr, list) and attach_arr:
            attach_ids = ','.join([str(aid) for aid in attach_arr if aid])
            if attach_ids:
                attach = AttachModel()
                attach.update_attach(attach_ids, param.get('form_id'))
    
    def update(self, param):
        """
        更新项目数据
        对应原PHP的ActionModel::update()
        
        Args:
            param (dict): 参数字典
            
        Returns:
            bool: 更新是否成功
        """
        form_id = param.get('form_id', '')
        if not form_id:
            return False
        
        # 更新主表
        self._update_main(param)
        
        # 更新客户信息
        self._update_customer_info(param)
        
        # 更新项目信息
        self._update_project_info(param)
        
        # 更新WITLINK项目信息
        self._update_project_info_witlink(param)
        
        # 更新项目明细
        self._update_project_detail(param)
        
        # 更新投标策略
        self._update_bidding_strategy(param)
        
        # 处理附件
        self._update_attach(param)
        
        return True
    
    def _update_main(self, param):
        """
        更新主表数据
        对应原PHP的ActionModel::updateMainData()
        
        Args:
            param (dict): 参数字典
        """
        user_name = get_login_user_name()
        user_id = get_login_user_id()
        dept_id = get_login_dept_id()
        
        update_param = {
            'user_id': user_id,
            'organ_id': dept_id,
            'user_name': user_name,
            'dept_name': param.get('deptName'),
            'update_time': datetime.now().strftime('%Y-%m-%d %H:%M:%S'),
            'title': param.get('title'),
            'remark': param.get('remark'),
            'project_code': param.get('project_code'),
            'status': param.get('status'),
            'type': param.get('type'),
            'is_bidding_project': param.get('is_bidding_project'),
            'change_star': param.get('change_star'),
            'user_bu': param.get('user_bu'),
            'is_collective': param.get('is_collective'),
            'past_pcode': param.get('past_pcode'),
            'c_past_pcode': param.get('c_past_pcode'),
            'create_user_bu': param.get('create_user_bu')
        }
        condition = {'form_id': param.get('form_id')}
        form_model = FormModel()
        form_model.update_main_data(update_param, condition, 'inhe_project_main')
    
    def _update_customer_info(self, param):
        """
        更新客户信息数据
        对应原PHP的ActionModel::updateClientData()
        
        Args:
            param (dict): 参数字典
        """
        form_model = FormModel()
        normal_field = {'form_id': 'form_id'}
        batch_field = {
            'name': 'name',
            'customer_id': 'customer_id',
            'country': 'country',
            'contact': 'contact',
            'phone': 'phone',
            'email': 'email',
            'introduction': 'introduction',
            'type': ''
        }
        condition = {'form_id': param.get('form_id')}
        count = 4  # 3种类型 + 1个空记录
        form_model.update_detail_data(normal_field, batch_field, param, condition, 'inhe_customer_info', count)
        CustomerInfo.update_customer_info(param, param.get('form_id'))
    
    def _update_project_info(self, param):
        """
        更新项目信息数据
        对应原PHP的ActionModel::updateProjectMainData()
        
        Args:
            param (dict): 参数字典
        """
        update_param = {
            'project_code': param.get('project_code'),
            'temp_project_code': param.get('temp_project_code'),
            'country': param.get('country'),
            'scope': param.get('scope'),
            'project_type': param.get('project_type'),
            'prototype': param.get('prototype'),
            'potential': param.get('potential'),
            'background': param.get('background'),
            'explain_user': param.get('explain_user'),
            'explain_project': param.get('explain_project'),
            'cooperate_history': param.get('cooperate_history'),
            'service': param.get('service')
        }
        condition = {'form_id': param.get('form_id')}
        form_model = FormModel()
        form_model.update_main_data(update_param, condition, 'inhe_project_info')
    
    def _update_project_info_witlink(self, param):
        """
        更新WITLINK项目信息数据
        对应原PHP的ActionModel::updateProjectWitlinkMainData()
        
        Args:
            param (dict): 参数字典
        """
        ProjectInfoWitlink.update_project_info_witlink(param, param.get('form_id'))
    
    def _update_project_detail(self, param):
        """
        更新项目明细数据
        对应原PHP的ActionModel::updateProjectDetailData()
        
        Args:
            param (dict): 参数字典
        """
        form_model = FormModel()
        normal_field = {'form_id': 'form_id'}
        batch_field = {
            'product': 'product',
            'price': 'price',
            'serial_number': 'serial_number',
            'first_price': 'first_price',
            'standard': 'standard',
            'supplier': 'supplier',
            'type': 'type',
            'number': 'number'
        }
        condition = {'form_id': param.get('form_id')}
        count = int(param.get('addProjectDetailCount', 0))
        form_model.update_detail_data(normal_field, batch_field, param, condition, 'inhe_project_detail', count)
        ProjectDetail.update_project_detail(param, param.get('form_id'))
    
    def _update_bidding_strategy(self, param):
        """
        更新投标策略数据
        对应原PHP的ActionModel::updateBiddingStrategy()
        
        Args:
            param (dict): 参数字典
        """
        update_param = {
            'tender_no': param.get('tender_no'),
            'end_date': param.get('end_date'),
            'project_name': param.get('project_name'),
            'tender': param.get('tender'),
            'bid_rule': param.get('bid_rule'),
            'biding_rule': param.get('biding_rule'),
            'effective_demand': param.get('effective_demand'),
            'tender_demand': param.get('tender_demand'),
            'file_demand': param.get('file_demand'),
            'format': param.get('format'),
            'reason': param.get('reason'),
            'process_description': param.get('process_description'),
            'pay_process': param.get('pay_process'),
            'pay_energy': param.get('pay_energy'),
            'partner_analysis': param.get('partner_analysis'),
            'relationship': param.get('relationship'),
            'market_analysis': param.get('market_analysis'),
            'strategy_analysis': param.get('strategy_analysis'),
            'feasibility_analysis': param.get('feasibility_analysis'),
            'key_to_success': param.get('key_to_success'),
            'personal_opinion': param.get('personal_opinion'),
            'project_level': param.get('project_level'),
            'have_manager': param.get('have_manager')
        }
        condition = {'form_id': param.get('form_id')}
        form_model = FormModel()
        form_model.update_main_data(update_param, condition, 'inhe_bidding_strategy')
    
    def change_add(self, param):
        """
        变更添加（基于原记录创建新记录）
        对应原PHP的ActionModel::changeAdd()
        
        Args:
            param (dict): 参数字典
            
        Returns:
            bool: 添加是否成功
        """
        # 变更主表
        self._change_main(param)
        
        # 变更客户信息
        self._change_customer_info(param)
        
        # 变更项目信息
        self._change_project_info(param)
        
        # 变更WITLINK项目信息
        self._change_project_info_witlink(param)
        
        # 变更项目明细
        self._change_project_detail(param)
        
        # 变更投标策略
        self._change_bidding_strategy(param)
        
        # 处理附件
        self._change_attach(param)
        
        return True
    
    def _change_main(self, param):
        """
        变更主表数据
        对应原PHP的ActionModel::changeMainData()
        
        Args:
            param (dict): 参数字典
        """
        dept_id = get_login_dept_id()
        user_id = get_login_user_id()
        
        insert_param = {
            'organ_id': dept_id,
            'user_id': user_id,
            'write_time': datetime.now().strftime('%Y-%m-%d %H:%M:%S'),
            'title': param.get('title'),
            'form_id': param.get('form_id'),
            'related_id': param.get('related_id'),
            'project_code': param.get('project_code'),
            'status': param.get('status'),
            'type': param.get('type'),
            'is_bidding_project': param.get('is_bidding_project'),
            'change_star': param.get('change_star'),
            'remark': param.get('remark'),
            'user_bu': param.get('user_bu'),
            'is_collective': param.get('is_collective'),
            'past_pcode': param.get('past_pcode'),
            'create_user_bu': param.get('create_user_bu'),
            'from_id': param.get('from_id'),
            'c_past_pcode': param.get('c_past_pcode')
        }
        condition = {'form_id': param.get('related_id')}
        form_model = FormModel()
        form_model.change_main_data(insert_param, condition, 'inhe_project_main')
    
    def _change_customer_info(self, param):
        """
        变更客户信息数据
        对应原PHP的ActionModel::changeClientData()
        
        Args:
            param (dict): 参数字典
        """
        form_model = FormModel()
        normal_field = {'form_id': 'form_id'}
        batch_field = {
            'name': 'name',
            'customer_id': 'customer_id',
            'country': 'country',
            'contact': 'contact',
            'phone': 'phone',
            'email': 'email',
            'introduction': 'introduction',
            'type': ''
        }
        condition = {'form_id': param.get('related_id')}
        count = 4
        form_model.change_detail_data(normal_field, batch_field, param, condition, 'inhe_customer_info', count)
        CustomerInfo.change_customer_info(param, param.get('form_id'), param.get('related_id'))
    
    def _change_project_info(self, param):
        """
        变更项目信息数据
        对应原PHP的ActionModel::changeProjectMainData()
        
        Args:
            param (dict): 参数字典
        """
        insert_param = {
            'form_id': param.get('form_id'),
            'project_code': param.get('project_code'),
            'temp_project_code': param.get('temp_project_code'),
            'country': param.get('country'),
            'scope': param.get('scope'),
            'project_type': param.get('project_type'),
            'prototype': param.get('prototype'),
            'potential': param.get('potential'),
            'background': param.get('background'),
            'explain_user': param.get('explain_user'),
            'explain_project': param.get('explain_project'),
            'service': param.get('service'),
            'cooperate_history': param.get('cooperate_history')
        }
        condition = {'form_id': param.get('related_id')}
        form_model = FormModel()
        form_model.change_main_data(insert_param, condition, 'inhe_project_info')
    
    def _change_project_info_witlink(self, param):
        """
        变更WITLINK项目信息数据
        对应原PHP的ActionModel::changeProjectWitlinkMainData()
        
        Args:
            param (dict): 参数字典
        """
        ProjectInfoWitlink.change_project_info_witlink(param, param.get('form_id'), param.get('related_id'))
    
    def _change_project_detail(self, param):
        """
        变更项目明细数据
        对应原PHP的ActionModel::changeProjectDetailData()
        
        Args:
            param (dict): 参数字典
        """
        form_model = FormModel()
        normal_field = {'form_id': 'form_id'}
        batch_field = {
            'product': 'product',
            'price': 'price',
            'first_price': 'first_price',
            'serial_number': 'serial_number',
            'standard': 'standard',
            'supplier': 'supplier',
            'type': 'type',
            'number': 'number'
        }
        condition = {'form_id': param.get('related_id')}
        count = int(param.get('addProjectDetailCount', 0))
        form_model.change_detail_data(normal_field, batch_field, param, condition, 'inhe_project_detail', count)
    
    def _change_bidding_strategy(self, param):
        """
        变更投标策略数据
        对应原PHP的ActionModel::changeBiddingStrategy()
        
        Args:
            param (dict): 参数字典
        """
        insert_param = {
            'form_id': param.get('form_id'),
            'tender_no': param.get('tender_no'),
            'end_date': param.get('end_date'),
            'project_name': param.get('project_name'),
            'tender': param.get('tender'),
            'bid_rule': param.get('bid_rule'),
            'biding_rule': param.get('biding_rule'),
            'effective_demand': param.get('effective_demand'),
            'tender_demand': param.get('tender_demand'),
            'file_demand': param.get('file_demand'),
            'format': param.get('format'),
            'reason': param.get('reason'),
            'process_description': param.get('process_description'),
            'pay_process': param.get('pay_process'),
            'pay_energy': param.get('pay_energy'),
            'partner_analysis': param.get('partner_analysis'),
            'relationship': param.get('relationship'),
            'market_analysis': param.get('market_analysis'),
            'strategy_analysis': param.get('strategy_analysis'),
            'feasibility_analysis': param.get('feasibility_analysis'),
            'key_to_success': param.get('key_to_success'),
            'personal_opinion': param.get('personal_opinion'),
            'project_level': param.get('project_level'),
            'have_manager': param.get('have_manager')
        }
        condition = {'form_id': param.get('related_id')}
        form_model = FormModel()
        form_model.change_main_data(insert_param, condition, 'inhe_bidding_strategy')
    
    def _change_attach(self, param):
        """
        变更附件
        对应原PHP的ActionModel::changeAdd()中的附件处理
        
        Args:
            param (dict): 参数字典
        """
        attach_arr = param.get('attach', [])
        if isinstance(attach_arr, list) and attach_arr:
            attach_ids = ','.join([str(aid) for aid in attach_arr if aid])
            if attach_ids:
                attach = AttachModel()
                attach.change_add_attach(attach_ids, param.get('form_id'))
    
    def change_update(self, param):
        """
        变更更新（更新变更记录）
        对应原PHP的ActionModel::changeUpdate()
        
        Args:
            param (dict): 参数字典
            
        Returns:
            bool: 更新是否成功
        """
        # 变更更新实际上就是更新操作
        return self.update(param)
    
    def delete(self, param):
        """
        删除项目数据
        对应原PHP的ActionModel::delete()
        
        Args:
            param (dict): 参数字典，包含id或form_id
            
        Returns:
            bool: 删除是否成功
        """
        form_id = param.get('id') or param.get('form_id', '')
        if not form_id:
            return False
        
        # 删除主表
        ProjectMain.delete_by_form_id(form_id)
        
        # 删除客户信息
        CustomerInfo.delete_by_form_id(form_id)
        
        # 删除WITLINK项目信息
        ProjectInfoWitlink.delete_by_form_id(form_id)
        
        # 删除项目信息
        ProjectInfo.delete_by_form_id(form_id)
        
        # 删除项目明细
        ProjectDetail.delete_by_form_id(form_id)
        
        # 删除投标策略
        BiddingStrategy.delete_by_form_id(form_id)
        
        # 删除流程意见
        from app.utils.db import Database
        Database.execute_update("DELETE FROM inhe_flow_opinion WHERE form_id = %s", (form_id,))
        
        # 删除附件
        Database.execute_update("DELETE FROM inhe_attachment WHERE relatedId = %s", (form_id,))
        
        return True
    
    def submit(self, param):
        """
        提交项目数据
        对应原PHP的ActionModel::submit()
        
        Args:
            param (dict): 参数字典
            
        Returns:
            dict: 返回结果
        """
        # 使用FormModel处理提交
        form_model = FormModel()
        result = form_model.submit(param)
        
        # 如果返回action，执行相应操作
        if isinstance(result, dict) and 'action' in result:
            action = result['action']
            action_param = result['param']
            
            # 检查是否有其他步骤正在审批
            process_model = ProcessModel()
            approve_info = process_model.exist_other_step(action_param)
            if approve_info:
                return ResponseCode.failed(False, '当前提交存在其他步骤正在审批')
            
            # 执行操作
            if action == 'add':
                flag = self.add(action_param)
            elif action == 'update':
                flag = self.update(action_param)
            elif action == 'changeAdd':
                flag = self.change_add(action_param)
            elif action == 'changeUpdate':
                flag = self.change_update(action_param)
            else:
                flag = False
            
            # 创建流程数据
            return form_model.make_process_data(action_param, flag)
        
        return result
    
    def save(self, param):
        """
        保存项目数据（不提交）
        对应原PHP的ActionModel::save()
        
        Args:
            param (dict): 参数字典
            
        Returns:
            dict: 返回结果
        """
        # 使用FormModel处理保存
        form_model = FormModel()
        result = form_model.save(param)
        
        # 如果返回action，执行相应操作
        if isinstance(result, dict) and 'action' in result:
            action = result['action']
            action_param = result['param']
            
            # 检查是否有其他步骤正在审批
            process_model = ProcessModel()
            approve_info = process_model.exist_other_step(action_param)
            if approve_info:
                return ResponseCode.failed(False, '当前保存存在其他步骤正在审批')
            
            # 执行操作
            if action == 'add':
                flag = self.add(action_param)
            elif action == 'update':
                flag = self.update(action_param)
            elif action == 'changeAdd':
                flag = self.change_add(action_param)
            elif action == 'changeUpdate':
                flag = self.change_update(action_param)
            else:
                flag = False
            
            return ResponseCode.success(action_param.get('form_id')) if flag else ResponseCode.failed(False, '保存失败')
        
        return result
    
    def change(self, param):
        """
        变更项目数据（提交）
        对应原PHP的ActionModel::change()
        
        Args:
            param (dict): 参数字典
            
        Returns:
            dict: 返回结果
        """
        # 使用FormModel处理提交
        form_model = FormModel()
        result = form_model.submit(param)
        
        # 如果返回action，执行相应操作
        if isinstance(result, dict) and 'action' in result:
            action = result['action']
            action_param = result['param']
            
            # 执行操作
            if action == 'changeAdd':
                flag = self.change_add(action_param)
            elif action == 'changeUpdate':
                flag = self.change_update(action_param)
            else:
                flag = False
            
            # 创建流程数据
            return form_model.make_process_data(action_param, flag)
        
        return result
    
    def change_save(self, param):
        """
        变更保存项目数据（不提交）
        对应原PHP的ActionModel::changeSave()
        
        Args:
            param (dict): 参数字典
            
        Returns:
            dict: 返回结果
        """
        # 使用FormModel处理保存
        form_model = FormModel()
        result = form_model.save(param)
        
        # 如果返回action，执行相应操作
        if isinstance(result, dict) and 'action' in result:
            action = result['action']
            action_param = result['param']
            
            # 执行操作
            if action == 'changeAdd':
                flag = self.change_add(action_param)
            elif action == 'changeUpdate':
                flag = self.change_update(action_param)
            else:
                flag = False
            
            return ResponseCode.success(action_param.get('form_id')) if flag else ResponseCode.failed(False, '保存失败')
        
        return result
    
    def update_approve_opinion(self, param):
        """
        更新审批意见
        对应原PHP的ActionModel::updateApproveOpinion()
        
        Args:
            param (dict): 参数字典
            
        Returns:
            dict: 返回结果
        """
        process = ProcessModel()
        result = process.update_process_opinion(param)
        
        # 更新项目主表中的项目星级
        if param.get('project_star') and param.get('form_id'):
            # 查询项目星级配置
            from app.utils.db import Database
            star_sql = "SELECT * FROM inhe_oa_paras WHERE type='project_star'"
            star_result = Database.execute_query(star_sql)
            count_arr = {}
            for item in star_result:
                count_arr[item['paras_value']] = item.get('extra', '')
            
            # 更新项目主表
            condition = {'form_id': param.get('form_id')}
            update_data = {
                'project_star': param.get('project_star'),
                'star_icon': count_arr.get(param.get('project_star'), '')
            }
            form_model = FormModel()
            form_model.update_main_data(update_data, condition, 'inhe_project_main')
        
        return result

