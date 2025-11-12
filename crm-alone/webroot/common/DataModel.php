<?php
require_once("../inc/auth.php");
require_once("../inc/utility_all.php");
require_once("../inc/utility_file.php");
require_once("../inc/utility_field.php");
require_once("ResponseCode.php");

/**
 * 数据增删改查操作类
 * @author ysr
 */
class DataModel
{
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

    /**
     * 获取数据集公共方法
     * @param string dataType 
     */
    public function queryData()
    {
        $param = $this->getParamToArray();
        $response = new ResponseCode();
        $result = array();
        if (!array_key_exists("dataType", $param) || $param['dataType'] == "") {
            $result = $response->failed(false);
            return $result;
        }
        $dataType = $param['dataType'];
        $data = $this->$dataType($param);
        $result = $response->success($data);

        return $result;
    }

    /**
     * 获取大洲数据
     */
    public function getContinentData()
    {
        $sql = " select * from continent_code ";
        $res = exequery(TD::conn(), $sql);
        $result = array();
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
        }

        mysql_free_result($res);

        return $result;
    }

    /**
     * 获取国家代码数据
     */
    public function getCountryData()
    {
        $param = $this->getParamToArray();
        $where = " where 1=1 ";
        if (array_key_exists("continent", $param) && $param['continent'] != "") {
            $where .= " and k.continent = '" . $param['continent'] . "' ";
        }
        if (array_key_exists("zhName", $param) && $param['zhName'] != "") {
            $where .= " and k.zh_name like '%" . $param['zhName'] . "%' ";
        }

        $sql = "select distinct k.* from country_code k " . $where;
        $res = exequery(TD::conn(), $sql);
        $result = array();
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
        }

        mysql_free_result($res);

        return $result;
    }

    /**
     * 常用参数数据获取
     */

    public function getCommonParam($param)
    {
        $strWhere = " where 1=1 ";
        // 主分组
        if (array_key_exists("paras_group", $param) && $param['paras_group'] != "") {
            $strWhere .= " and f.paras_group = '" . $param['paras_group'] . "' ";
        }
        // 类型英文字段名
        if (array_key_exists("type", $param) && $param['type'] != "") {
            $typeStr = "";
            $typeArr = explode(',', $param['type']);
            foreach ($typeArr as $tv) {
                $typeStr .= "'" . trim($tv) . "',";
            }
            $strWhere .= " and f.type in (" . rtrim($typeStr, ',') . ") ";
        }
        // 类型中文描述
        if (array_key_exists("type_desc", $param) && $param['type_desc'] != "") {
            $strWhere .= " and f.type_desc like '%" . $param['type'] . "%' ";
        }
        // 流程步骤
        if (array_key_exists("bz", $param) && $param['bz'] != "") {
            $strWhere .= " and f.bz = '" . $param['bz'] . "' ";
        }
        // 值描述
        if (array_key_exists("paras_desc", $param) && $param['paras_desc'] != "") {
            $strWhere .= " and f.paras_desc = '" . $param['paras_desc'] . "' ";
        }
        // 值选项
        if (array_key_exists("paras_value", $param) && $param['paras_value'] != "") {
            $strWhere .= " and f.paras_value = '" . $param['paras_value'] . "' ";
        }
        // 是否可用
        if (array_key_exists("is_used", $param) && $param['is_used'] != "") {
            $strWhere .= " and f.is_used = '" . $param['is_used'] . "' ";
        }
        // 公司使用归属
        if (array_key_exists("user_bu", $param) && $param['user_bu'] != "") {
            $strWhere .= " and f.user_bu = '" . $param['user_bu'] . "' ";
        }

        $sql = " select f.*
                from inhe_oa_paras f " . $strWhere . " order by sort_code ";
        $result = array();
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result[$item['type']][] = $item;
        }
        mysql_free_result($res);

        return $result;
    }

    public function verifyProjectCode()
    {
        $param = $this->getParamToArray();
        $response = new ResponseCode();
        $result = array();
        $where = " where 1=1 ";
        if (!array_key_exists("projectCode", $param) || $param['projectCode'] == "") {
            $result = $response->failed(false);
            return $result;
        }
        if ($param['user_bu']=='INHEJX'&&(strlen($param['projectCode'])<5 || !preg_match('/^[A-Z]+[-][A-Z]+[-]\d{4}+[A-Z]{1}+[A-Z]?$/u',$param['projectCode']))) {
            $result = $response->success('项目代号只能是:电网-省份-年份序号产品，比如GW-SD-2201D/GW-SD-2201DA');
            return $result;
        }
        if ($param['user_bu']!='INHEJX'&&(strlen($param['projectCode'])!=3 || !preg_match('/^[A-Z]+$/u',$param['projectCode']))) {
            $result = $response->success('项目代号只能是三位大写字母');
            return $result;
        }
        $where .= " and k.project_code = '" . $param['projectCode'] . "' ";

        $sql = "select count(1) count from inhe_project_main k " . $where;
        $res = exequery(TD::conn(), $sql);
        $item = mysql_fetch_assoc($res);
        if ($item['count'] > 0) {
            $result = $response->success('项目代号已存在');
            return $result;
        }
        if (array_key_exists("temp_project_code", $param)&&$param['temp_project_code']!= "") {
            $where = " where 1=1 ";
            $where .= " and k.temp_code = '" . $param['temp_project_code'] . "' ";
            $sql = "select count(1) count from inhe_temp_code k " . $where;
            $res = exequery(TD::conn(), $sql);
            $item2 = mysql_fetch_assoc($res);
            if ($item2['count'] == 0) {
                $result = $response->success('临时项目代号不存在');
                return $result;
            }
        }
        mysql_free_result($res);

        return $result;
    }

    public function existProjectCode()
    {
        $param = $this->getParamToArray();
        $response = new ResponseCode();
        $result = array();
        $where = " where 1=1 ";
        if (!array_key_exists("project_code", $param) || $param['project_code'] == "") {
            $result = $response->failed(false);
            return $result;
        }
        $where .= " and k.project_code = '" . $param['project_code'] . "' ";

        $sql = "select count(1) count from inhe_project_main k " . $where;
        $res = exequery(TD::conn(), $sql);
        $item = mysql_fetch_assoc($res);
        if ($item['count'] > 0) {
            $result = $response->success();
            return $result;
        }else{
            $result = $response->failed(false);
            return $result;
        }
        
        mysql_free_result($res);

        return $result;
    }
    /**
     * 获取所属客户信息数据列表
     * 数据权限范围：登记人，
     * 使用模块：立项代理商、招标方、买方；合同评审合同相对方
     */
    public function getCustomerList()
    {
        $userId = $_SESSION['LOGIN_USER_ID'];
        $where = " where 1=1 and k.status in('R','Y') and k.user_id = '" . $userId . "' and type = 'customer_data' ";
        $orderBy = ' order by k.id desc ';

        $sql = "select k.id, k.customer_name,k.version,k.form_id from inhe_customer_data k " . $where . $orderBy;
        $res = exequery(TD::conn(), $sql);
        $result = array();
        while ($item = mysql_fetch_assoc($res)) {
            if($item['version']>0){
                $changeSql = "select k.id, k.customer_name,k.version from inhe_customer_data k "
                ."where 1=1 and k.type ='customer_data_change' and k.status in('R','Y') and version=".$item['version']." and related_id='".$item['form_id']."'";
                $changeData = exequery(TD::conn(), $changeSql);
                $item = mysql_fetch_assoc($changeData);
            }  
            $result[$item['id']] = $item['customer_name'];
        }
        mysql_free_result($res);

        $sql2 = "select distinct k.id, k.customer_name,k.version,k.form_id from inhe_customer_share a join inhe_customer_data k on a.customer_id=k.id where a.user_ids = '" . $userId . "' ";
        $res2 = exequery(TD::conn(), $sql2);
        while ($item2 = mysql_fetch_assoc($res2)) {
            if($item2['version']>0){
                $changeSql = "select k.id, k.customer_name,k.version from inhe_customer_data k "
                ."where 1=1 and k.type ='customer_data_change' and k.status in('R','Y') and version=".$item2['version']." and related_id='".$item2['form_id']."'";
                $changeData = exequery(TD::conn(), $changeSql);
                $item2 = mysql_fetch_assoc($changeData);
            }  
            $result[$item2['id']] = $item2['customer_name'];
        }
        mysql_free_result($res2);
        return $result;
    }

    /**
     * 变更使用
     * 获取所属客户信息数据列表
     * 数据权限范围：登记人，
     * 使用模块：立项代理商、招标方、买方；合同评审合同相对方
     */
    public function getCustomerList2()
    {
        $userId = $_SESSION['LOGIN_USER_ID'];
        $where = " where 1=1 and k.user_id = '" . $userId . "' and type = 'customer_data' ";
        $orderBy = ' order by k.id desc ';

        $sql = "select k.id, k.customer_name,k.version,k.form_id from inhe_customer_data k " . $where . $orderBy;
        $res = exequery(TD::conn(), $sql);
        $result = array();
        while ($item = mysql_fetch_assoc($res)) {
            $result[$item['id']] = $item['customer_name'];
        }
        mysql_free_result($res);

        $sql2 = "select distinct k.id, k.customer_name,k.version,k.form_id from inhe_customer_share a join inhe_customer_data k on a.customer_id=k.id where a.user_ids = '" . $userId . "' ";
        $res2 = exequery(TD::conn(), $sql2);
        while ($item2 = mysql_fetch_assoc($res2)) {
            $result[$item2['id']] = $item2['customer_name'];
        }
        mysql_free_result($res2);
        return $result;
    }

    /**
     * 获取指定客户详情
     */
    public function getCustomerDetail()
    {
        $param = $this->getParamToArray();
        $where = " where 1=1 ";
        if (array_key_exists("id", $param) && $param['id'] != "") {
            $where .= " and k.id = '" . $param['id'] . "' ";
        }

        // FIX: 多个联系人时取第一条数据
        $sql = "select distinct k.customer_name, k.short_name, cc.zh_name country_desc, c.name contact_name, c.phone_one contact_phone, c.email, k.summarize "
            . " from inhe_customer_data k "
            . " left join inhe_customer_contact c on c.form_id = k.form_id "
            . " left join country_code cc on cc.iso_three = k.country and cc.continent = k.continent "
            . $where . " order by k.id, c.id desc ";
        $res = exequery(TD::conn(), $sql);
        $result = array();
        while ($item = mysql_fetch_assoc($res)) {
            $result = $item;
        }
        mysql_free_result($res);
        return $result;
    }


    /**
     * 获取项目代号数据
     */
    public function getProjectCode($param)
    {
        $where = " where 1=1 and m.if_project != '01' "; // 已立项项目代号除外
        if (array_key_exists("project_code", $param) && $param['project_code'] != "") {
            $where .= " and m.project_code = '" . $param['project_code'] . "' ";
        }
        if (array_key_exists("user_id", $param) && $param['user_id'] != "") {
            $where .= " and m.user_id = '" . $param['user_id'] . "' ";
        }
        $sql = "select distinct m.*,d.dept_name realDeptName, c.company company_desc from inhe_project_code m "
            . " left join department d on d.dept_id = m.dept_id "
            . " left join company c on c.user_bu = m.company "
            . $where;
        $res = exequery(TD::conn(), $sql);
        $result = array();
        while ($item = mysql_fetch_assoc($res)) {
            $item['dept_name'] = $item['realDeptName'] == '' ? $item['dept_name'] : $item['realDeptName'];
            $result[] = $item;
        }
        mysql_free_result($res);
        return $result;
    }

    public function getTempCode($param)
    {
        $where = " where 1=1 "; // 已立项项目代号除外
        if (array_key_exists("temp_code", $param) && $param['temp_code'] != "") {
            $where .= " and m.temp_code = '" . $param['temp_code'] . "' ";
        }
        if (array_key_exists("user_id", $param) && $param['user_id'] != "") {
            $where .= " and m.user_id = '" . $param['user_id'] . "' ";
        }
        $sql = "select distinct m.*,d.dept_name realDeptName, c.company company_desc from inhe_temp_code m "
            . " left join department d on d.dept_id = m.dept_id "
            . " left join company c on c.user_bu = m.company "
            . $where;
        $res = exequery(TD::conn(), $sql);
        $result = array();
        while ($item = mysql_fetch_assoc($res)) {
            $item['dept_name'] = $item['realDeptName'] == '' ? $item['dept_name'] : $item['realDeptName'];
            $result[] = $item;
        }
        mysql_free_result($res);
        return $result;
    }

    /**
     * 获取指定客户详情
     */
    public function getCustomerShare($param)
    {
        $where = " where 1=1 ";
        if (array_key_exists("id", $param) && $param['id'] != "") {
            $where .= " and k.form_id = '" . $param['id'] . "' ";
        }
        $sql = "select distinct k.form_id,k.version,k.user_bu,k.user_id,k.customer_name, k.id,u.user_name create_user "
            . " from inhe_customer_data k "
            . " left join user u on u.user_id = k.user_id "
            . $where;
        $res = exequery(TD::conn(), $sql);
        $result = mysql_fetch_assoc($res);
        $change=$this->getLastChange($result['form_id'],$result['version'],'customer_data_change');
        $sql = "select * from inhe_customer_company where form_id='$change[form_id]'";
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result['contract_party'][] = $item['contract_party'];
        }
        mysql_free_result($res);
        return $result;
    }

    public function queryShareList($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";
        if (!array_key_exists("id", $param) || $param['id'] == "") {
            return $result;
        }
        $strWhere .= " and m.form_id = '" . $param['id'] . "' ";
        $sql = " select distinct u.user_name from inhe_customer_share k " .
            "left join inhe_customer_data m on m.id = k.customer_id " .
            "left join user u on u.user_id = k.user_ids " . $strWhere;
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $userArr[] = $item['user_name'];
        }
        mysql_free_result($res);
        $result = implode(',', $userArr);
        $result = rtrim($result, ',');
        return $result;
    }

    /**
     * 生成随机项目代号
     * @param $len 随机字符长度
     * @param $chars 随机字符范围
     */
    function getRandomString($param)
    {
        $len = $param['charLength'];
        $chars = $param['chars'];
        if (is_null($chars)) {
            // $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        }
        mt_srand(10000000 * (float)microtime());
        for ($i = 0, $str = '', $lc = strlen($chars) - 1; $i < $len; $i++) {
            $str .= $chars[mt_rand(0, $lc)];  // $array[mt_rand(0, count($array) - 1)];
        }
        // 若取号为已使用代号则重新取号 todo 

        return $str;
    }

    function confirmCode($param)
    {
        $userId = $_SESSION['LOGIN_USER_ID'];
        $userName = $_SESSION['LOGIN_USER_NAME'];
        $deptId = $_SESSION['LOGIN_DEPT_ID'];
        $time = date('Y-m-d H:i:s');
        $mainSql = "insert into inhe_project_code (project_code,
        if_project,
        company,
        dept_id,
        user_id,
        user_name,
        create_time
        ) values" .
            "('" . $param['project_code'] . "', '02', '" . $param['company'] . "', '"
            . $deptId . "','" . $userId . "','" . $userName . "', '" . $time . "')";
        exequery(TD::conn(), $mainSql);
        $id = mysql_insert_id();

        return $id;
    }

    /** 
     * isset()检测变量是否已设置并且非 NULL;
     * empty()检查一个变量是否为空;
     * is_null()检测变量是否为 NULL
     * */
    function getFormalProjectCode($param)
    {
        $newCode = '';
        // 获取新正式立项项目代号
        $newCode = $this->getRandomString($param);
        if (empty($newCode)) {
            $newCode = $this->getRandomString($param);
        }
        // 若取号为已使用代号则重新取号 todo 
        // $sql = " select * from inhe_project_main where project_code = '$newCode' ";
        $sql = "select a.project_code,b.project_code from inhe_project_main a,inhe_project_code b
                where a.project_code = '$newCode' or b.project_code = '$newCode' limit 0,1";
        $res = exequery(TD::conn(), $sql);
        if (mysql_fetch_assoc($res)) {
             return $this->getFormalProjectCode($param);
        }
        mysql_free_result($res);
        return $newCode;
    }

    /**
     * 判断用户是否为业务项目经理人员
     */
    public function verifyProjectManger($userId)
    {
        $ifProjectManager = false;
        $where = " where if_manager = '01' ";
        if (empty($userId)) {
            return $ifProjectManager;
        } else {
            $where .= " and user_id = '" . $userId . "'";
        }
        $sql = "select * from inhe_project_manager " . $where;
        $res = exequery(TD::conn(), $sql);
        if (mysql_fetch_assoc($res)) {
            $ifProjectManager = true;
        }
        mysql_free_result($res);
        return $ifProjectManager;
    }

    /**
     * 项目代号命名规则
     * 按公司划分,国内国外区分
     */
    public function tempCodeRule($param)
    {
    }

    public function findCustomerByCode()
    {
        $sql = " select distinct m.project_code, c.customer_name from inhe_project_main m left join inhe_customer_info c on m.form_id = c.form_id where c.customer_name != '' ";
        $res = exequery(TD::conn(), $sql);
        $result = array();
        while ($item = mysql_fetch_assoc($res)) {
            $result[$item['project_code']][] = $item['customer_name'];
        }
        mysql_free_result($res);
        $result = json_encode($this->array_iconv($result));

        return $result;
    }

    /**
     * 获报表栏位数据
     * @param system_id 系统ID
     * @param module_id 功能模块ID
     * @return array 返回栏位数组 
     */
    public function getReportColumn($param)
    {
        $where = " where 1=1 ";
        if (array_key_exists("system_id", $param) && $param['system_id'] != "") {
            $where .= " and m.system_id = '" . $param['system_id'] . "' ";
        }
        if (array_key_exists("module_id", $param) && $param['module_id'] != "") {
            $where .= " and m.module_id = '" . $param['module_id'] . "' ";
        }
        $orderBy = " order by order_no ";
        $sql = " select distinct m.field_name, m.colum_name, m.field_type, m.verify, m.css, m.other_info from inhe_report_column m  " . $where . $orderBy;
        $res = exequery(TD::conn(), $sql);
        $result = array();
        $column = $field = array();
        while ($item = mysql_fetch_assoc($res)) {
            $column[] = $item['colum_name'];
            $field[] = $item;
        }
        mysql_free_result($res);
        $result = array(
            'column' => $column,
            'field' => $field
        );

        return $result;
    }

    /**
     * 获取项目参与人数据
     */
    public function findParticipantData($param)
    {
        $where = " where 1=1 ";
        if (array_key_exists("project_code", $param) && $param['project_code'] != "") {
            $where .= " and m.project_code = '" . $param['project_code'] . "' ";
        }
        $sql = " select distinct m.project_code, m.leader_id, m.leader, m.proportion leader_proportion, " .
            " p.user_id, p.user_name, p.proportion member_proportion from inhe_project_participant m " .
            " left join inhe_project_participant_item p on p.form_id = m.form_id " . $where;
        $res = exequery(TD::conn(), $sql);
        $result = array();
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
        }

        mysql_free_result($res);
        return $result;
    }

    public function findContractParty($param)
    {
        $where = " where 1=1 ";
        if (array_key_exists("customer_name", $param) && $param['customer_name'] != "") {
            $where .= " and m.customer_name = '" . $param['customer_name'] . "' ";
        }else{
            $where .= " and m.user_id = '" . $_SESSION['LOGIN_USER_ID'] . "' ";
        }
        $sql = " select distinct IFNULL(p.contract_party,m.customer_name)contract_party from inhe_customer_data m " .
            " left join inhe_customer_company p on p.form_id = m.form_id " . $where." order by m.id desc";
        $res = exequery(TD::conn(), $sql);
        $result = array();
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item['contract_party'];
        }
        mysql_free_result($res);

        $userId = $_SESSION['LOGIN_USER_ID'];
        $addSql = " select distinct customer_name from inhe_customer_share where user_ids = '$userId' ";
        $addRes = exequery(TD::conn(), $addSql);
        $addResult = array();
        while ($item = mysql_fetch_assoc($addRes)) {
            $addResult[] = $item['customer_name'];
        }
        mysql_free_result($res);
        
        if(!in_array(stripslashes($param['customer_name']),$result)){
            array_push($result, stripslashes($param['customer_name']));
        }
        
        $result = array_merge($result, $addResult);
        $result = array_unique($result);
        $result = array_flip($result);
        $result = array_keys($result);

        return $result;
    }

    public function findProductDetail($param)
    {
        $where = " where 1=1 ";
        if (array_key_exists("form_id", $param) && $param['form_id'] != "") {
            $where .= " and m.form_id = '" . $param['form_id'] . "' ";
        }
        $orderBy = " order by type, serial_number ";
        $sql = " select distinct m.* from inhe_product_detail m " . $where . $orderBy;
        $res = exequery(TD::conn(), $sql);
        $result = array();
        while ($item = mysql_fetch_assoc($res)) {
            $result[$item['type']][] = $item;
        }
        mysql_free_result($res);
        return $result;
    }

    public function findCustomerNameExists($param)
    {
        $where = " where 1=1 ";
        if (array_key_exists("customer_name", $param) && $param['customer_name'] != "") {
            $where .= " and m.customer_name = '" . $param['customer_name'] . "' ";
        }
        $sql = " select distinct m.* from inhe_customer_data m " . $where;
        $res = exequery(TD::conn(), $sql);
        $count = mysql_num_rows($res);
        $result = false;
        if ($count > 0) {
            // 客户名已存在
            $result = true;
        }
        mysql_free_result($res);
        return $result;
    }

    public function getApproverList($param)
    {
        $where = " where 1=1 ";
        $result = array();
        if (array_key_exists("approver", $param) && $param['approver'] != "") {
            if($param['approver']=="director_Y"&&array_key_exists("dept_id", $param) && $param['dept_id'] != ""){
                return $this->getDirectorY($param['dept_id']);
            }
            $userArr = explode(',', $param['approver']);
            $userList = "";
            foreach ($userArr as $userId) {
                if (!empty($userId)) {
                    $userList .= "'" . $userId . "',";
                }
            }
            $userList = rtrim($userList, ',');
            $where .= " and u.user_id in($userList) ";
        } else {
            return $result;
        }
        $sql = "select distinct uid, user_id, user_byid, user_name from user u " . $where;
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
        }
        mysql_free_result($res);
        return $result;
    }
    //getApproverList 根据approver设置获取不同数据
    public function getDirectorY($deptId)
    {
        //获取所有主管总监
        $directorData=$this->getDirectorList();
        //判断部门主管总监是否为主管总监，是则自动选择，不是则根据则手动选择
        $approver=$this->getDirectorInfo($deptId,$directorData);
        if($approver){
            $approver=$approver;
        }else{
            $approver=implode(",",$directorData);
        }
        $where = " where 1=1 ";
        $where .= " and find_in_set(u.user_id,'$approver')";
        $sql = "select distinct uid, user_id, user_byid, user_name from user u " . $where;
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
        }
        mysql_free_result($res);
        return $result;

    }
    

    public function sumReceiptedAmount($param)
    {
        $result = array();
        if (!array_key_exists("orderNumber", $param) || $param['orderNumber'] == "") {
            return $result;
        }
        $where = " where a.form_id=b.form_id and b.status in('P','R','Y') and a.order_number = '" . $param['orderNumber'] . "' ";
        $groupBy = " group by a.order_number ";
        $sql = " select a.order_number, sum(a.receipt_amount) receipted_amount from inhe_sales_receipt_detail a,inhe_sales_receipt b " . $where . $groupBy;
        $res = exequery(TD::conn(), $sql);
        $item = mysql_fetch_assoc($res);
        $result = $item;
        mysql_free_result($res);
        return $result;
    }

    //获取某个人参与的项目
    public function findNumberParticipant($userId){
        $projectNumber=array();
        $where = " where 1=1 and status='Y' ";
        if (!empty($userId)) {
            $where .= " and (m.leader_id = '$userId' or p.user_id = '$userId')";
        }else{
            return $projectNumber;
        }
        $sql = " select distinct project_number from inhe_number_participant m " .
            " left join inhe_number_participant_item p on p.form_id = m.form_id " . $where;
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $projectNumber[]=$item['project_number'];
        }

        mysql_free_result($res);
        return $projectNumber;
    }

    public function findProjectParticipant($userId){
        $project_codes=array();
        $where = " where 1=1 and status='Y' ";
        if (!empty($userId)) {
            $where .= " and (m.leader_id = '$userId' or p.user_id = '$userId')";
        }else{
            return $project_codes;
        }
        $where .= " and (m.user_id = '" . $userId . "' ";
        //增加立项登记人可见项目
        $sql="SELECT
        DISTINCT
            a.form_id,
            a.leader_id,
            a.type,
            b.form_id,
            b.related_id,
            b.leader_id,
            c.form_id,
            c.user_id,
        a.project_code
        FROM
            inhe_project_participant a
        LEFT JOIN inhe_project_participant b ON a.form_id = b.related_id and a.version=b.version
        LEFT JOIN inhe_project_participant_item c ON (
            a.form_id = c.form_id
            OR b.form_id = c.form_id
        )
        AND c.user_id = '$userId'
        WHERE
            (
                (
                    a.leader_id = '$userId'
                    OR c.user_id = '$userId'
                )
                AND b.related_id IS NULL
                AND a.type = 'project_participant'
            )
        OR (
            (
                b.leader_id = '$userId'
                OR c.user_id = '$userId'
            )
            AND b.related_id IS NOT NULL
            AND a.type = 'project_participant'
        )";
        //echo $sql;
        $res = exequery(TD::conn(), $sql);
        //360极速浏览器下载$item=mysql_fetch_assoc($res);
        $project_codes='';
        while ($item = mysql_fetch_assoc($res)) {
            $project_codes .= "'".$item['project_code']."',";
        }
        if($project_codes){
            $project_codes =rtrim($project_codes, ',');
        }
        return $project_codes;
    }

    //获取原记录和所有变更记录
    public function getNewChange($formId,$systemId){
        $sqlStr='';
        $whereStr=' where 1=1';
        switch ($systemId) {
            case 'customer_data':
                $sqlStr="select DISTINCT a.id,a.version,a.form_id,a.related_id,a.change_explain from inhe_customer_data a ";
                break;
            case 'project_review':
                //$sqlStr="select DISTINCT a.id,a.version,a.form_id,a.related_id,b.opinion change_explain from inhe_project_main a left join inhe_flow_opinion b on a.form_id=b.form_id and b.step='1' ";
                $sqlStr="select DISTINCT a.id,a.version,a.form_id,a.related_id,a.remark change_explain from inhe_project_main a ";
                break;
            case 'sales_contract_review':
                $sqlStr="select DISTINCT a.id,a.version,a.form_id,a.related_id,b.change_explain from inhe_contract_review a left join inhe_contract_detail b on a.form_id=b.form_id ";
                break;
            case 'production_order':
                $sqlStr="select a.id,a.version,a.form_id,a.related_id,a.other_explain change_explain from inhe_production_order a ";
                break;
            case 'quotation_apply':
                $sqlStr="select a.id,a.version,a.form_id,a.related_id,a.change_explain from inhe_quotation_apply a ";
                break;
            case 'project_participant':
            case 'project_number_participant':
                break;
        }
        $result=array();
        if(empty($sqlStr)||empty($formId)){
            return $result;
        }
        $where =$whereStr." and a.form_id='".$formId."'";
        $sql = $sqlStr.$where;
        $res = exequery(TD::conn(), $sql);
        $row=mysql_fetch_assoc($res);
        
        if(empty($row['related_id'])){
            //原记录访问
            //原记录且不存在变更记录
            if($row['version']<=0){
                return $result;
            }
            //原记录访问且存在变更记录
            $result[] = $row;
            $relatedId=$formId;
        }else{
            //变更记录访问，先获取原记录信息
            $where =$whereStr." and a.status in ('Y','R') and a.form_id='".$row['related_id']."'";
            $sql = $sqlStr.$where;
            $res = exequery(TD::conn(), $sql);
            $result[] = mysql_fetch_assoc($res);
            $relatedId=$row['related_id'];
        }
        //正常来说，来到这里原记录的version不可能<=0
        if($result[0]['version']<=0){
            return $result;
        }
        //备案或者审核通过('Y','R')
        $where =$whereStr." and a.status in ('Y','R') and a.related_id='".$relatedId."'  order by a.version,a.id";
        $sql = $sqlStr.$where;
        $res = exequery(TD::conn(), $sql);
        
        while($row=mysql_fetch_assoc($res)){
            $result[] = $row;
        }
        return $result;
    }

    //获取原记录和所有变更记录
    public function getLastChange($formId,$version,$systemId){
        $sqlStr='';
        $whereStr=' where 1=1';
        switch ($systemId) {
            case 'customer_data_change':
                $sqlStr="select * from inhe_customer_data a ";
                break;
            case 'project_review_change':
                $sqlStr="select * from inhe_project_main a ";
                break;
            case 'sales_contract_review_change':
                $sqlStr="select * from inhe_contract_review a ";
                break;
            case 'production_order_change'||'trial_production_order_change'||'rd_production_order_change':
                $sqlStr="select * from inhe_production_order a ";
                break;
            case 'project_participant_change':
            case 'project_number_participant_change':
                break;
        }
        $result=array();
        if(empty($sqlStr)||empty($formId)){
            return $result;
        }
        $whereStr.=" and a.type='".$systemId."' ";
        $whereStr.=" and a.version='".$version."' ";
        //备案或者审核通过('Y','R')
        $where =$whereStr." and a.status in ('Y','R') and a.related_id='".$formId."'  order by a.id desc limit 1";
        $sql = $sqlStr.$where;
        $res = exequery(TD::conn(), $sql);
        $result =  mysql_fetch_assoc($res);
        mysql_free_result($res);

        return $result;
    }

    function addCustomerFlow($param)
    {
        $param=$_POST;
        if(count($_FILES) > 1)
        {
            $ATTACHMENTS = upload();
            $ATTACH_ID = $ATTACHMENTS["ID"];
            $ATTACH_NAME = $ATTACHMENTS["NAME"];
        }
        else
        {
            $ATTACH_ID = "";
            $ATTACH_NAME = "";
        }
        $time = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $userId = $_SESSION['LOGIN_USER_ID'];
        $deptId = $_SESSION['LOGIN_DEPT_ID'];
        $userName = $_SESSION['LOGIN_USER_NAME'];
        if(empty($param['id'])){
            $mainSql = "insert into inhe_customer_data_flow (form_id,
            customer_id,
            flow_date,
            remark,
            user_id,
            user_name,
            flow_type,
            contract_id,
            contract_name,
            order_num,
            order_will,
            need,
            purma,
            ATTACH_ID,
            ATTACH_NAME,
            dept_id,
            user_bu,
            create_time
            ) values" .
                "('" . $param['form_id'] . "','" . $param['customer_id'] . "', '" . $param['flow_date'] . "', '" . $param['remark'] . "', '"
                . $userId . "','" . $userName . "','" .$param['flow_type'] . "','" .$param['contract_id'] . "','" .$param['contract_name'] . "','" 
                .$param['order_num'] . "','" .$param['order_will'] . "','" .$param['need'] . "','" .$param['purma'] . "','" 
                .$ATTACH_ID . "','" .$ATTACH_NAME . "','" .$deptId . "','" . $param['user_bu']. "','" .  $time . "')";
            exequery(TD::conn(), $mainSql);
            $id = mysql_insert_id();
        }else{
            $query = "select * from inhe_customer_data_flow k  where k.id='$param[id]'";
            $res = exequery(TD::conn(), $query);
            $flow = mysql_fetch_assoc($res);
            $ATTACH_ID= $flow['ATTACH_ID'].$ATTACH_ID;
            $ATTACH_NAME=$flow['ATTACH_NAME'].$ATTACH_NAME;
            $sql="update inhe_customer_data_flow set ATTACH_ID='$ATTACH_ID',ATTACH_NAME='$ATTACH_NAME' where id='$param[id]'";
            $id = exequery(TD::conn(), $sql);
        }
       

        return $id;
    }

    function addCustomerContact($param)
    {
        $param=$_POST;
        $sql = "insert into inhe_customer_contact (
            customer_id,
            form_id,
            name,
            phone_one,
            phone_two,
            email,
            position,
            address,
            remark) values" .
            "('" . $param['customer_id'] . "', '" . $param['form_id'] . "', '" . $param['name'] . "', '" .
            $param['phone_one'] . "', '" . $param['phone_two'] . "','" .
            $param['email'] . "', '" . $param['position'] . "', '" . $param['address'] . "', '" . $param['remark'] . "')";
            exequery(TD::conn(), $sql);
            $id = mysql_insert_id();
            return $id;
    }

    
     /**
     * 获取银河电力集团总监人员
     */
    public function getDirectorList()
    {
         //获取所有主管总监
        $directorData=array();
        $diretor_sql = " select u.user_id,d.dept_id from department d left join user u on find_in_set(u.user_id,d.manager) where d.is_director='Y' ";
        $res1 = exequery(TD::conn(), $diretor_sql);
        while ($item1 = mysql_fetch_assoc($res1)) {
            $directorData[dept_user][$item1[dept_id]]=$item1[user_id];
            $directorData[user][]=$item1[user_id];
            $directorData[dept][]=$item1[dept_id];
        }

        mysql_free_result($res1);

        return $directorData;
    }
    //递归获取主管总监
    public function getDirectorInfo($deptId,$directorData)
    {
        
        $directorList=$directorData[user];
        $director = "";
        $sql = " select dept_parent,is_director,manager,leader1 from department where dept_id = '" . $deptId . "' ";
        $res = exequery(TD::conn(), $sql);
        $item = mysql_fetch_assoc($res);
        $deptParent = $item['dept_parent'];
        if (empty($deptParent)) {
            return "";
        }
        if (in_array(trim($item['manager'],","),$directorList)) {
            return $item['manager'];
        }
        if (in_array(trim($item['leader1'],","),$directorList)) {
            return $item['leader1'];
        }
        if ($item['is_director']=='Y') {
            $director = $item['manager'];
            return $director;
        } else {
            return $this->getDirectorInfo($deptParent,$directorData);
        }
    }
}
