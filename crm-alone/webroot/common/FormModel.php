<?php
require_once("../inc/auth.php");
require_once("../inc/utility_all.php");
require_once("../inc/utility_file.php");
require_once("../inc/utility_field.php");
require_once("CommonMethodModel.php");
require_once("ProcessModel.php");
require_once("AttachModel.php");
require_once("ResponseCode.php");

/**
 * ����������
 * @author ysr
 */
class FormModel
{
    const USER_ID = "user_id";  // �Ƶ���ID
    const ORGAN_ID = "organ_id";  // �Ƶ��˲���ID
    const WRITE_TIME = "write_time";  // �Ƶ�ʱ��
    const UPDATE_TIME = "update_time";  // ���ݸ���ʱ��
    const HANDLING = 'U'; // δ����
    const UN_SUBMIT = 'T'; // ���ύ
    const UNDER_APPROVE = 'P'; // �����?
    const AGREE = '01'; // ͬ��
    const DISAGREE = '02'; // ��ͬ��

    public static $FORM_ACTION_TYPE = array(
        // make find send 
        'update_approver' => 'updateFormFlow', //�������̲���
        'customer_share' => 'addCustomerShare', // �ͻ�����
        'project_code_link' => 'makeProjectCodeLink', // ��ʱ��Ŀ���Ź����������?
        'temp_code' => 'addTempCode', // �½���ʱ����
        'change_approve_user' => 'updateFlowOpinionApprover', // ת������
        'urgent_notify' => 'urgentNotify', // �߰�
        
    );

    /**
     * �������ܣ��ؼ���������
     * @param string id form_id name ��������
     * @param string tableName ��ѯ����
     * @param string sortField sortBy �����ֶμ���ʽ
     * @return array result ���ؽ������?
     */
    public function query($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";
        // ��������
        if (array_key_exists("tableName", $param) && $param['tableName'] != "") {
            $tableName = $param['tableName'];
        } else {
            return $result;
        }
        // ID
        if (array_key_exists("id", $param) && $param['id'] != "") {
            $strWhere .= " and k.id = '" . $param['id'] . "' ";
        }
        // ����ID
        if (array_key_exists("form_id", $param) && $param['form_id'] != "") {
            $strWhere .= " and k.form_id = '" . $param['form_id'] . "' ";
        }
        // �ͻ���
        if (array_key_exists("name", $param) && $param['name'] != "") {
            $strWhere .= " and k.name like '%" . trim($param['name']) . "%' ";
        }
        $limit = "";
        if (array_key_exists("page", $param) && $param['page'] != "" && array_key_exists("page_size", $param) && $param['page_size'] != "") {
            $limit .= " limit  " . ($param['page'] - 1) * $param['page_size'] . "," . $param['page_size'];
        }
        $orderBy = "";
        // ����ʽ
        if (array_key_exists("sortField", $param) && $param['sortField'] != "") {
            $orderBy .= " order by " . $param['sortField'];
        }
        if (array_key_exists("sortBy", $param) && $param['sortBy'] != "") {
            $orderBy .= " " . $param['sortBy'];
        }

        $sql = " select k.* from $tableName k " . $strWhere . $orderBy . $limit;
        $res = exequery(TD::conn(), $sql);

        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
        }
        mysql_free_result($res);

