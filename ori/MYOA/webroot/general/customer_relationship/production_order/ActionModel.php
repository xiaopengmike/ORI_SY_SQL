<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_field.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");
include_once("../common/CommonMethodModel.php");  // 公共类
require_once("../common/ProcessModel.php");
require_once("../common/FormModel.php");
require_once("../common/ResponseCode.php");
require_once("../common/AttachModel.php");
require_once("../common/DataModel.php");

/**
 * 名词翻译库后台操作类
 * @author ysr
 * 修改记录
 * 20200701-按需求完善后台所有逻辑
 */

/**
 * 表说明：
 * inhe_customer_data  数据主表 insert or update
 * inhe_control_library_history 历史记录表 insert
 */

class ActionModel
{
    public $systemId = "production_order";
    public static $METER_TYPE = array('meter_one', 'meter_two', 'meter_three');
    public static $NON_METER_TYPE = array('non_meter');

    public function queryById($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";

        if (array_key_exists("id", $param) && $param['id'] != "") {
            $strWhere .= " and k.id = '" . $param['id'] . "' ";
        } else {
            return $result;
        }

        $main = $this->queryMain($param);
        $orderDetail = $this->queryOrderDetail($param);
        $detailItem = $this->getOrderDetailItem();

        $result = array(
            'main' => $main,
            'orderDetail' => $orderDetail,
            'detailItem' => $detailItem,
        );

        return $result;
    }

    public function queryMain($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";

        if (array_key_exists("id", $param) && $param['id'] != "") {
            $strWhere .= " and k.form_id = '" . $param['id'] . "' ";
        } else {
            return $result;
        }

        $sql = " select k.*,cr.customer_order_no sale_order_no,cr.version cr_version,u.user_name createUserName,d.dept_name deptName from inhe_production_order k " .
            " left join user u on u.user_id = k.user_id " .
            " left join inhe_contract_review cr on cr.form_id = k.order_num " .
            " left join department d on d.dept_id = k.organ_id " . $strWhere;
        $res = exequery(TD::conn(), $sql);
        $dataModel = new DataModel();
        while ($item = mysql_fetch_assoc($res)) {
            if($item[cr_version]>0){
                $crdata=$dataModel->getLastChange($item[order_num],$item[cr_version],'sales_contract_review_change');
                $item[sale_order_no]=$crdata[customer_order_no];
            }
            
            $result = $item;
        }
        mysql_free_result($res);

        return $result;
    }

