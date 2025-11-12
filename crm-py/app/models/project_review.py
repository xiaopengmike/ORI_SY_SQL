"""
立项申请主表模型
对应原PHP项目的ActionModel.php中的立项申请主表操作
对应数据库表: inhe_project_main
"""
from app.utils.db import Database
from datetime import datetime

class ProjectReview:
    """
    立项申请主表模型类
    对应原PHP的ActionModel类中的项目主表相关方法
    """
    
    def __init__(self, data=None):
        """
        初始化立项申请对象
        
        Args:
            data (dict): 立项申请数据字典
        """
        if data:
            self.id = data.get('id')
            self.organ_id = data.get('organ_id')
            self.user_id = data.get('user_id')
            self.write_time = data.get('write_time')
            self.update_time = data.get('update_time')
            self.title = data.get('title')
            self.form_id = data.get('form_id')
            self.related_id = data.get('related_id')
            self.project_code = data.get('project_code')
            self.if_project = data.get('if_project')
            self.project_star = data.get('project_star')
            self.star_icon = data.get('star_icon')
            self.remark = data.get('remark')
            self.attach_name = data.get('attach_name')
            self.dept_name = data.get('dept_name')
            self.user_name = data.get('user_name')
            self.create_date = data.get('create_date')
            self.flow_type = data.get('flow_type')
            self.status = data.get('status')
            self.type = data.get('type')
            self.user_bu = data.get('user_bu')
            self.version = data.get('version', 0)
            self.is_bidding_project = data.get('is_bidding_project')
            self.change_star = data.get('change_star', '0')
            self.temp_project_code = data.get('temp_project_code')
            self.is_collective = data.get('is_collective')
            self.past_pcode = data.get('past_pcode')
            self.c_past_pcode = data.get('c_past_pcode')
            self.from_id = data.get('from_id')
            self.create_user_bu = data.get('create_user_bu')
            # 关联查询字段
            self.create_user_name = data.get('createUserName')
            self.dept_name_full = data.get('deptName')
    
    @staticmethod
    def query_by_form_id(form_id):
        """
        根据form_id查询立项申请主表信息
        对应原PHP的ActionModel::queryMain()
        
        Args:
            form_id (str): 表单ID
            
        Returns:
            dict: 立项申请主表信息字典
        """
        sql = """
            SELECT k.*, 
                   u.user_name AS createUserName, 
                   d.dept_name AS deptName 
            FROM inhe_project_main k 
            LEFT JOIN user u ON u.user_id = k.user_id 
            LEFT JOIN department d ON d.dept_id = k.organ_id 
            WHERE k.form_id = %s
        """
        result = Database.execute_query(sql, (form_id,))
        return result[0] if result else {}
    
    @staticmethod
    def add_main(param):
        """
        添加立项申请主表数据
        对应原PHP的ActionModel::addMain()
        
        Args:
            param (dict): 立项申请参数字典
            
        Returns:
            int: 插入的ID
        """
        sql = """
            INSERT INTO inhe_project_main (
                title, form_id, project_code, remark, organ_id, user_id, user_name,
                dept_name, write_time, status, type, is_bidding_project, change_star,
                create_user_bu, is_collective, past_pcode, c_past_pcode, user_bu
            ) VALUES (
                %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s
            )
        """
        params = (
            param.get('title'),
            param.get('form_id'),
            param.get('project_code'),
            param.get('remark'),
            param.get('organ_id'),
            param.get('user_id'),
            param.get('user_name'),
            param.get('dept_name'),
            param.get('write_time', datetime.now().strftime('%Y-%m-%d %H:%M:%S')),
            param.get('status'),
            param.get('type'),
            param.get('is_bidding_project'),
            param.get('change_star', '0'),
            param.get('create_user_bu'),
            param.get('is_collective'),
            param.get('past_pcode'),
            param.get('c_past_pcode'),
            param.get('user_bu')
        )
        return Database.execute_insert(sql, params)
    
    @staticmethod
    def update_main(param, form_id):
        """
        更新立项申请主表数据
        对应原PHP的ActionModel::updateMainData()
        
        Args:
            param (dict): 更新参数字典
            form_id (str): 表单ID
            
        Returns:
            bool: 更新是否成功
        """
        sql = """
            UPDATE inhe_project_main 
            SET update_time = %s,
                title = %s,
                remark = %s,
                project_code = %s,
                status = %s,
                type = %s,
                is_bidding_project = %s,
                change_star = %s,
                user_bu = %s,
                is_collective = %s,
                past_pcode = %s,
                c_past_pcode = %s,
                create_user_bu = %s
            WHERE form_id = %s
        """
        params = (
            param.get('update_time', datetime.now().strftime('%Y-%m-%d %H:%M:%S')),
            param.get('title'),
            param.get('remark'),
            param.get('project_code'),
            param.get('status'),
            param.get('type'),
            param.get('is_bidding_project'),
            param.get('change_star', '0'),
            param.get('user_bu'),
            param.get('is_collective'),
            param.get('past_pcode'),
            param.get('c_past_pcode'),
            param.get('create_user_bu'),
            form_id
        )
        Database.execute_update(sql, params)
        return True
    
    @staticmethod
    def delete_by_form_id(form_id):
        """
        根据form_id删除立项申请主表数据
        对应原PHP的ActionModel::delete()
        
        Args:
            form_id (str): 表单ID
            
        Returns:
            bool: 删除是否成功
        """
        sql = "DELETE FROM inhe_project_main WHERE form_id = %s"
        Database.execute_update(sql, (form_id,))
        return True
    
    @staticmethod
    def verify_project_code(project_code, user_bu, form_id=None):
        """
        验证项目代号是否已存在
        对应原PHP的ActionModel::verifyProjectCode()
        
        Args:
            project_code (str): 项目代号
            user_bu (str): 公司代码
            form_id (str): 表单ID（更新时使用，排除自己）
            
        Returns:
            bool: True表示已存在，False表示不存在
        """
        if not project_code:
            return False
        
        sql = "SELECT COUNT(*) AS cnt FROM inhe_project_main WHERE project_code = %s AND user_bu = %s"
        params = [project_code, user_bu]
        
        if form_id:
            sql += " AND form_id != %s"
            params.append(form_id)
        
        result = Database.execute_query(sql, tuple(params))
        return result[0]['cnt'] > 0 if result else False

