<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_field.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");
include_once("../common/CommonMethodModel.php");
require_once("../common/ProcessModel.php");
require_once("../common/FormModel.php");
require_once("../common/ResponseCode.php");
require_once("../common/AttachModel.php");

/**
 * 后台操作类
 * @author ysr
 */

class ActionModel
{
    const MAIN_TABLE = "inhe_sales_receipt";  // 主表
    const DETAIL_TABLE = "inhe_sales_receipt_detail";
    const ATTACH_TABLE = "inhe_sales_receipt_attach";
    const PRODUCT_TABLE = "inhe_sales_receipt_product";

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
        $detail = $this->queryDetail($param);
        $attach = $this->queryAttach($param);
        $product = $this->queryProduct($param);
        $result = array(
            'main' => $main,
            'detail' => $detail,
            'attach' => $attach,
            'product' => $product
        );
        return $result;
    }

    public function queryMain($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";
        if (array_key_exists("id", $param) && $param['id'] != "") {
            $strWhere .= " and m.form_id = '" . $param['id'] . "' ";
        } else {
            return $result;
        }
        $sql = " select m.*,d.dept_name deptName from " . self::MAIN_TABLE . " m " .
            " left join department d on d.dept_id = m.organ_id " . $strWhere;
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result = $item;
        }
        mysql_free_result($res);
        return $result;
    }

    public function queryDetail($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";
        if (array_key_exists("id", $param) && $param['id'] != "") {
            $strWhere .= " and k.form_id = '" . $param['id'] . "' ";
        } else {
            return $result;
        }
        $sql = " select * from " . self::DETAIL_TABLE . " k " . $strWhere;
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
        }
        mysql_free_result($res);
        return $result;
    }

    public function queryAttach($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";
        if (array_key_exists("id", $param) && $param['id'] != "") {
            $strWhere .= " and k.form_id = '" . $param['id'] . "' ";
        } else {
            return $result;
        }
        $sql = " select * from " . self::ATTACH_TABLE . " k " . $strWhere;
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result[$item['type']][] = $item;
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
        $sql = " select * from " . self::PRODUCT_TABLE . " k " . $strWhere;
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
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
        $id = $this->addMain($param);
        if ($id <= 0) {
            return false;
        }
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
        $mainSql = "insert into inhe_sales_receipt (
        organ_id, 
        user_id, 
        write_time, 
        title, 
        form_id, 
        payer_name, 
        receipt_bank, 
        original_amount, 
        currency, 
        rmb_amount, 
        payment_term, 
        receipt_date, 
        business_dept, 
        project_number, 
        status, 
        type, 
        user_bu
        ) values" .
            "('" . $deptId . "','" . $userId . "', '" . $time . "', '" . $param['title'] . "', '" . $param['form_id'] . "', '"
            . $param['payer_name'] . "','". $param['receipt_bank'] . "','" . $param['original_amount'] . "','" . $param['currency'] . "','" . $param['rmb_amount'] . "' , '"
            . $param['payment_term'] . "','" . $param['receipt_date'] . "','" . $param['dept_name'] . "','" . $param['project_number'] . "' , '"
            . $param['status'] . "' , '" . $param['type'] . "','"  . $param['user_bu'] . "')";
        exequery(TD::conn(), $mainSql);
        $id = mysql_insert_id();
        return $id;
    }

    public function addDetail($param)
    {
        for ($i = 1; $i <= $param['addReceiptCount']; $i++) {
            if (!empty($param['order_number' . $i])) {
                $sql = "insert into inhe_sales_receipt_detail (
                    form_id,
                    serial_number,
                    project_code,
                    project_number,
                    order_number,
                    customer_name,
                    contract_amount,
                    currency,
                    rmb_amount,
                    receipt_amount,
                    other_cost,
                    receipted,
                    un_receipt,
                    remark,
                    contract_party,
                    if_third_pay
                    ) values" .
                    "('" . $param['form_id'] . "', '" . $param['serial_number' . $i] . "','" . $param['project_code' . $i] . "','"
                    . $param['project_number' . $i] . "','" . $param['order_number' . $i] . "' , '" . $param['customer_name' . $i] . "','"
                    . $param['contract_amount' . $i] . "','" . $param['currency_detail' . $i] . "','" . $param['rmb_amount' . $i] . "' , '"
                    . $param['receipt_amount' . $i] . "','" . $param['other_cost' . $i] . "','" . $param['receipted' . $i] . "','"
                    . $param['un_receipt' . $i] . "' , '" . $param['remark' . $i] . "','" . $param['contract_party' . $i] . "','"
                    . $param['if_third_pay' . $i] . "')";
                exequery(TD::conn(), $sql);
            }
        }
        return true;
    }

    public function update($param)
    {
        $this->updateMainData($param);
        $this->updateDetailData($param);
        $this->updateAttachData($param);
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
            'payer_name' => $param['payer_name'],
            'receipt_bank' => $param['receipt_bank'],
            'original_amount' => $param['original_amount'],
            'currency' => $param['currency'],
            'rmb_amount' => $param['rmb_amount'],
            'payment_term' => $param['payment_term'],
            'receipt_date' => $param['receipt_date'],
            'business_dept' => $param['dept_name'],
            'project_number' => $param['project_number'],
            'status' => $param['status'],
            'type' => $param['type'],
            'user_bu' => $param['user_bu']
        );
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_sales_receipt';
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
            'project_code' => 'project_code',
            'project_number' => 'project_number',
            'order_number' => 'order_number',
            'customer_name' => 'customer_name',
            'contract_amount' => 'contract_amount',
            'currency' => 'currency_detail',
            'rmb_amount' => 'rmb_amount',
            'receipt_amount' => 'receipt_amount',
            'other_cost' => 'other_cost',
            'receipted' => 'receipted',
            'un_receipt' => 'un_receipt',
            'remark' => 'remark',
            'contract_party' => 'contract_party',
            'if_third_pay' => 'if_third_pay'
        );
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_sales_receipt_detail';
        $count = $param['addReceiptCount'];
        $result = $form->updateDetailData($normalField, $batchField, $param, $condition, $tableName, $count);
        $deleteSql = "delete from inhe_sales_receipt_detail where order_number = '' and form_id = '" . $param['form_id'] . "' ";
        exequery(TD::conn(), $deleteSql);
        return $result;
    }

    public function updateAttachData($param)
    {
        $form = new FormModel();
        $normalField = array(
            'form_id' => 'form_id'
        );
        $batchField = array(
            'serial_number' => 'serial_number_attach',
            'attach_name' => 'attach_name',
            // 'attach_id' => 'attach_id',
            'type' => 'type'
        );
        $condition = array('form_id' => $param['form_id'], 'type' => $param['type1']);
        $tableName = 'inhe_sales_receipt_attach';
        $count = $param['addAttachCount'];
        $result = $form->updateDetailData($normalField, $batchField, $param, $condition, $tableName, $count);
        // 附件数据保更新
        $attachArr = $param['attach'];
        if (is_array($attachArr)) {
            $attachIds = implode(',', $attachArr);
            $attachIds = rtrim($attachIds, ',');
            $attach = new AttachModel();
            $attach->updateAttach($attachIds, $param['form_id']);
        }
        $deleteSql = "delete from inhe_sales_receipt_attach where form_id = '' or serial_number <= 0 ";
        exequery(TD::conn(), $deleteSql);
        return $result;
    }

    public function updateProductData($param)
    {
        $form = new FormModel();
        $normalField = array(
            'form_id' => 'form_id'
        );
        $batchField = array(
            'serial_number' => 'serial_number_product',
            'product_type' => 'p_product_type',
            'order_number' => 'p_order_number',
            'contract_amount' => 'p_contract_amount',
            'receipt_amount' => 'p_receipt_amount',
        );
        $condition = array('form_id' => $param['form_id']);
        $tableName = self::PRODUCT_TABLE;
        $count = $param['addProductCount'];
        $result = $form->updateDetailData($normalField, $batchField, $param, $condition, $tableName, $count);
        $deleteSql = "delete from ".$tableName." where order_number = '' and form_id = '" . $param['form_id'] . "' ";
        exequery(TD::conn(), $deleteSql);
        return $result;
    }

    /**
     * 删除指定词条
     */
    public function delete()
    {
        $param = $this->getParamToArray();
        $result = array();
        $where = " where 1=1 ";
        $response = new ResponseCode();
        if (!array_key_exists('id', $param) || $param['id'] == '') {
            $result = $response->failed(false);
            return $result;
        }
        $where .= " and form_id = '" . $param['id'] . "' ";
        $sql = " delete from " . self::MAIN_TABLE . $where;
        $re = exequery(TD::conn(), $sql);
        $sql = " delete from " . self::DETAIL_TABLE . $where;
        $re = exequery(TD::conn(), $sql);
        $sql = " delete from " . self::ATTACH_TABLE . $where;
        $re = exequery(TD::conn(), $sql);
        $sql = " delete from " . self::PRODUCT_TABLE . $where;
        $re = exequery(TD::conn(), $sql);
        $sql = " delete from inhe_attachment where relatedId = '" . $param['id'] . "' ";

        $sql = " delete from inhe_flow_opinion  where  form_id = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $sql);

        $re = exequery(TD::conn(), $sql);

        $result = $response->success(true);
        return $result;
    }

    public function updateApproveOpinion()
    {
        $param = $this->getParamToArray();
        if ($param['step'] == 2) {
            $this->updateDetailData($param);
            $this->updateAttachData($param);
            $this->updateProductData($param);
        }
        if ($param['step'] == 5) {
            $this->updateAttachData($param);
        }
        $process = new ProcessModel();
        $result = $process->updateProcessOpinion($param);
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
