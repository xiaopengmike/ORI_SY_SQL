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


        $sql = " select k.* from inhe_customer_data k " . $strWhere . $orderBy . $limit;
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

        $main = $this->queryMain($param);
        $customer = $this->queryCustomer($param);
        $project = $this->queryProject($param);
        $param['user_bu']=$main['user_bu'];
        $projectWitlink = $this->queryWitlinkProject($param);
        $projectDetail = $this->queryProjectDetail($param);
        $bidding = $this->queryBidding($param);

        $result = array(
            'main' => $main,
            'customer' => $customer,
            'project' => $project,
            'projectWitlink' => $projectWitlink,
            'projectDetail' => $projectDetail,
            'bidding' => $bidding
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

        $sql = " select k.*,u.user_name createUserName, d.dept_name deptName from inhe_project_main k " .
            " left join user u on u.user_id = k.user_id " .
            " left join department d on d.dept_id = k.organ_id " . $strWhere;
        $res = exequery(TD::conn(), $sql);

        while ($item = mysql_fetch_assoc($res)) {
            $result = $item;
        }
        mysql_free_result($res);

        return $result;
    }

    public function queryCustomer($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";

        if (array_key_exists("id", $param) && $param['id'] != "") {
            $strWhere .= " and k.form_id = '" . $param['id'] . "' ";
        } else {
            return $result;
        }

        $sql = " select * from inhe_customer_info k " . $strWhere;
        $res = exequery(TD::conn(), $sql);

        while ($item = mysql_fetch_assoc($res)) {
            $result['name' . $item['type']] = $item['name'];
            $result['customer_name' . $item['type']] = $item['customer_name'];
            $result['customer_id' . $item['type']] = $item['customer_id'];
            $result['country' . $item['type']] = $item['country'];
            $result['contact' . $item['type']] = $item['contact'];
            $result['phone' . $item['type']] = $item['phone'];
            $result['email' . $item['type']] = $item['email'];
            $result['introduction' . $item['type']] = $item['introduction'];
            $result['form_id'] = $item['form_id'];
        }
        mysql_free_result($res);

        return $result;
    }

    public function queryProject($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";

        if (array_key_exists("id", $param) && $param['id'] != "") {
            $strWhere .= " and k.form_id = '" . $param['id'] . "' ";
        } else {
            return $result;
        }

        $sql = " select * from inhe_project_info k " . $strWhere;
        $res = exequery(TD::conn(), $sql);

        while ($item = mysql_fetch_assoc($res)) {
            $result = $item;
        }
        mysql_free_result($res);

        return $result;
    }
    public function queryWitlinkProject($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";

        if (array_key_exists("id", $param) && $param['id'] != "") {
            $strWhere .= " and k.form_id = '" . $param['id'] . "' ";
        } else {
            return $result;
        }

        $sql = " select * from inhe_project_info_witLink k " . $strWhere;
        $res = exequery(TD::conn(), $sql);

        while ($item = mysql_fetch_assoc($res)) {
            $result = $item;
        }
        mysql_free_result($res);

        return $result;
    }
    public function queryProjectDetail($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";

        if (array_key_exists("id", $param) && $param['id'] != "") {
            $strWhere .= " and k.form_id = '" . $param['id'] . "' ";
        } else {
            return $result;
        }

        $sql = " select * from inhe_project_detail k " . $strWhere;
        $res = exequery(TD::conn(), $sql);

        while ($item = mysql_fetch_assoc($res)) {
            if(empty($item['type'])){
                if($param['user_bu']=="witlink"){
                    $result['Soft'][] = $item;
                    continue;
                }
                if($param['user_bu']=="inhenergy"){
                    $result['Inhenergy'][] = $item;
                    continue;
                }
                if($param['user_bu']=="inhegrid"){
                    $result['Inhegrid'][] = $item;
                    continue;
                }
                $result['Electric'][] = $item;
            }else{
                $result[$item['type']][] = $item;
            }
           
            
        }
        mysql_free_result($res);

        return $result;
    }

    public function queryBidding($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";

        if (array_key_exists("id", $param) && $param['id'] != "") {
            $strWhere .= " and k.form_id = '" . $param['id'] . "' ";
        } else {
            return $result;
        }

        $sql = " select * from inhe_bidding_strategy k " . $strWhere;
        $res = exequery(TD::conn(), $sql);

        while ($item = mysql_fetch_assoc($res)) {
            $result = $item;
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

    /**
     * 新增一个客户
     */
    public function add($param)
    {
        $id = $this->addMain($param);
        $this->addClientInfo($param);
        $this->addProjectInfo($param);
        $this->addProjectInfoWitlink($param);
        $this->addProjectDetail($param);
        $this->addBiddingStrategy($param);
        if ($id <= 0) {
            return false;
        }
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
        $userName = $_SESSION['LOGIN_USER_NAME'];
        $deptName = $param['deptName'];
        $time = date('Y-m-d H:i:s');
        $status = $param['status'];

        $mainSql = "insert into inhe_project_main (title, form_id,
            project_code,
            remark,
            organ_id,
            user_id,
            user_name,
            dept_name,
            write_time,
            status,
            type,
            is_bidding_project,
            change_star,
            create_user_bu,
            is_collective,
            past_pcode,
            c_past_pcode,
            user_bu) values" .
            "('" . $param['title'] . "', '" . $param['form_id'] . "', '" . $param['project_code']. "', '" .$param['remark'] . "', '" . $deptId .
            "', '" . $userId ."', '" . $userName ."', '" . $deptName . "','" . $time . "', '" . $status .
            "', '" . $param['type'] . "','".$param['is_bidding_project']."', '". $param['change_star']."', '"  . $param['create_user_bu']."', '"  . 
            $param['is_collective']."', '"  . $param['past_pcode']."', '"  . $param['c_past_pcode']."', '"  . $param['user_bu'] . "')";
        exequery(TD::conn(), $mainSql);
        $id = mysql_insert_id();

        return $id;
    }

    public function addClientInfo($param)
    {
        for ($i = 1; $i <= 3; $i++) {
            $mainSql = "insert into inhe_customer_info (form_id,
            name,
            customer_id,
            country,
            contact,
            phone,
            email,
            introduction,
            type) values" .
                "('" . $param['form_id'] . "', '" . $param['name' . $i] . "', '" . $param['customer_id' . $i] . "', '" . $param['country' . $i] .
                "', '" . $param['contact' . $i] . "','" . $param['phone' . $i] . "', '" . $param['email' . $i] .
                "', '" . $param['introduction' . $i] . "', '" . $i . "')";
            exequery(TD::conn(), $mainSql);
        }

        $updateSql = "update inhe_customer_info a left join inhe_customer_data b on a.name = b.id 
        set a.customer_name = b.customer_name where a.customer_name = '' or a.customer_name is null ";
        exequery(TD::conn(), $updateSql);

        return true;
    }

    public function addProjectInfo($param)
    {
        $mainSql = "insert into inhe_project_info (form_id,
        project_code,
        temp_project_code,
        country,
        scope,
        project_type,
        prototype,
        potential,
        background,
        explain_user,
        explain_project,
        cooperate_history,
        service) values" .
            "('" . $param['form_id'] . "', '" . $param['project_code']. "', '" .$param['temp_project_code']. "', '" . $param['country'] . "', '" . $param['scope'] . "','" .
            $param['project_type'] . "', '" . $param['prototype'] . "', '" . $param['potential'] . "', '" . $param['background'] . "','" .
            $param['explain_user'] . "','" . $param['explain_project'] . "','" . $param['cooperate_history'] . "','" . $param['service'] . "')";
        exequery(TD::conn(), $mainSql);
        $id = mysql_insert_id();
        if ($id > 0) {
            return true;
        }

        return false;

        return $id;
    }
    //慧联立项专属信息
    public function addProjectInfoWitlink($param)
    {
        if($param['user_bu']!='WITLINK'){
            return true;
        }
        $mainSql = "insert into inhe_project_info_witlink (form_id,
        customer_type,
        num_users,
        users_five_years_later,
        replacement_reason,
        old_system_situation,
        purchase_method,
        customer_it_leader,
        leader_phone,
        leader_email,
        supply_server_database,
        database_type,
        database_config,
        database_num,
        server_model,
        server_config,
        server_num,
        central_type,
        distributed,
        cloud_deployment,
        os_language,
        data_migration,
        compatible_equipment,
        compatible_module,
        integrated_software,
        custom_api,
        other_requirements,
        demand_change,
        implement_scene,
        implement_long,
        implement_agent,
        implement_scene_duration,
        implement_scene_person,
        implement_long_duration,
        implement_long_person,
        implement_agent_duration,
        implement_agent_person,
        cooperate_history,
        maintain_obligation,
        maintain_mode,
        maintain_years,
        service_level) values" .
            "('" . $param['form_id'] . "', '" . $param['customer_type'] . "', '" . $param['num_users'] . "', '" . $param['users_five_years_later'] . "','" .
            $param['replacement_reason'] . "', '" . $param['old_system_situation'] . "', '" . $param['purchase_method'] . "', '" . $param['customer_it_leader'] . "','" .
            $param['leader_phone'] . "', '" . $param['leader_email'] . "', '" . $param['supply_server_database'] . "', '" . $param['database_type'] . "','" .
            $param['database_config'] . "', '" . $param['database_num'] . "', '" . $param['server_model'] . "', '" . $param['server_config'] . "','" .
            $param['server_num'] . "', '" . $param['central_type'] . "', '" . $param['distributed'] . "', '" . $param['cloud_deployment'] . "','" .
            $param['os_language'] . "', '" . $param['data_migration'] . "', '" . $param['compatible_equipment'] . "', '" . $param['compatible_module'] . "','" .
            $param['integrated_software'] . "', '" . $param['custom_api'] . "', '" . $param['other_requirements'] . "', '" . $param['demand_change'] . "','" .
            $param['implement_scene'] . "', '" . $param['implement_long'] . "', '" . $param['implement_agent'] . "', '" . $param['implement_scene_duration'] . "','" .
            $param['implement_scene_person'] . "', '" . $param['implement_long_duration'] . "', '" . $param['implement_long_person'] . "', '" . $param['implement_agent_duration'] . "','" .
            $param['implement_agent_person'] . "', '" . $param['cooperate_history'] . "', '" .
            $param['maintain_obligation'] . "', '" . $param['maintain_mode'] . "', '" . $param['maintain_years'] . "', '" .$param['service_level'] . "')";
        exequery(TD::conn(), $mainSql);
        $id = mysql_insert_id();
        if ($id > 0) {
            return true;
        }

        return false;

        return $id;
    }

    public function addProjectDetail($param)
    {
        if ($param['addProjectDetailCount'] <= 0) {
            return true;
        }
        for ($i = 1; $i <= $param['addProjectDetailCount']; $i++) {
            $sql = "insert into inhe_project_detail (form_id,
            product,
            price,
            serial_number,
            first_price,
            standard,
            supplier,
            type,
            number) values" .
                "('" . $param['form_id'] . "', '" . $param['product' . $i] . "', '" . $param['price' . $i] . "', '" . $i . "', '" .
                $param['first_price' . $i] . "','" . $param['standard' . $i] . "','" . $param['supplier' . $i] . "', '" . $param['type' . $i] . "', '" . $param['number' . $i] . "')";
            if (!empty($param['product' . $i])) exequery(TD::conn(), $sql);
        }

        return true;
    }

    public function addBiddingStrategy($param)
    {
        $sql = "insert into inhe_bidding_strategy (form_id, 
                    tender_no, 
                    end_date, 
                    project_name, 
                    tender, 
                    bid_rule, 
                    biding_rule, 
                    effective_demand, 
                    tender_demand, 
                    file_demand, 
                    format, 
                    reason, 
                    process_description, 
                    pay_process, 
                    pay_energy, 
                    partner_analysis, 
                    relationship, 
                    market_analysis, 
                    strategy_analysis, 
                    feasibility_analysis,
                    key_to_success,
                    personal_opinion,
                    project_level,
                    have_manager
                ) values" .
            "('" . $param['form_id'] . "', '" . $param['tender_no'] . "', '" . $param['end_date'] . "', '" .
            $param['project_name'] . "','" . $param['tender'] . "', '" . $param['bid_rule'] . "', '" .
            $param['biding_rule'] . "','" . $param['effective_demand'] . "', '" . $param['tender_demand'] . "', '" .
            $param['file_demand'] . "','" . $param['format'] . "', '" . $param['reason'] . "', '" . $param['process_description'] . "', '" .
            $param['pay_process'] . "','" . $param['pay_energy'] . "','" . $param['partner_analysis'] . "', '" . $param['relationship'] . "', '" .
            $param['market_analysis'] . "','" . $param['strategy_analysis'] . "','" . $param['feasibility_analysis'] . "','" . $param['key_to_success'] . "', '" . $param['personal_opinion'] . "', '" .
            $param['project_level'] . "','" . $param['have_manager'] . "')";
        exequery(TD::conn(), $sql);

        return true;
    }

    public function update($param)
    {
        $this->updateMainData($param);
        $this->updateClientData($param);
        $this->updateProjectMainData($param);
        $this->updateProjectWitlinkMainData($param);
        $this->updateProjectDetailData($param);
        $this->updateBiddingStrategy($param);
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
        $userName = $_SESSION['LOGIN_USER_NAME'];
        $userid = $_SESSION['LOGIN_USER_ID'];
        $deptid = $_SESSION['LOGIN_DEPT_ID'];
        $deptName = $param['deptName'];
        $form = new FormModel();
        $paramInsert = array(
            'user_id' => $userid,
            'organ_id' => $deptid,
            'user_name' => $userName,
            'dept_name' => $deptName,
            'update_time' => date('Y-m-d H:i:s'),
            'title' => $param['title'],
            'remark' => $param['remark'],
            'project_code' => $param['project_code'],
            'status' => $param['status'],
            'type' => $param['type'],
            'is_bidding_project' => $param['is_bidding_project'],
            'change_star' => $param['change_star'],
            'user_bu' => $param['user_bu'],
            'is_collective' => $param['is_collective'],
            'past_pcode' => $param['past_pcode'],
            'c_past_pcode' => $param['c_past_pcode'],
            'create_user_bu' => $param['create_user_bu'],
        );
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_project_main';
        $result = $form->updateMainData($paramInsert, $condition, $tableName, array(), '');
        return $result;
    }

    public function updateClientData($param)
    {
        $form = new FormModel();
        $normalField = array(
            'form_id' => 'form_id'
        );
        $batchField = array(
            'name' => 'name',
            'customer_id' => 'customer_id',
            'country' => 'country',
            'contact' => 'contact',
            'phone' => 'phone',
            'email' => 'email',
            'introduction' => 'introduction',
            'type' => ''
        );
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_customer_info';
        $count = 4;
        $result = $form->updateDetailData($normalField, $batchField, $param, $condition, $tableName, $count);
        $deleteSql = "delete from inhe_customer_info where type = 0 ";
        exequery(TD::conn(), $deleteSql);
        $updateSql = "update inhe_customer_info a left join inhe_customer_data b on a.name = b.id 
        set a.customer_name = b.customer_name where a.customer_name = '' or a.customer_name is null ";
        exequery(TD::conn(), $updateSql);

        return $result;
    }

    public function updateProjectMainData($param)
    {
        $form = new FormModel();
        $paramInsert = array(
            'project_code' => $param['project_code'],
            'temp_project_code' => $param['temp_project_code'],
            'country' => $param['country'],
            'scope' => $param['scope'],
            'project_type' => $param['project_type'],
            'prototype' => $param['prototype'],
            'potential' => $param['potential'],
            'background' => $param['background'],
            'explain_user' => $param['explain_user'],
            'explain_project' => $param['explain_project'],
            'cooperate_history' => $param['cooperate_history'],
            'service' => $param['service'],
        );
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_project_info';
        $result = $form->updateMainData($paramInsert, $condition, $tableName, array(), '');
        return $result;
    }
    public function updateProjectWitlinkMainData($param)
    {
        if($param['user_bu']!='WITLINK'){
            return true;
        }
        $form = new FormModel();
        $paramInsert = array(
            'customer_type' => $param['customer_type'],
            'num_users' => $param['num_users'],
            'users_five_years_later' => $param['users_five_years_later'],
            'replacement_reason' => $param['replacement_reason'],
            'old_system_situation' => $param['old_system_situation'],
            'purchase_method' => $param['purchase_method'],
            'customer_it_leader' => $param['customer_it_leader'],
            'leader_phone' => $param['leader_phone'],
            'leader_email' => $param['leader_email'],
            'supply_server_database' => $param['supply_server_database'],
            'database_type' => $param['database_type'],
            'database_config' => $param['database_config'],
            'database_num' => $param['database_num'],
            'server_model' => $param['server_model'],
            'server_config' => $param['server_config'],
            'server_num' => $param['server_num'],
            'central_type' => $param['central_type'],
            'distributed' => $param['distributed'],
            'cloud_deployment' => $param['cloud_deployment'],
            'os_language' => $param['os_language'],
            'data_migration' => $param['data_migration'],
            'compatible_equipment' => $param['compatible_equipment'],
            'compatible_module' => $param['compatible_module'],
            'integrated_software' => $param['integrated_software'],
            'custom_api' => $param['custom_api'],
            'other_requirements' => $param['other_requirements'],
            'demand_change' => $param['demand_change'],
            'maintain_obligation' => $param['maintain_obligation'],
            'maintain_mode' => $param['maintain_mode'],
            'maintain_years' => $param['maintain_years'],
            'service_level' => $param['service_level'],
            'implement_scene' => $param['implement_scene'],
            'implement_long' => $param['implement_long'],
            'implement_agent' => $param['implement_agent'],
            'implement_scene_duration' => $param['implement_scene_duration'],
            'implement_scene_person' => $param['implement_scene_person'],
            'implement_long_duration' => $param['implement_long_duration'],
            'implement_long_person' => $param['implement_long_person'],
            'implement_agent_duration' => $param['implement_agent_duration'],
            'implement_agent_person' => $param['implement_agent_person'],
            'cooperate_history' => $param['cooperate_history'],
        );
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_project_info_witlink';
        $result = $form->updateMainData($paramInsert, $condition, $tableName, array(), '');
        return $result;
    }

    public function updateProjectDetailData($param)
    {
        $form = new FormModel();
        $normalField = array(
            'form_id' => 'form_id'
        );
        $batchField = array(
            'product' => 'product',
            'price' => 'price',
            'serial_number' => 'serial_number',
            'first_price' => 'first_price',
            'standard' => 'standard',
            'supplier' => 'supplier',
            'type' => 'type',
            'number' => 'number'
        );
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_project_detail';
        $count = $param['addProjectDetailCount'];
        $result = $form->updateDetailData($normalField, $batchField, $param, $condition, $tableName, $count);
        // 删除空数据
        $sql = " delete from " . $tableName . " where form_id = '" . $param['form_id'] . "' and (product = '' or product is null) ";
        exequery(TD::conn(), $sql);
        return $result;
    }

    public function updateBiddingStrategy($param)
    {
        $form = new FormModel();
        $paramInsert = array(
            'tender_no' => $param['tender_no'],
            'end_date' => $param['end_date'],
            'project_name' => $param['project_name'],
            'tender' => $param['tender'],
            'bid_rule' => $param['bid_rule'],
            'biding_rule' => $param['biding_rule'],
            'effective_demand' => $param['effective_demand'],
            'tender_demand' => $param['tender_demand'],
            'file_demand' => $param['file_demand'],
            'format' => $param['format'],
            'reason' => $param['reason'],
            'process_description' => $param['process_description'],
            'pay_process' => $param['pay_process'],
            'pay_energy' => $param['pay_energy'],
            'partner_analysis' => $param['partner_analysis'],
            'relationship' => $param['relationship'],
            'market_analysis' => $param['market_analysis'],
            'strategy_analysis' => $param['strategy_analysis'],
            'feasibility_analysis' => $param['feasibility_analysis'],
            'key_to_success' => $param['key_to_success'],
            'personal_opinion' => $param['personal_opinion'],
            'project_level' => $param['project_level'],
            'have_manager' => $param['have_manager'],
        );
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_bidding_strategy';
        $result = $form->updateMainData($paramInsert, $condition, $tableName, array(), '');
        return $result;
    }

    public function change()
    {
        $flag = false;
        $param = $this->getParamToArray();
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
        $this->changeClientData($param);
        $this->changeProjectMainData($param);
        $this->changeProjectWitlinkMainData($param);
        $this->changeProjectDetailData($param);
        $this->changeBiddingStrategy($param);
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
        $this->updateClientData($param);
        $this->updateProjectMainData($param);
        $this->updateProjectWitlinkMainData($param);
        $this->updateProjectDetailData($param);
        $this->updateBiddingStrategy($param);
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
            'project_code' => $param['project_code'],
            'status' => $param['status'],
            'type' => $param['type'],
            'is_bidding_project' => $param['is_bidding_project'],
            'change_star' => $param['change_star'],
            'remark' => $param['remark'],
            'user_bu' => $param['user_bu'],
            'is_collective' => $param['is_collective'],
            'past_pcode' => $param['past_pcode'],
            'create_user_bu' => $param['create_user_bu'],
            'from_id' => $param['from_id'],
            'c_past_pcode' => $param['c_past_pcode'],
        );
        $condition = array('form_id' => $param['related_id']);
        $tableName = 'inhe_project_main';
        $result = $form->changeMainData($paramInsert, $condition, $tableName, array(), '');
        return $result;
    }

    public function changeClientData($param)
    {
        $form = new FormModel();
        $normalField = array(
            'form_id' => 'form_id'
        );
        $batchField = array(
            'name' => 'name',
            'customer_id' => 'customer_id',
            'country' => 'country',
            'contact' => 'contact',
            'phone' => 'phone',
            'email' => 'email',
            'introduction' => 'introduction',
            'type' => ''
        );
        $condition = array('form_id' => $param['related_id']);
        $tableName = 'inhe_customer_info';
        $count = 4;
        $result = $form->changeDetailData($normalField, $batchField, $param, $condition, $tableName, $count);
        $deleteSql = "delete from inhe_customer_info where type = 0 ";
        exequery(TD::conn(), $deleteSql);
        $updateSql = "update inhe_customer_info a left join inhe_customer_data b on a.name = b.id 
        set a.customer_name = b.customer_name where a.customer_name = '' or a.customer_name is null ";
        exequery(TD::conn(), $updateSql);

        return $result;
    }

    public function changeProjectMainData($param)
    {
        $form = new FormModel();
        $paramInsert = array(
            'form_id' => $param['form_id'],
            'project_code' => $param['project_code'],
            'temp_project_code' => $param['temp_project_code'],
            'country' => $param['country'],
            'scope' => $param['scope'],
            'project_type' => $param['project_type'],
            'prototype' => $param['prototype'],
            'potential' => $param['potential'],
            'background' => $param['background'],
            'explain_user' => $param['explain_user'],
            'explain_project' => $param['explain_project'],
            'service' => $param['service'],
            'cooperate_history' => $param['cooperate_history'],           
        );
        $condition = array('form_id' => $param['related_id']);
        $tableName = 'inhe_project_info';
        $result = $form->changeMainData($paramInsert, $condition, $tableName, array(), '');
        return $result;
    }
    public function changeProjectWitlinkMainData($param)
    {
        if($param['user_bu']!='WITLINK'){
            return true;
        }
        $form = new FormModel();
        $paramInsert = array(
            'form_id' => $param['form_id'],
            'customer_type' => $param['customer_type'],
            'num_users' => $param['num_users'],
            'users_five_years_later' => $param['users_five_years_later'],
            'replacement_reason' => $param['replacement_reason'],
            'old_system_situation' => $param['old_system_situation'],
            'purchase_method' => $param['purchase_method'],
            'customer_it_leader' => $param['customer_it_leader'],
            'leader_phone' => $param['leader_phone'],
            'leader_email' => $param['leader_email'],
            'supply_server_database' => $param['supply_server_database'],
            'database_type' => $param['database_type'],
            'database_config' => $param['database_config'],
            'database_num' => $param['database_num'],
            'server_model' => $param['server_model'],
            'server_config' => $param['server_config'],
            'server_num' => $param['server_num'],
            'central_type' => $param['central_type'],
            'distributed' => $param['distributed'],
            'cloud_deployment' => $param['cloud_deployment'],
            'os_language' => $param['os_language'],
            'data_migration' => $param['data_migration'],
            'compatible_equipment' => $param['compatible_equipment'],
            'compatible_module' => $param['compatible_module'],
            'integrated_software' => $param['integrated_software'],
            'custom_api' => $param['custom_api'],
            'other_requirements' => $param['other_requirements'],
            'demand_change' => $param['demand_change'],
            'maintain_obligation' => $param['maintain_obligation'],
            'maintain_mode' => $param['maintain_mode'],
            'maintain_years' => $param['maintain_years'],
            'service_level' => $param['service_level'],
            'implement_scene' => $param['implement_scene'],
            'implement_long' => $param['implement_long'],
            'implement_agent' => $param['implement_agent'],
            'implement_scene_duration' => $param['implement_scene_duration'],
            'implement_scene_person' => $param['implement_scene_person'],
            'implement_long_duration' => $param['implement_long_duration'],
            'implement_long_person' => $param['implement_long_person'],
            'implement_agent_duration' => $param['implement_agent_duration'],
            'implement_agent_person' => $param['implement_agent_person'],
            'cooperate_history' => $param['cooperate_history'],
        );
        $condition = array('form_id' => $param['related_id']);
        $tableName = 'inhe_project_info_witlink';
        $result = $form->changeMainData($paramInsert, $condition, $tableName, array(), '');
        return $result;
    }

    public function changeProjectDetailData($param)
    {
        $form = new FormModel();
        $normalField = array(
            'form_id' => 'form_id'
        );
        $batchField = array(
            'product' => 'product',
            'price' => 'price',
            'first_price' => 'first_price',
            'serial_number' => 'serial_number',
            'standard' => 'standard',
            'supplier' => 'supplier',
            'type' => 'type',
            'number' => 'number'
        );
        $condition = array('form_id' => $param['related_id']);
        $tableName = 'inhe_project_detail';
        $count = $param['addProjectDetailCount'];
        $result = $form->changeDetailData($normalField, $batchField, $param, $condition, $tableName, $count);
        return $result;
    }

    public function changeBiddingStrategy($param)
    {
        $form = new FormModel();
        $paramInsert = array(
            'form_id' => $param['form_id'],
            'tender_no' => $param['tender_no'],
            'end_date' => $param['end_date'],
            'project_name' => $param['project_name'],
            'tender' => $param['tender'],
            'bid_rule' => $param['bid_rule'],
            'biding_rule' => $param['biding_rule'],
            'effective_demand' => $param['effective_demand'],
            'tender_demand' => $param['tender_demand'],
            'file_demand' => $param['file_demand'],
            'format' => $param['format'],
            'reason' => $param['reason'],
            'process_description' => $param['process_description'],
            'pay_process' => $param['pay_process'],
            'pay_energy' => $param['pay_energy'],
            'partner_analysis' => $param['partner_analysis'],
            'relationship' => $param['relationship'],
            'market_analysis' => $param['market_analysis'],
            'strategy_analysis' => $param['strategy_analysis'],
            'feasibility_analysis' => $param['feasibility_analysis'],
            'key_to_success' => $param['key_to_success'],
            'personal_opinion' => $param['personal_opinion'],
            'project_level' => $param['project_level'],
            'have_manager' => $param['have_manager'],
        );
        $condition = array('form_id' => $param['related_id']);
        $tableName = 'inhe_bidding_strategy';
        $result = $form->changeMainData($paramInsert, $condition, $tableName, array(), '');
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
        if (array_key_exists('id', $param) && $param['id']) {
            $where .= " and form_id = '" . $param['id'] . "' ";
        } else {
            $result = $response->failed(false);
            return  $result;
        }
        $sql = " delete from inhe_project_main " . $where;
        $re = exequery(TD::conn(), $sql);

        $sql = " delete from inhe_customer_info  where form_id = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $sql);
        $sql = " delete from inhe_project_info_witlink  where form_id = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $sql);
        $sql = " delete from inhe_project_info  where form_id = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $sql);
        $sql = " delete from inhe_project_detail  where form_id = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $sql);
        $sql = " delete from inhe_bidding_strategy  where form_id = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $sql);

        $sql = " delete from inhe_flow_opinion  where form_id = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $sql);
        $sql = " delete from inhe_attachment  where relatedId = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $sql);

        $result = $response->success(true);
        return $result;
    }
    /**
     * 更新project_main中的星级project_star
     */
    public function updateMain($condition=array(),$paramInsert=array())
    {
        if(!is_array($condition)||empty($condition)){
            return false;
        }
        if(!is_array($paramInsert)||empty($paramInsert)){
            return false;
        }
        $form = new FormModel();
        $tableName = 'inhe_project_main';
        $result = $form->updateMainData($paramInsert, $condition, $tableName, array(), '');
        return $result;
    }
    public function updateApproveOpinion()
    {
        $param = $this->getParamToArray();
        $process = new ProcessModel();
        $result = $process->updateProcessOpinion($param);
        //更新project_main 中的星级
        if(array_key_exists('project_star',$param)&&array_key_exists('form_id',$param)){
            $starSql=" select * from inhe_oa_paras where type='project_star'";
            $res = exequery(TD::conn(), $starSql);
            $countArr = array();
            while ($item = mysql_fetch_assoc($res)) {
                $countArr[$item['paras_value']] = $item['extra'];
            }
            mysql_free_result($res);
            $condition=array('form_id'=>$param['form_id']);
            $updateData=array(
                'project_star'=>$param['project_star'],
                'star_icon'=>$countArr[$param['project_star']]
             );
            $this->updateMain($condition,$updateData);
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
