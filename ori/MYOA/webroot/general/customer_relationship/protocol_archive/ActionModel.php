<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_field.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");
include_once("../common/CommonControl.php");  // 公共类

/**
 * 名词翻译库后台操作类
 * @author ysr
 * 修改记录
 * 20200701-按需求完善后台所有逻辑
 */

/**
 * 表说明：
 * inhe_contract_protocol  数据主表 insert or update
 * inhe_control_library_history 历史记录表 insert
 */

class ActionModel
{
    public $systemId = "contract_protocol";

    /**
     * 检索功能（关键词搜索）
     * 搜索条件：
     */
    public function query($param)
    {
        $strWhere = " where 1=1 ";
        // 客户ID
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
        // 客户简称
        if (array_key_exists("short_name", $param) && $param['short_name'] != "") {
            $strWhere .= " and k.short_name like '%" . trim($param['short_name']) . "%' ";
        }
        // 大洲
        if (array_key_exists("continent", $param) && $param['continent'] != "") {
            $strWhere .= " and k.continent = '" . trim($param['continent']) . "' ";
        }
        // 大洲
        if (array_key_exists("country", $param) && $param['country'] != "") {
            $strWhere .= " and k.country = '" . trim($param['country']) . "' ";
        }

        $orderBy = "";
        $limit = "";
        // 排序方式
        if (array_key_exists("sortField", $param) && $param['sortField'] != "") {
            $orderBy .= " order by " . $param['sortField'];
        }
        if (array_key_exists("sortBy", $param) && $param['sortBy'] != "") {
            $orderBy .= " " . $param['sortBy'];
        }


        $sql = " select k.* from inhe_contract_protocol k " . $strWhere . $orderBy . $limit;
        $res = exequery(TD::conn(), $sql);

        $result = array();
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
        }
        mysql_free_result($res);

