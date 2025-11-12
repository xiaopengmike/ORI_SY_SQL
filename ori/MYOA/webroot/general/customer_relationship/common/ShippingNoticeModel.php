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
class ShippingNoticeModel
{
    public function insert_main($param){
        $mainSql = "insert into inhe_shipping_notice (
            form_id, 
            title, 
            customer_name, 
            contract_party, 
            address, 
            shipment_place, 
            order_no, 
            order_form_id, 
            project_number, 
            erp_no, 
            currency, 
            consignee, 
            phone, 
            shipment_time, 
            transport_mode, 
            freight_bearing, 
            business_belong, 
            business_dept, 
            have_entrepot_trade, 
            import_permit,
            involved_product, 
            notice_type, 
            organ_id, 
            user_id, 
            write_time, 
            remark, 
            signature, 
            status,
            type,
            user_bu) values" .
        "('" . $param['form_id'] . "', '" . $param['title'] . "', '" . addslashes($param['customer_name']) . "', '" . addslashes($param['contract_party']) . "', '" .
        $param['address'] . "', '" . $param['shipment_place'] . "', '" . $param['order_no'] .  "', '" . $param['order_form_id'] . "', '"  . $param['project_number'] . "', '" .
        $param['erp_no'] . "', '" . $param['currency'] . "', '" . $param['consignee'] . "', '" . $param['phone'] . "', '" .
        $param['shipment_time'] . "', '" . $param['transport_mode'] . "', '" . $param['freight_bearing'] . "', '" . $param['business_belong'] . "', '" .
        $param['business_dept'] . "', '" . $param['have_entrepot_trade'] . "', '" .$param['import_permit'] . "', '" . $param['involved_product'] . "', '" . $param['notice_type'] . "', '" .
        $param['organ_id'] . "', '" . $param['user_id'] . "','" . $param['write_time'] . "', '" . $param['remark'] .
        "', '" . $param['signature'] . "', '" . $param['status'] . "', '" . $param['type'] . "', '" . $param['user_bu'] . "')";
        exequery(TD::conn(), $mainSql);
        $id = mysql_insert_id();
        return $id;
    }
    public function insert_shipping_detail($param){
        $mainSql = "insert into inhe_shipping_detail (
            form_id,
            serial_number,
            product_name,
            internal_model,
            number,
            price,
            total_amount,
            project_number,
            project_code,
            order_number,
            license_product,
            license_years,
            license_type,
            license_key,
            license_term,
            license_key_date,
            type) values" .
                "('" . $param['form_id'] . "', '" . $param['serial_number'] . "', '" . $param['product_name'] . "', '" .
                $param['internal_model'] . "', '" . $param['number'] . "','" . $param['price'] . "', '" .
                $param['total_amount'] . "', '" . $param['project_number'] . "', '" . $param['project_code'] . "', '" .
                $param['order_number'] . "', '" .$param['license_product'] . "', '" .$param['license_years'] . "', '" .
                $param['license_type'] . "', '" .$param['license_key'] . "', '" .$param['license_term'] . "', '" .
                $param['license_key_date'] . "', '" . $param['type'] . "')";
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
