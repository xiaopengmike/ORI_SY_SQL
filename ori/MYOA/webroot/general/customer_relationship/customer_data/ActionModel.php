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

/**
 * 表说明：
 * inhe_customer_data  数据主表
 * inhe_customer_data_history 历史记录表 
 */
class ActionModel
{
    const SYSTEM_ID = "customer_data";

    public function queryById($param)
    {
        $result = array();
        $main = $this->queryMain($param);
        $param['customer_id'] = $param[customer_id]?$param[customer_id]:$main['id'];  // 取客户ID号，可直接取form_id关联
        $contacts = $this->queryContacts($param);
        $company = $this->queryCompany($param);
        $share = $this->queryShare($param);
        $param['related_id'] = $main[related_id];
        $flow = $this->queryFlow($param);
        $result = array(
            'main' => $main,
            'contacts' => $contacts,
            'company' => $company,
            'share' => $share,
            'flow' => $flow,
        );
        return $result;
    }

    public function queryMain($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";
        if (!array_key_exists("id", $param) || $param['id'] == "") {
            return $result;
        }
        $strWhere .= " and k.form_id = '" . $param['id'] . "' ";
        $sql = " select k.*, u.user_name createUserName, d.dept_name deptName, tc.zh_name continentName,yc.zh_name countryName, tc.en_name continentEnName,yc.country countryEnName from inhe_customer_data k " .
            " left join user u on u.user_id = k.create_user_id " .
            " left join department d on d.dept_id = k.create_dept_id " .
            " left join continent_code tc on tc.iso_two = k.continent " .
            " left join country_code yc on yc.iso_three = k.country and yc.continent=k.continent " .
            $strWhere;
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result = $item;
        }
        mysql_free_result($res);
        return $result;
    }

    public function queryContacts($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";
        if (!array_key_exists("id", $param) || $param['id'] == "") {
            return $result;
        }
        $strWhere .= " and k.form_id = '" . $param['id'] . "' ";
        $sql = " select k.* from inhe_customer_contact k " . $strWhere;
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
        }
        mysql_free_result($res);
        return $result;
    }

    public function queryFlow($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";
        if (!array_key_exists("id", $param) || $param['id'] == "") {
            return $result;
        }
        if ($param['related_id'] != "") {
            $param['id']=$param['related_id'];
        }
        $strWhere .= " and k.form_id = '" . $param['id'] . "' ";
        $sql = " select k.* from inhe_customer_data_flow k " . $strWhere;
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
        }
        mysql_free_result($res);
        return $result;
    }

    public function queryCompany($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";
        if (!array_key_exists("id", $param) || $param['id'] == "") {
            return $result;
        }
        $strWhere .= " and k.form_id = '" . $param['id'] . "' ";
        $sql = " select k.* from inhe_customer_company k " . $strWhere;
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
        }
        mysql_free_result($res);
        return $result;
    }

    public function queryShare($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";
        if (!array_key_exists("customer_id", $param) || $param['customer_id'] == "") {
            return $result;
        }
        $strWhere .= " and k.customer_id = '" . $param['customer_id'] . "' ";
        $sql = " select u.user_name from inhe_customer_share k left join user u on u.user_id = k.user_ids " . $strWhere;
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $userArr[] = $item['user_name'];
        }
        mysql_free_result($res);
        $result = implode(',', $userArr);
        $result = rtrim($result, ',');
        return $result;
    }

    // 提交
    public function submit()
    {
        
        $flag = false;
        $param = $this->getParamToArray();
        $form = new FormModel();
        $result = $form->submit($param);
        include("lang.php");
        //判断是否耐吉
        $is_nergy=($param['user_bu']=="INHENERGY"||$param['user_bu']=="HKNERGY")?"INHENERGY":$param['user_bu'];
        if($is_nergy=="INHENERGY"&&empty($param['form_type'])){
            if($param['addContactCount']==0){
                $result = array(
                    'code' => '0',
                    'status' => 'failed',
                    'msg' => $LG_CUSTOMER_DATA['联系人不能为空！'][$_COOKIE['LANG']],
                    'data' => '联系人不能为空！'
                );
                return $result;
            }
            $contacts_name_flag=true;
            $contacts_phone_flag=true;
            for ($i = 1; $i <= $param['addContactCount']; $i++) {
                if(!empty($param['contacts_name'.$i])){
                    $contacts_name_flag=false;
                    if(!empty($param['phone_one'.$i])||!empty($param['phone_two'.$i])||!empty($param['email'.$i])){
                        $contacts_phone_flag=false;
                    }
                }
                
            }
            $contacts_name_flag=true;
            $contacts_phone_flag=true;
            for ($i = 1; $i <= $param['addContactCount']; $i++) {
                if(!empty($param['contacts_name'.$i])){
                    $contacts_name_flag=false;
                    if(!empty($param['phone_one'.$i])||!empty($param['phone_two'.$i])||!empty($param['email'.$i])){
                        $contacts_phone_flag=false;
                    }
                }
                
            }
            if($contacts_name_flag){
                $result = array(
                    'code' => '0',
                    'status' => 'failed',
                    'msg' => $LG_CUSTOMER_DATA['联系人姓名不能为空！'][$_COOKIE['LANG']],
                    'data' => '联系人姓名不能为空！'
                );
                return $result;
            }
            if($contacts_phone_flag){
                $result = array(
                    'code' => '0',
                    'status' => 'failed',
                    'msg' => $LG_CUSTOMER_DATA['电话或者邮箱不能为空！'][$_COOKIE['LANG']],
                    'data' => '电话或者邮箱不能为空！'
                );
                return $result;
            }
        }
        if (array_key_exists('action', $result)) {
            if(!empty($result['param']["customer_name"])&&!$this->verifyNotExist($result['param'],'customer_name')){
                $result = array(
                    'code' => '0',
                    'status' => 'failed',
                    'msg' => $LG_CUSTOMER_DATA['客户已存在'][$_COOKIE['LANG']],
                    'data' => '客户名称已经存在，请重新填写'
                );
                return $result;
            }
            if(!empty($result['param']["short_name"])&&!$this->verifyNotExist($result['param'],'short_name')){
                $result = array(
                    'code' => '0',
                    'status' => 'failed',
                    'msg' => $LG_CUSTOMER_DATA['简称已存在'][$_COOKIE['LANG']],
                    'data' => '简称已经存在，请重新填写'
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
        include("lang.php");
        //判断是否耐吉
        $is_nergy=($param['user_bu']=="INHENERGY"||$param['user_bu']=="HKNERGY")?"INHENERGY":$param['user_bu'];
        if($is_nergy=="INHENERGY"&&empty($param['form_type'])){
            if($param['addContactCount']==0){
                $result = array(
                    'code' => '0',
                    'status' => 'failed',
                    'msg' => $LG_CUSTOMER_DATA['联系人不能为空！'][$_COOKIE['LANG']],
                    'data' => '联系人不能为空！'
                );
                return $result;
            }
            $contacts_name_flag=true;
            $contacts_phone_flag=true;
            for ($i = 1; $i <= $param['addContactCount']; $i++) {
                if(!empty($param['contacts_name'.$i])){
                    $contacts_name_flag=false;
                    if(!empty($param['phone_one'.$i])||!empty($param['phone_two'.$i])||!empty($param['email'.$i])){
                        $contacts_phone_flag=false;
                    }
                }
                
            }
            if($contacts_name_flag){
                $result = array(
                    'code' => '0',
                    'status' => 'failed',
                    'msg' => $LG_CUSTOMER_DATA['联系人姓名不能为空！'][$_COOKIE['LANG']],
                    'data' => '联系人姓名不能为空！'
                );
                return $result;
            }
            if($contacts_phone_flag){
                $result = array(
                    'code' => '0',
                    'status' => 'failed',
                    'msg' => $LG_CUSTOMER_DATA['电话或者邮箱不能为空！'][$_COOKIE['LANG']],
                    'data' => '电话或者邮箱不能为空！'
                );
                return $result;
            }
            
        }
        if (array_key_exists('action', $result)) {
            if(!empty($result['param']["customer_name"])&&!$this->verifyNotExist($result['param'],'customer_name')){
                $result = array(
                    'code' => '0',
                    'status' => 'failed',
                    'msg' => $LG_CUSTOMER_DATA['客户已存在'][$_COOKIE['LANG']],
                    'data' => '客户名称已经存在，请重新填写'
                );
                return $result;
            }
            if(!empty($result['param']["short_name"])&&!$this->verifyNotExist($result['param'],'short_name')){
                $result = array(
                    'code' => '0',
                    'status' => 'failed',
                    'msg' => $LG_CUSTOMER_DATA['简称已存在'][$_COOKIE['LANG']],
                    'data' => '简称已经存在，请重新填写'
                );
                return $result;
            }
            $flag = $this->$result['action']($result['param']);
            $result = $form->makeProcessData($result['param'], $flag);
        }
        return $result;
    }

    public function add($param)
    {
        $customerId = $this->addMain($param);
        if ($customerId <= 0) {
            return false;
        }
        $param['customer_id'] = $customerId;
        $this->addContacts($param);
        $this->addCompany($param);
        return true;
    }

    public function addMain($param)
    {
        $userId = $_SESSION['LOGIN_USER_ID'];
        $userName = $_SESSION['LOGIN_USER_NAME'];
        $deptId = $_SESSION['LOGIN_DEPT_ID'];
        $time = date('Y-m-d H:i:s');
        $mainSql = "insert into inhe_customer_data (
            organ_id,
            user_id,
            create_dept_id,
            create_user_id,
            write_time,
            title,
            form_id,
            customer_name,
            short_name,
            continent,
            country,
            website,
            address,
            summarize,
            user_name,
            dept_name,
            status,
            type, 
            customer_type,
            customer_source,
            is_vaild,
            change_explain,
            focus,
            user_bu) values" .
            "('" . $deptId . "', '" . $userId . "', '" .$deptId . "', '" . $userId . "', '" . $time . "',  '" . $param['title'] . "', '" .
            $param['form_id'] . "', '" . $param['customer_name'] . "', '" . $param['short_name'] . "', '" . $param['continent'] . "','" .
            $param['country'] . "', '" . $param['website'] . "', '" . $param['address'] . "', '" . $param['summarize'] . "', '" .
            $userName . "', '" . $param['dept_belong'] . "', '" . $param['status'] . "', '" . $param['type'] . "', '" .
             $param['customer_type'] . "', '" .$param['customer_source'] . "', '" .$param['is_vaild'] . "', '" .  $param['change_explain'] . "', '" .
             $param['focus'] . "', '" . $param['user_bu'] . "')";
        exequery(TD::conn(), $mainSql);
        $id = mysql_insert_id();
        return $id;
    }

    public function addContacts($param)
    {
        if ($param['addContactCount'] <= 0) {
            return true;
        }
        for ($i = 1; $i <= $param['addContactCount']; $i++) {
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
                "('" . $param['customer_id'] . "', '" . $param['form_id'] . "', '" . $param['contacts_name' . $i] . "', '" .
                $param['phone_one' . $i] . "', '" . $param['phone_two' . $i] . "','" .
                $param['email' . $i] . "', '" . $param['position' . $i] . "', '" . $param['contacts_address' . $i] . "', '" . $param['remark' . $i] . "')";
            if (!empty($param['contacts_name' . $i])) {
                exequery(TD::conn(), $sql);
            }
        }
        return true;
    }

    public function addCompany($param)
    {
        if ($param['addCompanyCount'] <= 0) {
            return true;
        }
        for ($i = 1; $i <= $param['addCompanyCount']; $i++) {
            $sql = "insert into inhe_customer_company (
                customer_id,
                form_id,
                contract_party,
                short_name,
                country,
                address,
                contact_name,
                phone) values" .
                "('" . $param['customer_id'] . "', '" . $param['form_id'] . "', '" . $param['company_name' . $i] . "', '" .
                $param['company_short_name' . $i] . "', '" . $param['company_country' . $i] . "','" .
                $param['company_address' . $i] . "', '" . $param['contact_name' . $i] . "', '" . $param['phone' . $i] . "')";
            if (!empty($param['company_name' . $i])) {
                exequery(TD::conn(), $sql);
            }
        }
        return true;
    }

    public function update($param)
    {
        $this->updateMainData($param);
        $this->updateContactData($param);
        $this->updateCompanyData($param);
        return true;
    }

    public function updateMainData($param)
    {
        $form = new FormModel();
        $paramInsert = array(
            'update_time' => date('Y-m-d H:i:s'),
            'title' => $param['title'],
            'customer_name' => $param['customer_name'],
            'short_name' => $param['short_name'],
            'continent' => $param['continent'],
            'country' => $param['country'],
            'website' => $param['website'],
            'address' => $param['address'],
            'summarize' => $param['summarize'],
            'user_name' => $param['user_name'],
            'dept_name' => $param['dept_name'],
            'customer_type' => $param['customer_type'],
            'customer_source' => $param['customer_source'],
            'focus' => $param['focus'],
            'is_vaild' => $param['is_vaild'],
            'change_explain' => $param['change_explain'],
          //  'project_star' => $param['project_star'],
            'status' => $param['status']
        );
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_customer_data';
        $result = $form->updateMainData($paramInsert, $condition, $tableName, array(), '');
        return $result;
    }

    public function updateContactData($param)
    {
        $form = new FormModel();
        $normalField = array(
            'form_id' => 'form_id'
        );
        $batchField = array(
            'name' => 'contacts_name',
            'phone_one' => 'phone_one',
            'phone_two' => 'phone_two',
            'email' => 'email',
            'position' => 'position',
            'address' => 'contacts_address',
            'remark' => 'remark'
        );
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_customer_contact';
        $count = $param['addContactCount'];
        $result = $form->updateDetailData($normalField, $batchField, $param, $condition, $tableName, $count);
        $sql = "delete from inhe_customer_contact where name = '' and form_id = '" . $param['form_id'] . "' ";
        exequery(TD::conn(), $sql);
        return $result;
    }

    public function updateCompanyData($param)
    {
        $form = new FormModel();
        $normalField = array(
            'form_id' => 'form_id'
        );
        $batchField = array(
            'contract_party' => 'company_name',
            'short_name' => 'company_short_name',
            'country' => 'company_country',
            'address' => 'company_address',
            'contact_name' => 'contact_name',
            'phone' => 'phone'
        );
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_customer_company';
        $count = $param['addCompanyCount'];
        $result = $form->updateDetailData($normalField, $batchField, $param, $condition, $tableName, $count);
        $sql = "delete from inhe_customer_company where contract_party = '' and form_id = '" . $param['form_id'] . "' ";
        exequery(TD::conn(), $sql);
        return $result;
    }

    public function change()
    {
        $flag = false;
        $param = $this->getParamToArray();
        $form = new FormModel();
        $result = $form->submit($param);
        include("lang.php");
        //判断是否耐吉
        $is_nergy=($param['user_bu']=="INHENERGY"||$param['user_bu']=="HKNERGY")?"INHENERGY":$param['user_bu'];
        if($is_nergy=="INHENERGY"&&empty($param['form_type'])){
            if($param['addContactCount']==0){
                $result = array(
                    'code' => '0',
                    'status' => 'failed',
                    'msg' => $LG_CUSTOMER_DATA['联系人不能为空！'][$_COOKIE['LANG']],
                    'data' => '联系人不能为空！'
                );
                return $result;
            }
            $contacts_name_flag=true;
            $contacts_phone_flag=true;
            for ($i = 1; $i <= $param['addContactCount']; $i++) {
                if(!empty($param['contacts_name'.$i])){
                    $contacts_name_flag=false;
                    if(!empty($param['phone_one'.$i])||!empty($param['phone_two'.$i])||!empty($param['email'.$i])){
                        $contacts_phone_flag=false;
                    }
                }
                
            }
            if($contacts_name_flag){
                $result = array(
                    'code' => '0',
                    'status' => 'failed',
                    'msg' => $LG_CUSTOMER_DATA['联系人姓名不能为空！'][$_COOKIE['LANG']],
                    'data' => '联系人姓名不能为空！'
                );
                return $result;
            }
            if($contacts_phone_flag){
                $result = array(
                    'code' => '0',
                    'status' => 'failed',
                    'msg' => $LG_CUSTOMER_DATA['电话或者邮箱不能为空！'][$_COOKIE['LANG']],
                    'data' => '电话或者邮箱不能为空！'
                );
                return $result;
            }
        }
        if (array_key_exists('action', $result)) {
            // if(!empty($result['param']["customer_name"])&&!$this->verifyNotExist($result['param'],'customer_name')){
            //     $result = array(
            //         'code' => '0',
            //         'status' => 'failed',
            //         'msg' => $LG_CUSTOMER_DATA['客户已存在'][$_COOKIE['LANG']],
            //         'data' => '客户名称已经存在，请重新填写'
            //     );
            //     return $result;
            // }
            // if(!empty($result['param']["short_name"])&&!$this->verifyNotExist($result['param'],'short_name')){
            //     $result = array(
            //         'code' => '0',
            //         'status' => 'failed',
            //         'msg' => $LG_CUSTOMER_DATA['简称已存在'][$_COOKIE['LANG']],
            //         'data' => '简称已经存在，请重新填写'
            //     );
            //     return $result;
            // }
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
        include("lang.php");
        //判断是否耐吉
        $is_nergy=($param['user_bu']=="INHENERGY"||$param['user_bu']=="HKNERGY")?"INHENERGY":$param['user_bu'];
        if($is_nergy=="INHENERGY"&&empty($param['form_type'])){
            if($param['addContactCount']==0){
                $result = array(
                    'code' => '0',
                    'status' => 'failed',
                    'msg' => $LG_CUSTOMER_DATA['联系人不能为空！'][$_COOKIE['LANG']],
                    'data' => '联系人不能为空！'
                );
                return $result;
            }
            $contacts_name_flag=true;
            $contacts_phone_flag=true;
            for ($i = 1; $i <= $param['addContactCount']; $i++) {
                if(!empty($param['contacts_name'.$i])){
                    $contacts_name_flag=false;
                    if(!empty($param['phone_one'.$i])||!empty($param['phone_two'.$i])||!empty($param['email'.$i])){
                        $contacts_phone_flag=false;
                    }
                }
                
            }
            if($contacts_name_flag){
                $result = array(
                    'code' => '0',
                    'status' => 'failed',
                    'msg' => $LG_CUSTOMER_DATA['联系人姓名不能为空！'][$_COOKIE['LANG']],
                    'data' => '联系人姓名不能为空！'
                );
                return $result;
            }
            if($contacts_phone_flag){
                $result = array(
                    'code' => '0',
                    'status' => 'failed',
                    'msg' => $LG_CUSTOMER_DATA['电话或者邮箱不能为空！'][$_COOKIE['LANG']],
                    'data' => '电话或者邮箱不能为空！'
                );
                return $result;
            }
        }
        if (array_key_exists('action', $result)) {
            // if(!empty($result['param']["customer_name"])&&!$this->verifyNotExist($result['param'],'customer_name')){
            //     $result = array(
            //         'code' => '0',
            //         'status' => 'failed',
            //         'msg' => $LG_CUSTOMER_DATA['客户已存在'][$_COOKIE['LANG']],
            //         'data' => '客户名称已经存在，请重新填写'
            //     );
            //     return $result;
            // }
            // if(!empty($result['param']["short_name"])&&!$this->verifyNotExist($result['param'],'short_name')){
            //     $result = array(
            //         'code' => '0',
            //         'status' => 'failed',
            //         'msg' => $LG_CUSTOMER_DATA['简称已存在'][$_COOKIE['LANG']],
            //         'data' => '简称已经存在，请重新填写'
            //     );
            //     return $result;
            // }
            $flag = $this->$result['action']($result['param']);
            $result = $form->makeProcessData($result['param'], $flag);
        }
        return $result;
    }

    public function changeAdd($param)
    {
        $this->changeMainData($param);
        $this->changeContactData($param);
        $this->changeCompanyData($param);
        // $this->changeFlowData($param);
        return true;
    }

    public function changeUpdate($param)
    {
        $this->updateMainData($param);
        $this->updateContactData($param);
        $this->updateCompanyData($param);
        return true;
    }
    public function changeFlowData($param)
    {
        $sql="insert into inhe_customer_data_flow (form_id,customer_id,flow_date,create_time,remark,user_id,user_name)
         select '$param[form_id]' form_id,customer_id,flow_date,create_time,remark,user_id,user_name from inhe_customer_data_flow where form_id='$param[related_id]'";
         exequery(TD::conn(), $sql);
        return $result;
    }

    public function changeMainData($param)
    {
        $form = new FormModel();
        $paramInsert = array(
            'organ_id' => $_SESSION['LOGIN_DEPT_ID'],
            'user_id' => $_SESSION['LOGIN_USER_ID'],
            'create_dept_id' => $_SESSION['LOGIN_DEPT_ID'],
            'create_user_id' => $_SESSION['LOGIN_USER_ID'],
            'write_time' => date('Y-m-d H:i:s'),
            'title' => $param['title'],
            'form_id' => $param['form_id'],
            'related_id' => $param['related_id'],
            'customer_name' => $param['customer_name'],
            'short_name' => $param['short_name'],
            'continent' => $param['continent'],
            'country' => $param['country'],
            'website' => $param['website'],
            'address' => $param['address'],
            'summarize' => $param['summarize'],
            'user_name' => $param['user_name'],
            'dept_name' => $param['dept_name'],
            'status' => $param['status'],
            'type' => $param['type'],
            'customer_type' => $param['customer_type'],
            'customer_source' => $param['customer_source'],
            'focus' => $param['focus'],
            'is_vaild' => $param['is_vaild'],
            'change_explain' => $param['change_explain'],
          //  'project_star' => $param['project_star'],
            'user_bu' => $param['user_bu']
        );
        $condition = array('form_id' => $param['related_id']);
        $tableName = 'inhe_customer_data';
        $result = $form->changeMainData($paramInsert, $condition, $tableName, array(), '');
        return $result;
    }

    public function changeContactData($param)
    {
        $form = new FormModel();
        $normalField = array(
            'form_id' => 'form_id'
        );
        $batchField = array(
            'name' => 'contacts_name',
            'phone_one' => 'phone_one',
            'phone_two' => 'phone_two',
            'email' => 'email',
            'position' => 'position',
            'address' => 'contacts_address',
            'remark' => 'remark'
        );
        $condition = array('form_id' => $param['related_id']);
        $tableName = 'inhe_customer_contact';
        $count = $param['addContactCount'];
        $result = $form->changeDetailData($normalField, $batchField, $param, $condition, $tableName, $count);
        $sql = "delete from inhe_customer_contact where name = '' and form_id = '" . $param['form_id'] . "' ";
        exequery(TD::conn(), $sql);
        return $result;
    }

    public function changeCompanyData($param)
    {
        $form = new FormModel();
        $normalField = array(
            'form_id' => 'form_id'
        );
        $batchField = array(
            'contract_party' => 'company_name',
            'short_name' => 'company_short_name',
            'country' => 'company_country',
            'address' => 'company_address',
            'contact_name' => 'contact_name',
            'phone' => 'phone'
        );
        $condition = array('form_id' => $param['related_id']);
        $tableName = 'inhe_customer_company';
        $count = $param['addCompanyCount'];
        $result = $form->changeDetailData($normalField, $batchField, $param, $condition, $tableName, $count);
        $sql = "delete from inhe_customer_company where contract_party = '' and form_id = '" . $param['form_id'] . "' ";
        exequery(TD::conn(), $sql);
        return $result;
    }

    public function updateApproveOpinion()
    {
        $param = $this->getParamToArray();
        $process = new ProcessModel();
        $result = $process->updateProcessOpinion($param);
        return $result;
    }

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
        $sql = " delete from inhe_customer_data " . $where;
        exequery(TD::conn(), $sql);
        $sql = " delete from inhe_customer_contact  where form_id = '" . $param['id'] . "' ";
        exequery(TD::conn(), $sql);
        $sql = " delete from inhe_customer_company  where form_id = '" . $param['id'] . "' ";
        exequery(TD::conn(), $sql);
        
        $sql = " delete from inhe_flow_opinion  where form_id = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $sql);
        $result = $response->success(true);
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

    public function verifyNotExist($param,$type='customer_name'){
        switch($type){
            case 'customer_name':
                $condition=array(
                    'customer_name'=>$param['customer_name'],
                );
                if($param['form_id']){
                    $neqCondition=array(
                        'form_id'=>$param['form_id']
                    );
                }
                break;
            case 'short_name':
                $condition=array(
                    'short_name'=>$param['short_name'],
                );
                if($param['form_id']){
                    $neqCondition=array(
                        'form_id'=>$param['form_id']
                    );
                }
                break;
        }
        $form = new FormModel();
        $flag=$form->verifyNotExist("inhe_customer_data",$condition,$neqCondition);
        return $flag;
    }
}
