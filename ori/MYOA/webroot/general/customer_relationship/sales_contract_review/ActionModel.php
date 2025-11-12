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
 * inhe_contract_review  数据主表 insert or update
 * inhe_control_library_history 历史记录表 insert
 */

class ActionModel
{
    public $systemId = "sales_contract_review";

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
        $product = $this->queryProduct($param);
        //TODO 慧软CRM合同评审$licenseProduct = $this->queryLicenseProduct($param);
        $contract = $this->queryContractDetail($param);
        $settle = $this->querySettle($param);
        $param['user_bu'] = $main['user_bu'];
        $settleModel = $this->querySettleModel($param);

        $result = array(
            'main' => $main,
            'product' => $product,
            //TODO 慧软CRM合同评审'licenseProduct' => $licenseProduct,
            'contract' => $contract,
            'settle' => $settle,
            'settleModel' => $settleModel
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

        $sql = " select distinct k.*,m.project_star realStar from inhe_contract_review k left join inhe_project_main m on m.project_code = k.project_name " . $strWhere;
        $res = exequery(TD::conn(), $sql);

        while ($item = mysql_fetch_assoc($res)) {
            if ($item['project_star'] != $item['realStar'] && $item['realStar'] != '') {
                $item['project_star'] = $item['realStar'];
            }
            $result = $item;
        }
        mysql_free_result($res);

        return $result;
    }

