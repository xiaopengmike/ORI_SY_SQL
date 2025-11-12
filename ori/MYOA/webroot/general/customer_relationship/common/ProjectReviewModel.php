<?php
require_once("inc/auth.inc.php");
require_once("inc/utility_all.php");
require_once("inc/utility_file.php");
require_once("inc/utility_field.php");

/**
 * 公共操作类
 * @author ysr
 * 已有功能：权限查询；附件操作；参数获取；编码转换；消息发送；返回结果封装；
 * 邮件发送；下属部门获取；获取部门人员；获取公司归属；获取admin权限；翻页控件
 * 待补充：
 */
class ProjectReviewModel
{
    public function insert_main($param){
        
        $time = date('Y-m-d H:i:s');
        $mainSql = "insert into inhe_project_main (title, form_id,
            project_code,
            remark,
            organ_id,
            user_id,
            user_name,
            dept_name,
            write_time,
            status,
            star_icon,
            type,
            is_bidding_project,
            change_star,
            if_project,
            project_star,
            from_id,
            is_collective,
            past_pcode,
            c_past_pcode,
            user_bu) values" .
            "('" . $param['title'] . "', '" . $param['form_id'] . "', '" . $param['project_code']. "', '" .$param['remark'] . "', '" . $param['organ_id'] .
            "', '" . $param['user_id'] ."', '" . $param['user_name'] ."', '" . $param['dept_name'] . "','" . $time . "', '" . $param['status'] ."', '" . $param['star_icon'] .
            "', '" . $param['type'] . "','".$param['is_bidding_project']."', '". $param['change_star']."', '". $param['if_project']."', '". $param['project_star']."', '"  .  $param['from_id']."', '"  . 
            $param['is_collective']."', '"  . $param['past_pcode']."', '"  . $param['c_past_pcode']."', '"  . $param['user_bu'] . "')";
        exequery(TD::conn(), $mainSql);
        $id = mysql_insert_id();
        return $id;
    }

    public function insert_main_detail($param){
        $mainSql = "insert into inhe_project_info (form_id,
        project_code,
        temp_project_code,
        country,
        scope,
        project_type,
        prototype,
        potential,
        background,
        explain_user,
        explain_project,
        cooperate_history,
        service) values" .
            "('" . $param['form_id'] . "', '" . $param['project_code']. "', '" .$param['temp_project_code']. "', '" . $param['country'] . "', '" . $param['scope'] . "','" .
            $param['project_type'] . "', '" . $param['prototype'] . "', '" . $param['potential'] . "', '" . $param['background'] . "','" .
            $param['explain_user'] . "','" . $param['explain_project'] . "','" . $param['cooperate_history'] . "','" . $param['service'] . "')";
        exequery(TD::conn(), $mainSql);
        $id = mysql_insert_id();
        return $id;
    }
    public function insert_project_detail($param)
    {
        $sql = "insert into inhe_project_detail (form_id,
        product,
        price,
        serial_number,
        first_price,
        standard,
        supplier,
        type,
        number) values" .
            "('" . $param['form_id'] . "', '" . $param['product'] . "', '" . $param['price'] . "', '" .$param['serial_number'] . "', '" .
            $param['first_price'] . "','" . $param['standard'] . "','" . $param['supplier'] . "', '" . $param['type'] . "', '" . $param['number'] . "')";
        exequery(TD::conn(), $sql);
        return true;
    }

    public function insert_bidding_strategy($param)
    {
        $sql = "insert into inhe_bidding_strategy (form_id, 
                    tender_no, 
                    end_date, 
                    project_name, 
                    tender, 
                    bid_rule, 
                    biding_rule, 
                    effective_demand, 
                    tender_demand, 
                    file_demand, 
                    format, 
                    reason, 
                    process_description, 
                    pay_process, 
                    pay_energy, 
                    partner_analysis, 
                    relationship, 
                    market_analysis, 
                    strategy_analysis, 
                    feasibility_analysis,
                    key_to_success,
                    personal_opinion,
                    project_level,
                    have_manager
                ) values" .
            "('" . $param['form_id'] . "', '" . $param['tender_no'] . "', '" . $param['end_date'] . "', '" .
            $param['project_name'] . "','" . $param['tender'] . "', '" . $param['bid_rule'] . "', '" .
            $param['biding_rule'] . "','" . $param['effective_demand'] . "', '" . $param['tender_demand'] . "', '" .
            $param['file_demand'] . "','" . $param['format'] . "', '" . $param['reason'] . "', '" . $param['process_description'] . "', '" .
            $param['pay_process'] . "','" . $param['pay_energy'] . "','" . $param['partner_analysis'] . "', '" . $param['relationship'] . "', '" .
            $param['market_analysis'] . "','" . $param['strategy_analysis'] . "','" . $param['feasibility_analysis'] . "','" . $param['key_to_success'] . "', '" . $param['personal_opinion'] . "', '" .
            $param['project_level'] . "','" . $param['have_manager'] . "')";
        exequery(TD::conn(), $sql);

        return true;
    }

    public function addClientInfo($param)
    {
        //for ($i = 1; $i <= 3; $i++) {
            $mainSql = "insert into inhe_customer_info (form_id,
            name,
            customer_name,
            customer_id,
            country,
            contact,
            phone,
            email,
            introduction,
            type) values" .
                "('" . $param['form_id'] . "', '" . $param['name']. "', '" . $param['customer_name'] . "', '" . $param['customer_id'] . "', '" . $param['country'] .
                "', '" . $param['contact'] . "','" . $param['phone'] . "', '" . $param['email'] .
                "', '" . $param['introduction'] . "', '" . $param['type'] . "')";
            exequery(TD::conn(), $mainSql);
        //}
        return true;
    }
   
    public function insert_attach($item){
        $sql = "insert into inhe_attachment (filename, 
                path, 
                size, 
                relatedId, 
                type
                ) values" .
                "('" .$item['filename']. "', '" . $item['path'] . "', '" . $item['size'] . "', '" .
                $item['relatedId'] . "','" . $item['type'] . "')";
            exequery(TD::conn(), $sql);
        $id = mysql_insert_id();
        return $id;
    }

     
    public function insert_opinion($item){
        $sql = "insert into inhe_flow_opinion (
            `type`,
            `user_id`,
            `write_time`,
            `form_id`,
            `title`,
            `step`,
            `work_flow`,
            `approve_user`,
            `approve_time`,
            `status`,
            `prev_task`,
            `prev_writer`,
            `prev_time`,
            `opinion`,
            `project_star`,
            `have_manager`,
            `extra`,
            `if_agree`,
            `signature`,
            `user_bu`,
            `common_opinion`
                ) values" .
                "('" .$item['type']. "', '" . $item['user_id'] . "', '" . $item['write_time'] . "', '" .
                $item['form_id'] . "','" . $item['title'] . "','" . $item['step'] . "','" . $item['work_flow'] . "','" .
                $item['approve_user'] . "','" . $item['approve_time'] . "','" . $item['status'] . "','" .
                $item['prev_task'] . "','" . $item['prev_writer'] . "','" . $item['prev_time'] . "','" . $item['opinion'] . "','" .
                $item['project_star'] . "','" . $item['have_manager'] . "','" . $item['extra'] . "','" . $item['if_agree'] . "','" .
                $item['signature'] . "','" . $item['user_bu'] . "','" . $item['common_opinion'] . "')";
            exequery(TD::conn(), $sql);
        $id = mysql_insert_id();
        return $id;
    }

   
}