        return $result;
    }

    public function queryById($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";

        if (array_key_exists("id", $param) && $param['id'] != "") {
            $strWhere .= " and k.id = '" . $param['id'] . "' ";
        } else {
            return $result;
        }

        $result = $this->queryMain($param);

        return $result;
    }

    public function getAttachList($id, $type)
    {
        // 获取附件数据
        $sql = "select * from inhe_attachment where relatedId = '" . $id . "' and type = '" . $type . "' ";
        $res = exequery(TD::conn(), $sql);
        $result = array();
        while ($item = mysql_fetch_assoc($res)) {
            $ext = strtolower(substr($item['filename'], strrpos($item['filename'], '.') + 1));
            $item['isImage'] = false;
            if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'tif'))) {
                $item['isImage'] = true;
            }
            $item['size'] = number_format($item['size'] / 1024, 1) . 'KB';
            $item['name'] = $item['filename'];
            $result[] = $item;
        }
        $attachObj = json_encode($this->array_iconv($result));

        return $attachObj;
    }

    public function getAttachById($id)
    {
        // 获取附件数据
        $sql = "select * from inhe_attachment where relatedId = '" . $id . "' ";
        $res = exequery(TD::conn(), $sql);
        $result = array();
        while ($item = mysql_fetch_assoc($res)) {
            $ext = strtolower(substr($item['filename'], strrpos($item['filename'], '.') + 1));
            $item['isImage'] = false;
            if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'tif'))) {
                $item['isImage'] = true;
            }
            $item['size'] = number_format($item['size'] / 1024, 1) . 'KB';
            $item['name'] = $item['filename'];
            $result[$item['type']][] = $item;
        }
        $attachObj = json_encode($this->array_iconv($result));

        return $attachObj;
    }

    public function queryMain($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";

        if (array_key_exists("id", $param) && $param['id'] != "") {
            $strWhere .= " and k.id = '" . $param['id'] . "' ";
        } else {
            return $result;
        }

        $sql = " select k.*,u.user_name createUserName, uu.user_name applyUserName, d.dept_name deptName from inhe_contract_protocol k " .
            " left join user u on u.user_id = k.create_user " .
            " left join user uu on uu.user_id = k.user_id " .
            " left join department d on d.dept_id = k.dept_id " . $strWhere;
        $res = exequery(TD::conn(), $sql);

        while ($item = mysql_fetch_assoc($res)) {
            $result = $item;
        }
        mysql_free_result($res);

        return $result;
    }

    public function querySalesContract($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";

        if (array_key_exists("id", $param) && $param['id'] != "") {
            $strWhere .= " and k.id = '" . $param['id'] . "' ";
        } else {
            return $result;
        }

        $sql = " select k.* from inhe_sales_contract k " . $strWhere;
        $res = exequery(TD::conn(), $sql);

        while ($item = mysql_fetch_assoc($res)) {
            $result = $item;
        }
        mysql_free_result($res);

        return $result;
    }

    public function queryAuthorizationLetter($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";

        if (array_key_exists("id", $param) && $param['id'] != "") {
            $strWhere .= " and k.id = '" . $param['id'] . "' ";
        } else {
            return $result;
        }

        $sql = " select k.* from inhe_authorization_letter k " . $strWhere;
        $res = exequery(TD::conn(), $sql);

        while ($item = mysql_fetch_assoc($res)) {
            $result = $item;
        }
        mysql_free_result($res);

        return $result;
    }

    public function queryGuaranteeLetter($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";

        if (array_key_exists("id", $param) && $param['id'] != "") {
            $strWhere .= " and k.id = '" . $param['id'] . "' ";
        } else {
            return $result;
        }

        $sql = " select k.* from inhe_guarantee_letter k " . $strWhere;
        $res = exequery(TD::conn(), $sql);

        while ($item = mysql_fetch_assoc($res)) {
            $result = $item;
        }
        mysql_free_result($res);

        return $result;
    }

    /**
     * 提交申请
     * 保存表单数据 -> 获取下一步审核人员 -> 新增申请人记录，更新审核人数据，发送消息提醒
     * */
    public function submit()
    {
        $flag = false;
        $params = $this->getParamToArray();
        // var_export($params);exit;

        if (array_key_exists('data', $params) && $params['data'] != '') {
            $param = $params['data'];
        } else {
            $param = $params;
        }

        if (array_key_exists('id', $param) && $param['id'] != '') {
            // 更新表单数据
            $flag = $this->update($param);
        } else {
            // 新增表单数据
            $flag = $this->add($param);
        }

        if ($flag) {
            $result = array(
                'code' => '200',
                'status' => 'success'
            );
        }

        return $result;
    }

    // 保存
    public function save()
    {
        $flag = false;
        $param = $this->getParamToArray();
        $param['status'] = 'T';
        $flag = $this->add($param);

        if ($flag) {
            $result = array(
                'code' => '200',
                'status' => 'success'
            );
        } else {
            $result = array(
                'code' => '400',
                'status' => 'failed'
            );
        }

        return $result;
    }

    /**
     * 新增一个客户
     */
    public function add($param)
    {
        // var_export($param);exit;
        if ($param['form_type'] == 'contract_protocol') {
            $id = $this->addContractProtocol($param);
        }
        if ($param['form_type'] == 'sales_contract') {
            $id = $this->addSalesContract($param);
        }
        if ($param['form_type'] == 'authorization_letter') {
            $id = $this->addAuthorizationLetter($param);
        }
        if ($param['form_type'] == 'guarantee_letter') {
            $id = $this->addGuaranteeLetter($param);
        }
        if ($param['form_type'] == 'system_admin') {
            $id = $this->addSystemAdmin($param);
        }

        if ($id <= 0) {
            return false;
        }

        $attachArr = $param['attach'];
        if (is_array($attachArr)) {
            $attachIds = implode(',', $attachArr);
            $attachIds = rtrim($attachIds, ',');
            $this->updateAttach($attachIds, $id);
        }

        return true;
    }

    public function addContractProtocol($param)
    {
        $userId = $_SESSION['LOGIN_USER_ID'];
        $deptId = $_SESSION['LOGIN_DEPT_ID'];
        $time = date('Y-m-d H:i:s');
        // $formType = '业务部合同/协议登记表';
        $formType = '国际业务部合作协议登记表';

        $sql = " select max(serial_number)+1 newNumber from inhe_contract_protocol ";
        $res = exequery(TD::conn(), $sql);
        $item = mysql_fetch_assoc($res);
        $newNumber = $item['newNumber'];

        $mainSql = "insert into inhe_contract_protocol (dept_id,
        create_user,
        create_time,
        title,
        serial_number,
        continent,
        country,
        project_code,
        contract_party,
        protocol_no,
        detail_type,
        type,
        if_exclusive,
        effective_condition,
        effective_date,
        end_date,
        sign_date,
        flow_no,
        apply_dept,
        apply_dept_name,
        user_id,
        user_name,
        if_archive,
        archive_mode,
        attach_name,
        remark,
        manage_dept) values" .
            "('" . $deptId . "', '" . $userId . "', '" . $time . "', '" . $formType . "', '" . $newNumber . "', '"
            . $param['continent'] . "','" . $param['country'] . "', '" . $param['project_code'] . "', '" . $param['contract_party'] . "','"
            . $param['protocol_no'] . "','" . $param['detail_type'] . "', '" . $param['type'] . "', '" . $param['if_exclusive'] . "','"
            . $param['effective_condition'] . "','" . $param['effective_date'] . "', '" . $param['end_date'] . "', '" . $param['sign_date'] . "','"
            . $param['flow_no'] . "', '" . $param['apply_dept'] . "', '" . $param['apply_dept_name'] . "', '" . $param['user_name'] . "','"
            . $param['real_name'] . "','" . $param['if_archive'] . "', '" . $param['archive_mode'] . "', '" . $param['attach_name'] . "','"
            . $param['remark'] . "', '" . $param['manage_dept'] . "')";
        exequery(TD::conn(), $mainSql);
        $id = mysql_insert_id();

        return $id;
    }

    public function addSalesContract($param)
    {
        $userId = $_SESSION['LOGIN_USER_ID'];
        $deptId = $_SESSION['LOGIN_DEPT_ID'];
        $time = date('Y-m-d H:i:s');
        $formType = date('Y年') . '销售合同';

        $sql = " select max(serial_number)+1 newNumber from inhe_sales_contract ";
        $res = exequery(TD::conn(), $sql);
        $item = mysql_fetch_assoc($res);
        $newNumber = $item['newNumber'];

        $mainSql = "insert into inhe_sales_contract (organ_id,
        user_id,
        create_time,
        title,
        serial_number,
        sign_date,
        contract_category,
        project_name,
        project_no,
        contract_no,
        currency,
        amount,
        remark,
        customer_name,
        contract_party,
        business_belong,
        create_user,
        create_user_name,
        dept_id,
        dept_name,
        manage_dept
        ) values" .
            "('" . $deptId . "', '" . $userId . "', '" . $time . "', '" . $formType . "', '" . $newNumber . "', '"
            . $param['sign_date'] . "','" . $param['contract_category'] . "', '" . $param['project_name'] . "', '" . $param['project_no'] . "','"
            . $param['contract_no'] . "','" . $param['currency'] . "', '" . $param['amount'] . "', '" . $param['remark'] . "','"
            . $param['customer_name'] . "','" . $param['contract_party'] . "', '" . $param['business_belong'] . "', '" . $param['create_user'] . "','"
            . $param['create_user_name'] . "', '" . $param['dept_id'] . "', '" . $param['dept_name'] . "', '" . $param['manage_dept'] . "')";
        exequery(TD::conn(), $mainSql);
        $id = mysql_insert_id();

        return $id;
    }

    public function addAuthorizationLetter($param)
    {
        $userId = $_SESSION['LOGIN_USER_ID'];
        $deptId = $_SESSION['LOGIN_DEPT_ID'];
        $time = date('Y-m-d H:i:s');
        $formType = '银河电力保函';

        $mainSql = "insert into inhe_authorization_letter (organ_id,
        user_id,
        create_time,
        title,
        project_code,
        bid_type,
        opening_time,
        status,
        effective_date,
        end_date,
        bank_charge,
        transfer_fee,
        currency,
        guarantee_amount,
        margin_amount,
        occupation_quota,
        opening_bank,
        transfer_bank,
        apply_dept,
        apply_dept_name,
        project_country,
        flow_no,
        commerce_manager,
        business_id,
        business_leader,
        manage_dept
        ) values" .
            "('" . $deptId . "', '" . $userId . "', '" . $time . "', '" . $formType . "', '"
            . $param['project_code'] . "','" . $param['bid_type'] . "', '" . $param['opening_time'] . "','"
            . $param['status'] . "','" . $param['effective_date'] . "', '" . $param['end_date'] . "', '" . $param['bank_charge'] . "','"
            . $param['transfer_fee'] . "','" . $param['currency'] . "', '" . $param['guarantee_amount'] . "', '" . $param['margin_amount'] . "','"
            . $param['occupation_quota'] . "', '" . $param['opening_bank'] . "', '" . $param['transfer_bank'] . "', '" . $param['apply_dept'] . "','"
            . $param['apply_dept_name'] . "','" . $param['project_country'] . "', '" . $param['flow_no'] . "', '" . $param['commerce_manager'] . "','"
            . $param['user_name'] . "', '" . $param['business_leader'] . "', '" . $param['manage_dept'] . "')";
        exequery(TD::conn(), $mainSql);
        $id = mysql_insert_id();

        return $id;
    }

    public function addGuaranteeLetter($param)
    {
        $userId = $_SESSION['LOGIN_USER_ID'];
        $deptId = $_SESSION['LOGIN_DEPT_ID'];
        $time = date('Y-m-d H:i:s');
        $formType = '银河电力授权函登记';

        $sql = " select max(serial_number)+1 newNumber from inhe_contract_protocol ";
        $res = exequery(TD::conn(), $sql);
        $item = mysql_fetch_assoc($res);
        $newNumber = $item['newNumber'];

        $mainSql = "insert into inhe_guarantee_letter (
        organ_id,
        user_id,
        create_time,
        title,
        serial_number,
        project_code,
        country,
        content,
        authorized_person,
        contract_party,
        effective_date,
        end_date,
        sign_date,
        flow_no,
        dept_id,
        dept_name,
        apply_user,
        apply_name,
        if_archive,
        archive_mode,
        manage_dept
        ) values" .
            "('" . $deptId . "', '" . $userId . "', '" . $time . "', '" . $formType . "', '"
            . $newNumber . "','" . $param['project_code'] . "', '" . $param['country'] . "','"
            . $param['content'] . "','" . $param['authorized_person'] . "', '" . $param['contract_party'] . "', '" . $param['effective_date'] . "','"
            . $param['end_date'] . "','" . $param['sign_date'] . "', '" . $param['flow_no'] . "', '" . $param['dept_id'] . "','"
            . $param['dept_name'] . "', '" . $param['user_name'] . "', '" . $param['apply_name'] . "', '" . $param['if_archive'] . "','"
            . $param['archive_mode'] . "', '" . $param['manage_dept'] . "')";
        exequery(TD::conn(), $mainSql);
        $id = mysql_insert_id();

        return $id;
    }

    public function addSystemAdmin($param)
    {
        $mainSql = "insert into inhe_system_admin (
        user_id,
        dept_id,
        user_ids,
        dept_ids,
        user_bu,
        is_admin,
        system_admin,
        type
        ) values" .
            "('" . $param['user_name'] . "','" . $param['dept_id'] . "', '" . $param['user_ids'] . "','"
            . $param['manage_dept'] . "','" . $param['user_bu'] . "', '" . $param['is_admin'] . "', '" . $param['system_admin'] . "','"
            . $param['type'] . "')";
        exequery(TD::conn(), $mainSql);
        $id = mysql_insert_id();

        return $id;
    }

    public function updateAttachLink($id)
    {
        $sql = " select * from inhe_attachment t where relatedId = '" . $id . "'  ";
        $res = exequery(TD::conn(), $sql);
        $attachInfo = mysql_fetch_assoc($res);
        $updateSql = " update inhe_contract_protocol set attach_id ='" . $attachInfo['id'] . "',attach_name='" . $attachInfo['filename'] . "' where id='" . $id . "' ";
        exequery(TD::conn(), $updateSql);
    }

    /**
     * 更新词条数据
     */
    public function update($param)
    {
        $flag = false;

        if ($param['form_type'] == 'contract_protocol') {
            $flag = $this->updateContractProtocol($param);
        }
        if ($param['form_type'] == 'sales_contract') {
            $flag = $this->updateSalesContract($param);
        }
        if ($param['form_type'] == 'authorization_letter') {
            $flag = $this->updateAuthorizationLetter($param);
        }
        if ($param['form_type'] == 'guarantee_letter') {
            $flag = $this->updateGuaranteeLetter($param);
        }

        if (!$flag) {
            return false;
        }

        $attachArr = $param['attach'];
        if (is_array($attachArr)) {
            $attachIds = implode(',', $attachArr);
            $attachIds = rtrim($attachIds, ',');
            $this->updateAttach($attachIds, $param['id']);
        }

        return true;
    }

    public function updateContractProtocol($param)
    {
        $time = date('Y-m-d H:i:s');

        $mainSql = "update inhe_contract_protocol set update_time = '" . $time . "', continent='" . $param['continent'] . "', country='" . $param['country'] . "', "
            . "project_code='" . $param['project_code'] . "', contract_party='" . $param['contract_party'] . "', protocol_no='" . $param['protocol_no'] . "', detail_type='" . $param['detail_type'] . "', "
            . "type='" . $param['type'] . "', if_exclusive='" . $param['if_exclusive'] . "', effective_condition='" . $param['effective_condition'] . "', effective_date='" . $param['effective_date'] . "', "
            . "end_date='" . $param['end_date'] . "', sign_date='" . $param['sign_date'] . "', flow_no='" . $param['flow_no'] . "', apply_dept='" . $param['apply_dept'] . "', "
            . "apply_dept_name='" . $param['apply_dept_name'] . "', user_id='" . $param['user_name'] . "', user_name='" . $param['real_name'] . "', if_archive='" . $param['if_archive'] . "', "
            . "archive_mode='" . $param['archive_mode'] . "', remark='" . $param['remark'] . "', manage_dept='" . $param['manage_dept'] . "' "
            . " where id = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $mainSql);
        if ($res > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function updateSalesContract($param)
    {
        $time = date('Y-m-d H:i:s');

        $mainSql = "update inhe_sales_contract set update_time = '" . $time . "', sign_date='" . $param['sign_date'] . "', contract_category='" . $param['contract_category'] . "', "
            . "project_name='" . $param['project_name'] . "', project_no='" . $param['project_no'] . "', contract_no='" . $param['contract_no'] . "', currency='" . $param['currency'] . "', "
            . "amount='" . $param['amount'] . "', remark='" . $param['remark'] . "', customer_name='" . $param['customer_name'] . "', contract_party='" . $param['contract_party'] . "', "
            . "business_belong='" . $param['business_belong'] . "', create_user='" . $param['create_user'] . "', create_user_name='" . $param['create_user_name'] . "', dept_id='" . $param['dept_id'] . "', "
            . "dept_name='" . $param['dept_name'] . "', manage_dept='" . $param['manage_dept'] . "' "
            . " where id = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $mainSql);
        if ($res > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function updateAuthorizationLetter($param)
    {
        $time = date('Y-m-d H:i:s');

        $mainSql = "update inhe_authorization_letter set update_time = '" . $time . "', project_code='" . $param['project_code'] . "', bid_type='" . $param['bid_type'] . "', "
            . "opening_time='" . $param['opening_time'] . "', status='" . $param['status'] . "', effective_date='" . $param['effective_date'] . "', end_date='" . $param['end_date'] . "', "
            . "bank_charge='" . $param['bank_charge'] . "', transfer_fee='" . $param['transfer_fee'] . "', currency='" . $param['currency'] . "', guarantee_amount='" . $param['guarantee_amount'] . "', "
            . "margin_amount='" . $param['margin_amount'] . "', occupation_quota='" . $param['occupation_quota'] . "', opening_bank='" . $param['opening_bank'] . "', transfer_bank='" . $param['transfer_bank'] . "', "
            . "apply_dept='" . $param['apply_dept'] . "', apply_dept_name='" . $param['apply_dept_name'] . "', project_country='" . $param['project_country'] . "', flow_no='" . $param['flow_no'] . "', "
            . "commerce_manager='" . $param['commerce_manager'] . "', business_id='" . $param['user_name'] . "', business_leader='" . $param['business_leader'] . "', manage_dept='" . $param['manage_dept'] . "' "
            . " where id = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $mainSql);
        if ($res > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function updateGuaranteeLetter($param)
    {
        $time = date('Y-m-d H:i:s');

        $mainSql = "update inhe_guarantee_letter set update_time = '" . $time . "', project_code='" . $param['project_code'] . "', country='" . $param['country'] . "', content='" . $param['content'] . "', "
            . "authorized_person='" . $param['authorized_person'] . "', contract_party='" . $param['contract_party'] . "', effective_date='" . $param['effective_date'] . "', end_date='" . $param['end_date'] . "', "
            . "sign_date='" . $param['sign_date'] . "', flow_no='" . $param['flow_no'] . "', dept_id='" . $param['dept_id'] . "', dept_name='" . $param['dept_name'] . "', "
            . "apply_user='" . $param['user_name'] . "', apply_name='" . $param['apply_name'] . "', if_archive='" . $param['if_archive'] . "', archive_mode='" . $param['archive_mode'] . "', "
            . "manage_dept='" . $param['manage_dept'] . "' "
            . " where id = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $mainSql);
        if ($res > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 删除指定词条
     */
    public function delete()
    {
        $param = $this->getParamToArray();

        $result = array();
        $where = " where 1=1 ";
        if (!array_key_exists('tableName', $param) || $param['tableName'] == '') {
            $result = array(
                'data' => '',
                'status' => 'failed',
                'msg' => '删除失败'
            );
            return $result;
        }
        if (array_key_exists('id', $param) && $param['id'] != '') {
            $where .= " and id = '" . $param['id'] . "' ";
        } else {
            $result = array(
                'data' => '',
                'status' => 'failed',
                'msg' => '删除失败'
            );
            return $result;
        }

        $sql = " delete from " . $param['tableName'] . " " . $where;
        $re = exequery(TD::conn(), $sql);

        $result = array(
            'data' => '',
            'status' => 'success',
            'msg' => '删除成功'
        );

        return $result;
    }

    /**
     * 更新附件关联词条ID
     */
    public function updateAttach($ids, $relateId)
    {
        $sql = "update inhe_attachment set relatedId = '" . $relateId . "' where id in(" . $ids . ")";
        $re = exequery(TD::conn(), $sql);
        return $re;
    }

    /**
     * 多个附件删除
     * 条件： 功能模块、 指定记录
     */
    function batchDeleteAttach($relatedId, $type)
    {
        $sql = "SELECT * FROM inhe_attachment where relatedId='" . $relatedId . "' and type='" . $type . "'";
        $attachs = exequery(TD::conn(), $sql);
        $result = array();
        while ($i = mysql_fetch_assoc($attachs)) {
            $result[] = $i;
        }
        mysql_free_result($attachs);

        foreach ($result as $a) {
            $sql2 = 'DELETE FROM inhe_attachment WHERE id = ' . $a['id'];
            exequery(TD::conn(), $sql2);
            if (file_exists($a['path'])) {
                @unlink($a['path']);
            }
        }
    }

    /**
     * 获取附件列表
     */
    public function attachList($informationId, $type)
    {
        $attachs = array();
        $sql = 'SELECT * FROM inhe_attachment WHERE  type ="' . $type . '" AND relatedId = "' . $informationId . '" ORDER BY id ASC';
        $res = exequery(TD::conn(), $sql);
        while ($a = mysql_fetch_assoc($res)) {
            $a['size'] = number_format($a['size'] / 1024, 2) . 'KB';
            $attachs[] = $a;
        }
        mysql_free_result($res);

        return $attachs;
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

    /**
     * 返回数据结构构造
     * 
     */
    public function returnData()
    {
        $result = array();
        $result = array(
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        );

        return $result;
    }

    /**
     * sms短信发送提醒
     * @param $param 参数数组
     */
    public function sendSms($param)
    {
        $remindUrl = $param['url'];
        $smsContent = $param['content'];
        $approver = $param['approver'];
        // 发送消息提醒
        send_sms("", $_SESSION["LOGIN_USER_ID"], $approver, 0, $smsContent, $remindUrl);
    }
}
