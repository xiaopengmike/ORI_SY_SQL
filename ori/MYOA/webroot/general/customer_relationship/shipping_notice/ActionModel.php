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
    public $systemId = "shipping_notice";

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
        $productDetail = $this->queryProductDetail($param);

        $result = array(
            'main' => $main,
            'productDetail' => $productDetail,
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

        $sql = " select k.*,u.user_name createUserName, d.dept_name deptName from inhe_shipping_notice k " .
            " left join user u on u.user_id = k.user_id " .
            " left join department d on d.dept_id = k.organ_id " . $strWhere;
        $res = exequery(TD::conn(), $sql);

        while ($item = mysql_fetch_assoc($res)) {
            $result = $item;
        }
        mysql_free_result($res);

        return $result;
    }

    public function queryProductDetail($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";

        if (array_key_exists("id", $param) && $param['id'] != "") {
            $strWhere .= " and k.form_id = '" . $param['id'] . "' ";
        } else {
            return $result;
        }

        $sql = " select * from inhe_shipping_detail k " . $strWhere . " order by serial_number ";
        $res = exequery(TD::conn(), $sql);

        while ($item = mysql_fetch_assoc($res)) {
            $result[$item['type']][] = $item;
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
            $flag = $this->$result['action']($result['param']);
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
     * 新增一个客户
     */
    public function add($param)
    {
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
        $userId = $_SESSION['LOGIN_USER_ID'];
        $deptId = $_SESSION['LOGIN_DEPT_ID'];
        $time = date('Y-m-d H:i:s');

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
                batch_number,
                erp_no, 
                currency, 
                consignee, 
                phone, 
                shipment_time, 
                transport_mode, 
                freight_bearing, 
                business_belong, 
                create_user_bu, 
                business_dept, 
                have_entrepot_trade, 
                import_permit,
                involved_product, 
                notice_type, 
                organ_id, 
                user_id, 
                write_time, 
                remark, 
                customer_service,
                customer_service_name,
                signature, 
                status,
                type,
                user_bu) values" .
            "('" . $param['form_id'] . "', '" . $param['title'] . "', '" . $param['customer_name'] . "', '" . $param['contract_party'] . "', '" .
            $param['address'] . "', '" . $param['shipment_place'] . "', '" . $param['order_no'] .  "', '" . $param['order_form_id'] . "', '"  . $param['project_number'] . "', '" .
            $param['batch_number'] . "', '" .$param['erp_no'] . "', '" . $param['currency'] . "', '" . $param['consignee'] . "', '" . $param['phone'] . "', '" .
            $param['shipment_time'] . "', '" . $param['transport_mode'] . "', '" . $param['freight_bearing'] . "', '" . $param['business_belong'] . "', '" .$param['create_user_bu'] . "', '" .
            $param['business_dept'] . "', '" . $param['have_entrepot_trade'] . "', '" .$param['import_permit'] . "', '" . $param['involved_product'] . "', '" . $param['notice_type'] . "', '" .
            $deptId . "', '" . $userId . "','" . $time . "', '" . $param['remark'] ."', '" . $param['customer_service'] ."', '" . $param['customer_service_name'] .
            "', '" . $param['signature'] . "', '" . $param['status'] . "', '" . $param['type'] . "', '" . $param['user_bu'] . "')";
        exequery(TD::conn(), $mainSql);
        $id = mysql_insert_id();
        if ($id > 0) {
            return true;
        }

        return false;
    }

    public function addDetail($param)
    {
        for ($i = 1; $i <= $param['addEleDetailCount']; $i++) {
            $type = "electric";
            $str = "_electric";
            $str .= $i;
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
            type) values" .
                "('" . $param['form_id'] . "', '" . $i . "', '" . $param['name' . $str] . "', '" .
                $param['specification' . $str] . "', '" . $param['number' . $str] . "','" . $param['price' . $str] . "', '" .
                $param['total_amount' . $str] . "', '" . $param['no' . $str] . "', '" . $param['code' . $str] . "', '" .
                $param['order_no' . $str] . "', '" . $type . "')";
            exequery(TD::conn(), $mainSql);
        }
        for ($i = 1; $i <= $param['addOtherDetailCount']; $i++) {
            $type = "other";
            $str = "_other";
            $str .= $i;
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
            type) values" .
                "('" . $param['form_id'] . "', '" . $i . "', '" . $param['name' . $str] . "', '" .
                $param['specification' . $str] . "', '" . $param['number' . $str] . "','" . $param['price' . $str] . "', '" .
                $param['total_amount' . $str] . "', '" . $param['no' . $str] . "', '" . $param['code' . $str] . "', '" .
                $param['order_no' . $str] . "', '" . $type . "')";
            exequery(TD::conn(), $mainSql);
        }
        for ($i = 1; $i <= $param['addCdinheDetailCount']; $i++) {
            $type = "cdinhe";
            $str = "_cdinhe";
            $str .= $i;
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
            type) values" .
                "('" . $param['form_id'] . "', '" . $i . "', '" . $param['name' . $str] . "', '" .
                $param['specification' . $str] . "', '" . $param['number' . $str] . "','" . $param['price' . $str] . "', '" .
                $param['total_amount' . $str] . "', '" . $param['no' . $str] . "', '" . $param['code' . $str] . "', '" .
                $param['order_no' . $str] . "', '" . $type . "')";
            exequery(TD::conn(), $mainSql);
        }
        for ($i = 1; $i <= $param['addInhegridDetailCount']; $i++) {
            $type = "inhegrid";
            $str = "_inhegrid";
            $str .= $i;
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
            type) values" .
                "('" . $param['form_id'] . "', '" . $i . "', '" . $param['name' . $str] . "', '" .
                $param['specification' . $str] . "', '" . $param['number' . $str] . "','" . $param['price' . $str] . "', '" .
                $param['total_amount' . $str] . "', '" . $param['no' . $str] . "', '" . $param['code' . $str] . "', '" .
                $param['order_no' . $str] . "', '" . $type . "')";
            exequery(TD::conn(), $mainSql);
        }
        for ($i = 1; $i <= $param['addInhenergyDetailCount']; $i++) {
            $type = "inhenergy";
            $str = "_inhenergy";
            $str .= $i;
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
            type) values" .
                "('" . $param['form_id'] . "', '" . $i . "', '" . $param['name' . $str] . "', '" .
                $param['specification' . $str] . "', '" . $param['number' . $str] . "','" . $param['price' . $str] . "', '" .
                $param['total_amount' . $str] . "', '" . $param['no' . $str] . "', '" . $param['code' . $str] . "', '" .
                $param['order_no' . $str] . "', '" . $type . "')";
            exequery(TD::conn(), $mainSql);
        }
        for ($i = 1; $i <= $param['addSoftDetailCount']; $i++) {
            $type = "soft";
            $str = "_soft";
            $str .= $i;
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
                "('" . $param['form_id'] . "', '" . $i . "', '" . $param['name' . $str] . "', '" .
                $param['specification' . $str] . "', '" . $param['number' . $str] . "','" . $param['price' . $str] . "', '" .
                $param['total_amount' . $str] . "', '" . $param['no' . $str] . "', '" . $param['code' . $str] . "', '" .
                $param['order_no' . $str] . "', '" .$param['license_product' . $str] . "', '" .$param['license_years' . $str] . "', '" 
                .$param['license_type' . $str] . "', '" .$param['license_key' . $str] . "', '" .$param['license_term' . $str] . "', '" .$param['license_key_date' . $str] . "', '" . $type . "')";
            exequery(TD::conn(), $mainSql);
        }
        $deleteSql = "delete from inhe_shipping_detail where product_name = '' and form_id = '" . $param['form_id'] . "' ";
        exequery(TD::conn(), $deleteSql);
        return true;
    }

   
    public function update($param)
    {
        $this->updateMainData($param);
        $this->updateDetailData($param);
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
            // 'title' => $param['title'],
            'form_id' => $param['form_id'],
            'customer_name' => $param['customer_name'],
            'contract_party' => $param['contract_party'],
            'address' => $param['address'],
            'shipment_place' => $param['shipment_place'],
            'order_no' => $param['order_no'],
            'order_form_id' => $param['order_form_id'],
            'project_number' => $param['project_number'],
            'batch_number' => $param['batch_number'],
            'erp_no' => $param['erp_no'],
            'currency' => $param['currency'],
            'consignee' => $param['consignee'],
            'phone' => $param['phone'],
            'shipment_time' => $param['shipment_time'],
            'transport_mode' => $param['transport_mode'],
            'freight_bearing' => $param['freight_bearing'],
            'business_belong' => $param['business_belong'],
            'business_dept' => $param['business_dept'],
            'have_entrepot_trade' => $param['have_entrepot_trade'],
            'import_permit' => $param['import_permit'],
            'involved_product' => $param['involved_product'],
            'notice_type' => $param['notice_type'],
            'customer_service' => $param['customer_service'],
            'customer_service_name' => $param['customer_service_name'],
            'remark' => $param['remark'],
            'status' => $param['status'],
            'type' => $param['type'],
            'user_bu' => $param['user_bu']
        );
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_shipping_notice';
        $result = $form->updateMainData($paramInsert, $condition, $tableName, array(), '');
        return $result;
    }

    public function updateDetailData($param)
    {
        $form = new FormModel();
        $normalField = array(
            'form_id' => 'form_id'
        );
        $batchField = array(
            'serial_number' => 'serial_number',
            'product_name' => 'name',
            'internal_model' => 'specification',
            'number' => 'number',
            'price' => 'price',
            'total_amount' => 'total_amount',
            'project_number' => 'no',
            'project_code' => 'code',
            'order_number' => 'order_no',
            'license_product' => 'license_product',
            'license_years' => 'license_years',
            'license_type' => 'license_type',
            'license_key' => 'license_key',
            'license_term' => 'license_term',
            'license_key_date' => 'license_key_date',
            'type' => ''
        );
        $condition = array('form_id' => $param['form_id']);
        $typeArr = array('electric', 'other', 'soft','inhegrid','inhenergy','cdinhe');
        $countArr = array(
            'electric' => $param['addEleDetailCount'],
            'other' => $param['addOtherDetailCount'],
            'cdinhe' => $param['addCdinheDetailCount'],
            'soft' => $param['addSoftDetailCount'],
            'inhegrid' => $param['addInhegridDetailCount'],
            'inhenergy' => $param['addInhenergyDetailCount'],
        );
        $tableName = 'inhe_shipping_detail';
        $result = $form->updateBatchDetailByType($normalField, $batchField, $param, $condition, $tableName, $typeArr, $countArr);
        $deleteSql = "delete from inhe_shipping_detail where product_name = '' and form_id = '" . $param['form_id'] . "' ";
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

        $sql = " delete from inhe_shipping_notice " . $where;
        $re = exequery(TD::conn(), $sql);
        $sql = " delete from inhe_shipping_detail  where form_id = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $sql);

        $sql = " delete from inhe_flow_opinion  where  form_id = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $sql);

        $sql = " delete from inhe_attachment  where relatedId = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $sql);

        $result = $response->success(true);
        return $result;
    }
    public function updateLicenseInfo($param)
    {
        
        if ($param['license_product_num'] <= 0) {
            return true;
        }
        for ($i = 1; $i <= $param['license_product_num']; $i++) {
            $license_product_id=$param['license_product_id_'.$i];
            $license_version=$param['license_version_'.$i];
            $license_effect_time=$param['license_effect_time_'.$i];
            $license_times=$param['license_times_'.$i];
            $license_days=$param['license_days_'.$i];
            $license_expire_time=$param['license_expire_time_'.$i];
            $license_code=$param['license_code_'.$i];
           
            if( $license_product_id>0){
                if(isset($param['license_version_'.$i])){
                    $sql="UPDATE `inhe_shipping_detail` 
                    SET `license_version`='$license_version'
                     WHERE `id` = $license_product_id";
                    exequery(TD::conn(), $sql);
                }else{
                    $sql="UPDATE `inhe_shipping_detail` 
                    SET 
                    `license_effect_time` = '$license_effect_time',
                    `license_times` = '$license_times',
                    `license_days` = '$license_days',
                    `license_expire_time` = '$license_expire_time',
                    `license_code` = '$license_code'
                     WHERE `id` = $license_product_id";
                    exequery(TD::conn(), $sql);
                }
               
            }
        }
    }
    public function updateApproveOpinion()
    {
        $param = $this->getParamToArray();
        $process = new ProcessModel();
        $result = $process->updateProcessOpinion($param);
         //保存授权信息
         $this->updateLicenseInfo($param);
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
