<?php
require_once("inc/auth.inc.php");
require_once("inc/utility_all.php");
require_once("inc/utility_file.php");
require_once("inc/utility_field.php");
require_once("CommonMethodModel.php");
require_once("ProcessModel.php");
require_once("AttachModel.php");
require_once("ResponseCode.php");

/**
 * 表单操作类
 * @author ysr
 */
class FormModel
{
    const USER_ID = "user_id";  // 制单人ID
    const ORGAN_ID = "organ_id";  // 制单人部门ID
    const WRITE_TIME = "write_time";  // 制单时间
    const UPDATE_TIME = "update_time";  // 数据更新时间
    const HANDLING = 'U'; // 未办理
    const UN_SUBMIT = 'T'; // 待提交
    const UNDER_APPROVE = 'P'; // 审核中
    const AGREE = '01'; // 同意
    const DISAGREE = '02'; // 不同意

    public static $FORM_ACTION_TYPE = array(
        // make find send 
        'update_approver' => 'updateFormFlow', //更新流程步骤
        'customer_share' => 'addCustomerShare', // 客户共享
        'project_code_link' => 'makeProjectCodeLink', // 临时项目代号关联立项代号
        'temp_code' => 'addTempCode', // 新建临时代号
        'change_approve_user' => 'updateFlowOpinionApprover', // 转交办理
        'urgent_notify' => 'urgentNotify', // 催办
        
    );

