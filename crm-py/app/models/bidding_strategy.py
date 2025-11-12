"""
投标策略模型
对应原PHP项目的ActionModel.php中的投标策略操作
对应数据库表: inhe_bidding_strategy
"""
from app.utils.db import Database

class BiddingStrategy:
    """
    投标策略模型类
    对应原PHP的ActionModel类中的投标策略相关方法
    """
    
    @staticmethod
    def query_by_form_id(form_id):
        """
        根据form_id查询投标策略
        对应原PHP的ActionModel::queryBidding()
        
        Args:
            form_id (str): 表单ID
            
        Returns:
            dict: 投标策略字典
        """
        sql = "SELECT * FROM inhe_bidding_strategy WHERE form_id = %s"
        result = Database.execute_query(sql, (form_id,))
        return result[0] if result else {}
    
    @staticmethod
    def add_bidding_strategy(param):
        """
        添加投标策略
        对应原PHP的ActionModel::addBiddingStrategy()
        
        Args:
            param (dict): 投标策略参数字典
            
        Returns:
            bool: 添加是否成功
        """
        sql = """
            INSERT INTO inhe_bidding_strategy (
                form_id, tender_no, end_date, project_name, tender, bid_rule,
                biding_rule, effective_demand, tender_demand, file_demand,
                format, reason, process_description, pay_process, pay_energy,
                partner_analysis, relationship, market_analysis, strategy_analysis,
                feasibility_analysis, key_to_success, personal_opinion,
                project_level, have_manager
            ) VALUES (
                %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,
                %s, %s, %s, %s, %s, %s, %s, %s, %s
            )
        """
        params = (
            param.get('form_id'),
            param.get('tender_no'),
            param.get('end_date'),
            param.get('project_name'),
            param.get('tender'),
            param.get('bid_rule'),
            param.get('biding_rule'),
            param.get('effective_demand'),
            param.get('tender_demand'),
            param.get('file_demand'),
            param.get('format'),
            param.get('reason'),
            param.get('process_description'),
            param.get('pay_process'),
            param.get('pay_energy'),
            param.get('partner_analysis'),
            param.get('relationship'),
            param.get('market_analysis'),
            param.get('strategy_analysis'),
            param.get('feasibility_analysis'),
            param.get('key_to_success'),
            param.get('personal_opinion'),
            param.get('project_level'),
            param.get('have_manager')
        )
        Database.execute_insert(sql, params)
        return True
    
    @staticmethod
    def update_bidding_strategy(param, form_id):
        """
        更新投标策略
        对应原PHP的ActionModel::updateBiddingStrategy()
        
        Args:
            param (dict): 更新参数字典
            form_id (str): 表单ID
            
        Returns:
            bool: 更新是否成功
        """
        sql = """
            UPDATE inhe_bidding_strategy 
            SET tender_no = %s,
                end_date = %s,
                project_name = %s,
                tender = %s,
                bid_rule = %s,
                biding_rule = %s,
                effective_demand = %s,
                tender_demand = %s,
                file_demand = %s,
                format = %s,
                reason = %s,
                process_description = %s,
                pay_process = %s,
                pay_energy = %s,
                partner_analysis = %s,
                relationship = %s,
                market_analysis = %s,
                strategy_analysis = %s,
                feasibility_analysis = %s,
                key_to_success = %s,
                personal_opinion = %s,
                project_level = %s,
                have_manager = %s
            WHERE form_id = %s
        """
        params = (
            param.get('tender_no'),
            param.get('end_date'),
            param.get('project_name'),
            param.get('tender'),
            param.get('bid_rule'),
            param.get('biding_rule'),
            param.get('effective_demand'),
            param.get('tender_demand'),
            param.get('file_demand'),
            param.get('format'),
            param.get('reason'),
            param.get('process_description'),
            param.get('pay_process'),
            param.get('pay_energy'),
            param.get('partner_analysis'),
            param.get('relationship'),
            param.get('market_analysis'),
            param.get('strategy_analysis'),
            param.get('feasibility_analysis'),
            param.get('key_to_success'),
            param.get('personal_opinion'),
            param.get('project_level'),
            param.get('have_manager'),
            form_id
        )
        Database.execute_update(sql, params)
        return True
    
    @staticmethod
    def delete_by_form_id(form_id):
        """
        根据form_id删除投标策略
        
        Args:
            form_id (str): 表单ID
            
        Returns:
            bool: 删除是否成功
        """
        sql = "DELETE FROM inhe_bidding_strategy WHERE form_id = %s"
        Database.execute_update(sql, (form_id,))
        return True

