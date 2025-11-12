"""
客户业务逻辑服务
对应原PHP项目的ActionModel.php中的业务逻辑
"""
from datetime import datetime
from app.customer.models.customer import Customer
from app.customer.models.contact import Contact
from app.customer.models.company import Company
from app.customer.models.flow import Flow
from app.customer.models.share import Share
from app.common.form_model import FormModel
from app.utils.auth import get_login_user_id, get_login_user_name, get_login_dept_id
from app.utils.response import ResponseCode

class CustomerService:
    """
    客户业务逻辑服务类
    对应原PHP的ActionModel类
    """
    
    SYSTEM_ID = "customer_data"
    
    def query_by_id(self, param):
        """
        根据ID查询客户完整信息
        优先使用主键id查询，如果没有id则使用form_id查询（向后兼容）
        对应原PHP的ActionModel::queryById()
        
        Args:
            param (dict): 参数字典，包含id（主键）或form_id
            
        Returns:
            dict: 包含main, contacts, company, share, flow的字典
        """
        # 优先使用主键id查询
        customer_id = param.get('id')
        form_id = param.get('form_id', '')
        
        # 判断id是否为数字（主键id是整数）
        is_primary_id = False
        if customer_id:
            try:
                customer_id = int(customer_id)
                is_primary_id = True
            except (ValueError, TypeError):
                # 如果不是数字，则当作form_id处理（向后兼容）
                form_id = customer_id
                customer_id = None
        
        # 如果既没有主键id也没有form_id，返回空数据
        if not is_primary_id and not form_id:
            return {
                'main': {},
                'contacts': [],
                'company': [],
                'share': '',
                'flow': []
            }
        
        # 查询主表
        if is_primary_id:
            main = Customer.query_by_primary_id(customer_id)
        else:
            main = Customer.query_by_form_id(form_id)
        
        # 修复Bug #4: 空值处理问题
        if not main:
            return {
                'main': {},
                'contacts': [],
                'company': [],
                'share': '',
                'flow': []
            }
        
        # 获取form_id（用于查询关联数据）
        form_id = main.get('form_id', '')
        # 获取主键id（用于查询分享信息）
        primary_id = main.get('id')
        
        # 查询联系人
        contacts = Contact.query_by_form_id(form_id) if form_id else []
        
        # 查询公司信息
        company = Company.query_by_form_id(form_id) if form_id else []
        
        # 查询分享信息 - 使用主键id
        share = ''
        if primary_id:
            share = Share.query_by_customer_id(primary_id)
        
        # 查询流程记录
        related_id = main.get('related_id')
        flow = Flow.query_by_form_id(form_id, related_id) if form_id else []
        
        return {
            'main': main,
            'contacts': contacts,
            'company': company,
            'share': share,
            'flow': flow
        }
    
    def add(self, param):
        """
        添加客户数据
        对应原PHP的ActionModel::add()
        
        Args:
            param (dict): 参数字典
            
        Returns:
            bool: 添加是否成功
        """
        # 添加主表
        customer_id = Customer.add_main(param)
        if customer_id <= 0:
            return False
        
        param['customer_id'] = customer_id
        
        # 添加联系人
        self._add_contacts(param)
        
        # 添加公司信息
        self._add_companies(param)
        
        return True
    
    def _add_contacts(self, param):
        """
        添加联系人列表
        对应原PHP的ActionModel::addContacts()
        
        Args:
            param (dict): 参数字典
        """
        contact_count = int(param.get('addContactCount', 0))
        if contact_count <= 0:
            return
        
        for i in range(1, contact_count + 1):
            contact_name = param.get(f'contacts_name{i}', '')
            if not contact_name:
                continue
            
            contact_param = {
                'customer_id': param.get('customer_id'),
                'form_id': param.get('form_id'),
                'name': contact_name,
                'phone_one': param.get(f'phone_one{i}', ''),
                'phone_two': param.get(f'phone_two{i}', ''),
                'email': param.get(f'email{i}', ''),
                'position': param.get(f'position{i}', ''),
                'address': param.get(f'contacts_address{i}', ''),
                'remark': param.get(f'remark{i}', '')
            }
            Contact.add_contact(contact_param)
    
    def _add_companies(self, param):
        """
        添加公司信息列表
        对应原PHP的ActionModel::addCompany()
        
        Args:
            param (dict): 参数字典
        """
        company_count = int(param.get('addCompanyCount', 0))
        if company_count <= 0:
            return
        
        for i in range(1, company_count + 1):
            company_name = param.get(f'company_name{i}', '')
            if not company_name:
                continue
            
            company_param = {
                'customer_id': param.get('customer_id'),
                'form_id': param.get('form_id'),
                'contract_party': company_name,
                'short_name': param.get(f'company_short_name{i}', ''),
                'country': param.get(f'company_country{i}', ''),
                'address': param.get(f'company_address{i}', ''),
                'contact_name': param.get(f'contact_name{i}', ''),
                'phone': param.get(f'phone{i}', '')
            }
            Company.add_company(company_param)
    
    def update(self, param):
        """
        更新客户数据
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
        self._update_main(param, form_id)
        
        # 更新联系人
        self._update_contacts(param)
        
        # 更新公司信息
        self._update_companies(param)
        
        return True
    
    def _update_main(self, param, form_id):
        """
        更新主表数据
        对应原PHP的ActionModel::updateMainData()
        
        Args:
            param (dict): 参数字典
            form_id (str): 表单ID
        """
        update_param = {
            'update_time': datetime.now().strftime('%Y-%m-%d %H:%M:%S'),
            'title': param.get('title'),
            'customer_name': param.get('customer_name'),
            'short_name': param.get('short_name'),
            'continent': param.get('continent'),
            'country': param.get('country'),
            'website': param.get('website'),
            'address': param.get('address'),
            'summarize': param.get('summarize'),
            'user_name': param.get('user_name'),
            'dept_name': param.get('dept_name'),
            'customer_type': param.get('customer_type'),
            'customer_source': param.get('customer_source'),
            'focus': param.get('focus'),
            'is_vaild': param.get('is_vaild'),
            'change_explain': param.get('change_explain'),
            'status': param.get('status')
        }
        Customer.update_main(update_param, form_id)
    
    def _update_contacts(self, param):
        """
        更新联系人数据
        对应原PHP的ActionModel::updateContactData()
        
        Args:
            param (dict): 参数字典
        """
        form_id = param.get('form_id', '')
        form_model = FormModel()
        
        normal_field = {'form_id': 'form_id'}
        batch_field = {
            'name': 'contacts_name',
            'phone_one': 'phone_one',
            'phone_two': 'phone_two',
            'email': 'email',
            'position': 'position',
            'address': 'contacts_address',
            'remark': 'remark'
        }
        condition = {'form_id': form_id}
        count = int(param.get('addContactCount', 0))
        
        form_model.update_detail_data(normal_field, batch_field, param, condition, 'inhe_customer_contact', count)
        
        # 删除空名称的联系人
        Contact.delete_empty_by_form_id(form_id)
    
    def _update_companies(self, param):
        """
        更新公司信息数据
        对应原PHP的ActionModel::updateCompanyData()
        
        Args:
            param (dict): 参数字典
        """
        form_id = param.get('form_id', '')
        form_model = FormModel()
        
        normal_field = {'form_id': 'form_id'}
        batch_field = {
            'contract_party': 'company_name',
            'short_name': 'company_short_name',
            'country': 'company_country',
            'address': 'company_address',
            'contact_name': 'contact_name',
            'phone': 'phone'
        }
        condition = {'form_id': form_id}
        count = int(param.get('addCompanyCount', 0))
        
        form_model.update_detail_data(normal_field, batch_field, param, condition, 'inhe_customer_company', count)
        
        # 删除空合同方的公司信息
        Company.delete_empty_by_form_id(form_id)
    
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
        
        # 变更联系人
        self._change_contacts(param)
        
        # 变更公司信息
        self._change_companies(param)
        
        return True
    
    def _change_main(self, param):
        """
        变更主表数据
        对应原PHP的ActionModel::changeMainData()
        
        Args:
            param (dict): 参数字典
        """
        user_id = get_login_user_id()
        dept_id = get_login_dept_id()
        
        insert_param = {
            'organ_id': dept_id,
            'user_id': user_id,
            'create_dept_id': dept_id,
            'create_user_id': user_id,
            'write_time': datetime.now().strftime('%Y-%m-%d %H:%M:%S'),
            'title': param.get('title'),
            'form_id': param.get('form_id'),
            'related_id': param.get('related_id'),
            'customer_name': param.get('customer_name'),
            'short_name': param.get('short_name'),
            'continent': param.get('continent'),
            'country': param.get('country'),
            'website': param.get('website'),
            'address': param.get('address'),
            'summarize': param.get('summarize'),
            'user_name': param.get('user_name'),
            'dept_name': param.get('dept_name'),
            'status': param.get('status'),
            'type': param.get('type'),
            'customer_type': param.get('customer_type'),
            'customer_source': param.get('customer_source'),
            'focus': param.get('focus'),
            'is_vaild': param.get('is_vaild'),
            'change_explain': param.get('change_explain'),
            'user_bu': param.get('user_bu')
        }
        
        form_model = FormModel()
        condition = {'form_id': param.get('related_id')}
        form_model.change_main_data(insert_param, condition, 'inhe_customer_data')
    
    def _change_contacts(self, param):
        """
        变更联系人数据
        对应原PHP的ActionModel::changeContactData()
        
        Args:
            param (dict): 参数字典
        """
        form_model = FormModel()
        
        normal_field = {'form_id': 'form_id'}
        batch_field = {
            'name': 'contacts_name',
            'phone_one': 'phone_one',
            'phone_two': 'phone_two',
            'email': 'email',
            'position': 'position',
            'address': 'contacts_address',
            'remark': 'remark'
        }
        condition = {'form_id': param.get('related_id')}
        count = int(param.get('addContactCount', 0))
        
        form_model.change_detail_data(normal_field, batch_field, param, condition, 'inhe_customer_contact', count)
        
        # 删除空名称的联系人
        Contact.delete_empty_by_form_id(param.get('form_id'))
    
    def _change_companies(self, param):
        """
        变更公司信息数据
        对应原PHP的ActionModel::changeCompanyData()
        
        Args:
            param (dict): 参数字典
        """
        form_model = FormModel()
        
        normal_field = {'form_id': 'form_id'}
        batch_field = {
            'contract_party': 'company_name',
            'short_name': 'company_short_name',
            'country': 'company_country',
            'address': 'company_address',
            'contact_name': 'contact_name',
            'phone': 'phone'
        }
        condition = {'form_id': param.get('related_id')}
        count = int(param.get('addCompanyCount', 0))
        
        form_model.change_detail_data(normal_field, batch_field, param, condition, 'inhe_customer_company', count)
        
        # 删除空合同方的公司信息
        Company.delete_empty_by_form_id(param.get('form_id'))
    
    def delete(self, param):
        """
        删除客户数据
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
        Customer.delete_by_form_id(form_id)
        
        # 删除联系人
        Contact.delete_by_form_id(form_id)
        
        # 删除公司信息
        Company.delete_by_form_id(form_id)
        
        # 删除流程意见（如果需要）
        from app.utils.db import Database
        Database.execute_update("DELETE FROM inhe_flow_opinion WHERE form_id = %s", (form_id,))
        
        return True
    
    def verify_not_exist(self, param, field='customer_name'):
        """
        验证字段值是否不存在
        对应原PHP的ActionModel::verifyNotExist()
        
        Args:
            param (dict): 参数字典
            field (str): 要验证的字段名
            
        Returns:
            bool: True表示不存在（可以添加），False表示已存在
        """
        return Customer.verify_not_exist(param, field)
    
    def submit(self, param):
        """
        提交客户数据
        对应原PHP的ActionModel::submit()
        
        Args:
            param (dict): 参数字典
            
        Returns:
            dict: 返回结果
        """
        # 验证联系人（INHENERGY公司需要）
        is_nergy = param.get('user_bu') in ['INHENERGY', 'HKNERGY']
        if is_nergy and not param.get('form_type'):
            if int(param.get('addContactCount', 0)) == 0:
                return ResponseCode.failed(False, '联系人不能为空')
            
            # 验证联系人姓名和联系方式
            has_name = False
            has_contact = False
            for i in range(1, int(param.get('addContactCount', 0)) + 1):
                if param.get(f'contacts_name{i}'):
                    has_name = True
                    if param.get(f'phone_one{i}') or param.get(f'phone_two{i}') or param.get(f'email{i}'):
                        has_contact = True
                        break
            
            if not has_name:
                return ResponseCode.failed(False, '联系人姓名不能为空')
            if not has_contact:
                return ResponseCode.failed(False, '电话或邮箱不能为空')
        
        # 使用FormModel处理提交
        form_model = FormModel()
        result = form_model.submit(param)
        
        # 如果返回action，执行相应操作
        if isinstance(result, dict) and 'action' in result:
            action = result['action']
            action_param = result['param']
            
            # 验证客户名称和简称是否已存在
            if action_param.get('customer_name') and not self.verify_not_exist(action_param, 'customer_name'):
                return ResponseCode.failed(False, '客户名称已存在，请重新填写')
            
            if action_param.get('short_name') and not self.verify_not_exist(action_param, 'short_name'):
                return ResponseCode.failed(False, '简称已存在，请重新填写')
            
            # 执行操作
            if action == 'add':
                flag = self.add(action_param)
            elif action == 'update':
                flag = self.update(action_param)
            elif action == 'changeAdd':
                flag = self.change_add(action_param)
            elif action == 'changeUpdate':
                flag = self.update(action_param)
            else:
                flag = False
            
            # 创建流程数据
            return form_model.make_process_data(action_param, flag)
        
        return result
    
    def save(self, param):
        """
        保存客户数据（不提交）
        对应原PHP的ActionModel::save()
        
        Args:
            param (dict): 参数字典
            
        Returns:
            dict: 返回结果
        """
        # 验证联系人（INHENERGY公司需要）
        is_nergy = param.get('user_bu') in ['INHENERGY', 'HKNERGY']
        if is_nergy and not param.get('form_type'):
            if int(param.get('addContactCount', 0)) == 0:
                return ResponseCode.failed(False, '联系人不能为空')
            
            # 验证联系人姓名和联系方式
            has_name = False
            has_contact = False
            for i in range(1, int(param.get('addContactCount', 0)) + 1):
                if param.get(f'contacts_name{i}'):
                    has_name = True
                    if param.get(f'phone_one{i}') or param.get(f'phone_two{i}') or param.get(f'email{i}'):
                        has_contact = True
                        break
            
            if not has_name:
                return ResponseCode.failed(False, '联系人姓名不能为空')
            if not has_contact:
                return ResponseCode.failed(False, '电话或邮箱不能为空')
        
        # 使用FormModel处理保存
        form_model = FormModel()
        result = form_model.save(param)
        
        # 如果返回action，执行相应操作
        if isinstance(result, dict) and 'action' in result:
            action = result['action']
            action_param = result['param']
            
            # 验证客户名称和简称是否已存在
            if action_param.get('customer_name') and not self.verify_not_exist(action_param, 'customer_name'):
                return ResponseCode.failed(False, '客户名称已存在，请重新填写')
            
            if action_param.get('short_name') and not self.verify_not_exist(action_param, 'short_name'):
                return ResponseCode.failed(False, '简称已存在，请重新填写')
            
            # 执行操作
            if action == 'add':
                flag = self.add(action_param)
            elif action == 'update':
                flag = self.update(action_param)
            elif action == 'changeAdd':
                flag = self.change_add(action_param)
            elif action == 'changeUpdate':
                flag = self.update(action_param)
            else:
                flag = False
            
            return ResponseCode.success(action_param.get('form_id')) if flag else ResponseCode.failed(False, '保存失败')
        
        return result
    
    def change(self, param):
        """
        变更客户数据（提交）
        对应原PHP的ActionModel::change()
        
        Args:
            param (dict): 参数字典
            
        Returns:
            dict: 返回结果
        """
        # 验证联系人（INHENERGY公司需要）
        is_nergy = param.get('user_bu') in ['INHENERGY', 'HKNERGY']
        if is_nergy and not param.get('form_type'):
            if int(param.get('addContactCount', 0)) == 0:
                return ResponseCode.failed(False, '联系人不能为空')
            
            # 验证联系人姓名和联系方式
            has_name = False
            has_contact = False
            for i in range(1, int(param.get('addContactCount', 0)) + 1):
                if param.get(f'contacts_name{i}'):
                    has_name = True
                    if param.get(f'phone_one{i}') or param.get(f'phone_two{i}') or param.get(f'email{i}'):
                        has_contact = True
                        break
            
            if not has_name:
                return ResponseCode.failed(False, '联系人姓名不能为空')
            if not has_contact:
                return ResponseCode.failed(False, '电话或邮箱不能为空')
        
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
                flag = self.update(action_param)
            else:
                flag = False
            
            # 创建流程数据
            return form_model.make_process_data(action_param, flag)
        
        return result
    
    def change_save(self, param):
        """
        变更保存客户数据（不提交）
        对应原PHP的ActionModel::changeSave()
        
        Args:
            param (dict): 参数字典
            
        Returns:
            dict: 返回结果
        """
        # 验证联系人（INHENERGY公司需要）
        is_nergy = param.get('user_bu') in ['INHENERGY', 'HKNERGY']
        if is_nergy and not param.get('form_type'):
            if int(param.get('addContactCount', 0)) == 0:
                return ResponseCode.failed(False, '联系人不能为空')
            
            # 验证联系人姓名和联系方式
            has_name = False
            has_contact = False
            for i in range(1, int(param.get('addContactCount', 0)) + 1):
                if param.get(f'contacts_name{i}'):
                    has_name = True
                    if param.get(f'phone_one{i}') or param.get(f'phone_two{i}') or param.get(f'email{i}'):
                        has_contact = True
                        break
            
            if not has_name:
                return ResponseCode.failed(False, '联系人姓名不能为空')
            if not has_contact:
                return ResponseCode.failed(False, '电话或邮箱不能为空')
        
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
                flag = self.update(action_param)
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
        from app.common.process_model import ProcessModel
        process = ProcessModel()
        return process.update_process_opinion(param)