    public function queryOrderDetail($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";

        if (array_key_exists("id", $param) && $param['id'] != "") {
            $strWhere .= " and k.form_id = '" . $param['id'] . "' ";
        } else {
            return $result;
        }

        $sql = " select * from inhe_production_detail_one k " . $strWhere . " order by id ";
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result['meter_one'][] = $item;
        }
        $sql = " select * from inhe_production_detail_two k " . $strWhere . " order by id ";
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result['meter_two'][] = $item;
        }
        $sql = " select * from inhe_production_detail_three k " . $strWhere . " order by id ";
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result['meter_three'][] = $item;
        }
        $sql = " select * from inhe_production_detail_non k " . $strWhere . " order by id ";
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result['non_meter'][] = $item;
        }

        //获取品号数据
        $sql = " select * from inhe_production_order_pn k " . $strWhere . " order by id ";
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result['pn'][] = $item;
        }

          //获取验收数据
          $sql = " select * from inhe_production_order_accept k " . $strWhere . " order by id ";
          $res = exequery(TD::conn(), $sql);
          while ($item = mysql_fetch_assoc($res)) {
              $result['accept'][] = $item;
          }

        mysql_free_result($res);

        return $result;
    }

    public function getOrderDetailItem()
    {
        $sql = " select * from inhe_detail_item where module_id = '" . $this->systemId . "' order by table_id, sort_code";
        $res = exequery(TD::conn(), $sql);
        $result = array();
        while ($item = mysql_fetch_assoc($res)) {
            $result[$item['table_id']][] = $item;
        }
        mysql_free_result($res);

        return $result;
    }

    public function addOtherApprover($process,$param,$item){
//TODO 慧软CRM合同评审
        /*$querySql = "select * from inhe_flow_opinion where form_id='$param[form_id]' and status='U' and type='$param[systemId]' and user_bu='$param[user_bu]' and work_flow='$item[role]' ";
        $res = exequery(TD::conn(), $querySql);
        if ($item2 = mysql_fetch_assoc($res)) {
            return false;
        }*/
        $sql = " insert into inhe_flow_opinion (type, form_id, title, step, work_flow, approve_user, status, prev_task, prev_writer, prev_time, " .
            " user_bu) values ('" .
            $param['systemId'] . "','" . $param['form_id'] . "','" . $param['title'] . "','" .
            $item['step'] . "','" . $item['role'] . "','" . rtrim($item['approver'],",") . "','U','" .
            $param['prev_task'] . "','" . $_SESSION['LOGIN_USER_ID']. "','" . date("Y-m-d H:i:s") . "','" . $param['user_bu'] . "'" .
            ")";
        exequery(TD::conn(), $sql);
        $mainUrl = str_replace('_change', '', $param['systemId']);
        $mainUrl = str_replace('trial_', '', $mainUrl);
        $mainUrl = str_replace('rd_', '', $mainUrl);
        $processType = $process->findProcessType($param[type]);
        $smsParam = array(
            'url' => "/customer_relationship/" . $mainUrl . "/detail.php?id=" . $param['form_id'] . "&operate=approve&step=" . $item['step'],
            'content' => $_SESSION['create_user_name']  . '转交' . $processType[$param['systemId']]['paras_desc'] . '流程' . $param['form_id'] . '，请及时备案结束',
            'approver' => $item['approver']
        );
        $process->sendSms($smsParam);
    }

    public function updateLicenseProduct($param)
    {
        
        // if ($param['license_product_num'] <= 0) {
        //     return true;
        // }
        // for ($i = 1; $i <= $param['license_product_num']; $i++) {
        //     $license_product_id=$param['license_product_id_'.$i];
        //     $license_product_name=$param['license_product_name_'.$i];
        //     $license_product_code=$param['license_product_code_'.$i];
           
        //     if( $license_product_id>0){
        //         $sql="UPDATE `inhe_production_detail_non` SET `license_product_name`='$license_product_name',`license_product_code` = '$license_product_code' WHERE `id` = $license_product_id";
        //         exequery(TD::conn(), $sql);
        //     }
        // }
        if (!isset($param['addLicenseProductCount'])) {
            return true;
        }
        exequery(TD::conn(), "delete from inhe_production_order_pn where form_id='".$param['form_id']."'");
       
        if ($param['addLicenseProductCount'] <= 0) {
            return true;
        }
        $create_time=date("Y-m-d H:i:s");
        $valueSql="";
        for ($i = 1; $i <= $param['addLicenseProductCount']; $i++) {
            if(!empty($param['license_product_name_'.$i])){
                $license_product_name=$param['license_product_name_'.$i];
                $license_product_code=$param['license_product_code_'.$i];
                $license_product_remark=$param['license_product_remark_'.$i];
                $license_product_fromid=$param['form_id'];
                $valueSql.="('$license_product_name','$license_product_code','$license_product_remark','$license_product_fromid','$create_time','".$_SESSION[LOGIN_USER_ID]."'),";
            }
        }
        if($valueSql){
            $sql="INSERT INTO `TD_OA`.`inhe_production_order_pn` (`license_product_name`, `license_product_code`, `remark`, `form_id`, `create_time`, `user_id`)
            VALUES ".rtrim($valueSql,",");
            exequery(TD::conn(), $sql);
        }
        
    }

    public function updateAcceptanceInfo($param)
    {
        
        if (!isset($param['addLicenseAcceptCount'])) {
            return true;
        }
        exequery(TD::conn(), "delete from inhe_production_order_accept where form_id='".$param['form_id']."'");
        //exequery(TD::conn(), "delete from inhe_attachment where type like 'acceptance_report%' and relatedId='".$param['form_id']."'");
        if ($param['addLicenseAcceptCount'] <= 0) {
            return true;
        }
        $create_time=date("Y-m-d H:i:s");
        $valueSql="";
        for ($i = 1; $i <= $param['addLicenseAcceptCount']; $i++) {
            if(!empty($param['acceptance_date_'.$i])){
                $acceptance_date=$param['acceptance_date_'.$i];
                $server_end_date=$param['server_end_date_'.$i];
                $remark=$param['remark_'.$i];
                $license_product_fromid=$param['form_id'];
                $valueSql.="('$acceptance_date','$server_end_date','$remark','$i','$license_product_fromid','$create_time','".$_SESSION[LOGIN_USER_ID]."'),";
            }
        }
        if($valueSql){
            $sql="INSERT INTO `TD_OA`.`inhe_production_order_accept` (`acceptance_date`, `server_end_date`, `remark`, `serial_number`, `form_id`, `create_time`, `user_id`)
            VALUES ".rtrim($valueSql,",");
            exequery(TD::conn(), $sql);
        }
        // if(!empty($param['acceptance_date'])||!$param['server_end_date']){
        //     $acceptance_date=$param['acceptance_date'];
        //     $server_end_date=$param['server_end_date'];
        //     $sql="UPDATE `inhe_production_order` SET `acceptance_date`='$acceptance_date',`server_end_date` = '$server_end_date' WHERE `form_id` = '$param[form_id]'";
        //     exequery(TD::conn(), $sql);
        // }
            
  
    }
    /**
     * 提交申请
     * 保存表单数据 -> 获取下一步审核人员 -> 新增申请人记录，更新审核人数据，发送消息提醒
     * */
    public function submit()
    {
        $flag = false;
        $param = $this->getParamToArray();
        $form = new FormModel();

        //备案时，流程转到某个步骤，同时新增其他步骤填写或通知
        if($param[actionType]=='Approve'){
                
            $process = new ProcessModel();
            $param[type]=$param[systemId];
            $approveInfo = $process->getApprover($param);
            //var_dump($approveInfo);
            //当前步骤操作内容放到updateApproveOpinion 处理
			//TODO 慧软CRM合同评审            
			//if($approveInfo[0][role]=='备案'&&$param[user_bu]=='WITLINK'&&$param[licenseProduct]>0){
           if($approveInfo[0][role]=='备案'&&$param[user_bu]=='WITLINK'){
                $querySql = "select * from inhe_flow_manage where type='$param[systemId]' and user_bu='$param[user_bu]' and role='填写品号' ";
                $res = exequery(TD::conn(), $querySql);
                if ($item = mysql_fetch_assoc($res)) {
                    $this->addOtherApprover($process,$param,$item);
                }
            }
            if($approveInfo[0][role]=='品号通知'&&$param[user_bu]=='WITLINK'){
                $querySql = "select * from inhe_flow_manage where type='$param[systemId]' and user_bu='$param[user_bu]' and role='验收填写' ";
                $res = exequery(TD::conn(), $querySql);
                if ($item = mysql_fetch_assoc($res)) {
                    $this->addOtherApprover($process,$param,$item);
                }
                
            }
        }


        $result = $form->submit($param); // 拆分add和update部分独立，其他公共使用
        if (array_key_exists('action', $result)) {
            
            $processModel = new ProcessModel();
            $approveInfo = $processModel->existOtherStep($param);
            if(count($approveInfo)>0){
                $result = array(
                    'code' => '0',
                    'status' => 'failed',
                    'msg' => '流程已提交，请勿重复操作！',
                    'data' => '流程已提交，请勿重复操作！'
                );
                return $result;
            }
            $flag = $this->$result['action']($result['param']); // action分为add和update
            $result = $form->makeProcessData($result['param'], $flag);
        }
        return $result;
    }

    public function save()
    {
        $flag = false;
        $param = $this->getParamToArray();
        $form = new FormModel();
        $result = $form->save($param);
        if (array_key_exists('action', $result)) {
            $flag = $this->$result['action']($result['param']);
            $result = $form->makeProcessData($result['param'], $flag);
        }
        return $result;
    }

    /**
     * 更新审核意见，包括同意提交和退回操作
     */
    public function updateApproveOpinion()
    {
        $param = $this->getParamToArray();
        $attachArr = $param['attach'];
        if (is_array($attachArr)) {
            $attachIds = implode(',', $attachArr);
            $attachIds = rtrim($attachIds, ',');
            $attach = new AttachModel();
            $attach->updateAttach($attachIds, $param['form_id']);
        }
        $process = new ProcessModel();
        $result = $process->updateProcessOpinion($param);
        //保存授权产品品号
        $this->updateLicenseProduct($param);
        //保存验收信息
        $this->updateAcceptanceInfo($param);
        return $result;
    }

    /**
     * 新增一个客户
     */
    public function add($param)
    {
        $userId = $_SESSION['LOGIN_USER_ID'];
        $deptId = $_SESSION['LOGIN_DEPT_ID'];
        $time = date('Y-m-d H:i:s');
        $param['user_id']=$userId;
        $param['organ_id']=$deptId;
        $param['write_time']=$time;
        $param['third_inspection']=$param['if_third'];
        $param['inspect_type']=$param['inspection_type'];
        $param['contract_belong']=$param['company'];
        $param['others']=$param['other_explain'];
        $param['package_in_sequence']=$param['if_package'];
        $this->addMain($param);
        $this->addDetail($param);
        // 附件数据保存 
        $attachArr = $param['attach'];
        if (is_array($attachArr)) {
            $attachIds = implode(',', $attachArr);
            $attachIds = rtrim($attachIds, ',');
            $attach = new AttachModel();
            $attach->updateAttach($attachIds, $param['form_id']);
        }
        return true;
    }

    public function addMain($param)
    {
        $mainSql = "insert into inhe_production_order (title, 
        form_id, 
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
        create_user_bu, 
        status, 
        type, 
        sub_user_bu,
        is_collective,
        urgent,
        is_sgc,
        sgc,
        past_pcode,
        c_past_pcode,
        user_bu
        ) values" .
            "('" . $param['title'] . "', '" . $param['form_id'] . "', '" . $param['form_type'] . "', '" . $param['customer_name'] . "', '" . $param['project_code'] .
            "', '" . $param['project_num'] . "', '" . $param['order_num'] . "', '" . $param['order_form_id'] . "','" . $param['contract_party'] . "', '" . $param['delivery_time'] .
            "', '" . $param['apply_dept'] . "','" . $param['apply_user'] . "', '" . $param['third_inspection'] .
            "', '" . $param['inspect_type'] . "','" . $param['project_star'] . "', '" . $param['contract_belong'] .
            "', '" . $param['other_explain'] . "','" . $param['spare_requirement'] . "','" . $param['meter_number'] . "', '" . $param['package_in_sequence'] .
            "', '" . $param['organ_id'] . "','" . $param['user_id'] . "', '" . $param['write_time'] ."', '" . $param['create_user_bu'] .
            "', '" . $param['status'] . "', '" . $param['type'] .  "', '" . $param['sub_user_bu'] . "', '" . $param['is_collective'] . "', '" . $param['urgent'] . "', '" .
            $param['is_sgc'] . "', '" .$param['sgc'] . "', '" . $param['past_pcode'] . "', '" . $param['past_pcode'] . "', '" . $param['user_bu'] . "')";
        exequery(TD::conn(), $mainSql);
        $id = mysql_insert_id();
        if ($id > 0) {
            return true;
        }

        return false;
    }

    public function addDetail($param)
    {
        // sctz2015121410	meter_one	2	1	serial_number	2
        $this->addMeter($param);
        $this->addNonMeter($param);
        return true;
    }

    public function addMeter($param)
    {
        if ($param['addMeterCount'] <= 0) {
            return true;
        }
        for ($i = 1; $i <= $param['addMeterCount']; $i++) {
            $str = $i;
            if (!empty($param['product_name' . $str])) {
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
                    "('" . $param['form_id'] . "', '" . $param['serial_number' . $str] . "', '" . $param['product_name' . $str] . "', '" .$param['product_brand' . $str] . "', '" .
                    $param['internal_model' . $str] . "','" . $param['order_number' . $str] . "', '" . $param['voltage' . $str] . "','" .
                    $param['current' . $str] . "','" . $param['frequency' . $str] . "', '" . $param['active_precision' . $str] . "','" .
                    $param['reactive_power_accuracy' . $str] . "','" . $param['active_pulse_constant' . $str] . "', '" . $param['reactive_pulse_constant' . $str] . "','" .
                    $param['meter_type' . $str] . "','" .$param['display_mode' . $str] . "','" . $param['display_digit' . $str] . "')";
                exequery(TD::conn(), $sql1);

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
                    "('" . $param['form_id'] . "', '" . $param['serial_number' . $str] . "', '" . $param['product_name' . $str] . "', '" .$param['product_brand' . $str] . "', '" .
                    $param['connection_mode' . $str] . "','" . $param['production_mode' . $str] . "', '" . $param['time_zone' . $str] . "','" .
                    $param['dst_option' . $str] . "','" . $param['meter_no_range' . $str] . "', '" . $param['enclosure_requirement' . $str] . "','" .
                    $param['logo' . $str] . "','" . $param['panel' . $str] . "', '" . $param['power_plug_model' . $str] . "','" .
                    $param['communication_module' . $str] . "','" .$param['card_matching_text' . $str] . "','" . $param['factory_settings_file' . $str] . "')";
                exequery(TD::conn(), $sql2);

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
                    attachment,
                    uhas_module,
                    module_shipping,
                    other_company
                    ) values" .
                    "('" . $param['form_id'] . "', '" . $param['serial_number' . $str] . "', '" . $param['product_name' . $str] . "', '" .$param['product_brand' . $str] . "', '" .
                    $param['product_specification' . $str] . "','" . $param['operation_manual' . $str] . "', '" . $param['carton_design_document' . $str] . "','" .
                    $param['inner_box_design_document' . $str] . "','" . $param['accessories_requirement' . $str] . "', '" . $param['test_report' . $str] . "','" .
                    $param['play_tray' . $str] . "','" . $param['wooden_cases_packed' . $str] . "', '" . $param['related_attachment' . $str] . "','" .
                    $param['other_instruction' . $str] . "','" . $param['attachment' . $str] . "','".
                    $param['uhas_module' . $str]. "','" . $param['module_shipping' . $str]. "','" . $param['other_company' . $str]. "')";
                exequery(TD::conn(), $sql3);
            }
        }
        return true;
    }

    public function addNonMeter($param)
    {
        $selectParam = array(
            'is_used' => '1', // 可用参数
            'type' => 'product_detail_type',
        );
        $dataModel = new DataModel();
        $selectArr = $dataModel->getCommonParam($selectParam);
        foreach($selectArr['product_detail_type'] as $key => $val){
            $typeArr[$val['paras_value']]="add".$val['paras_value']."Count";
        }
        
        //$typeArr = array('electric' => 'addEleDetailCount', 'other' => 'addOtherDetailCount', 'soft' => 'addSoftDetailCount', 'electricParts' => 'addElectricPartsDetailCount', 'distribution' => 'addDistributionDetailCount');
        foreach ($typeArr as $type => $addCount) {
            for ($i = 1; $i <= $param[$addCount]; $i++) {
                $str = "_".$type.$i;
                if (!empty($param['non_product_name' . $str])) {
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
                        sub_process,
                        other_company
                        ) values" .
                        "('" . $param['form_id'] . "', '" . $param['non_serial_number' . $str] . "', '" . $param['non_product_name' . $str] . "', '" .
                        $param['non_order_number' . $str] . "','" . $param['specification_model' . $str] . "', '" . $param['unit' . $str] . "','" .$param['non_play_tray' . $str] . "','" .
                        $param['non_attachment' . $str] . "','".$type."','".$param['license_product' . $str] . "','".$param['license_years' . $str] . "','".
                        $param['license_type' . $str] . "','".$param['is_' . $type]. "','".$param['other_company' . $str]."')";
                        
                    exequery(TD::conn(), $sql);
                }
            }
        }
        return true;
    }

    public function update($param)
    {
        $this->updateMainData($param);
        $this->updateMeterOne($param);
        $this->updateMeterTwo($param);
        $this->updateMeterThree($param);
        $this->updateNoneMeter($param);
        // 附件数据保存 
        $attachArr = $param['attach'];
        if (is_array($attachArr)) {
            $attachIds = implode(',', $attachArr);
            $attachIds = rtrim($attachIds, ',');
            $attach = new AttachModel();
            $attach->updateAttach($attachIds, $param['form_id']);
        }
        return true;
    }

    public function updateMainData($param)
    {
        $form = new FormModel();
        $paramInsert = array(
            'update_time' => date('Y-m-d H:i:s'),
            'title' => $param['title'],
            'form_id' => $param['form_id'],
            'form_type' => $param['form_type'],
            'related_id' => $param['related_id'],
            'customer_name' => $param['customer_name'],
            'project_code' => $param['project_code'],
            'project_num' => $param['project_num'],
            'order_num' => $param['order_num'],
            'order_form_id' => $param['order_form_id'],
            'contract_party' => $param['contract_party'],
            'delivery_time' => $param['delivery_time'],
            'apply_dept' => $param['apply_dept'],
            'apply_user' => $param['apply_user'],
            'third_inspection' => $param['third_inspection'],
            'inspect_type' => $param['inspection_type'],
            'project_star' => $param['project_star'],
            'contract_belong' => $param['contract_belong'],
            'other_explain' => $param['other_explain'],
            'spare_requirement' => $param['spare_requirement'],
            'meter_number' => $param['meter_number'],
            'package_in_sequence' => $param['if_package'],
            'status' => $param['status'],
            'type' => $param['type'],
            'sub_user_bu' => $param['sub_user_bu'],
            'is_collective' => $param['is_collective'],
            'urgent' => $param['urgent'],
            'is_sgc' => $param['is_sgc'],
            'sgc' => $param['sgc'],
            'past_pcode' => $param['past_pcode'],
            'c_past_pcode' => $param['c_past_pcode'],
            'create_user_bu' => $param['create_user_bu'],
            'user_bu' => $param['user_bu']
        );
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_production_order';
        $result = $form->updateMainData($paramInsert, $condition, $tableName, array(), '');
        return $result;
    }

    public function updateMeterOne($param)
    {
        $form = new FormModel();
        $normalField = array(
            'form_id' => 'form_id'
        );
        $batchField = array(
            'serial_number' => 'serial_number',
            'product_name' => 'product_name',
            'product_brand' =>  'product_brand',
            'internal_model' => 'internal_model',
            'order_number' => 'order_number',
            'voltage' => 'voltage',
            'current' => 'current',
            'frequency' => 'frequency',
            'active_precision' => 'active_precision',
            'reactive_power_accuracy' => 'reactive_power_accuracy',
            'active_pulse_constant' => 'active_pulse_constant',
            'reactive_pulse_constant' => 'reactive_pulse_constant',
            'display_mode' => 'display_mode',
            'display_digit' => 'display_digit',
            'meter_type' => 'meter_type'
        );
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_production_detail_one';
        $count = $param['addMeterCount'];
        $result = $form->updateDetailData($normalField, $batchField, $param, $condition, $tableName, $count);
        $deleteSql = "delete from inhe_production_detail_one where product_name = '' and form_id = '" . $param['form_id'] . "' ";
        exequery(TD::conn(), $deleteSql);
        return $result;
    }

    public function updateMeterTwo($param)
    {
        $form = new FormModel();
        $normalField = array(
            'form_id' => 'form_id'
        );
        $batchField = array(
            'serial_number' => 'serial_number',
            'product_name' => 'product_name',
            'product_brand' =>  'product_brand',
            'connection_mode' => 'connection_mode',
            'production_mode' => 'production_mode',
            'time_zone' => 'time_zone',
            'dst_option' => 'dst_option',
            'meter_no_range' => 'meter_no_range',
            'enclosure_requirement' => 'enclosure_requirement',
            'logo' => 'logo',
            'panel' => 'panel',
            'power_plug_model' => 'power_plug_model',
            'card_matching_text' => 'card_matching_text',
            'factory_settings_file' => 'factory_settings_file',
            'communication_module' => 'communication_module'
        );
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_production_detail_two';
        $count = $param['addMeterCount'];
        $result = $form->updateDetailData($normalField, $batchField, $param, $condition, $tableName, $count);
        $deleteSql = "delete from inhe_production_detail_two where product_name = '' and form_id = '" . $param['form_id'] . "' ";
        exequery(TD::conn(), $deleteSql);
        return $result;
    }

    public function updateMeterThree($param)
    {
        $form = new FormModel();
        $normalField = array(
            'form_id' => 'form_id'
        );
        $batchField = array(
            'serial_number' =>  'serial_number',
            'product_name' =>  'product_name',
            'product_brand' =>  'product_brand',
            'product_specification' =>  'product_specification',
            'operation_manual' =>  'operation_manual',
            'carton_design_document' =>  'carton_design_document',
            'inner_box_design_document' =>  'inner_box_design_document',
            'accessories_requirement' =>  'accessories_requirement',
            'test_report' =>  'test_report',
            'play_tray' =>  'play_tray',
            'wooden_cases_packed' =>  'wooden_cases_packed',
            'related_attachment' =>  'related_attachment',
            'other_instruction' =>  'other_instruction',
            'attachment' =>  'attachment',
            'uhas_module' =>  'uhas_module',
            'module_shipping' =>  'module_shipping',
            'other_company' =>  'other_company',
        );
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_production_detail_three';
        $count = $param['addMeterCount'];
        $result = $form->updateDetailData($normalField, $batchField, $param, $condition, $tableName, $count);
        $deleteSql = "delete from inhe_production_detail_three where product_name = '' and form_id = '" . $param['form_id'] . "' ";
        exequery(TD::conn(), $deleteSql);
        return $result;
    }

    public function updateNoneMeter($param)
    {
        $form = new FormModel();
        $normalField = array(
            'form_id' => 'form_id'
        );
        $batchField = array(
            'non_serial_number' => 'non_serial_number',
            'non_product_name' => 'non_product_name',
            'non_order_number' => 'non_order_number',
            'specification_model' => 'specification_model',
            'unit' => 'unit',
            'non_play_tray' => 'non_play_tray',
            'non_attachment' => 'non_attachment',
            'sub_process' => array('is'),
            'non_type' => '',
            'license_product' => 'license_product',
            'license_years' => 'license_years',
            'license_type' => 'license_type',
            'other_company' =>  'other_company',
        );
        $selectParam = array(
            'is_used' => '1', // 可用参数
            'type' => 'product_detail_type',
        );
        $dataModel = new DataModel();
        $selectArr = $dataModel->getCommonParam($selectParam);
        foreach($selectArr['product_detail_type'] as $key => $val){
            $typeArr[]=$val['paras_value'];
            $countArr[$val['paras_value']]=$param["add".$val['paras_value']."Count"];
        }
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_production_detail_non';
        $result = $form->updateBatchDetailByType($normalField, $batchField, $param, $condition, $tableName, $typeArr, $countArr);
        $sql = " delete from $tableName where form_id = '" . $param['form_id'] . "' and (non_product_name = '' or non_product_name is null) ";
        exequery(TD::conn(), $sql);
        return $result;
    }

    public function change()
    {
        $flag = false;
        $param = $this->getParamToArray();
        $form = new FormModel();
        $result = $form->submit($param); // 拆分add和update部分独立，其他公共使用
        if (array_key_exists('action', $result)) {
            $flag = $this->$result['action']($result['param']); // action分为add和update
            $result = $form->makeProcessData($result['param'], $flag);
        }
        return $result;
    }

    public function changeSave()
    {
        $flag = false;
        $param = $this->getParamToArray();
        $form = new FormModel();
        $result = $form->save($param);
        if (array_key_exists('action', $result)) {
            $flag = $this->$result['action']($result['param']);
            $result = $form->makeProcessData($result['param'], $flag);
        }
        return $result;
    }

    public function changeAdd($param)
    {
        $this->changeMainData($param);
        $this->changeMeterOne($param);
        $this->changeMeterTwo($param);
        $this->changeMeterThree($param);
        $this->changeNoneMeter($param);
        // 附件数据保存 
        $attachArr = $param['attach'];
        if (is_array($attachArr)) {
            $attachIds = implode(',', $attachArr);
            $attachIds = rtrim($attachIds, ',');
            $attach = new AttachModel();
            //$attach->updateAttach($attachIds, $param['form_id']);
            $attach->changeAddAttach($attachIds, $param['form_id']);
            
        }
        return true;
    }

    public function changeUpdate($param)
    {
        $this->updateMainData($param);
        $this->updateMeterOne($param);
        $this->updateMeterTwo($param);
        $this->updateMeterThree($param);
        $this->updateNoneMeter($param);
        // 附件数据保存 
        $attachArr = $param['attach'];
        if (is_array($attachArr)) {
            $attachIds = implode(',', $attachArr);
            $attachIds = rtrim($attachIds, ',');
            $attach = new AttachModel();
            $attach->updateAttach($attachIds, $param['form_id']);
        }
        return true;
    }

    public function changeMainData($param)
    {
        $form = new FormModel();
        $paramInsert = array(
            'organ_id' => $_SESSION['LOGIN_DEPT_ID'],
            'user_id' => $_SESSION['LOGIN_USER_ID'],
            'write_time' => date('Y-m-d H:i:s'),
            'title' => $param['title'],
            'form_id' => $param['form_id'],
            'form_type' => $param['form_type'],
            'related_id' => $param['related_id'],
            'customer_name' => $param['customer_name'],
            'project_code' => $param['project_code'],
            'project_num' => $param['project_num'],
            'order_num' => $param['order_num'],
            'order_form_id' => $param['order_form_id'],
            'contract_party' => $param['contract_party'],
            'delivery_time' => $param['delivery_time'],
            'apply_dept' => $param['apply_dept'],
            'apply_user' => $param['apply_user'],
            'third_inspection' => $param['third_inspection'],
            'inspect_type' => $param['inspection_type'],
            'project_star' => $param['project_star'],
            'contract_belong' => $param['contract_belong'],
            'other_explain' => $param['other_explain'],
            'spare_requirement' => $param['spare_requirement'],
            'meter_number' => $param['meter_number'],
            'package_in_sequence' => $param['if_package'],
            'status' => $param['status'],
            'type' => $param['type'],
            'sub_user_bu' => $param['sub_user_bu'],
            'is_collective' => $param['is_collective'],
            'urgent' => $param['urgent'],
            'is_sgc' => $param['is_sgc'],
            'sgc' => $param['sgc'],
            'past_pcode' => $param['past_pcode'],
            'c_past_pcode' => $param['c_past_pcode'],
            'create_user_bu' => $param['create_user_bu'],
            'from_id' => $param['from_id'],
            'user_bu' => $param['user_bu']
        );
        $condition = array('form_id' => $param['related_id']);
        $tableName = 'inhe_production_order';
        $result = $form->changeMainData($paramInsert, $condition, $tableName, array(), '');
        return $result;
    }

    public function changeMeterOne($param)
    {
        $form = new FormModel();
        $normalField = array(
            'form_id' => 'form_id'
        );
        $batchField = array(
            'serial_number' => 'serial_number',
            'product_name' => 'product_name',
            'product_brand' => 'product_brand',
            'internal_model' => 'internal_model',
            'order_number' => 'order_number',
            'voltage' => 'voltage',
            'current' => 'current',
            'frequency' => 'frequency',
            'active_precision' => 'active_precision',
            'reactive_power_accuracy' => 'reactive_power_accuracy',
            'active_pulse_constant' => 'active_pulse_constant',
            'reactive_pulse_constant' => 'reactive_pulse_constant',
            'display_mode' => 'display_mode',
            'display_digit' => 'display_digit',
            'meter_type' => 'meter_type'
        );
        $condition = array('form_id' => $param['related_id']);
        $tableName = 'inhe_production_detail_one';
        $count = $param['addMeterCount'];
        $result = $form->changeDetailData($normalField, $batchField, $param, $condition, $tableName, $count);
        $deleteSql = "delete from inhe_production_detail_one where product_name = '' and form_id = '" . $param['form_id'] . "' ";
        exequery(TD::conn(), $deleteSql);
        return $result;
    }

    public function changeMeterTwo($param)
    {
        $form = new FormModel();
        $normalField = array(
            'form_id' => 'form_id'
        );
        $batchField = array(
            'serial_number' => 'serial_number',
            'product_name' => 'product_name',
            'product_brand' => 'product_brand',
            'connection_mode' => 'connection_mode',
            'production_mode' => 'production_mode',
            'time_zone' => 'time_zone',
            'dst_option' => 'dst_option',
            'meter_no_range' => 'meter_no_range',
            'enclosure_requirement' => 'enclosure_requirement',
            'logo' => 'logo',
            'panel' => 'panel',
            'power_plug_model' => 'power_plug_model',
            'card_matching_text' => 'card_matching_text',
            'factory_settings_file' => 'factory_settings_file',
            'communication_module' => 'communication_module'
        );
        $condition = array('form_id' => $param['related_id']);
        $tableName = 'inhe_production_detail_two';
        $count = $param['addMeterCount'];
        $result = $form->changeDetailData($normalField, $batchField, $param, $condition, $tableName, $count);
        $deleteSql = "delete from inhe_production_detail_two where product_name = '' and form_id = '" . $param['form_id'] . "' ";
        exequery(TD::conn(), $deleteSql);
        return $result;
    }

    public function changeMeterThree($param)
    {
        $form = new FormModel();
        $normalField = array(
            'form_id' => 'form_id'
        );
        $batchField = array(
            'serial_number' =>  'serial_number',
            'product_name' =>  'product_name',
            'product_brand' => 'product_brand',
            'product_specification' =>  'product_specification',
            'operation_manual' =>  'operation_manual',
            'carton_design_document' =>  'carton_design_document',
            'inner_box_design_document' =>  'inner_box_design_document',
            'accessories_requirement' =>  'accessories_requirement',
            'test_report' =>  'test_report',
            'play_tray' =>  'play_tray',
            'wooden_cases_packed' =>  'wooden_cases_packed',
            'related_attachment' =>  'related_attachment',
            'other_instruction' =>  'other_instruction',
            'attachment' =>  'attachment',
            'uhas_module' =>  'uhas_module',
            'module_shipping' =>  'module_shipping',
            'other_company' =>  'other_company',
        );
        $condition = array('form_id' => $param['related_id']);
        $tableName = 'inhe_production_detail_three';
        $count = $param['addMeterCount'];
        $result = $form->changeDetailData($normalField, $batchField, $param, $condition, $tableName, $count);
        $deleteSql = "delete from inhe_production_detail_three where product_name = '' and form_id = '" . $param['form_id'] . "' ";
        exequery(TD::conn(), $deleteSql);
        return $result;
    }

    public function changeNoneMeter($param)
    {
        $form = new FormModel();
        $normalField = array(
            'form_id' => 'form_id'
        );
        $batchField = array(
            'non_serial_number' => 'non_serial_number',
            'non_product_name' => 'non_product_name',
            'non_order_number' => 'non_order_number',
            'specification_model' => 'specification_model',
            'unit' => 'unit',
            'non_play_tray' => 'non_play_tray',
            'non_attachment' => 'non_attachment',
            'sub_process'=>array('is'),
            'non_type' => '',
            'license_product' => 'license_product',
            'license_years' => 'license_years',
            'license_type' => 'license_type',
            'other_company' =>  'other_company',
        );
        $selectParam = array(
            'is_used' => '1', // 可用参数
            'type' => 'product_detail_type',
        );
        $dataModel = new DataModel();
        $selectArr = $dataModel->getCommonParam($selectParam);
        foreach($selectArr['product_detail_type'] as $key => $val){
            $typeArr[]=$val['paras_value'];
            $countArr[$val['paras_value']]=$param["add".$val['paras_value']."Count"];
        }

        $condition = array('form_id' => $param['related_id']);
        $tableName = 'inhe_production_detail_non';
        $result = $form->changeDetailByType($normalField, $batchField, $param, $condition, $tableName, $typeArr, $countArr);
        $deleteSql = "delete from inhe_production_detail_non where non_product_name = '' and form_id = '" . $param['form_id'] . "' ";
        exequery(TD::conn(), $deleteSql);
        return $result;
    }

    /**
     * 删除指定词条
     */
    public function delete()
    {
        $param = $this->getParamToArray();
        $response = new ResponseCode();
        $result = array();
        $where = " where 1=1 ";
        if (array_key_exists('id', $param) && $param['id']) {
            $where .= " and form_id = '" . $param['id'] . "' ";
        } else {
            $result = $response->failed(false);
            return  $result;
        }
        $sql = " delete from inhe_production_order " . $where;
        $re = exequery(TD::conn(), $sql);
        $sql = " delete from inhe_production_detail_one  where form_id = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $sql);
        $sql = " delete from inhe_production_detail_two  where form_id = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $sql);
        $sql = " delete from inhe_production_detail_three  where form_id = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $sql);
        $sql = " delete from inhe_production_detail_non  where form_id = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $sql);

        $sql = " delete from inhe_flow_opinion  where  form_id = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $sql);

        $sql = " delete from inhe_attachment  where relatedId = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $sql);

        $result = $response->success(true);
        return $result;
    }

    public function makeLineHtml($param, $number,$add_type='',$other_company)
    {
        $model = new DataModel();
        $selectParam = array(
            'is_used' => '1', // 可用参数
            'type' => 'yes_or_no,inspection_type,display_mode,production_mode,test_report,product_brand,license_product,license_type,auto_flow_user'
        );
        $selectArr = $model->getCommonParam($selectParam);

        if ($number <= 0) $number = 1;
        $htmlArr = array();
        foreach ($param as $meter_type => $lineArr) {
            $html = '';
            $html = '<tr align="center">';
            foreach ($lineArr as $val) {
                if($val[user_bu]!='INHE'&&$add_type!="add".$val[user_bu]){
                    continue;
                }
                
                if(empty($other_company)&&$val['item_id']=='other_company'){
                    continue;
                }
                $fieldId = '';
                $fieldId = $val['item_id'] .$add_type. $number;
                $required=$val['is_required']==1?'lay-verify="required"':'';
                $html .= '<td class="TableData" colspan="' . $val['colspan'] . '">';
                if ($val['have_attach']) {
                    $html .= '<input id="' .  $fieldId . '" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" />';
                    // $htmlArr['htmlScript'] .= 'attach.init({elem: "#' . $fieldId . '",uploadUrl: "/general/attachment/attach.php?type=' . $fieldId . '&url=common"});';
                } else if (!empty($val['if_select'])) {
                    $html .= '<div class="layui-input-inline">' .
                        '<select lay-filter="'.$val['item_id'].'" id="' .  $fieldId . '" name="' .  $fieldId . '" lay-search '.$required.'>' .
                        '<option value="">请选择</option>';
                        if($val['item_id']=='other_company'){
                            foreach ($selectArr[$val['if_select']] as $v) {
                                if($other_company==$v['user_bu']){
                                    continue;
                                }
                                $html .= '<option value="' . $v['user_bu'] . '">' . $v['extra'] . '</option>';
                            }
                        }else{
                            foreach ($selectArr[$val['if_select']] as $v) {
                                $html .= '<option value="' . $v['paras_value'] . '">' . $v['paras_desc'] . '</option>';
                            }
                        }
                    
                    $html .= '</select></div>';
                } else {
                    if (strpos($fieldId, 'serial_number') !== false) {
                        $html .= '<input type="text" id="' .  $val['item_id'].$add_type . '" name="' .  $fieldId . '" class="layui-input" readonly value="' . $number . '" />';
                    } else {
                        $html .= '<input type="text" '.$required.' id="' .  $val['item_id'].$add_type . '" name="' .  $fieldId . '" class="layui-input" />';
                    }
                }
                $html .= '</td>';
            }
            $html = mb_substr($html, 0, -5);
            $html .= '<a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a></td>';
            $html .= '</tr>';
            $htmlArr[$meter_type] = $html;
        }
        return $htmlArr;
    }

    /**
     * 获取前台传递的get和post参数值
     */
    public function getParamToArray()
    {
        $postParam = eval(" return " . @iconv("utf-8//IGNORE", "gbk//IGNORE", var_export($_POST, true)) . ";");
        $getParam = eval(" return " . @iconv("utf-8//IGNORE", "gbk//IGNORE", var_export($_GET, true)) . ";");
        return array_merge($postParam, $getParam);
    }

    public function array_iconv($arr, $in_charset = "gbk", $out_charset = "utf-8", $returnType = 'json')
    {
        $ret = eval('return ' . iconv($in_charset, $out_charset, var_export($arr, true) . ';'));
        // 返回值类型判断
        if ($returnType = "array") {
            return $ret;
        }

        // 这里转码之后可以输出json,默认输出json字符串
        return json_encode($ret);
    }
}