        return $result;
    }

    /**
     * ����ID��ѯָ������
     * @param string id ����ID
     * @return array main ����  detail �����?
     */
    public function queryById($param)
    {
        $result = array();
        if (!array_key_exists("id", $param) || $param['id'] == "") {
            return $result;
        }
        $main = $this->queryMain($param);
        $detail = $this->queryDetail($param);
        $result = array('main' => $main, 'detail' => $detail);
        return $result;
    }

    public function queryMain($param)
    {
        $result = array();
        if (!array_key_exists("id", $param) || $param['id'] == "" || !array_key_exists("mainTable", $param) || $param['mainTable'] == "") {
            return $result;
        }
        $where = " where k.form_id = '" . $param['id'] . "' ";
        $sql = " select k.*,u.user_name createUserName, d.dept_name deptName from " . $param['mainTable'] . " k " .
            " left join user u on u.user_id = k." . self::USER_ID .
            " left join department d on d.dept_id = k." . self::ORGAN_ID . $where;
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
        $orderBy = "";
        $strWhere = " where 1=1 ";
        if (!array_key_exists("id", $param) || $param['id'] == "" || !array_key_exists("detailTable", $param) || $param['detailTable'] == "") {
            return $result;
        }
        $strWhere .= " and k.form_id = '" . $param['id'] . "' ";
        // ����ʽ
        if (array_key_exists("sortField", $param) && $param['sortField'] != "") {
            $orderBy .= " order by " . $param['sortField'];
        }
        if (array_key_exists("sortBy", $param) && $param['sortBy'] != "") {
            $orderBy .= " " . $param['sortBy'];
        }
        $sql = " select * from " . $param['detailTable'] . " k " . $strWhere . $orderBy;
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
        }
        mysql_free_result($res);
        return $result;
    }

    /**
     * �ύ����
     * �����������?-> ��ȡ��һ��������?-> ���������˼�¼��������������ݣ�������Ϣ����?
     * */
    public function submit($param)
    {
        $param['status'] = self::UN_SUBMIT;
        $process = new ProcessModel();
        $response = new ResponseCode();
        $result = array();
        if (array_key_exists($param['form_type'], self::$FORM_ACTION_TYPE)) {
            $formAction = self::$FORM_ACTION_TYPE[$param['form_type']];
            $process->$formAction($param);
            $result = $response->success($param['form_id']);
        } else {
            if (array_key_exists('form_id', $param) && $param['form_id'] != '') {
                $action = 'update';
                if ($param['action'] == 'Change') $action = 'changeUpdate';
                $result = array(
                    'action' => $action,
                    'param' => $param
                );
            } else {
                // ������Ź��򡣴����µı�����?
                $param['form_id'] = $this->createFlowId($param);
                $param['title'] = $this->makeFormTitle($param);
                $action = 'add';
                if ($param['action'] == 'Change') $action = 'changeAdd';
                $result = array(
                    'action' => $action,
                    'param' => $param
                );
            }
        }
        return $result;
    }

    public function save($param)
    {
        $param['status'] = self::UN_SUBMIT;
        $result = array();
        if (array_key_exists('form_id', $param) && $param['form_id'] != '') {
            $action = 'update';
            if ($param['action'] == 'ChangeSave') $action = 'changeUpdate';
            $result = array(
                'action' => $action,
                'param' => $param
            );
        } else {
            // ������Ź��򡣴����µı�����?
            $param['form_id'] = $this->createFlowId($param);
            $param['title'] = $this->makeFormTitle($param);
            $action = 'add';
            if ($param['action'] == 'Change'||$param['action'] == 'ChangeSave') $action = 'changeAdd';
            $result = array(
                'action' => $action,
                'param' => $param
            );
        }
        return $result;
    }

    /**
     * �½����������̲���
     */
    public function makeProcessData($param, $flag)
    {
        $result = array();
        $userId = $_SESSION['LOGIN_USER_ID'];
        $process = new ProcessModel();
        if ($flag) {
            // ��ȡ��һ�������?
            $appParam = array(
                'step' => $param['step'],
                'type' => $param['type'],
                'user_bu' => $param['user_bu']
            );
            $approverArr = $process->getApprover($appParam);
            $approverInfo = $approverArr[0];
            // ���������˲�����¼
            $flowParam = array(
                'type' => $param['type'],
                'form_id' => $param['form_id'],
                'title' => $param['title'],
                'step' => $param['step'],
                'work_flow' => $approverInfo['role'],
                'common_opinion' => $approverInfo['common_opinion'],
                'approve_user' => $userId,
                'status' => self::HANDLING,
                'user_bu' => $param['user_bu']
            );
            $process->updateFlowData($flowParam);
            $response = new ResponseCode();
            $result = $response->success($param['form_id']);
        }
        return $result;
    }

    public function addMainData($paramInsert, $tableName, $attach, $relatedId)
    {
        $result = array();
        $fieldSql = " insert into " . $tableName . "(";
        $valSql = " )values( ";
        foreach ($paramInsert as $field => $val) {
            $fieldSql .= $field . ",";
            $valSql .= "'" . $val . "',";
        }
        $fieldSql = rtrim($fieldSql, ',');
        $valSql = rtrim($valSql, ',');
        $insertSql = $fieldSql . $valSql . ")";
        $res = exequery(TD::conn(), $insertSql);
        $id = mysql_insert_id();
        // ������ָ��ID��Ϊ��������ID�����ݶ�Ӧ����ֵ$relatedId
        if (!empty($relatedId)) {
            $id = $relatedId;
        }
        if (is_array($attach) && count($attach) > 0) {
            $attachIds = implode(',', $attach);
            $attachIds = rtrim($attachIds, ',');
            $attachModel = new AttachModel();
            // ��������relatedIdһ���������IDֵ����������ؼ�ʱ����form_id�������ֶ�Ӧģ��
            $attachModel->updateAttach($attachIds, $id);
        }
        $response = new ResponseCode();
        $result = $response->success(true);

        return $result;
    }

    public function addDetailData($normalField, $batchField, $formData, $tableName, $count)
    {
        $result = array();
        if ($count <= 0) {
            return $result;
        }
        for ($i = 1; $i <= $count; $i++) {
            $sql = "insert into " . $tableName . " (";
            $fieldName = $fieldValue = "";
            foreach ($normalField as $nKey => $nVal) {
                $fieldName .= $nKey . ",";
                $fieldValue  .= "'" . $formData[$nVal] . "',";
            }
            foreach ($batchField as $key => $val) {
                $fieldName .= $key . ",";
                if (empty($val)) {
                    $fieldValue  .= "'" . $i . "',";
                    continue;
                }
                $fieldValue  .= "'" . $formData[$val . $i] . "',";
            }
            $sql .= rtrim($fieldName, ',') . ') values (' . rtrim($fieldValue, ',') . ')';
            exequery(TD::conn(), $sql);
        }
        $response = new ResponseCode();
        $result = $response->success(true);

        return $result;
    }

    public function batchDetailByType($normalField, $batchField, $formData, $tableName, $typeArr, $countArr)
    {
        $result = array();
        if (count($typeArr) <= 0) {
            return $result;
        }
        foreach ($typeArr as $type) {
            for ($i = 1; $i <= $countArr[$type]; $i++) {
                $sql = "insert into " . $tableName . " (";
                $fieldName = $fieldValue = "";
                foreach ($normalField as $nKey => $nVal) {
                    $fieldName .= $nKey . ",";
                    $fieldValue  .= "'" . $formData[$nVal] . "',";
                }
                foreach ($batchField as $key => $val) {
                    $fieldName .= $key . ",";
                    if (empty($val)) {
                        $fieldValue  .= "'" . $type . "',";
                        continue;
                    }
                    if (is_array($val)) {
                        $fieldValue  .= "'" . $formData[$val[0] . '_' . $type] . "',";
                        continue;
                    }
                    $fieldValue  .= "'" . $formData[$val . '_' . $type . $i] . "',";
                }
                $sql .= rtrim($fieldName, ',') . ') values (' . rtrim($fieldValue, ',') . ')';
                
                exequery(TD::conn(), $sql);
            }
        }
        $response = new ResponseCode();
        $result = $response->success(true);

        return $result;
    }

    public function addDetailByType($normalField, $batchField, $formData, $tableName, $typeArr)
    {
        $result = array();
        if (count($typeArr) <= 0) {
            return $result;
        }
        foreach ($typeArr as $type) {
            $sql = "insert into " . $tableName . " (";
            $fieldName = $fieldValue = "";
            foreach ($normalField as $nKey => $nVal) {
                $fieldName .= $nKey . ",";
                $fieldValue  .= "'" . $formData[$nVal] . "',";
            }
            foreach ($batchField as $key => $val) {
                $fieldName .= $key . ",";
                if (empty($val)) {
                    $fieldValue  .= "'" . $type . "',";
                    continue;
                }
                $fieldValue  .= "'" . $formData[$val . '_' . $type] . "',";
            }
            $sql .= rtrim($fieldName, ',') . ') values (' . rtrim($fieldValue, ',') . ')';
            exequery(TD::conn(), $sql);
        }
        $response = new ResponseCode();
        $result = $response->success(true);

        return $result;
    }

    public function updateMainData($paramUpdate, $condition, $tableName, $attach, $relatedId)
    {
        $result = array();
        $sql = " update " . $tableName . " set ";
        $where = " where 1=1 ";
        foreach ($paramUpdate as $field => $val) {
            $sql .= $field . " = '" . $val . "',";
        }
        foreach ($condition as $conField => $conVal) {
            $where .= " and $conField = '$conVal' ";
        }
        $sql = rtrim($sql, ',');
        $res = exequery(TD::conn(), $sql . $where);
        // ������ָ��ID��Ϊ��������ID�����ݶ�Ӧ����ֵ$relatedId
        if (!empty($relatedId)) {
            $id = $relatedId;
        }
        if (is_array($attach) && count($attach) > 0) {
            $attachIds = implode(',', $attach);
            $attachIds = rtrim($attachIds, ',');
            $attachModel = new AttachModel();
            // ��������relatedIdһ���������IDֵ����������ؼ�ʱ����form_id�������ֶ�Ӧģ��
            $attachModel->updateAttach($attachIds, $id);
        }
        $response = new ResponseCode();
        $result = $response->success(true);

        return $result;
    }

    public function updateDetailData($normalField, $batchField, $formData, $condition, $tableName, $count)
    {
        $result = array();
        // ��������ͨ����ɾ���������ķ�ʽ�����²������Է���ɾ���ݶ�ʧ��ɾ��ǰ������ʷ���ݵ���ʱ��
        $where = " where 1=1 ";
        foreach ($condition as $conField => $conVal) {
            $where .= " and $conField = '$conVal' ";
        }
        // ��ʱ��������ʷ����
        $sql = " CREATE TABLE IF NOT EXISTS  " . $tableName . "_temp (LIKE " . $tableName . ")";
        exequery(TD::conn(), $sql);
        $tempSql = " insert into " . $tableName . "_temp select * from " . $tableName . $where;
        exequery(TD::conn(), $tempSql);
        // ɾ��ԭ��
        $deleteSql = " delete from " . $tableName . $where;
        exequery(TD::conn(), $deleteSql);
        // ��������
        $result = $this->addDetailData($normalField, $batchField, $formData, $tableName, $count);

        return $result;
    }

    public function updateBatchDetailByType($normalField, $batchField, $formData, $condition, $tableName, $typeArr, $countArr)
    {
        $result = array();
        $where = " where 1=1 ";
        foreach ($condition as $conField => $conVal) {
            $where .= " and $conField = '$conVal' ";
        }
        // ������ʷ����
        $sql = " CREATE TABLE IF NOT EXISTS  " . $tableName . "_temp (LIKE " . $tableName . ")";
        exequery(TD::conn(), $sql);
        $tempSql = " insert into " . $tableName . "_temp select * from " . $tableName . $where;
        exequery(TD::conn(), $tempSql);
        $deleteSql = " delete from " . $tableName . $where;
        exequery(TD::conn(), $deleteSql);
        $result = $this->batchDetailByType($normalField, $batchField, $formData, $tableName, $typeArr, $countArr);

        return $result;
    }

    public function updateDetailByType($normalField, $batchField, $formData, $condition, $tableName, $typeArr)
    {
        $result = array();
        $where = " where 1=1 ";
        foreach ($condition as $conField => $conVal) {
            $where .= " and $conField = '$conVal' ";
        }
        // ������ʷ����
        $sql = " CREATE TABLE IF NOT EXISTS  " . $tableName . "_temp (LIKE " . $tableName . ")";
        exequery(TD::conn(), $sql);
        $tempSql = " insert into " . $tableName . "_temp select * from " . $tableName . $where;
        exequery(TD::conn(), $tempSql);
        $deleteSql = " delete from " . $tableName . $where;
        exequery(TD::conn(), $deleteSql);
        $result = $this->addDetailByType($normalField, $batchField, $formData, $tableName, $typeArr);

        return $result;
    }

    /**
     * ���������룬������ʷ����
     */
    public function changeMainData($paramInsert, $condition, $tableName, $attach, $relatedId)
    {
        $result = array();
        $where = " where 1=1 ";
        foreach ($condition as $conField => $conVal) {
            $where .= " and $conField = '$conVal' ";
        }
        // ������ʷ����
        $sql = " CREATE TABLE IF NOT EXISTS  " . $tableName . "_history (LIKE " . $tableName . ")";
        exequery(TD::conn(), $sql);
        $tempSql = " insert into " . $tableName . "_history select * from " . $tableName . $where;
        exequery(TD::conn(), $tempSql);

        $result = $this->addMainData($paramInsert, $tableName, $attach, $relatedId);

        return $result;
    }
    public function changeDetailData($normalField, $batchField, $formData, $condition, $tableName, $count)
    {
        $result = array();
        $where = " where 1=1 ";
        foreach ($condition as $conField => $conVal) {
            $where .= " and $conField = '$conVal' ";
        }
        // ������ʷ����
        $sql = " CREATE TABLE IF NOT EXISTS  " . $tableName . "_history (LIKE " . $tableName . ")";
        exequery(TD::conn(), $sql);
        $tempSql = " insert into " . $tableName . "_history select * from " . $tableName . $where;
        exequery(TD::conn(), $tempSql);

        $result = $this->addDetailData($normalField, $batchField, $formData, $tableName, $count);

        return $result;
    }

    public function changeDetailByType($normalField, $batchField, $formData, $condition, $tableName, $typeArr, $countArr)
    {
        $result = array();
        $where = " where 1=1 ";
        foreach ($condition as $conField => $conVal) {
            $where .= " and $conField = '$conVal' ";
        }
        // ������ʷ����
        $sql = " CREATE TABLE IF NOT EXISTS  " . $tableName . "_history (LIKE " . $tableName . ")";
        exequery(TD::conn(), $sql);
        $tempSql = " insert into " . $tableName . "_history select * from " . $tableName . $where;
        exequery(TD::conn(), $tempSql);

        $result = $this->batchDetailByType($normalField, $batchField, $formData, $tableName, $typeArr, $countArr);

        return $result;
    }

    /**
     * create form title format
     * @param array type ����ģ��ID��form_id ����ID; 
     * @return string title ���⣬��Ӧ��ϵͳǨ������
     */
    public function makeFormTitle($param)
    {
        $title = "";
        if (empty($param['type'])) {
            return $title;
        }
        // �ο���ʽ: form_id/������Ҫ����/��������
        $model = new DataModel();
        $selectParam = array(
            'is_used' => '1',
            'type' => 'crm_process_type'
        );
        $result = $model->getCommonParam($selectParam);
        foreach ($result['crm_process_type'] as $val) {
            if ($param['type'] == $val['paras_value']) {
                if(strpos($val['paras_desc'],'��������֪ͨ��')===0){
                    if($param[form_type]=='02'){
                        $val['paras_desc']=str_replace('��������֪ͨ��', '�ۺ��������֪ͨ��?, $val['paras_desc']);
                    }
                    if($param[form_type]=='04'){
                        $val['paras_desc']=str_replace('��������֪ͨ��', '��������֪ͨ��', $val['paras_desc']);
                    }
                }
                $extra_arr=explode(",",$val['extra']);
                $extra_str="/";
                foreach($extra_arr as $extra){
                    if(!empty($extra)){
                        $extra_str.=$param[$extra]."/";
                    }
                }
                //$title = $param['form_id'] . '/' . $param[$val['extra']] . '/' . $val['paras_desc'];
                $title = $param['form_id'] . $extra_str . $val['paras_desc'];
                break;
            }
        }
        return $title;
    }

    /**
     * ����������ID����
     * @param type fieldName ��λ��
     * @param type tableName ����
     * @param type flowType �������?
     * @param type digit ����λ��
     * @param type reset �������ù���
     * @param type flag Ĭ��false,Ϊtrueʱ������������
     * @return type $newFlowId ������ID
     */
    public function createFlowId($paras, $flag = false)
    {
        $curDate = date("Ymd", time());
        $month = date("Ym");
        $year = date("Y");
        $century = substr($year, 0, 2);
        $where = " where 1=1 ";

        // ����λ��
        if (array_key_exists('digit', $paras) && $paras['digit'] != "") {
            $digit = $paras['digit'];
        } else {
            $digit = 3;  // Ĭ�ϲ���3λ����λ����001
        }

        if (!array_key_exists('fieldName', $paras) || $paras['fieldName'] == "" || !array_key_exists('tableName', $paras) || $paras['tableName'] == "" || !array_key_exists('flowType', $paras) || $paras['flowType'] == "") {
            return $curDate . sprintf("%0" . $digit . "d", 1); // Ĭ�ϱ�Ź���?0200804001
        }
        $fieldName = $paras['fieldName'];
        $tableName = $paras['tableName'];
        $flowType = $paras['flowType'];
        // ���������ü�������: ���ա��¡��������?
        if (array_key_exists('reset', $paras) && $paras['reset'] != "") {
            $resetByFields = array('day' => $curDate, 'month' => $month, 'year' => $year);
            if (array_key_exists($paras['reset'], $resetByFields)) {
                $where .=  " and " . $fieldName . " like '" . $flowType . $resetByFields[$paras['reset']] . "%' ";
            }
        } else {
            // Ĭ��1��������һ�μ�����ʼ
            $where .=  " and " . $fieldName . " like '" . $flowType . $century . "%' ";
        }
        if ($flag) {
            $where =  " where " . $fieldName . " like '" . $flowType . "%' ";
            $curDate = "";
        }

        if($tableName=="inhe_temp_code"){
            $where.=" and id>1117";
        }

        $sql = " select max(" . $fieldName . ") flowId from " . $tableName . $where;
        $res = exequery(TD::conn(), $sql);
        if ($row = mysql_fetch_assoc($res)) {
            $flowId = $row["flowId"];
            $num = substr($flowId, -$digit) + 1;
        } else {
            $num = 1;
        }
        $numStr = sprintf("%0" . $digit . "d", $num);
        $newFlowId = $flowType . $curDate . $numStr;

        return $newFlowId;
    }

    /**
     * ��ȡǰ̨���ݵ�get��post����ֵ
     */
    public function getParamToArray()
    {
        $postParam = eval(" return " . @iconv("utf-8//IGNORE", "gbk//IGNORE", var_export($_POST, true)) . ";");
        $getParam = eval(" return " . @iconv("utf-8//IGNORE", "gbk//IGNORE", var_export($_GET, true)) . ";");
        return array_merge($postParam, $getParam);
    }

    /**
     * ��ȡ�����ʷ����?
     * find make send start 
     */
    public function findChangeHistory($param)
    {
        $mainTable = $param['main_table'];
        $result = array();
        if (!array_key_exists('form_id', $param) || $param['form_id'] == "") {
            return $result;
        }
        $where = " where m.form_id = '" . $param['form_id'] . "' ";
        $sql = " select * from " . $mainTable . " m " . $where;
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
        }
        mysql_free_result($res);
        return $result;
    }

    public function verifyNotExist($tableName,$condition,$neqCondition){
        $result = array();
        $where = " where 1=1 ";
        foreach ($condition as $conField => $conVal) {
            $where .= " and $conField = '$conVal' ";
        }
        foreach ($neqCondition as $conField => $conVal) {
            $where .= " and $conField != '$conVal' ";
        }
        $tempSql = " select * from " . $tableName . $where;
        $res=exequery(TD::conn(), $tempSql);
        if(mysql_num_rows($res)<=0){
            return true;
        }else{
            return false;
        }
    }

     /**
     * ����������ID����
     * @param type fieldName ��λ��
     * @param type tableName ����
     * @param type flowType �������?
     * @param type digit ����λ��
     * @param type reset �������ù���
     * @return type $newFlowId ������ID
     */
    public function updateFlowId($paras,$id)
    {
        $curDate = date("Ymd", time());
        // ����λ��
        if (array_key_exists('digit', $paras) && $paras['digit'] != "") {
            $digit = $paras['digit'];
        } else {
            $digit = 3;  // Ĭ�ϲ���3λ����λ����001
        }

        if (!array_key_exists('fieldName', $paras) || $paras['fieldName'] == "" || !array_key_exists('tableName', $paras) || $paras['tableName'] == "" || !array_key_exists('flowType', $paras) || $paras['flowType'] == "") {
            return $curDate . sprintf("%0" . $digit . "d", 1); // Ĭ�ϱ�Ź���?0200804001
        }
        $fieldName = $paras['fieldName'];
        $tableName = $paras['tableName'];
        $flowType = $paras['flowType'];
        
        $newFlowId=$this->createFlowIdById($id,$digit,$curDate,$flowType);
        $sql = " select * from " . $tableName ."  where  id=$id";
        $res = exequery(TD::conn(), $sql);
        $updateField='';
        while($row = mysql_fetch_assoc($res)){
            $updateField=$row[$fieldName];
        }
        $res = exequery(TD::conn(), $sql);
        if (empty($updateField)) {
            $sql = " update " . $tableName ." set $fieldName='$newFlowId' where id=$id";
            $res = exequery(TD::conn(), $sql);
            if(mysql_affected_rows()>0){
                return $newFlowId;
            }else{
                return 0;
            }
        } else {
            return $updateField;
        }
       
    }

    public function createFlowIdById($id,$digit=3,$curDate='',$flowType=''){
        $numStr=str_pad($id%str_pad(1,$digit+1,0,STR_PAD_RIGHT),$digit,'0',STR_PAD_LEFT);
        $newFlowId = $flowType . $curDate . $numStr;
        return $newFlowId;
    }
}
