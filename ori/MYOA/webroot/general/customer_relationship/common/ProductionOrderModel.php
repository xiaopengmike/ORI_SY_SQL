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
class ProductionOrderModel
{
    public function insert_main($param){
        $mainSql = "insert into inhe_production_order (title, 
        form_id, 
        related_id, 
        form_type, 
        customer_name, 
        project_code, 
        project_num, 
        order_num, 
        order_form_id, 
        contract_party, 
        delivery_time, 
        apply_dept, 
        apply_user, 
        third_inspection, 
        from_id, 
        inspect_type, 
        project_star, 
        contract_belong, 
        other_explain, 
        spare_requirement, 
        meter_number, 
        package_in_sequence, 
        organ_id, 
        user_id, 
        write_time, 
        status, 
        type, 
        is_collective,
        is_sgc,
        sgc,
        past_pcode,
        c_past_pcode,
        version,
        user_bu
        ) values" .
            "('" . $param['title'] . "', '" . $param['form_id'] . "', '" . $param['related_id'] . "', '" . $param['form_type'] . "', '" . addslashes($param['customer_name']) . "', '" . $param['project_code'] .
            "', '" . $param['project_num'] . "', '" . $param['order_num'] ."', '" . $param['order_form_id'] . "','" . addslashes($param['contract_party']) . "', '" . $param['delivery_time'] .
            "', '" . $param['apply_dept'] . "','" . $param['apply_user'] . "', '" . $param['third_inspection'] ."', '" . $param['from_id'] .
            "', '" . $param['inspect_type'] . "','" . $param['project_star'] . "', '" . $param['contract_belong'] .
            "', '" . $param['other_explain']. "','" . $param['spare_requirement'] . "','" . $param['meter_number'] . "', '" . $param['package_in_sequence'] .
            "', '" . $param['organ_id'] . "','" . $param['user_id'] . "', '" . $param['write_time'] .
            "', '" . $param['status'] . "', '" . $param['type'] . "', '" . $param['is_collective'] . "', '" .
            $param['is_sgc'] . "', '" .$param['sgc'] . "', '" . $param['past_pcode'] . "', '" . $param['past_pcode'] . "', '" . $param['version']. "', '" . $param['user_bu'] . "')";
        exequery(TD::conn(), $mainSql);
        $id = mysql_insert_id();
        return $id;
    }
    public function insert_production_non($item){
        $sql = "insert into inhe_production_detail_non (form_id, 
                non_serial_number, 
                non_product_name, 
                non_order_number, 
                specification_model, 
                unit, 
                non_play_tray, 
                non_attachment,
                non_type,
                license_product,
                license_years,
                license_type,
                sub_process
                ) values" .
                "('" .$item['form_id']. "', '" . $item['non_serial_number'] . "', '" . $item['non_product_name'] . "', '" .
                $item['non_order_number'] . "','" . $item['specification_model'] . "', '" . $item['unit'] . "','" .$item['non_play_tray'] . "','" .
                $item['non_attachment'] . "','". $item['non_type'] ."','". $item['license_product'] ."','". $item['license_years'] ."','". 
                $item['license_type'] ."','".$item['sub_process']."')";
            exequery(TD::conn(), $sql);
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

    //新增电表类产品
    public function addMeterOne($param){
        $sql1 = "insert into inhe_production_detail_one (form_id,
        serial_number,
        product_name,
        product_brand,
        internal_model,
        order_number,
        voltage,
        current,
        frequency,
        active_precision,
        reactive_power_accuracy,
        active_pulse_constant,
        reactive_pulse_constant,
        meter_type,
        display_mode,
        display_digit
        ) values" .
        "('" . $param['form_id'] . "', '" . $param['serial_number'] . "', '" . $param['product_name'] . "', '" .$param['product_brand'] . "', '" .
        $param['internal_model'] . "','" . $param['order_number'] . "', '" . $param['voltage'] . "','" .
        $param['current'] . "','" . $param['frequency'] . "', '" . $param['active_precision'] . "','" .
        $param['reactive_power_accuracy'] . "','" . $param['active_pulse_constant'] . "', '" . $param['reactive_pulse_constant'] . "','" .
        $param['meter_type'] . "','" .$param['display_mode'] . "','" . $param['display_digit'] . "')";
    exequery(TD::conn(), $sql1);
    }
    public function addMeterTwo($param){
        $sql2 = "insert into inhe_production_detail_two (form_id, 
        serial_number, 
        product_name, 
        product_brand, 
        connection_mode, 
        production_mode, 
        time_zone, 
        dst_option, 
        meter_no_range, 
        enclosure_requirement, 
        logo, 
        panel, 
        power_plug_model, 
        communication_module, 
        card_matching_text, 
        factory_settings_file
        ) values" .
        "('" . $param['form_id'] . "', '" . $param['serial_number'] . "', '" . $param['product_name'] . "', '" .$param['product_brand'] . "', '" .
        $param['connection_mode'] . "','" . $param['production_mode'] . "', '" . $param['time_zone'] . "','" .
        $param['dst_option'] . "','" . $param['meter_no_range'] . "', '" . $param['enclosure_requirement'] . "','" .
        $param['logo'] . "','" . $param['panel'] . "', '" . $param['power_plug_model'] . "','" .
        $param['communication_module'] . "','" .$param['card_matching_text'] . "','" . $param['factory_settings_file'] . "')";
    exequery(TD::conn(), $sql2);
    }

    public function addMeterThree($param)
    {
        $sql3 = "insert into inhe_production_detail_three (form_id, 
        serial_number, 
        product_name, 
        product_brand, 
        product_specification, 
        operation_manual, 
        carton_design_document, 
        inner_box_design_document, 
        accessories_requirement, 
        test_report, 
        play_tray, 
        wooden_cases_packed, 
        related_attachment, 
        other_instruction, 
        attachment
        ) values" .
        "('" . $param['form_id'] . "', '" . $param['serial_number'] . "', '" . $param['product_name'] . "', '" .$param['product_brand'] . "', '" .
        $param['product_specification'] . "','" . $param['operation_manual'] . "', '" . $param['carton_design_document'] . "','" .
        $param['inner_box_design_document'] . "','" . $param['accessories_requirement'] . "', '" . $param['test_report'] . "','" .
        $param['play_tray'] . "','" . $param['wooden_cases_packed'] . "', '" . $param['related_attachment'] . "','" .
        $param['other_instruction'] . "','" . $param['attachment'] . "')";
        exequery(TD::conn(), $sql3);
    }
}