    /**
     * 检索功能（关键词搜索）
     * @param string id form_id name 搜索条件
     * @param string tableName 查询主表
     * @param string sortField sortBy 排序字段及方式
     * @return array result 返回结果数组
     */
    public function query($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";
        // 主表名称
        if (array_key_exists("tableName", $param) && $param['tableName'] != "") {
            $tableName = $param['tableName'];
        } else {
            return $result;
        }
        // ID
        if (array_key_exists("id", $param) && $param['id'] != "") {
            $strWhere .= " and k.id = '" . $param['id'] . "' ";
        }
        // 表单ID
        if (array_key_exists("form_id", $param) && $param['form_id'] != "") {
            $strWhere .= " and k.form_id = '" . $param['form_id'] . "' ";
        }
        // 客户名
        if (array_key_exists("name", $param) && $param['name'] != "") {
            $strWhere .= " and k.name like '%" . trim($param['name']) . "%' ";
        }
        $limit = "";
        if (array_key_exists("page", $param) && $param['page'] != "" && array_key_exists("page_size", $param) && $param['page_size'] != "") {
            $limit .= " limit  " . ($param['page'] - 1) * $param['page_size'] . "," . $param['page_size'];
        }
        $orderBy = "";
        // 排序方式
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
     * 根据ID查询指定数据
     * @param string id 数据ID
     * @return array main 主表  detail 详情表
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
        // 排序方式
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
     * 提交申请
     * 保存表单数据 -> 获取下一步审核人员 -> 新增申请人记录，更新审核人数据，发送消息提醒
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
                // 表单编号规则。创建新的表单号
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
            // 表单编号规则。创建新的表单号
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
     * 新建申请人流程步骤
     */
    public function makeProcessData($param, $flag)
    {
        $result = array();
        $userId = $_SESSION['LOGIN_USER_ID'];
        $process = new ProcessModel();
        if ($flag) {
            // 获取下一步审核人
            $appParam = array(
                'step' => $param['step'],
                'type' => $param['type'],
                'user_bu' => $param['user_bu']
            );
            $approverArr = $process->getApprover($appParam);
            $approverInfo = $approverArr[0];
            // 新增申请人操作记录
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
        // 若采用指定ID作为附件关联ID，传递对应参数值$relatedId
        if (!empty($relatedId)) {
            $id = $relatedId;
        }
        if (is_array($attach) && count($attach) > 0) {
            $attachIds = implode(',', $attach);
            $attachIds = rtrim($attachIds, ',');
            $attachModel = new AttachModel();
            // 附件关联relatedId一般采用主表ID值，表单多个控件时采用form_id更易区分对应模块
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
        // 若采用指定ID作为附件关联ID，传递对应参数值$relatedId
        if (!empty($relatedId)) {
            $id = $relatedId;
        }
        if (is_array($attach) && count($attach) > 0) {
            $attachIds = implode(',', $attach);
            $attachIds = rtrim($attachIds, ',');
            $attachModel = new AttachModel();
            // 附件关联relatedId一般采用主表ID值，表单多个控件时采用form_id更易区分对应模块
            $attachModel->updateAttach($attachIds, $id);
        }
        $response = new ResponseCode();
        $result = $response->success(true);

        return $result;
    }

    public function updateDetailData($normalField, $batchField, $formData, $condition, $tableName, $count)
    {
        $result = array();
        // 批量内容通过先删除再新增的方式作更新操作，以防误删数据丢失，删除前保存历史数据到临时表
        $where = " where 1=1 ";
        foreach ($condition as $conField => $conVal) {
            $where .= " and $conField = '$conVal' ";
        }
        // 临时表添加历史数据
        $sql = " CREATE TABLE IF NOT EXISTS  " . $tableName . "_temp (LIKE " . $tableName . ")";
        exequery(TD::conn(), $sql);
        $tempSql = " insert into " . $tableName . "_temp select * from " . $tableName . $where;
        exequery(TD::conn(), $tempSql);
        // 删除原数
        $deleteSql = " delete from " . $tableName . $where;
        exequery(TD::conn(), $deleteSql);
        // 新增数据
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
        // 保存历史数据
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
        // 保存历史数据
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
     * 发起变更申请，保存历史数据
     */
    public function changeMainData($paramInsert, $condition, $tableName, $attach, $relatedId)
    {
        $result = array();
        $where = " where 1=1 ";
        foreach ($condition as $conField => $conVal) {
            $where .= " and $conField = '$conVal' ";
        }
        // 保存历史数据
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
        // 保存历史数据
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
        // 保存历史数据
        $sql = " CREATE TABLE IF NOT EXISTS  " . $tableName . "_history (LIKE " . $tableName . ")";
        exequery(TD::conn(), $sql);
        $tempSql = " insert into " . $tableName . "_history select * from " . $tableName . $where;
        exequery(TD::conn(), $tempSql);

        $result = $this->batchDetailByType($normalField, $batchField, $formData, $tableName, $typeArr, $countArr);

        return $result;
    }

    /**
     * create form title format
     * @param array type 表单模块ID；form_id 表单ID; 
     * @return string title 标题，对应旧系统迁移数据
     */
    public function makeFormTitle($param)
    {
        $title = "";
        if (empty($param['type'])) {
            return $title;
        }
        // 参考格式: form_id/表单重要数据/表单类型
        $model = new DataModel();
        $selectParam = array(
            'is_used' => '1',
            'type' => 'crm_process_type'
        );
        $result = $model->getCommonParam($selectParam);
        foreach ($result['crm_process_type'] as $val) {
            if ($param['type'] == $val['paras_value']) {
                if(strpos($val['paras_desc'],'备货生产通知单')===0){
                    if($param[form_type]=='02'){
                        $val['paras_desc']=str_replace('备货生产通知单', '售后服务生产通知单', $val['paras_desc']);
                    }
                    if($param[form_type]=='04'){
                        $val['paras_desc']=str_replace('备货生产通知单', '风险生产通知单', $val['paras_desc']);
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
     * 构造新流程ID规则
     * @param type fieldName 栏位名
     * @param type tableName 表名
     * @param type flowType 流程类别
     * @param type digit 计数位数
     * @param type reset 计数重置规则
     * @param type flag 默认false,为true时命名不含日期
     * @return type $newFlowId 新流程ID
     */
    public function createFlowId($paras, $flag = false)
    {
        $curDate = date("Ymd", time());
        $month = date("Ym");
        $year = date("Y");
        $century = substr($year, 0, 2);
        $where = " where 1=1 ";

        // 计数位数
        if (array_key_exists('digit', $paras) && $paras['digit'] != "") {
            $digit = $paras['digit'];
        } else {
            $digit = 3;  // 默认采用3位计数位，如001
        }

        if (!array_key_exists('fieldName', $paras) || $paras['fieldName'] == "" || !array_key_exists('tableName', $paras) || $paras['tableName'] == "" || !array_key_exists('flowType', $paras) || $paras['flowType'] == "") {
            return $curDate . sprintf("%0" . $digit . "d", 1); // 默认编号规则：20200804001
        }
        $fieldName = $paras['fieldName'];
        $tableName = $paras['tableName'];
        $flowType = $paras['flowType'];
        // 按日期重置计数规则: 按日、月、年或不重置
        if (array_key_exists('reset', $paras) && $paras['reset'] != "") {
            $resetByFields = array('day' => $curDate, 'month' => $month, 'year' => $year);
            if (array_key_exists($paras['reset'], $resetByFields)) {
                $where .=  " and " . $fieldName . " like '" . $flowType . $resetByFields[$paras['reset']] . "%' ";
            }
        } else {
            // 默认1世纪重置一次计数起始
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
     * 获取前台传递的get和post参数值
     */
    public function getParamToArray()
    {
        $postParam = eval(" return " . @iconv("utf-8//IGNORE", "gbk//IGNORE", var_export($_POST, true)) . ";");
        $getParam = eval(" return " . @iconv("utf-8//IGNORE", "gbk//IGNORE", var_export($_GET, true)) . ";");
        return array_merge($postParam, $getParam);
    }

    /**
     * 获取变更历史数据
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
     * 构造新流程ID规则
     * @param type fieldName 栏位名
     * @param type tableName 表名
     * @param type flowType 流程类别
     * @param type digit 计数位数
     * @param type reset 计数重置规则
     * @return type $newFlowId 新流程ID
     */
    public function updateFlowId($paras,$id)
    {
        $curDate = date("Ymd", time());
        // 计数位数
        if (array_key_exists('digit', $paras) && $paras['digit'] != "") {
            $digit = $paras['digit'];
        } else {
            $digit = 3;  // 默认采用3位计数位，如001
        }

        if (!array_key_exists('fieldName', $paras) || $paras['fieldName'] == "" || !array_key_exists('tableName', $paras) || $paras['tableName'] == "" || !array_key_exists('flowType', $paras) || $paras['flowType'] == "") {
            return $curDate . sprintf("%0" . $digit . "d", 1); // 默认编号规则：20200804001
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
