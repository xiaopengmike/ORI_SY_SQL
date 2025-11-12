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
class SalesContractReviewModel
{
    public function insert_main($param){
        
        $time = date('Y-m-d H:i:s');
        //更新数据时增加form_id是否存在对应值
        $where = " where not exists (select * from inhe_contract_review where form_id='".$param['form_id']."')";
        $limit = " limit 1";
        $mainSql = "insert into inhe_contract_review (title, 
            form_id,
            related_id,
            customer_name,
            contract_party,
            contract_number,
            project_name,
            project_number,
            currency,
            delivery_time,
            project_star,
            contract_belong,
            from_id,
            organ_id,
            user_id,
            write_time,
            status,
            type,
            customer_order_no,
            contract_type,
            version,
            user_bu)" .
            " select '" . $param['title'] . "', '" . $param['form_id'] . "', '" . $param['related_id'] . "', '" . $param['customer_name'] . "', '" . $param['contract_party'] . "', '" .
            $param['contract_number'] . "', '" . $param['project_name'] . "', '" . $param['project_number'] . "', '" .
            $param['currency'] . "', '" . $param['delivery_time'] . "','" . $param['project_star'] . "', '" . $param['contract_belong'] . "', '" .$param['from_id'] . "', '" .
            $param['organ_id'] . "', '" . $param['user_id'] . "','" . $time . "', '" . $param['status'] .
            "', '" . $param['type'] . "', '" . $param['customer_order_no'] . "', '" . $param['contract_type'] . "', '" . $param['version']. "', '" . $param['user_bu'] . "' from inhe_contract_review ".$where.$limit;
        exequery(TD::conn(), $mainSql);
        $id = mysql_insert_id();
        return $id;
    }

    public function insert_main_detail($param){
        $mainSql = "insert into inhe_contract_detail (form_id,
        total_amount,
        create_user,
        dept_id,
        business_attribution,
        business_user_id,
        business_dept_id,
        form_date,
        transport_mode,
        freight_bearing,
        delivery_place,
        shipping_address,
        package_requirement,
        acceptance_criteria,
        have_entrepot_trade,
        involved_product,
        quote_source,
        history_project_code,
        have_tech_specification,
        have_email_approval,
        email_name,
        product_model,
        have_inventory,
        functional_requirement,
        special_requirement,
        spare_requirement,
        change_explain,
        is_collective,
        past_pcode,
        c_past_pcode,
        funds_come,
        financing_bank,
        product_project_type,
        contract_begin_time,
        contract_end_time,".
        //TODO 慧软CRM合同评审"om_services,
        //TODO 慧软CRM合同评审om_services_years,
        //TODO 慧软CRM合同评审has_soft,".
        "contract_background,
        if_eligible) values" .
            "('" . $param['form_id'] . "', '" . $param['total_amount'] . "', '" . $param['create_user'] . "', '" . $param['dept_id'] . "','" .
            $param['business_attribution'] . "', '" . $param['business_user_id'] . "', '" .$param['business_dept_id'] . "', '" .$param['form_date'] . "', '" . $param['transport_mode'] . "', '" . $param['freight_bearing'] . "','" .
            $param['delivery_place'] . "', '" . $param['shipping_address'] . "', '" . $param['package_requirement'] . "', '" . $param['acceptance_criteria'] . "','" .
            $param['have_entrepot_trade'] . "', '" . $param['involved_product'] . "', '" . $param['quote_source'] . "', '" . $param['history_project_code'] . "','" .
            $param['have_tech_specification'] . "', '" . $param['have_email_approval'] . "', '" . $param['email_name'] . "', '" . $param['product_model'] . "','" .
            $param['have_inventory'] . "', '" . $param['functional_requirement'] . "', '" . $param['special_requirement'] . "', '" . $param['spare_requirement'] . "', '" . $param['change_explain']. "', '" .
            $param['is_collective']. "', '" .$param['past_pcode']. "', '" .$param['c_past_pcode']. "', '" .$param['funds_come']. "', '" .$param['financing_bank']. "', '" .
            $param['product_project_type']. "', '" .$param['contract_begin_time']. "', '" .$param['contract_end_time']. "', '". 
            //TODO 慧软CRM合同评审$param['om_services']. "', '" .$param['om_services_years']. "', '" .$param['has_soft']. "', '".
            $param['contract_background']. "', '". 
			$param['if_eligible'] . "')";
        exequery(TD::conn(), $mainSql);
        $id = mysql_insert_id();
        return $id;
    }

    public function insert_contract_settle($param){
        $mainSql = "insert into inhe_contract_settle (form_id,
            item_id,
            content,
            attach_name) values" .
        "('" . $param['form_id'] . "', '" .$param['item_id'] . "', '" . $param['content'] . "', '".$param['attach_name'] . "')";
        exequery(TD::conn(), $mainSql);
        $id = mysql_insert_id();
        return $id;
    }
    public function insert_product_detail($param){
            $mainSql = "insert into inhe_product_detail (form_id,
            serial_number,
            name,
            brand,
            license_product,
            license_years,
            license_type,
            specification,
            unit,
            number,
            price,
            total_amount,
            tech_requirement,
            type) values" .
        "('" . $param['form_id'] . "', '" .$param['serial_number'] . "', '" . $param['name'] . "', '".$param['brand'] . "', '" .$param['license_product'] . "', '" 
        .$param['license_years'] . "', '".$param['license_type'] . "', '" . $param['specification'] ."', '" . $param['unit'] . "','" 
        . $param['number'] . "', '" . $param['price'] ."', '" . $param['total_amount'] . "', '" . $param['tech_requirement'] . "', '" . $param['type'] . "')";
        exequery(TD::conn(), $mainSql);
        $id = mysql_insert_id();
        return $id;
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