    public function queryProduct($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";

        if (array_key_exists("id", $param) && $param['id'] != "") {
            $strWhere .= " and k.form_id = '" . $param['id'] . "' ";
        } else {
            return $result;
        }

        $sql = " select * from inhe_product_detail k " . $strWhere . " order by serial_number ";
        $res = exequery(TD::conn(), $sql);

        while ($item = mysql_fetch_assoc($res)) {
            $result[$item['type']][] = $item;
        }
        mysql_free_result($res);

        return $result;
    }
    public function queryLicenseProduct($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";

        if (array_key_exists("id", $param) && $param['id'] != "") {
            $strWhere .= " and k.form_id = '" . $param['id'] . "' ";
        } else {
            return $result;
        }

        $sql = " select * from inhe_contract_review_pn k " . $strWhere . " order by serial_number ";
        
        $res = exequery(TD::conn(), $sql);

        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
        }
        mysql_free_result($res);
        return $result;
    }

    public function queryContractDetail($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";

        if (array_key_exists("id", $param) && $param['id'] != "") {
            $strWhere .= " and k.form_id = '" . $param['id'] . "' ";
        } else {
            return $result;
        }

        $sql = " select k.*,u.user_name businessName, d.dept_name businessDept from inhe_contract_detail k 
        left join user u on u.user_id = k.create_user
        left join department d on d.dept_id = k.dept_id
        " . $strWhere;
        $res = exequery(TD::conn(), $sql);

        while ($item = mysql_fetch_assoc($res)) {
            $result = $item;
        }
        mysql_free_result($res);

        return $result;
    }

    public function querySettle($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";

        if (array_key_exists("id", $param) && $param['id'] != "") {
            $strWhere .= " and k.form_id = '" . $param['id'] . "' ";
        } else {
            return $result;
        }

        $sql = " select * from inhe_contract_settle k " . $strWhere;
        $res = exequery(TD::conn(), $sql);

        while ($item = mysql_fetch_assoc($res)) {
            $result[$item['item_id']] = $item;
        }
        mysql_free_result($res);

        return $result;
    }

    public function querySettleModel($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";

        if (array_key_exists("user_bu", $param) && $param['user_bu'] != "") {
            $strWhere .= " and k.user_bu = '" . $param['user_bu'] . "' ";
        }

        $sql = " select * from inhe_contract_settle_model k " . $strWhere . " order by sort_code asc ";
        $res = exequery(TD::conn(), $sql);

        while ($item = mysql_fetch_assoc($res)) {
            $result[$item['item_id']] = $item;
        }
        mysql_free_result($res);

        return $result;
    }

    public function submit()
    {
        $flag = false;
        $param = $this->getParamToArray();
        $form = new FormModel();
        $result = $form->submit($param);
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
            if($this->verifyDataExist($param['form_id']) && $param['pageName']){
                $result = array(
                    'code' => '0',
                    'status' => 'failed',
                    'msg' => '销售合同编号已经被使用！',
                    'data' => '销售合同编号已经被使用！'
                );
                return $result;
            }
            if($this->verifyProjectNumberExist($param['form_id'],$param['project_number'],$param['user_bu']) && $param['pageName']=='addPhp'){
                $result = array(
                    'code' => '0',
                    'status' => 'failed',
                    'msg' => '项目编号已经被使用！',
                    'data' => '项目编号已经被使用！'
                );
                return $result;
            }
            if (!$this->verifyDataExist($param['form_id'])) {
                $result['action'] = 'add';
                if ($param['action'] == 'ChangeSave') $result['action'] = 'changeAdd';
                $result['param']['title'] = $form->makeFormTitle($param);
            }
            $flag = $this->$result['action']($result['param']);
            //增加如果数据表存在form_id则重新调用
            if($flag == false && $result['action'] == 'add'){
                return $this->submit();
            }
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
            if($this->verifyDataExist($param['form_id']) && $param['pageName']){
                $result = array(
                    'code' => '0',
                    'status' => 'failed',
                    'msg' => '销售合同编号已经被使用！',
                    'data' => '销售合同编号已经被使用！'
                );
                return $result;
            }
            if($this->verifyProjectNumberExist($param['form_id'],$param['project_number'],$param['user_bu']) && $param['pageName']=='addPhp'){
                $result = array(
                    'code' => '0',
                    'status' => 'failed',
                    'msg' => '项目编号已经被使用！',
                    'data' => '项目编号已经被使用！'
                );
                return $result;
            }
            if (!$this->verifyDataExist($param['form_id'])) {
                $result['action'] = 'add';
                if ($param['action'] == 'ChangeSave') $result['action'] = 'changeAdd';
                $result['param']['title'] = $form->makeFormTitle($param);
            }
            $flag = $this->$result['action']($result['param']);
            $result = $form->makeProcessData($result['param'], $flag);
        }
        return $result;
    }

    public function verifyDataExist($formId)
    {
        $flag = false;
        if (isset($formId)) {
            $sql = " select * from inhe_contract_review where form_id = '$formId' ";
            $res = exequery(TD::conn(), $sql);
            $count = mysql_num_rows($res);
            if ($count > 0) {
                $flag = true;
            }
        }
        return $flag;
    }
    public function verifyProjectNumberExist($formId,$project_number,$user_bu)
    {
        $flag = false;
        if (isset($formId)) {
            $sql = " select * from inhe_contract_review where project_number = '$project_number' and user_bu ='$user_bu' ";
            $res = exequery(TD::conn(), $sql);
            $count = mysql_num_rows($res);
            if ($count > 0) {
                $flag = true;
            }
        }
        return $flag;
    }

    /**
     * 新增一个客户
     */
    public function add($param)
    {
        $addMainResult=$this->addMain($param);
        if(!$addMainResult){
            return false;
        }
        $this->addProduct($param);
        //TODO 慧软CRM合同评审$this->addLicenseProduct($param);
        $this->addContractDetail($param);
        $this->addSettle($param);

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
        $userId = $_SESSION['LOGIN_USER_ID'];
        $deptId = $_SESSION['LOGIN_DEPT_ID'];
        $time = date('Y-m-d H:i:s');
        $status = $param['status'];
        //更新数据时增加form_id是否存在对应值
        $where = " where not exists (select * from inhe_contract_review where form_id='".$param['form_id']."')";
        $limit = " limit 1";
        $sql = "insert into inhe_contract_review (title, 
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
            organ_id,
            user_id,
            write_time,
            status,
            create_user_bu,
            type,
            customer_order_no,            
            contract_type,
            urgent,
            user_bu)" .
            " select '" . $param['title'] . "', '" . $param['form_id'] . "', '" . $param['related_id'] . "', '" . $param['customer_name'] . "', '" . $param['contract_party'] . "', '" .
            $param['contract_number'] . "', '" . $param['project_name'] . "', '" . $param['project_number'] . "', '" .
            $param['currency'] . "', '" . $param['delivery_time'] . "','" . $param['project_star'] . "', '" . $param['contract_belong'] . "', '" .
            $deptId . "', '" . $userId . "','" . $time . "', '" . $status ."', '" . $param['create_user_bu'] .
            "', '" . $param['type'] . "', '" . $param['customer_order_no'] . "', '" . $param['contract_type'] . "', '" . $param['urgent'] . "', '" . $param['user_bu'] . "' from inhe_contract_review ".$where.$limit;
        exequery(TD::conn(), $sql);
        //更新数据时增加form_id是否存在对应值,若存在则重新调用add方法
        if(mysql_insert_id()==0){
            return false;
        };

        return true;
    }

    public function addProduct($param)
    {
        $selectParam = array(
            'is_used' => '1', // 可用参数
            'type' => 'product_detail_type',
        );
        $dataModel = new DataModel();
        $selectArr = $dataModel->getCommonParam($selectParam);
        foreach($selectArr['product_detail_type'] as $key => $val){
            $typeArr[strtolower($val['paras_value'])]="add".$val['paras_value']."DetailCount";
        }
        
        //$typeArr = array('electric' => 'addEleDetailCount', 'other' => 'addOtherDetailCount', 'soft' => 'addSoftDetailCount', 'electricParts' => 'addElectricPartsDetailCount', 'distribution' => 'addDistributionDetailCount');
        foreach ($typeArr as $type => $addCount) {
            for ($i = 1; $i <= $param[$addCount]; $i++) {
                $addItem = "";
                $addItem = $type . $i;
                $sql = "insert into inhe_product_detail (form_id,
                        serial_number,
                        name,
                        brand,
                        other_company_product,
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
                    "('" . $param['form_id'] . "', '" . $i . "', '" . $param['name_' . $addItem] . "', '".$param['brand_' . $addItem] . "', '" .$param['other_company_product_' . $addItem] . "', '" 
                    .$param['license_product_' . $addItem] . "', '" 
                    .$param['license_years_' . $addItem] . "', '".$param['license_type_' . $addItem] . "', '" . $param['specification_' . $addItem] ."', '" . $param['unit_' . $addItem] . "','" 
                    . $param['number_' . $addItem] . "', '" . $param['price_' . $addItem] ."', '" . $param['total_amount_' . $addItem] . "', '" . $param['tech_requirement_' . $addItem] . "', '" . $type . "')";
                if (!empty($param['name_' . $addItem])) {
                    exequery(TD::conn(), $sql);
                }
            }
        }

        return true;
    }

    public function addLicenseProduct($param)
    {
        for ($i = 1; $i <= $param["addSoftLicenseProductDetailCount"]; $i++) {
            $addItem = "";
            $addItem = "softlicenseproduct" . $i;
            $sql = "insert into inhe_contract_review_pn (form_id,
                    serial_number,
                    license_product,
                    license_years,
                    license_type
                    ) values" .
                "('" . $param['form_id'] . "', '" . $i . "', '" .$param['license_product_' . $addItem] . "', '" 
                .$param['license_years_' . $addItem] . "', '".$param['license_type_' . $addItem] . "')";
            if (!empty($param['license_product_' . $addItem])) {
                exequery(TD::conn(), $sql);
            }
        }
        return true;
    }


    public function addContractDetail($param)
    {
        $userId = $_SESSION['LOGIN_USER_ID'];
        $deptId = $_SESSION['LOGIN_DEPT_ID'];
        $param['contract_begin_time']=empty($param['contract_begin_time'])?null:$param['contract_begin_time'];
        $param['contract_end_time']=empty($param['contract_end_time'])?null:$param['contract_end_time'];
        $sql = "insert into inhe_contract_detail (form_id,
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
        if_eligible,customer_service) values" .
            "('" . $param['form_id'] . "', '" . $param['total_amount'] . "', '" . $userId . "', '" . $deptId . "','" .
            $param['business_attribution'] . "', '" . $param['business_user_id'] . "', '" .$param['business_dept_id'] . "', '" .$param['form_date'] . "', '" . $param['transport_mode'] . "', '" . $param['freight_bearing'] . "','" .
            $param['delivery_place'] . "', '" . $param['shipping_address'] . "', '" . $param['package_requirement'] . "', '" . $param['acceptance_criteria'] . "','" .
            $param['have_entrepot_trade'] . "', '" . $param['involved_product'] . "', '" . $param['quote_source'] . "', '" . $param['history_project_code'] . "','" .
            $param['have_tech_specification'] . "', '" . $param['have_email_approval'] . "', '" . $param['email_name'] . "', '" . $param['product_model'] . "','" .
            $param['have_inventory'] . "', '" . $param['functional_requirement'] . "', '" . $param['special_requirement'] . "', '" . $param['spare_requirement'] . "', '" . $param['change_explain']. "', '" .
            $param['is_collective']. "', '" .$param['past_pcode']. "', '" .$param['c_past_pcode']. "', '" .$param['funds_come']. "', '" .$param['financing_bank']. "', '" .
            $param['product_project_type']. "', '" .$param['contract_begin_time']. "', '" .$param['contract_end_time']. "', '". 
            //TODO 慧软CRM合同评审$param['om_services']. "', '" .$param['om_services_years']. "', '" .$param['has_soft']. "', '".
            $param['contract_background']. "', '". 
			$param['if_eligible'] . "', '". 
			$param['customer_service'] . "')";

        exequery(TD::conn(), $sql);
        $id = mysql_insert_id();

        return $id;
    }

    public function addSettle($param)
    {
        if ($param['model_count'] <= 0) {
            return true;
        }
        for ($i = 1; $i <= $param['model_count']; $i++) {
            $sql = "insert into inhe_contract_settle (form_id,
            item_id,
            content,
            attach_name) values" .
                "('" . $param['form_id'] . "', '" . $i . "', '" . $param['model_' . $i] . "', '')";
            exequery(TD::conn(), $sql);
        }

        return true;
    }

    /**
     * 更新数据
     */
    public function update($param)
    {
        $this->updateMainData($param);
        $this->updateProductData($param);
        //TODO 慧软CRM合同评审$this->updateLicenseProductData($param);
        $this->updateContractDetail($param);
        $this->updateSettleData($param);

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
            'customer_name' => $param['customer_name'],
            'contract_party' => $param['contract_party'],
            'project_name' => $param['project_name'],
            'project_number' => $param['project_number'],
            'currency' => $param['currency'],
            'delivery_time' => $param['delivery_time'],
            'project_star' => $param['project_star'],
            'contract_belong' => $param['contract_belong'],
            'status' => $param['status'],
            'type' => $param['type'],
            'customer_order_no' => $param['customer_order_no'],
            'urgent' => $param['urgent'],
            'contract_type' => $param['contract_type'],
            'user_bu' => $param['user_bu'],
            'create_user_bu' => $param['create_user_bu'],
        );
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_contract_review';
        $result = $form->updateMainData($paramInsert, $condition, $tableName, array(), '');
        return $result;
    }

    public function updateProductData($param)
    {
        $form = new FormModel();
        $normalField = array(
            'form_id' => 'form_id'
        );
        $batchField = array(
            'serial_number' => 'serial_number',
            'name' => 'name',
            'brand' => 'brand',
            'other_company_product'=>'other_company_product',
            'license_product' => 'license_product',
            'license_years' => 'license_years',
            'license_type' => 'license_type',
            'specification' => 'specification',
            'unit' => 'unit',
            'number' => 'number',
            'price' => 'price',
            'total_amount' => 'total_amount',
            'tech_requirement' => 'tech_requirement',
            'type' => '',
        );
        $selectParam = array(
            'is_used' => '1', // 可用参数
            'type' => 'product_detail_type',
        );
        $dataModel = new DataModel();
        $selectArr = $dataModel->getCommonParam($selectParam);
        foreach($selectArr['product_detail_type'] as $key => $val){
            $typeArr[]=strtolower($val['paras_value']);
            $countArr[strtolower($val['paras_value'])]=$param["add".$val['paras_value']."DetailCount"];
        }

        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_product_detail';
        $result = $form->updateBatchDetailByType($normalField, $batchField, $param, $condition, $tableName, $typeArr, $countArr);
        $sql = " delete from $tableName where form_id = '" . $param['form_id'] . "' and (name = '' or name is null) ";
        exequery(TD::conn(), $sql);
        return $result;
    }

    public function updateLicenseProductData($param)
    {
        $form = new FormModel();
        $normalField = array(
            'form_id' => 'form_id'
        );
        $batchField = array(
            'serial_number' => 'serial_number_softlicenseproduct',
            'license_product' => 'license_product_softlicenseproduct',
            'license_years' => 'license_years_softlicenseproduct',
            'license_type' => 'license_type_softlicenseproduct',
        );     
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_contract_review_pn';
        $count = $param['addSoftLicenseProductDetailCount'];
        $result = $form->updateDetailData($normalField, $batchField, $param, $condition, $tableName, $count);
        $sql = " delete from $tableName where form_id = '" . $param['form_id'] . "' and (license_product = '' or license_product is null) ";
        exequery(TD::conn(), $sql);
        return $result;
    }

    public function updateContractDetail($param)
    {
        $form = new FormModel();
        $paramInsert = array(
            'form_id' => $param['form_id'],
            'total_amount' => $param['total_amount'],
            'create_user' => $_SESSION['LOGIN_USER_ID'],
            'dept_id' => $_SESSION['LOGIN_DEPT_ID'],
            'business_attribution' => $param['business_attribution'],
            'business_user_id' => $param['business_user_id'],
            'business_dept_id' => $param['business_dept_id'],
            'form_date' => $param['form_date'],
            'transport_mode' => $param['transport_mode'],
            'freight_bearing' => $param['freight_bearing'],
            'delivery_place' => $param['delivery_place'],
            'shipping_address' => $param['shipping_address'],
            'package_requirement' => $param['package_requirement'],
            'acceptance_criteria' => $param['acceptance_criteria'],
            'have_entrepot_trade' => $param['have_entrepot_trade'],
            'involved_product' => $param['involved_product'],
            'quote_source' => $param['quote_source'],
            'history_project_code' => $param['history_project_code'],
            'have_tech_specification' => $param['have_tech_specification'],
            'have_email_approval' => $param['have_email_approval'],
            'email_name' => $param['email_name'],
            'product_model' => $param['product_model'],
            'have_inventory' => $param['have_inventory'],
            'functional_requirement' => $param['functional_requirement'],
            'special_requirement' => $param['special_requirement'],
            'spare_requirement' => $param['spare_requirement'],
            'change_explain' => $param['change_explain'],
            'is_collective' => $param['is_collective'],
            'past_pcode' => $param['past_pcode'],
            'c_past_pcode' => $param['c_past_pcode'],
            'funds_come' => $param['funds_come'],
            'financing_bank' => $param['financing_bank'],
            'product_project_type' => $param['product_project_type'],
            'contract_begin_time' => empty($param['contract_begin_time'])?null:$param['contract_begin_time'],
            'contract_end_time' => empty($param['contract_end_time'])?null:$param['contract_end_time'],
            //TODO 慧软CRM合同评审'om_services' => $param['om_services'],
            //TODO 慧软CRM合同评审'om_services_years' => $param['om_services_years'],
            //TODO 慧软CRM合同评审'has_soft' => $param['has_soft'],
            'contract_background' => $param['contract_background'],
            'if_eligible' => $param['if_eligible'],
            'customer_service' => $param['customer_service'],
            
        );
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_contract_detail';
        $sql = "select * from $tableName where form_id = '" . $param['form_id'] . "' ";
        $res = exequery(TD::conn(), $sql);
        $count = mysql_num_rows($res);
        if ($count <= 0) {
            // add data
            $result = $form->addMainData($paramInsert, $tableName, $param['attach'], $param['form_id']);
        } else {
            // update data
            $result = $form->updateMainData($paramInsert, $condition, $tableName, $param['attach'], $param['form_id']);
        }
        return $result;
    }

    public function updateSettleData($param)
    {
        $form = new FormModel();
        $normalField = array(
            'form_id' => 'form_id'
        );
        $batchField = array(
            'item_id' => '',
            'content' => 'model_'
        );
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_contract_settle';
        $count = $param['model_count'];
        $result = $form->updateDetailData($normalField, $batchField, $param, $condition, $tableName, $count);
        return $result;
    }

    public function change()
    {

        $flag = false;
        $param = $this->getParamToArray();
         //存在待审核变更单，请先处理
         $sql="select * from inhe_contract_review where status='p' and related_id = '".$param['related_id']."'";
         $res = exequery(TD::conn(), $sql);
         if(mysql_num_rows($res)>1){
             return array("code"=>0,"msg"=>"该编号（".$param['related_id']."）存在审核中变更单！请先处理");
         }
        $form = new FormModel();
        $result = $form->submit($param);
        if (array_key_exists('action', $result)) {
            $flag = $this->$result['action']($result['param']);
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
        $this->changeProductData($param);
        //TODO 慧软CRM合同评审$this->changeLicenseProductData($param);
        $this->changeContractDetail($param);
        $this->changeSettleData($param);
        // 附件数据保存 
        $attachArr = $param['attach'];
        if (is_array($attachArr)) {
            $attachIds = implode(',', $attachArr);
            $attachIds = rtrim($attachIds, ',');
            $attach = new AttachModel();
            $attach->changeAddAttach($attachIds, $param['form_id']);
        }
        return true;
    }

    public function changeUpdate($param)
    {
        $this->updateMainData($param);
        $this->updateProductData($param);
        //TODO 慧软CRM合同评审$this->updateLicenseProductData($param);
        $this->updateContractDetail($param);
        $this->updateSettleData($param);
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
            'related_id' => $param['related_id'],
            'customer_name' => $param['customer_name'],
            'contract_party' => $param['contract_party'],
            'project_name' => $param['project_name'],
            'project_number' => $param['project_number'],
            'currency' => $param['currency'],
            'delivery_time' => $param['delivery_time'],
            'project_star' => $param['project_star'],
            'contract_belong' => $param['contract_belong'],
            'create_user_bu' => $param['create_user_bu'],
            'from_id' => $param['from_id'],
            'status' => $param['status'],
            'type' => $param['type'],
            'customer_order_no' => $param['customer_order_no'],
            'urgent' => $param['urgent'],
            'contract_type' => $param['contract_type'],
            'user_bu' => $param['user_bu']
        );
        $condition = array('form_id' => $param['related_id']);
        $tableName = 'inhe_contract_review';
        $result = $form->changeMainData($paramInsert, $condition, $tableName, array(), '');
        return $result;
    }

    public function changeProductData($param)
    {
        $form = new FormModel();
        $normalField = array(
            'form_id' => 'form_id'
        );
        $batchField = array(
            'serial_number' => 'serial_number',
            'name' => 'name',
            'brand' => 'brand',
            'other_company_product'=>'other_company_product',
            'license_product' => 'license_product',
            'license_years' => 'license_years',
            'license_type' => 'license_type',
            'specification' => 'specification',
            'unit' => 'unit',
            'number' => 'number',
            'price' => 'price',
            'total_amount' => 'total_amount',
            'tech_requirement' => 'tech_requirement',
            'type' => '',
        );
        $selectParam = array(
            'is_used' => '1', // 可用参数
            'type' => 'product_detail_type',
        );
        $dataModel = new DataModel();
        $selectArr = $dataModel->getCommonParam($selectParam);
        foreach($selectArr['product_detail_type'] as $key => $val){
            $typeArr[]=strtolower($val['paras_value']);
            $countArr[strtolower($val['paras_value'])]=$param["add".$val['paras_value']."DetailCount"];
        }
        // $typeArr = array('electric', 'other', 'soft','electricParts','distribution');
        // $countArr = array(
        //     'electric' => $param['addEleDetailCount'],
        //     'other' => $param['addOtherDetailCount'],
        //     'soft' => $param['addSoftDetailCount'],
        //     'electricParts' => $param['addElectricPartsDetailCount'],
        //     'distribution' => $param['addDistributionDetailCount'],
        // );
        $condition = array('form_id' => $param['related_id']);
        $tableName = 'inhe_product_detail';
        $result = $form->changeDetailByType($normalField, $batchField, $param, $condition, $tableName, $typeArr, $countArr);
        $sql = " delete from $tableName where form_id = '" . $param['form_id'] . "' and (name = '' or name is null) ";
        exequery(TD::conn(), $sql);
        return $result;
    }

    public function changeLicenseProductData($param)
    {
        $form = new FormModel();
        $normalField = array(
            'form_id' => 'form_id'
        );
        $batchField = array(
            'serial_number' => 'serial_number_softlicenseproduct',
            'license_product' => 'license_product_softlicenseproduct',
            'license_years' => 'license_years_softlicenseproduct',
            'license_type' => 'license_type_softlicenseproduct',
        );     
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_contract_review_pn';
        $count = $param['addSoftLicenseProductDetailCount'];
        $result = $form->changeDetailData($normalField, $batchField, $param, $condition, $tableName, $count);
        $sql = " delete from $tableName where form_id = '" . $param['form_id'] . "' and (license_product = '' or license_product is null) ";
        exequery(TD::conn(), $sql);
        return $result;
    }

    public function changeContractDetail($param)
    {
        $form = new FormModel();
        $paramInsert = array(
            'form_id' => $param['form_id'],
            'total_amount' => $param['total_amount'],
            'create_user' => $_SESSION['LOGIN_USER_ID'],
            'dept_id' => $_SESSION['LOGIN_DEPT_ID'],
            'business_attribution' => $param['business_attribution'],
            'business_user_id' => $param['business_user_id'],
            'business_dept_id' => $param['business_dept_id'],
            'form_date' => $param['form_date'],
            'transport_mode' => $param['transport_mode'],
            'freight_bearing' => $param['freight_bearing'],
            'delivery_place' => $param['delivery_place'],
            'shipping_address' => $param['shipping_address'],
            'package_requirement' => $param['package_requirement'],
            'acceptance_criteria' => $param['acceptance_criteria'],
            'have_entrepot_trade' => $param['have_entrepot_trade'],
            'involved_product' => $param['involved_product'],
            'quote_source' => $param['quote_source'],
            'history_project_code' => $param['history_project_code'],
            'have_tech_specification' => $param['have_tech_specification'],
            'have_email_approval' => $param['have_email_approval'],
            'email_name' => $param['email_name'],
            'product_model' => $param['product_model'],
            'have_inventory' => $param['have_inventory'],
            'functional_requirement' => $param['functional_requirement'],
            'special_requirement' => $param['special_requirement'],
            'spare_requirement' => $param['spare_requirement'],
            'change_explain' => $param['change_explain'],
            'is_collective' => $param['is_collective'],
            'past_pcode' => $param['past_pcode'],
            'c_past_pcode' => $param['c_past_pcode'],
            'funds_come' => $param['funds_come'],
            'financing_bank' => $param['financing_bank'],
            'product_project_type' => $param['product_project_type'],
            'contract_begin_time' => empty($param['contract_begin_time'])?null:$param['contract_begin_time'],
            'contract_end_time' => empty($param['contract_end_time'])?null:$param['contract_end_time'],
            //TODO 慧软CRM合同评审'om_services' => $param['om_services'],
            //TODO 慧软CRM合同评审'om_services_years' => $param['om_services_years'],
            //TODO 慧软CRM合同评审'has_soft' => $param['has_soft'],
            'contract_background' => $param['contract_background'],
            'if_eligible' => $param['if_eligible'],
            'customer_service' => $param['customer_service'],
        );
        $condition = array('form_id' => $param['related_id']);
        $tableName = 'inhe_contract_detail';
        $result = $form->changeMainData($paramInsert, $condition, $tableName, array(), '');
        return $result;
    }

    public function changeSettleData($param)
    {
        $form = new FormModel();
        $normalField = array(
            'form_id' => 'form_id'
        );
        $batchField = array(
            'item_id' => '',
            'content' => 'model_'
        );
        $condition = array('form_id' => $param['related_id']);
        $tableName = 'inhe_contract_settle';
        $count = $param['model_count'];
        $result = $form->changeDetailData($normalField, $batchField, $param, $condition, $tableName, $count);
        return $result;
    }
    
    //获取最新版本变更单
    public function getNewChange($relatedId){
        $result=array();
        $sql = "select a.*,b.change_explain from inhe_contract_review a left join inhe_contract_detail b on a.form_id=b.form_id where a.form_id='".$relatedId."'";
        $res = exequery(TD::conn(), $sql);
        $row=mysql_fetch_assoc($res);
        $relatedId=empty($row['related_id'])?$relatedId:$row['related_id'];
        if(empty($row['related_id'])){
            if($row['version']<=0){
                return $result;
            }
            $result[] = $row;
        }else{
            $sql = "select a.*,b.change_explain from inhe_contract_review a left join inhe_contract_detail b on a.form_id=b.form_id where a.status in ('Y','R') and a.form_id='".$row['related_id']."'";
            $res = exequery(TD::conn(), $sql);
            $result[] = mysql_fetch_assoc($res);
        }
        if($result[0]['version']<=0){
            return $result;
        }
        //备案或者审核通过('Y','R')
        $sql = "select a.*,b.change_explain from inhe_contract_review a left join inhe_contract_detail b on a.form_id=b.form_id where a.status in ('Y','R') and a.related_id='".$relatedId."'  order by a.version,a.id";
        $res = exequery(TD::conn(), $sql);
        
        while($row=mysql_fetch_assoc($res)){
            $result[] = $row;
        }
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

        $sql = " delete from inhe_contract_review " . $where;
        $re = exequery(TD::conn(), $sql);
        $sql = " delete from inhe_product_detail  where form_id = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $sql);
        //TODO 慧软CRM合同评审$sql = " delete from inhe_contract_review_pn  where form_id = '" . $param['id'] . "' ";
        //TODO 慧软CRM合同评审$res = exequery(TD::conn(), $sql);
        $sql = " delete from inhe_contract_detail  where form_id = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $sql);
        $sql = " delete from inhe_contract_settle  where form_id = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $sql);

        $sql = " delete from inhe_flow_opinion  where form_id = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $sql);

        $sql = " delete from inhe_attachment  where relatedId = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $sql);

        $result = $response->success(true);
        return $result;
    }

    public function updateApproveOpinion()
    {
        $param = $this->getParamToArray();
        $process = new ProcessModel();
        $result = $process->updateProcessOpinion($param);
        if(isset($param[customer_service])&&$param[customer_service]!=$param[customer_service2]){
            $sql = " update inhe_contract_detail set customer_service='$param[customer_service]'  where form_id = '" . $param['form_id'] . "' ";
            exequery(TD::conn(), $sql);
            add_log(1, $param[form_id].'交付经理：'.$param[customer_service2].$param[customer_service_name2].'改为'.$param[customer_service].$param[customer_service_name],$_SESSION[LOGIN_USER_ID]);
        }
        return $result;
    }

    public function addOtherCondition()
    {
        $sql = " select * from inhe_oa_paras where type = 'other_condition' ";
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item['paras_value'];
        }
        return $result;
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

    /**
     * 将含有GBK的中文数组转为utf-8
     *
     * @param array $arr   数组
     * @param string $in_charset 原字符串编码
     * @param string $out_charset 输出的字符串编码
     * @param string $returnType   返回值类型：数组；json字符串
     * @return array
     */
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
