<?php
require_once("../inc/auth.php");
require_once("../inc/utility_all.php");
require_once("../inc/utility_file.php");
require_once("../inc/utility_field.php");
require_once("../inc/utility_sms1.php");
require_once("../inc/utility_sms2.php");
require_once("ResponseCode.php");
// 这些模型在独立系统中不需要，已注释
// require_once("ProductionOrderModel.php");
// require_once("ShippingNoticeModel.php");
// require_once("SalesContractReviewModel.php");
// require_once("ProjectReviewModel.php");

/**
 * ���̲�����
 * @author ysr
 */
class ProcessModel
{
    const TYPE = 'crm_process_type';

    /**
     * sms���ŷ�������
     * @param $param ��������
     * @param array url:�������ӣ�content�����ݣ�approver��������
     */
    public function sendSms($param)
    {
        $remindUrl = $param['url'];
        $smsContent = $param['content'];
        $reminder = $param['approver'];
        $fromUser = empty($param['smsFromUserId'])?$_SESSION["LOGIN_USER_ID"]:$param['smsFromUserId'];
        // ������Ϣ����
        send_sms("", $fromUser, $reminder, 0, $smsContent, $remindUrl);
    }

    /**
     * �������̲�����
     * �漰������approve��ˣ�backFlow�˻أ�notice���ѣ�record������
     */
    public function flowOperate($param)
    {
        $action = $param['operate'];

        require_once("Operation.php");
        //����
        $obj = new Operation();
        $obj->operateData(new $action());
    }

    /**
     * ��ȡ��˲�������?
     */
    public function getApprover($param)
    {
        $where = " where 1=1 ";
        if (array_key_exists('step', $param) && $param['step']) {
            $where .= " and k.step = '" . $param['step'] . "' ";
        }
        if (array_key_exists('type', $param) && $param['type']) {
            $where .= " and k.type = '" . $param['type'] . "' ";
        }
        if (array_key_exists('user_bu', $param) && $param['user_bu']) {
            $where .= " and k.user_bu = '" . $param['user_bu'] . "' ";
        }
        if (array_key_exists('role', $param) && $param['role']) {
            $where .= " and k.role = '" . $param['role'] . "' ";
        }

        $sql = " select k.* from inhe_flow_manage k " . $where;
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
        }
        mysql_free_result($res);

        return $result;
    }
    /**
     * ��ȡ��һ�������Ա���ݣ�����notice_user��Ա
     */
    public function skipApprover($param)
    {
        $result = array();
        if (!array_key_exists('type', $param) || $param['type'] == '' || !array_key_exists('user_bu', $param) || $param['user_bu'] == '') {
            return $result;
        }
        $approverArr = $this->getApprover($param);
        $nextStep = $approverArr[0]['next_step'];
        if (empty($nextStep)) {
            return $result;
        }
        $where = " where k.type = '" . $param['type'] . "' and k.user_bu = '" . $param['user_bu'] . "' ";
        $where .= " and k.step in($nextStep) ";

        $sql = " select step, role, approver, cdinhe_approver,notice_user, operate from inhe_flow_manage k " . $where . " order by step  ";
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
        }
        mysql_free_result($res);
        if($result[0][notice_user]=="1"){
            $param[step]=$result[0][step];
            return $this->skipApprover($param);
        }
        return $result;
    }

    /**
     * ��ȡ��һ�������Ա����?
     */
    public function nextApprover($param)
    {
        $result = array();
        if (!array_key_exists('type', $param) || $param['type'] == '' || !array_key_exists('user_bu', $param) || $param['user_bu'] == '') {
            return $result;
        }
        $approverArr = $this->getApprover($param);
        $nextStep = $approverArr[0]['next_step'];
        if (empty($nextStep)) {
            return $result;
        }
        $where = " where k.type = '" . $param['type'] . "' and k.user_bu = '" . $param['user_bu'] . "' ";
        $where .= " and k.step in($nextStep) ";

        $sql = " select step, role, approver, cdinhe_approver, operate from inhe_flow_manage k " . $where . " order by step  ";
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
        }
        mysql_free_result($res);

        return $result;
    }

    /**
     * ��ȡ�˻ؿ�ѡ����
     * ˵��������˲���״̬ΪH������ΪU
     */
    public function getBackStep($param)
    {
        $result = array();
        if (!array_key_exists('type', $param) || $param['type'] == '' || !array_key_exists('form_id', $param) || $param['form_id'] == '') {
            return $result;
        }
        $where = " where (step >0 or work_flow='������') and k.status = 'H' and k.step < $param[step] ";
        $where .= " and k.type = '" . $param['type'] . "' and k.form_id = '" . $param['form_id'] . "' ";

        $sql = " select distinct step, work_flow role, approve_user approver, 'approve' operate from inhe_flow_opinion k " . $where . " order by step  ";
        //echo $sql;
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
        }
        mysql_free_result($res);

        return $result;
    }

    /**
     * �������̽�������
     */
    public function updateFlowData($param)
    {
        // TODO: �����˻�״̬����Ӧ��д��ĸ N 
        $handling = "U";
        $handleStatus = "H";
        if ($param['actionType'] == 'Back') {
            $handleStatus = "N";
        }
        // ���µ�ǰ���ʱ���״̬
        $where = " where form_id = '" . $param['form_id'] . "' and type ='" . $param['type'] . "' and approve_user = '" . $param['prev_writer'] . "' and status = '$handling' ";
        $updateSql = " update inhe_flow_opinion set approve_time = '" . $param['prev_time'] . "', status = '" . $handleStatus . "' " . $where;
        exequery(TD::conn(), $updateSql);

        // ���������¼����?
        if (isset($param['step']) && !empty($param['step'])) {
            // �����ж����ò����Ѵ��ڣ��������ظ�����
            $existsSql = " select * from inhe_flow_opinion where form_id = '" . $param['form_id'] . "' and step ='" . $param['step'] . "' and status = '$handling' ";
            $res = exequery(TD::conn(), $existsSql);
            $dataCount = mysql_num_rows($res);
            if ($dataCount > 0) {
                return false;
            }
            $userArr = explode(',', $param['approve_user']);
            foreach ($userArr as $approveUser) {
                if (!empty($approveUser)) {
                    $sql = " insert into inhe_flow_opinion (type, form_id, title, step, work_flow, approve_user, status, prev_task, prev_writer, prev_time, " .
                        " opinion, project_star, have_manager, extra, if_agree, signature, user_bu,urgent,common_opinion) values ('" .
                        $param['type'] . "','" . $param['form_id'] . "','" . $param['title'] . "','" .
                        $param['step'] . "','" . $param['work_flow'] . "','" . $approveUser . "','" . $param['status'] . "','" .
                        $param['prev_task'] . "','" . $param['prev_writer'] . "','" . $param['prev_time'] . "','" . $param['opinion'] . "','" .
                        $param['project_star'] . "','" . $param['have_manager'] . "','" . $param['extra'] . "','" . $param['if_agree'] . "','" .
                        $param['signature'] . "','" . $param['user_bu'] . "','" .$param['urgent'] . "','" . $param['common_opinion'] . "'" .
                        ")";
                    exequery(TD::conn(), $sql);
                }
            }
        }
    }

    /**
     * ��ȡ�����ѡ���б�?
     * ��ȡ���������š�OA�˺ŵ�����
     */
    public function getApproverList($param)
    {
        $where = " where 1=1 ";
        $result = array();
        if (array_key_exists("approver", $param) && $param['approver'] != "") {
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

        $sql = "select uid, user_id, user_byid, user_name from user u " . $where;
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
        }

        mysql_free_result($res);

        return $result;
    }

    /**
     * �˻�����
     * @param string type ����ģ��ID
     */
    public function updateProcessOpinion($param)
    {
        $userId = $_SESSION['LOGIN_USER_ID'];
        $userName = $_SESSION['LOGIN_USER_NAME'];
        $time = date('Y-m-d H:i:s');
        // ������չ������λ���ݸ���
        $selectParam = array(
            'is_used' => '1', // ���ò���
            'type' => 'opinion_extra,opinion_select',
            'user_bu' => $param['user_bu'],
            'paras_group' => str_replace(array("_change","trial_","rd_"),array("",""),$param['type']),
        );
        $dataModel= new DataModel();
        $opinion_extra_arr = $dataModel->getCommonParam($selectParam);
        $opinion_extra = array();
        foreach ($opinion_extra_arr['opinion_extra'] as $ps) {
            if (isset($param[$ps['paras_value']]) && !empty($param[$ps['paras_value']])) $opinion_extra[$ps['paras_value']] =  $param[$ps['paras_value']];
            if (isset($param[$ps['paras_value']]) && !empty($param[$ps['paras_value']])&&$ps['related_table']=="user") $opinion_extra[$ps['paras_value']."_user"] =  $param[$ps['paras_value']."_user"];
        }
        foreach ($opinion_extra_arr['opinion_select'] as $ps) {
            if (isset($param[$ps['paras_value']]) && !empty($param[$ps['paras_value']])) $opinion_extra[$ps['paras_value']] =  $param[$ps['paras_value']];
        }

        if (!empty($opinion_extra)) $param['extra'] = array_to_json($opinion_extra);
        
        if (isset($param['if_external_procure']) && !empty($param['if_external_procure'])) $param['extra'] = $param['if_external_procure'];
        if (isset($param['if_eligible']) && !empty($param['if_eligible'])) $param['extra'] = $param['if_eligible'];
        if (isset($param['if_chief_contract']) && !empty($param['if_chief_contract'])) $param['extra'] = $param['if_chief_contract'];
        if (isset($param['if_match_contract']) && !empty($param['if_match_contract'])) $param['extra'] = $param['if_match_contract'];
        if (isset($param['if_chief_shipping']) && !empty($param['if_chief_shipping'])) $param['extra'] = $param['if_chief_shipping'];
        if (isset($param['if_not_full_payment_goods']) && !empty($param['if_not_full_payment_goods'])) $param['extra'] = $param['if_not_full_payment_goods'];
        if (isset($param['if_not_full_payment']) && !empty($param['if_not_full_payment'])) $param['extra'] = $param['if_not_full_payment'];
        if (isset($param['if_insure']) && !empty($param['if_insure'])) $param['extra'] = $param['if_insure'];
        if (!isset($param['project_star']) || empty($param['project_star'])) $param['project_star'] = '';
        // ��������������
        $where = " where form_id = '" . $param['form_id'] . "' and type ='" . $param['type'] . "' and approve_user = '" . $userId . "' and status = 'U' ";
        $updateSql = " update inhe_flow_opinion set opinion = '" . $param['opinion' . $param['step']] . "', if_agree = '" . $param['agree' . $param['step']] . "' "
            . " , project_star = '" . $param['project_star'] . "', extra = '" . $param['extra'] . "', signature = '" . $userName . ' ' . $time . "' " . $where;
        $res = exequery(TD::conn(), $updateSql);
        $response = new ResponseCode();
        $result = array();
        if ($res) {
            $result = $response->success(true);
        } else {
            $result = $response->failed(false);
        }
        return $result;
    }

    public function findApproveInfo($param)
    {
        $where = " where status = 'U' "; // ��ʱ��ȡ�������ݣ�������Ҫ���޸Ĳ���
        $result = array();
        if (!array_key_exists('step', $param) || $param['step'] == '' || !array_key_exists('form_id', $param) || $param['form_id'] == '') {
            return $result;
        }
        $where .= " and m.form_id = '" . $param['form_id'] . "' and step = '" . $param['step'] . "' ";
        if (array_key_exists('approve_user', $param) && $param['approve_user'] != '') {
            $where .= " and m.approve_user = '" . $param['approve_user'] . "' ";
        }
        $sql = " select * from inhe_flow_opinion m " . $where;
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result = $item;
        }
        return $result;
    }

    function updateFormFlow($param)
    {
        $userId = $_SESSION['LOGIN_USER_ID'];
        $userName = $_SESSION['LOGIN_USER_NAME'];
        $time = date('Y-m-d H:i:s');
        
        // ��ȡ������?
        $appParam = array(
            'step' => $param['step'],
            'type' => $param['systemId'],
            'user_bu' => $param['user_bu']
        );
        //�ɶ��ߵ������⴦��
        if($param['user_bu']=="INHE"&&$param['create_user_bu']=="CDINHE"&&in_array($param['systemId'],
        array('shipping_notice','sales_contract_review','sales_contract_review_change','trial_production_order','trial_production_order_change','production_order','production_order_change'))){
            $appParam[user_bu]=$param['user_bu']."_".$param['create_user_bu'];
        }
        $approverArr = $this->getApprover($appParam);
        $approverInfo = $approverArr[0];
        // ����һ����С�ڵ�ǰ���裬�ж�Ϊ�˻�����
        if ($param['step'] < $param['prev_step'])  $param['actionType'] = 'Back';
        $flowParam = array(
            'type' => $param['systemId'],
            'form_id' => $param['form_id'],
            'title' => $param['title'],
            'prev_step' => $param['prev_step'],
            'step' => $param['step'],
            'work_flow' => $approverInfo['role'],
            'common_opinion' => $approverInfo['common_opinion'],
            'approve_user' => $param['user_id'],
            'status' => 'U',
            'actionType' => $param['actionType'],
            'urgent' => $param['urgent'],
            'prev_writer' => $userId,
            'prev_time' => $time,
            'opinion' => $param['opinion' . $param['prev_step']],
            'user_bu' => $param['user_bu']
        );
        $updateFlowData=$this->updateFlowData($flowParam);
        
        $processType = $this->findProcessType(self::TYPE);
        $mainUrl = "";
        $formStatus = "";
        $mainUrl = str_replace('_change', '', $param['systemId']);
        $mainUrl = str_replace('trial_', '', $mainUrl);
        $mainUrl = str_replace('rd_', '', $mainUrl);
        $mainUrl = $mainUrl =="sample_comfirm"?"sample_request/sample_comfirm":$mainUrl;
        // �ύ������Ϣ���������?
        if ($param['actionType'] == 'Back') {
            // FIX:�˻�����url�Ż����˻�������ָ��������ҳ�棬�˻����������ָ�������ҳ��
            if ($param['step'] == 1) {
                // $backUrl = "/customer_relationship/" . $mainUrl . "/modify.php?id=" . $param['form_id'];  // �޸�ҳ��
                $backUrl = "/customer_relationship/" . $mainUrl . "/detail.php?id=" . $param['form_id']; // ����ҳ��
            } else {
                $backUrl = "/customer_relationship/" . $mainUrl . "/detail.php?id=" . $param['form_id'] . "&operate=approve&step=" . $param['step'];
            }
            $smsParam = array(
                'url' => $backUrl,
                'content' => $userName . '�˻�' . $processType[$param['systemId']]['paras_desc'] . '���̣��뼰ʱ����',
                'approver' => $param['user_id']
            );
            $formStatus = "N";
        } else {
            // ���ӱ���������ʾ - ����operate��λ���ݿ��ƣ� add approve record
            if ($approverInfo['operate'] == 'record') {
                // ������ʾ����
                $smsParam = array(
                    'url' => "/customer_relationship/" . $mainUrl . "/detail.php?id=" . $param['form_id'] . "&operate=approve&step=" . $param['step'],
                    // 'content' => $processType[$param['systemId']]['paras_desc'] . '����' . $param['form_id'] . '�������ˣ��뼰ʱ��������',
                    'content' => $userName . 'ת��' . $processType[$param['systemId']]['paras_desc'] . '����' . $param['form_id'] . '���뼰ʱ��������',
                    'approver' => $param['user_id']
                );
                $formStatus = "R"; // ������״̬
                // TODO ���������������ʱ��ɸ���ԭ�����ݲ���
                if (strpos($param['systemId'], '_change') !== false) {
                    $this->updateFormOldData($param['form_id'], $mainUrl);
                }
            } else {
                $smsParam = array(
                    'url' => "/customer_relationship/" . $mainUrl . "/detail.php?id=" . $param['form_id'] . "&operate=approve&step=" . $param['step'],
                    'content' => $userName . '�ύ' . $processType[$param['systemId']]['paras_desc'] . '���̣��뼰ʱ���?,
                    'approver' => $param['user_id']
                );
                $formStatus = "P";
            }
        }
        $updateWhere = " where 1=1 ";
        if (!empty($param['form_id'])) {
            $updateWhere .= " and form_id = '" . $param['form_id'] . "' ";
            $updateStatusSql = " update " . $processType[$param['systemId']]['related_table'] . " set status = '" . $formStatus . "' " . $updateWhere;
            exequery(TD::conn(), $updateStatusSql);
            
            if ($formStatus == "R" && strpos($param['systemId'], 'project_review') !== false) {
                
                // �������뱸��ʱ������������
                $this->updateProjectData($param,$time);

                //$this->updateProjectReport($param);
            }
            if ($formStatus == "R" && in_array($param['systemId'],array('trial_production_order','trial_production_order_change','production_order','production_order_change'))) {
                //����֪ͨ��ģ�飬���³���˺�?�������Զ�����������˾����֪ͨ��
                $this->autoCreateProductOrder20250801($param);
            }
            if ($formStatus == "R" && in_array($param['systemId'],array('shipping_notice'))) {
                //����֪ͨ��ģ�飬���³���˺�?�������Զ�����������˾����֪ͨ��
                $this->autoCreateShippingNotice20250926($param);
            }

            if ($formStatus == "R" && in_array($param['systemId'],array('project_review','project_review_change'))) {
                //��������ģ�飬��˺�?�������Զ�����������˾�������뵥
                $this->autoCreateProjectReview($param);
            }
            
            if ($formStatus == "R" && in_array($param['systemId'],array('sales_contract_review','sales_contract_review_change'))) {
                //��ͬ����ģ�飬��˺�?�������Զ�����������˾��ͬ����
                //$this->autoCreateSalesContractReview($param);
                
                $this->autoCreateSalesContractReview250729($param);
                
            }

            if ($formStatus == "R" && in_array($param['systemId'],array('sample_comfirm'))) {
                //����ȷ��ȫ���������������������������?
                $this->updateSampleRequest($param);
            }

            if ($formStatus == "R" && in_array($param['systemId'],array('sample_request'))) {
                //����ȷ��ȫ���������������������������?
                $this->addPurchasePartNumeber($param);
            }

            if (in_array($param['systemId'],array('sample_comfirm','sample_request'))) {
                //�޸�֪ͨ��Ϣ����
                $tempSql = " select * from ".$processType[$param['systemId']]['related_table']. $updateWhere;
                $res=exequery(TD::conn(), $tempSql);
                if($item = mysql_fetch_assoc($res)){
                    $smsParam[content].='\n'."������Ŀ:$item[project_code],��������:$item[material_name]";
                }
                if(isset($param[sample_no_reason])&&!empty($param[sample_no_reason])){
                    $sql = " insert into inhe_sample_return_report(request_id,opinion_id,reason,create_time)values('$param[request_id]','$param[opinion_id]','$param[sample_no_reason]','$time') ";
                    exequery(TD::conn(), $sql);  
                }
            }
            
        }
        if($updateFlowData===false){
            return false;
        }
        $this->sendSms($smsParam);
    }

    public function updateFormOldData($formId, $systemId)
    {
        if (empty($formId) || empty($systemId)) {
            return '';
        }
        // customer_data  project_review  sales_contract_review  production_order   project_participant  project_number_participant 
        switch ($systemId) {
            case 'customer_data':
                $this->updateChangeVersion('inhe_customer_data',$formId,1);
                // inhe_customer_data inhe_customer_contact inhe_customer_company
                $sql = " select related_id from inhe_customer_data m where form_id = '" . $formId . "' ";
                $res = exequery(TD::conn(), $sql);
                $item = mysql_fetch_assoc($res);
                $relatedId = $item['related_id'];
                if (!empty($relatedId)) {
                    // ����ǰ������ʷ����
                    // $sql = " CREATE TABLE IF NOT EXISTS  inhe_customer_data_temp (LIKE inhe_customer_data)";
                    // exequery(TD::conn(), $sql);
                    // $tempSql = " update inhe_customer_data where form_id = '$relatedId' ";
                    // exequery(TD::conn(), $tempSql);
                    // // ��������

                    // //������ϸ��
                    // $sql = " CREATE TABLE IF NOT EXISTS  " . $tableName . "_temp (LIKE " . $tableName . ")";
                    // exequery(TD::conn(), $sql);
                    // $tempSql = " insert into " . $tableName . "_temp select * from " . $tableName . $where;
                    // exequery(TD::conn(), $tempSql);
                    // $deleteSql = " delete from " . $tableName . $where;
                    // exequery(TD::conn(), $deleteSql);
                }
                break;
            case 'project_review':
                // inhe_project_main inhe_customer_info inhe_project_info inhe_project_detail inhe_bidding_strategy
                $this->updateChangeVersion('inhe_project_main',$formId,1);
                break;
            case 'sales_contract_review':
                // inhe_contract_review inhe_contract_detail inhe_product_detail inhe_contract_settle
                $this->updateContractView($formId);
                break;
            case 'production_order':
                // inhe_production_order inhe_production_detail_one inhe_production_detail_two inhe_production_detail_three inhe_production_detail_non
                $this->updateChangeVersion('inhe_production_order',$formId,1);
                break;
            case 'project_participant':
                // inhe_project_participant inhe_project_participant_item
                break;
            case 'project_number_participant':
                // inhe_number_participant inhe_number_participant_item
                break;
            case 'customer_share':
                $this->updateChangeVersion('inhe_customer_share_main',$formId,1);
                break;
            case 'sample_comfirm':
                
                break;

                // $Sql="select * from inhe_customer_share_main where form_id = '" . $formId . "' ";
                // $res = exequery(TD::conn(), $Sql);
                // $data = mysql_fetch_assoc($res);
                // if(empty($data)){
                //     return false;
                // }
                // $shareParam=array(
                //     'create_user_id'=>$data['user_id'],
                //     'create_time'=>$time,
                //     'form_id'=>$data['form_id'],
                //     'customer_id'=>$data['customer_id'],
                //     'customer_name'=>$data['customer_name'],
                //     'user_id'=>$data['user_ids'],
                // );
                // $this->addCustomerShare($shareParam);
        }
    }
    
    public function addPurchasePartNumeber($param){
        $time = date('Y-m-d H:i:s');
        $tempSql = " select id,supplier_number,user_id,title,user_bu,form_id from inhe_sample_request where form_id='$param[form_id]' ";
        $res=exequery(TD::conn(), $tempSql);
        if($item = mysql_fetch_assoc($res)){
            $sql = " insert into inhe_flow_opinion (type, form_id,title, user_id,write_time, step, work_flow, approve_user,approve_time, status,prev_writer,prev_time, user_bu) 
            values ('sample_request','$item[form_id]','$item[title]','$_SESSION[LOGIN_USER_ID]','$time','800','�����Ϻ�','$item[user_id]','','U','$_SESSION[LOGIN_USER_ID]','$time','$item[user_bu]')";
            exequery(TD::conn(), $sql);
            $REMIND_URL = "/customer_relationship/sample_request/list.php?form_id=" . $item[form_id];
            $SMS_CONTENT = $item[title] . _("�ɹ��ⷢ�������������Ϻţ�");
            send_sms($REMIND_TIME, $_SESSION["LOGIN_USER_ID"], $item[user_id], 0, $SMS_CONTENT, $REMIND_URL);
        }
    }

    public function updateSampleRequest($param){
        $formId=$param[form_id];
        $time = date('Y-m-d H:i:s');
        //�ж�����ȷ�Ͻ��������Ƿ��������������������δ������������������?
        $sql = "update inhe_sample_comfirm set closed = 'Y' where form_id = '$formId' ";
        exequery(TD::conn(), $sql);
        $tempSql = " select count(id) supplier_number,sup_form_id from inhe_sample_comfirm 
        where sup_form_id=(select sup_form_id from inhe_sample_comfirm where form_id='$formId') and closed = 'Y' ";

        $res=exequery(TD::conn(), $tempSql);
        if($item = mysql_fetch_assoc($res)){
            $supplier_number2= (int)$item[supplier_number];
            $sup_form_id=$item[sup_form_id];
        }
        $tempSql = " select id,supplier_number,user_id,title,user_bu,form_id from inhe_sample_request where form_id='$sup_form_id' and close_status='P'";
        $res=exequery(TD::conn(), $tempSql);
        if($item = mysql_fetch_assoc($res)){
            $supplier_number1= (int)$item[supplier_number];
            if($supplier_number2==$supplier_number1){
                $sql = "update inhe_sample_request set close_status = 'Y' and status = 'E' where id = '" . $item[id]. "' ";
                echo $sql;
                exequery(TD::conn(), $sql);
                // $sql = " insert into inhe_flow_opinion (type, form_id,title, user_id,write_time, step, work_flow, approve_user,approve_time, status,prev_writer,prev_time, user_bu) 
                // values ('sample_request','$item[form_id]','$item[title]','$_SESSION[LOGIN_USER_ID]','$time','900','�����Ϻ�','$item[user_id]','','U','$_SESSION[LOGIN_USER_ID]','$time','$item[user_bu]')";
                // exequery(TD::conn(), $sql);
                // $REMIND_URL = "/customer_relationship/sample_request/list.php?form_id=" . $item[form_id];
                // $SMS_CONTENT = $item[title] . _("���̽������������Ϻţ�");
                // send_sms($REMIND_TIME, $_SESSION["LOGIN_USER_ID"], $item[user_id], 0, $SMS_CONTENT, $REMIND_URL);
            }
        }
    }
    //�����޸�����ͳ�Ʊ�
    public function updateProjectReport($param,$time){
        if($param['type']!='project_review'){
            return false;
        }
        $sql="select * from inhe_project_main_report where project_code='$param[project_code]' and user_bu = '$param[user_bu]' ";
        $res = exequery(TD::conn(), $sql);
        if($item2 = mysql_fetch_assoc($res)){
            // $sql = " update inhe_project_main_report set where id=$item2[id]";
            // exequery(TD::conn(), $sql);
        }else{
            // $sql = " insert into inhe_project_main_report (project_code,project_time,region,user_bu,dept_id,business_user_id,business_user_name,create_time)
            //  values ('$param[project_code]','$time','$param[dept_name]','$param[user_bu]','$param[dept_id]','$param[user_id]','$param[user_name]','$time')";
             $sql = " insert into inhe_project_main_report (project_code,user_bu,project_time,create_time)
             values ('$param[project_code]','$param[user_bu]','$time','$time')";
            exequery(TD::conn(), $sql);
        }
    }
    //�޸��Ǽ�
    public function updateProjectData($param,$time)
    {
        $Sql="select * from inhe_project_main where form_id = '" . $param['form_id'] . "' ";
        $res = exequery(TD::conn(), $Sql);
        $item2 = mysql_fetch_assoc($res);
        $this->updateProjectReport($item2,$time);
        $select = " select if_agree, project_star from inhe_flow_opinion where form_id = '" . $param['form_id'] . "' and step=".$param['prev_step']." order by id desc";
        $res = exequery(TD::conn(), $select);
        $item = mysql_fetch_assoc($res);
        if(empty($item['project_star'])){
            return false;
        }
        $model = new DataModel();
        $selectParam = array('is_used' => '1', 'type' => 'project_star',);
        $selectArr = $model->getCommonParam($selectParam);
        $project_star = array();
        foreach ($selectArr['project_star'] as $v) {
            $project_star[$v['paras_value']] = $v;
        }
        
        $whereStr='';
        if(!empty($item2['related_id'])){
            $whereStr=" or form_id='".$item2['related_id']."' ";
        }
        $sql = " update inhe_project_main set if_project='01', project_star = '" . $item['project_star'] .
            "', star_icon='" . $project_star[$item['project_star']]['extra'] . "' where form_id = '" . $param['form_id'] . "' ".$whereStr;
        //echo $sql;exit;
        exequery(TD::conn(), $sql);
        
    }
    //�������۱����ͬ�汾��?
    public function updateContractView($formId){
        $Sql="select related_id from inhe_contract_review where form_id = '" . $formId . "' ";
        $res = exequery(TD::conn(), $Sql);
        $item = mysql_fetch_assoc($res);
        if(empty($item['related_id'])){
            return false;
        }
        $Sql="select * from inhe_contract_review where version>0 and related_id = '" . $item['related_id'] . "' ";
        $res = exequery(TD::conn(), $Sql);
        $count = mysql_num_rows($res);
        $newVersion=$count+1;
        //����ԭ��ͬ�汾�ż�¼
        $updateSql = " update inhe_contract_review set version=".$newVersion." where form_id = '" . $item['related_id'] . "' ";
        exequery(TD::conn(), $updateSql);
        //���µ�ǰ��������?
        $updateSql = " update inhe_contract_review set version=".$newVersion." where form_id = '" . $formId . "' ";
        exequery(TD::conn(), $updateSql);
    }
      //�������۱����ͬ�汾��?
    public function updateChangeVersion($tableName,$formId,$init_count=0){
        $Sql="select related_id from $tableName where form_id = '" . $formId . "' ";
        $res = exequery(TD::conn(), $Sql);
        $item = mysql_fetch_assoc($res);
        if(empty($item['related_id'])){
            return false;
        }
        $Sql="select * from $tableName where status in('R','Y') and related_id = '" . $item['related_id'] . "' ";
        $res = exequery(TD::conn(), $Sql);
        $count = mysql_num_rows($res);
        $newVersion=$count+$init_count;
        //����ԭ��ͬ�汾�ż�¼
        $updateSql = " update $tableName set version=".$newVersion." where form_id = '" . $item['related_id'] . "' ";
        exequery(TD::conn(), $updateSql);
        //���µ�ǰ��������?
        $updateSql = " update $tableName set version=".$newVersion." where form_id = '" . $formId . "' ";
        exequery(TD::conn(), $updateSql);
    }

    /**
     * ��ȡ���̲��輯��
     * �ֹ�˾����
     */
    public function getFlowStep($systemId, $userBu)
    {
        $where = " where m.type = '" . $systemId . "' ";
        if ($userBu != "") {
            $where .= " and m.user_bu = '" . trim($userBu) . "' ";
        }
        $flowSql = " select m.* from inhe_flow_manage m " . $where . " order by step ";
        $res = exequery(TD::conn(), $flowSql);
        while ($item = mysql_fetch_assoc($res)) {
            $flowArr[$item['step']] = $item;
        }
        mysql_free_result($res);

        return $flowArr;
    }

    /**
     * ��ȡ�������?
     */
    public function getApproveData($param)
    {
        $result = array();
        $where = " where 1=1 ";
        if (!array_key_exists('id', $param) || $param['id'] == '') {
            return $result;
        }
        $where .= " and k.form_id = '" . $param['id'] . "' ";

        $sql = " select k.*,u.user_name approverName from inhe_flow_opinion k left join user u on u.user_id = k.approve_user " . $where." order by step,id";
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            //���죬δ���ͬ�����ģ���ȡ�ϴ����
            if($item['status']=='U' && empty($item['if_agree'])){
                $where .= " and status!='U' and k.step= '$item[step]' and k.approve_user= '$item[approve_user]' ";
                $sql = " select k.*,u.user_name approverName from inhe_flow_opinion k left join user u on u.user_id = k.approve_user " . $where." order by id desc limit 1";
                $res2 = exequery(TD::conn(), $sql);
                if($newItem=mysql_fetch_assoc($res2)){
                    $item=$newItem;
                }
                mysql_free_result($res2);
            }
            $result[$item['step']] = $item;
        }
        
        return $result;
    }

    public function findProcessType($type)
    {
        $result = array();
        if (empty($type)) {
            return $result;
        }
        $sql = " select paras_value, paras_desc, extra, related_table, url from inhe_oa_paras where is_used = 1 and type = '$type' order by sort_code ";
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result[$item['paras_value']] = $item;
        }
        mysql_free_result($res);
        return $result;
    }

    /**
     * ��ȡ�����˻��������?
     */
    public function findBackRecord($param)
    {
        $result = array();
        $where = " where 1=1 and if_agree = '02' "; // ����ʾ�˻ص���˼��?
        if (!array_key_exists('id', $param) || $param['id'] == '') {
            return $result;
        }
        $where .= " and k.form_id = '" . $param['id'] . "' ";

        $sql = " select k.* from inhe_flow_opinion k " . $where;
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
        }
        mysql_free_result($res);
        return $result;
    }
    /**
     * ��ȡ�����˻��������?
     */
    public function findPassRecord($param)
    {
        $result = array();
        $where = " where 1=1 and if_agree = '01' and opinion!='' "; // ����ʾ�˻ص���˼��?
        if (!array_key_exists('id', $param) || $param['id'] == '') {
            return $result;
        }
        $where .= " and k.form_id = '" . $param['id'] . "' ";

        $sql = " select k.* from inhe_flow_opinion k " . $where;
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
        }
        mysql_free_result($res);
        return $result;
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

    public function addCustomerShare($param)
    {
        $time=$param[create_time]?$param[create_time]:date("Y-m-d H:i:s");
        $create_user_id=$param[create_user_id]?$param[create_user_id]:$_SESSION[LOGIN_USER_ID]; 
        $userArr = explode(',', $param['user_id']);
        $sql = "insert into inhe_customer_share (customer_id,customer_name,user_ids,user_id,create_time,form_id) values";
        $valSql="";
        foreach ($userArr as $userId) {
            if (empty($userId)) continue;
            $valSql .= "('" . $param['customer_id'] . "','". $param['customer_name'] . "','" . $userId ."','" . $create_user_id ."','" . $time ."','" . $param['form_id'] . "'),";
        }
        if(!empty($valSql)){
            $sql .= rtrim($valSql, ',');
            $res = exequery(TD::conn(), $sql);
        }
        return true;
    }

    function addTempCode($param)
    {
        $mainSql = "insert into inhe_temp_code (temp_code,
        project_name,
        location,
        scope_code,
        scope,
        formal_code,
        remark,
        dept_id,
        dept_name,
        user_id,
        user_name,
        create_time,
        company
        ) values" .
            "('" . $param['temp_code'] . "','" . $param['project_name'] . "', '" . $param['location'] . "', '" . $param['if_project'] . "', '"
            . $param['dept_id'] . "','" . $param['dept_name'] . "','" . $param['user_id'] . "','" . $param['user_name'] . "', '" . $time . "')";
        exequery(TD::conn(), $mainSql);
        $id = mysql_insert_id();

        return $id;
    }

    public function makeProjectCodeLink($param)
    {
        return true;
    }


     //����������������֪ͨ�����ģ�鶭�³����ͨ�������У��Զ�����������˾����������������ͨ���ͱ����?
    //�Զ����ɶ����Ź��򣬹�˾����_ԭ����
    /**
     * Array $param form_id user_bu
     */
    public function autoCreateProductOrderNew($param){
        
        //����ͼ������麣�ͼ��ж�?
        $param['user_bu']=$param['user_bu']=="HKNERGY"?"INHENERGY":$param['user_bu'];
        //�жϵ����Ƿ�Ϊ�ջ��ߵ���Ϊ�Զ����ɵĵ���
        if(empty($param['form_id'])||strpos($param['form_id'],strtolower($param['user_bu']."_"))===0){
            return true;
        }
        $mailSql=" select * from  inhe_production_order  where form_id='$param[form_id]'";
        $res2=exequery(TD::conn(), $mailSql);
        $mainData=array();
        while ($item2 = mysql_fetch_assoc($res2)) {
            $mainData=$item2;
        }
        if(!in_array($mainData['status'],array('R','Y'))){
            return true;
        }
        //������������֪ͨ����Ч��ת
        if(in_array($mainData['user_bu'],array('GRIDINHE'))){
            return true;
        }

         // ��ȡ������?
         $appParam = array(
            'step' => $param['prev_step'],
            'type' => $mainData['type'],
            'user_bu' => $mainData['user_bu']
        );
        $approverArr = $this->getApprover($appParam);
        $approverInfo = $approverArr[0];
        //��һ���Ǳ������������׵�
        if($approverInfo[operate]=="record"){
            return true;
        }

        $productionOrderModel = new ProductionOrderModel();
        $dataModel = new DataModel();
        $selectParam = array(
            'is_used' => '1', // ���ò���
            'type' => 'product_detail_type,crm_process_sort',
        );
        $selectArr = $dataModel->getCommonParam($selectParam);
        foreach($selectArr['product_detail_type'] as $key => $val){
            $typeArr[$val['paras_value']]=$val['user_bu'];
        }
        foreach($selectArr['crm_process_sort'] as $key => $val){
            $oriUserBuArr[$val['paras_value']]=$val['paras_desc'];
        }
        //����������Ʒ
        $attach_types=array();
        $user_bus=array();
        $productSql=" select * from  inhe_production_detail_non  where  form_id='$param[form_id]'";//sub_process=1 and �ֶ����Զ�
        $res=exequery(TD::conn(), $productSql); 
        while ($item = mysql_fetch_assoc($res)) {
            //��Ʒ��������������˾��Ʒ��'INHENERGY','INHEGRID','WITLINK'����ֻ�ǵ�ǰ��˾��Ʒ��������
            if($typeArr[$item['non_type']]==$param['user_bu']||empty($typeArr[$item['non_type']])){
                continue;
            }
            
            $product_non=$item;
            $product_non['form_id']=strtolower($typeArr[$item['non_type']]). "_" .$item['form_id'];
            $product_non['sub_process']='';
            //�Ѿ�������������д��
            $querySql=" select * from  inhe_production_detail_non  where form_id='$product_non[form_id]' and non_type='$product_non[non_type]' and non_serial_number='$product_non[non_serial_number]' ";
            if(!mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                $productionOrderModel->insert_production_non($product_non);//�Զ����ɶ�Ӧ������˾����Ҫ�����Ĳ�Ʒ��Ϣ
            }
            if(!in_array($typeArr[$item['non_type']],$user_bus)){
                array_push($user_bus,$typeArr[$item['non_type']]);
            }
            $attach_types["non_attachment_".$item['non_type'].$item['non_serial_number']]=$typeArr[$item['non_type']];
            
        }
        $INHEORJX=$mainData[sub_user_bu]=='INHE'?'INHE':'JXINHE';
        //�ж�������˾�Ƿ���ڵ����Ʒ�����������׵��������������׽�����||($INHEORJX=='JXINHE'&&in_array($mainData['user_bu'],array('INHE','INHEIAC')))||($INHEORJX=='INHE'&&in_array($mainData['user_bu'],array('JXINHE','INHEJX')))
        if(in_array($mainData['user_bu'],array('INHENERGY','HKNERGY','INHEGRID','WITLINK'))){
            $querySql=" select * from  inhe_production_detail_one  where form_id='$mainData[form_id]' ";
            if(mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
            //�жϻ����ͼ��������Ƿ���ڵ����Ʒ������������Ƿ���Ҫ�ܲ�����֧���׽������ߵ���?
                
                array_push($user_bus,$INHEORJX);
                //TODO �Զ����ӵ�����Ʒ $productionOrderModel->addMeter($product_non);
                $productSql=" select * from  inhe_production_detail_one  where  form_id='$mainData[form_id]'";
                $res=exequery(TD::conn(), $productSql); 
                while ($item = mysql_fetch_assoc($res)) {
                    $product_noe=$item;
                    $product_noe['form_id']=strtolower($INHEORJX. "_" .$item['form_id']);
                    $productionOrderModel->addMeterOne($product_noe);
                }
                $productSql=" select * from  inhe_production_detail_two  where  form_id='$mainData[form_id]'";
                $res=exequery(TD::conn(), $productSql); 
                while ($item = mysql_fetch_assoc($res)) {
                    $product_two=$item;
                    $product_two['form_id']=strtolower($INHEORJX. "_" .$item['form_id']);
                    $productionOrderModel->addMeterTwo($product_two);
                }
                $productSql=" select * from  inhe_production_detail_three  where  form_id='$mainData[form_id]'";
                $res=exequery(TD::conn(), $productSql); 
                while ($item = mysql_fetch_assoc($res)) {
                    $product_three=$item;
                    $product_three['form_id']=strtolower($INHEORJX. "_" .$item['form_id']);
                    $productionOrderModel->addMeterThree($product_three);
                    $attach_types["attachment".$item['serial_number']]=$INHEORJX;
                    $attach_types["related_attachment".$item['serial_number']]=$INHEORJX;
                    
                }
            }
        }
        
        if(empty($user_bus)){
            return true;
        }
        //��������
        //����������˾������Ʒ�����ִ��?
        $user_bus=array_unique($user_bus);
        $processType = $this->findProcessType(self::TYPE);
        
        $sql = " select * from inhe_flow_manage where type='$mainData[type]' and auto_approver='1' group by user_bu";
        $res = exequery(TD::conn(), $sql);
        $approverInfo=array();
        while ($item = mysql_fetch_assoc($res)) {
            $approverInfo[$item['user_bu']] = $item;
        }
        foreach($user_bus as $user_bu){
            $main=$mainData;
            $main['status']=$approverInfo[$user_bu]['operate']=='approve'?'P':'R';
            $main['step']=$approverInfo[$user_bu]['step'];
            $main['user_bu']=$user_bu;
            $main['form_id']=strtolower($user_bu)."_".$main['form_id'];
            $main['related_id']=empty($main['related_id'])?'':strtolower($user_bu)."_".$main['related_id'];
            $main['title']=$main['form_id'].'/'.$main['project_num'].'/'.$processType[$main['type']]['paras_desc'];
            $main['meter_number']=in_array($user_bu,array("INHE","JXINHE"))?$mainData['meter_number']:0;
            //�ͻ���Ϣ��Ϊ��ת���������幫˾
            $main['customer_name']=$oriUserBuArr[$mainData['user_bu']];
            $main['contract_party']=$oriUserBuArr[$mainData['user_bu']];
            //�Ѿ�������������д��
            $querySql=" select * from  inhe_production_order  where form_id='".$main['form_id']."'";
            if(mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                continue;
            }
            $productionOrderModel->insert_main($main);//�Զ����ɶ�Ӧ������˾�Ĵ���˲�������?
            
        }
        //��������
        $attachSql=" select * from  inhe_attachment  where relatedId='$mainData[form_id]'";
        $res3=exequery(TD::conn(), $attachSql); 
        while ($item = mysql_fetch_assoc($res3)) {
            if(empty($attach_types[$item['type']])){
                continue;    
            }
            $attach=$item;
            $attach['filename']=strtolower($attach_types[$item['type']]) .'-'.$item['filename'];
            $attach['path']="D:/MYOA/webroot/attachment/reportshop/attachment/". $attach['filename'];
            $attach['relatedId']=strtolower($attach_types[$item['type']]) .'_'.$item['relatedId'];
             //�Ѿ�������������д��
             $querySql=" select * from  inhe_attachment  where relatedId='$attach[relatedId]' and filename='$attach[filename]'";
             if(mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                 continue;
             }
            copy($item['path'],$attach['path']);
            $productionOrderModel->insert_attach($attach);//�Զ����ɶ�Ӧ��˾��Ʒ�ĸ���
        }
        //��������
        $opinionSql=" select * from  inhe_flow_opinion a where (a.common_opinion  like '%,%' or a.step='1') and status='H' and a.form_id='$mainData[form_id]' and a.type='$mainData[type]'";
        $res4=exequery(TD::conn(), $opinionSql); 
        $stepCount = mysql_num_rows($res4);
        while ($item = mysql_fetch_assoc($res4)) {
            // if(empty($attach_types[$item['type']])){
            //     continue;    
            // }
            $userBuStep=explode(",",$item[common_opinion]);
            foreach($user_bus as $user_bu){
               if(!in_array($user_bu,$userBuStep)){
                   continue;
               }
               $opinion=$item;
                $opinion['step']=0-$stepCount;
                $opinion['user_bu']=$user_bu;
                $opinion['form_id']=strtolower($user_bu)."_".$opinion['form_id'];
                $opinion['title']=str_replace($mainData['form_id'],$opinion['form_id'],$opinion['title']);
                $approverInfo[$user_bu]['prev_writer']=$opinion['approve_user'];
                $approverInfo[$user_bu]['prev_time']=$opinion['approve_time'];
                //�Ѿ�������������д��
                $querySql=" select * from  inhe_flow_opinion  where form_id='$opinion[form_id]' and approve_time='$opinion[approve_time]'";
                if(mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                    continue;
                }
                $productionOrderModel->insert_opinion($opinion);//�Զ����ɶ�Ӧ������˾������˲������̣�����Ϊ�գ����ⲽ���ͻ
                
            }
            $stepCount=$stepCount-1;
        }
        $mainData[title_number]=$mainData[project_num];
        $this->autoInsertOpinion($user_bus,$approverInfo,$mainData,$processType);
        // foreach($user_bus as $user_bu){
        //     if(empty($approverInfo[$user_bu])){
        //         continue;
        //     }
        //     $opinion=array();
        //     $approve_user=explode(",",$approverInfo[$user_bu]['approver']);
        //     $opinion = array(
        //         'type' => $approverInfo[$user_bu]['type'],
        //         'step' => $approverInfo[$user_bu]['step'],
        //         'work_flow' => $approverInfo[$user_bu]['role'],
        //         'common_opinion' => $approverInfo[$user_bu]['common_opinion'],
        //         'approve_user' => $approve_user[0],
        //         'status' => 'U',
        //         'prev_writer' => $approverInfo[$user_bu]['prev_writer'],
        //         'prev_time' => $approverInfo[$user_bu]['prev_time'],
        //         'user_bu' => $user_bu
        //     );
        //     $opinion['form_id']=strtolower($user_bu)."_".$mainData['form_id'];
        //     $opinion['title']=$opinion['form_id'].'/'.$mainData['project_num'].'/'.$processType[$mainData['type']]['paras_desc'];
        //     //�Ѿ�������������д��
        //     $querySql=" select * from  inhe_flow_opinion  where form_id='$opinion[form_id]' and approve_time='$opinion[approve_time]'";
        //     if(mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
        //         continue;
        //     }
        //     $productionOrderModel->insert_opinion($opinion);//�Զ����ɶ�Ӧ������˾����������
        //     $mainUrl = str_replace('_change', '', $mainData['type']);
        //     $mainUrl = str_replace('trial_', '', $mainUrl);
        //     $smsParam = array(
        //         'url' => "/customer_relationship/" . $mainUrl . "/detail.php?id=" . $opinion['form_id'] . "&operate=approve&step=" . $opinion['step'],
        //         'content' => '���ó���Զ����? . $processType[$mainData['type']]['paras_desc'] . '���̣��뼰ʱ��������',
        //         'approver' => $opinion['approve_user'],
        //         'smsFromUserId'=>$mainData['user_id'],
        //     );
        //     $this->sendSms($smsParam);
        // }   
    }

    public function autoCreateShippingNotice($param){
        //����ͼ������麣�ͼ��ж�RETAILNERGY
        $param['user_bu']=$param['user_bu']=="HKNERGY"||$param['user_bu']=="RETAILNERGY"?"INHENERGY":$param['user_bu'];
        //�жϵ����Ƿ�Ϊ�ջ��ߵ���Ϊ�Զ����ɵĵ���
        if(empty($param['form_id'])||strpos($param['form_id'],strtolower($param['user_bu']."_"))===0){
            return true;
        }
        $mailSql=" select * from  inhe_shipping_notice  where form_id='$param[form_id]'";
        $res2=exequery(TD::conn(), $mailSql);
        $mainData=array();
        while ($item2 = mysql_fetch_assoc($res2)) {
            $mainData=$item2;
        }
        if(!in_array($mainData['status'],array('R','Y'))){
            return true;
        }

         // ��ȡ������?
         $appParam = array(
            'step' => $param['prev_step'],
            'type' => $mainData['type'],
            'user_bu' => $mainData['user_bu']
        );
        $approverArr = $this->getApprover($appParam);
        $approverInfo = $approverArr[0];
        //��һ���Ǳ������������׵�
        if($approverInfo[operate]=="record"){
            return true;
        }

        $shippingNoticeModel = new ShippingNoticeModel();
        $dataModel = new DataModel();
        $selectParam = array(
            'is_used' => '1', // ���ò���
            'type' => 'product_detail_type,crm_process_sort',
        );
        $selectArr = $dataModel->getCommonParam($selectParam);
        foreach($selectArr['product_detail_type'] as $key => $val){
            $typeArr[strtolower($val['paras_value'])]=$val['user_bu'];
        }
        foreach($selectArr['crm_process_sort'] as $key => $val){
            $oriUserBuArr[$val['paras_value']]=$val['paras_desc'];
        }
        //����������Ʒ
        $attach_types=array();
        $user_bus=array();

        $productSql=" select * from  inhe_shipping_detail  where  form_id='$param[form_id]'";//sub_process=1 and �ֶ����Զ�
        $res=exequery(TD::conn(), $productSql); 
        while ($item = mysql_fetch_assoc($res)) {
            //��Ʒ��������������˾��Ʒ��ֻ�ǵ�ǰ��˾��Ʒ�������� //�������������ͽ��������׵�,������˾�׵�������
            if($typeArr[$item['type']]==$param['user_bu']||empty($typeArr[$item['type']])||($item['type']=="electric"&&in_array($param['user_bu'],array("JXINHE","INHEJX","INHE","HKINHE","INHEIAC","INHEIMC","IMCINHE")))){
                continue;
            }
            
            $product_non=$item;
            $product_non['form_id']=strtolower($typeArr[$item['type']]). "_" .$item['form_id'];
            //�Ѿ�������������д��
            $querySql=" select * from  inhe_shipping_detail  where form_id='$product_non[form_id]' and type='$product_non[type]' and serial_number='$product_non[serial_number]' ";
            if(!mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                $shippingNoticeModel->insert_shipping_detail($product_non);//�Զ����ɶ�Ӧ������˾����Ҫ�����Ĳ�Ʒ��Ϣ
            }
            if(!in_array($typeArr[$item['type']],$user_bus)){
                array_push($user_bus,$typeArr[$item['type']]);
            }
            
        }
        
        if(empty($user_bus)){
            return true;
        }
        //��������
        //����������˾������Ʒ�����ִ��?
        $user_bus=array_unique($user_bus);
        $processType = $this->findProcessType(self::TYPE);
        
        $sql = " select * from inhe_flow_manage where type='$mainData[type]' and auto_approver='1' group by user_bu";
        $res = exequery(TD::conn(), $sql);
        $approverInfo=array();
        while ($item = mysql_fetch_assoc($res)) {
            $approverInfo[$item['user_bu']] = $item;
        }
        foreach($user_bus as $user_bu){
            $main=$mainData;
            $main['status']=$approverInfo[$user_bu]['operate']=='approve'?'P':'R';
            $main['step']=$approverInfo[$user_bu]['step'];
            $main['user_bu']=$user_bu;
            $main['form_id']=strtolower($user_bu)."_".$main['form_id'];
            $main['related_id']=empty($main['related_id'])?'':strtolower($user_bu)."_".$main['related_id'];
            $main['title']=$main['form_id'].'/'.$main['project_number'].'/'.$processType[$main['type']]['paras_desc'];
            //�ͻ���Ϣ��Ϊ��ת���������幫˾
            $main['customer_name']=$oriUserBuArr[$mainData['user_bu']];
            $main['contract_party']=$oriUserBuArr[$mainData['user_bu']];
            //�Ѿ�������������д��
            $querySql=" select * from  inhe_shipping_notice  where form_id='".$main['form_id']."'";
            if(mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                continue;
            }
            $shippingNoticeModel->insert_main($main);//�Զ����ɶ�Ӧ������˾�Ĵ���˲�������?
            
        }

         //��������
         $opinionSql=" select * from  inhe_flow_opinion a where (a.common_opinion  like '%,%' or a.step='1') and status='H' and a.form_id='$mainData[form_id]' and a.type='$mainData[type]'";
         $res4=exequery(TD::conn(), $opinionSql); 
         $stepCount = mysql_num_rows($res4);
         while ($item = mysql_fetch_assoc($res4)) {
             // if(empty($attach_types[$item['type']])){
             //     continue;    
             // }
             $userBuStep=explode(",",$item[common_opinion]);
             foreach($user_bus as $user_bu){
                if(!in_array($user_bu,$userBuStep)){
                    continue;
                }
                $opinion=$item;
                 $opinion['step']=0-$stepCount;
                 $opinion['user_bu']=$user_bu;
                 $opinion['form_id']=strtolower($user_bu)."_".$opinion['form_id'];
                 $opinion['title']=str_replace($mainData['form_id'],$opinion['form_id'],$opinion['title']);
                 $approverInfo[$user_bu]['prev_writer']=$opinion['approve_user'];
                 $approverInfo[$user_bu]['prev_time']=$opinion['approve_time'];
                 //�Ѿ�������������д��
                 $querySql=" select * from  inhe_flow_opinion  where form_id='$opinion[form_id]' and approve_time='$opinion[approve_time]'";
                 if(mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                     continue;
                 }
                 $shippingNoticeModel->insert_opinion($opinion);//�Զ����ɶ�Ӧ������˾������˲������̣�����Ϊ�գ����ⲽ���ͻ
                 
             }
             $stepCount=$stepCount-1;
         }
         $mainData[title_number]=$mainData[project_number];
         $this->autoInsertOpinion($user_bus,$approverInfo,$mainData,$processType);
        //  foreach($user_bus as $user_bu){
        //      if(empty($approverInfo[$user_bu])){
        //          continue;
        //      }
        //      $opinion=array();
        //      $approve_user=explode(",",$approverInfo[$user_bu]['approver']);
        //      $opinion = array(
        //          'type' => $approverInfo[$user_bu]['type'],
        //          'step' => $approverInfo[$user_bu]['step'],
        //          'work_flow' => $approverInfo[$user_bu]['role'],
        //          'common_opinion' => $approverInfo[$user_bu]['common_opinion'],
        //          'approve_user' => $approve_user[0],
        //          'status' => 'U',
        //          'prev_writer' => $approverInfo[$user_bu]['prev_writer'],
        //          'prev_time' => $approverInfo[$user_bu]['prev_time'],
        //          'user_bu' => $user_bu
        //      );
        //      $opinion['form_id']=strtolower($user_bu)."_".$mainData['form_id'];
        //      $opinion['title']=$opinion['form_id'].'/'.$mainData['project_number'].'/'.$processType[$mainData['type']]['paras_desc'];
        //      //�Ѿ�������������д��
        //      $querySql=" select * from  inhe_flow_opinion  where form_id='$opinion[form_id]' and approve_time='$opinion[approve_time]'";
        //      if(mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
        //          continue;
        //      }
        //      $shippingNoticeModel->insert_opinion($opinion);//�Զ����ɶ�Ӧ������˾����������
        //      $mainUrl = str_replace('_change', '', $mainData['type']);
        //      $mainUrl = str_replace('trial_', '', $mainUrl);
        //      $smsParam = array(
        //          'url' => "/customer_relationship/" . $mainUrl . "/detail.php?id=" . $opinion['form_id'] . "&operate=approve&step=" . $opinion['step'],
        //          'content' => '���ó���Զ����? . $processType[$mainData['type']]['paras_desc'] . '���̣��뼰ʱ��������',
        //          'approver' => $opinion['approve_user'],
        //          'smsFromUserId'=>$mainData['user_id'],
        //      );
        //      $this->sendSms($smsParam);
        //  }
    }

    //�Զ��׵����ۺ�ͬ����
    public function autoCreateSalesContractReview($param){
        //����ͼ������麣�ͼ��ж�RETAILNERGY
        $param['user_bu']=$param['user_bu']=="HKNERGY"||$param['user_bu']=="RETAILNERGY"?"INHENERGY":$param['user_bu'];
        //�жϵ����Ƿ�Ϊ�ջ��ߵ���Ϊ�Զ����ɵĵ���
        if(empty($param['form_id'])||strpos($param['form_id'],strtolower($param['user_bu']."_"))===0){
            return true;
        }
        $mailSql=" select * from  inhe_contract_review  where form_id='$param[form_id]'";
        $res2=exequery(TD::conn(), $mailSql);
        $mainData=array();
        while ($item2 = mysql_fetch_assoc($res2)) {
            $mainData=$item2;
        }
        if(!in_array($mainData['status'],array('R','Y'))){
            return true;
        }
        $mailSql=" select * from  inhe_contract_detail  where form_id='$param[form_id]'";
        $res2=exequery(TD::conn(), $mailSql);
        $mainDetailData=array();
        if ($item2 = mysql_fetch_assoc($res2)) {
            $mainDetailData=$item2;
        }
         // ��ȡ������?
         $appParam = array(
            'step' => $param['prev_step'],
            'type' => $mainData['type'],
            'user_bu' => $mainData['user_bu']
        );
        $approverArr = $this->getApprover($appParam);
        $approverInfo = $approverArr[0];
        //��һ���Ǳ������������׵�
        if($approverInfo[operate]=="record"){
            return true;
        }

        $salesContractReviewModel = new SalesContractReviewModel();
        $dataModel = new DataModel();
        $selectParam = array(
            'is_used' => '1', // ���ò���
            'type' => 'product_detail_type,crm_process_sort',
        );
        $selectArr = $dataModel->getCommonParam($selectParam);
        foreach($selectArr['product_detail_type'] as $key => $val){
            $typeArr[strtolower($val['paras_value'])]=$val['user_bu'];
        }
        foreach($selectArr['crm_process_sort'] as $key => $val){
            $oriUserBuArr[$val['paras_value']]=$val['paras_desc'];
        }
        //����������Ʒ
        $attach_types=array();
        $user_bus=array();

        $productSql=" select * from  inhe_product_detail  where  form_id='$param[form_id]'";//sub_process=1 and �ֶ����Զ�
        $res=exequery(TD::conn(), $productSql); 
        while ($item = mysql_fetch_assoc($res)) {
            //��Ʒ��������������˾��Ʒ��ֻ�ǵ�ǰ��˾��Ʒ�������� //�������������ͽ��������׵�,������˾�׵�������
            if($typeArr[$item['type']]==$param['user_bu']||empty($typeArr[$item['type']])||($item['type']=="electric"&&in_array($param['user_bu'],array("JXINHE","INHEJX","INHE","HKINHE","INHEIAC","INHEIMC","IMCINHE")))){
                continue;
            }
            $product_non=$item;
            $product_non['form_id']=strtolower($typeArr[$item['type']]). "_" .$item['form_id'];
            //�Ѿ�������������д��
            $querySql=" select * from  inhe_product_detail  where form_id='$product_non[form_id]' and type='$product_non[type]' and serial_number='$product_non[serial_number]' ";
            if(!mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                $salesContractReviewModel->insert_product_detail($product_non);//�Զ����ɶ�Ӧ������˾����Ҫ�����Ĳ�Ʒ��Ϣ
            }
            if(!in_array($typeArr[$item['type']],$user_bus)){
                array_push($user_bus,$typeArr[$item['type']]);
            }
            
        }
        
        if(empty($user_bus)){
            return true;
        }
        //��������
        //����������˾������Ʒ�����ִ��?
        $user_bus=array_unique($user_bus);
        $processType = $this->findProcessType(self::TYPE);
        //�Զ��׵�����Ӧ��˾��Ҫ���յĽڵ�
        $sql = " select * from inhe_flow_manage where type='$mainData[type]' and auto_approver='1' group by user_bu";
        $res = exequery(TD::conn(), $sql);
        $approverInfo=array();
        while ($item = mysql_fetch_assoc($res)) {
            $approverInfo[$item['user_bu']] = $item;
        }
        foreach($user_bus as $user_bu){
            $main=$mainData;
            $main['status']=$approverInfo[$user_bu]['operate']=='approve'?'P':'R';
            $main['step']=$approverInfo[$user_bu]['step'];
            $main['user_bu']=$user_bu;
            $main['form_id']=strtolower($user_bu)."_".$main['form_id'];
            $main['related_id']=empty($main['related_id'])?'':strtolower($user_bu)."_".$main['related_id'];
            $main['title']=$main['form_id'].'/'.$main['project_number'].'/'.$processType[$main['type']]['paras_desc'];
            //�ͻ���Ϣ��Ϊ��ת���������幫˾
            $main['customer_name']=$oriUserBuArr[$mainData['user_bu']];
            $main['contract_party']=$oriUserBuArr[$mainData['user_bu']];
            //�Ѿ�������������д��
            $querySql=" select * from  inhe_contract_review  where form_id='".$main['form_id']."'";
            if(mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                continue;
            }
            $salesContractReviewModel->insert_main($main);//�Զ����ɶ�Ӧ������˾�Ĵ���˲�������?
            $mainDetail=$mainDetailData;
            $mainDetail[form_id]=$main['form_id'];
            $salesContractReviewModel->insert_main_detail($mainDetail);//�Զ����ɶ�Ӧ������˾�Ĵ���˲�������?
             //����������Ϣ
            $attachSql=" select * from  inhe_contract_settle  where form_id='$mainData[form_id]'";
            $res4=exequery(TD::conn(), $attachSql); 
            while ($item = mysql_fetch_assoc($res4)) {
                $contract_settle=$item;
                $contract_settle[form_id]=$main['form_id'];
                $salesContractReviewModel->insert_contract_settle($contract_settle);//���ɽ�����Ϣ
            }
            //��������
            $attachSql=" select * from  inhe_attachment  where relatedId='$mainData[form_id]'";
            $res3=exequery(TD::conn(), $attachSql); 
            while ($item = mysql_fetch_assoc($res3)) {
                $attach=$item;
                $attach['filename']=strtolower($main['user_bu']) .'-'.$item['filename'];
                $attach['path']="D:/MYOA/webroot/attachment/reportshop/attachment/". $attach['filename'];
                $attach['relatedId']=strtolower($main['user_bu']) .'_'.$item['relatedId'];
                //�Ѿ�������������д��
                // $querySql=" select * from  inhe_attachment  where relatedId='$attach[relatedId]' and filename='$attach[filename]'";
                // if(mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                //     continue;
                // }
                copy($item['path'],$attach['path']);
                $salesContractReviewModel->insert_attach($attach);//�Զ����ɶ�Ӧ��˾��Ʒ�ĸ���
            }
        }
       
        
         //��������
         $opinionSql=" select * from  inhe_flow_opinion a where (a.common_opinion  like '%,%' or a.step='1') and status='H' and a.form_id='$mainData[form_id]' and a.type='$mainData[type]'";
        //  $opinionSql=" select a.*,d.user_bu dept_user_bu from  inhe_flow_opinion a 
        //  left join user u on a.approve_user=u.user_id 
        //  left join department d on d.dept_id=u.dept_id 
        //  where a.common_opinion=1 and status='H' and a.form_id='$mainData[form_id]' and a.type='$mainData[type]'";
         $res4=exequery(TD::conn(), $opinionSql); 
         $stepCount = mysql_num_rows($res4);
         while ($item = mysql_fetch_assoc($res4)) {
             // if(empty($attach_types[$item['type']])){
             //     continue;    
             // }
             $userBuStep=explode(",",$item[common_opinion]);
             foreach($user_bus as $user_bu){
                if(!in_array($user_bu,$userBuStep)&&$item[step]!=1){
                    continue;
                }
                $opinion=$item;
                 $opinion['step']=$item[step]==1?1:0-$stepCount;
                 $opinion['user_bu']=$user_bu;
                 $opinion['form_id']=strtolower($user_bu)."_".$opinion['form_id'];
                 $opinion['title']=str_replace($mainData['form_id'],$opinion['form_id'],$opinion['title']);
                 $approverInfo[$user_bu]['prev_writer']=$opinion['approve_user'];
                 $approverInfo[$user_bu]['prev_time']=$opinion['approve_time'];
                 //�Ѿ�������������д��
                 $querySql=" select * from  inhe_flow_opinion  where form_id='$opinion[form_id]' and approve_time='$opinion[approve_time]'";
                 if(mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                     continue;
                 }
                 $salesContractReviewModel->insert_opinion($opinion);//�Զ����ɶ�Ӧ������˾������˲������̣�����Ϊ�գ����ⲽ���ͻ
                 
             }
             $stepCount=$stepCount-1;
         }
         $mainData[title_number]=$mainData[project_number];
         $this->autoInsertOpinion($user_bus,$approverInfo,$mainData,$processType);
    }


    //�Զ��׵����������?
    public function autoCreateProjectReview($param){
        //����ͼ������麣�ͼ��ж�RETAILNERGY
        $param['user_bu']=$param['user_bu']=="HKNERGY"||$param['user_bu']=="RETAILNERGY"?"INHENERGY":$param['user_bu'];
        //�жϵ����Ƿ�Ϊ�ջ��ߵ���Ϊ�Զ����ɵĵ���
        if(empty($param['form_id'])||strpos($param['form_id'],strtolower($param['user_bu']."_"))===0){
            return true;
        }
        $mailSql=" select * from  inhe_project_main  where form_id='$param[form_id]'";
        $res2=exequery(TD::conn(), $mailSql);
        $mainData=array();
        while ($item2 = mysql_fetch_assoc($res2)) {
            $mainData=$item2;
        }
        if(!in_array($mainData['status'],array('R','Y'))){
            return true;
        }
        $mailSql=" select * from  inhe_project_info  where form_id='$param[form_id]'";
        $res2=exequery(TD::conn(), $mailSql);
        $mainDetailData=array();
        if ($item2 = mysql_fetch_assoc($res2)) {
            $mainDetailData=$item2;
        }
        $mailSql=" select * from  inhe_bidding_strategy  where form_id='$param[form_id]'";
        $res2=exequery(TD::conn(), $mailSql);
        $biddingStrategyData=array();
        if ($item2 = mysql_fetch_assoc($res2)) {
            $biddingStrategyData=$item2;
        }
         // ��ȡ������?
         $appParam = array(
            'step' => $param['prev_step'],
            'type' => $mainData['type'],
            'user_bu' => $mainData['user_bu']
        );
        $approverArr = $this->getApprover($appParam);
        $approverInfo = $approverArr[0];
        //��һ���Ǳ������������׵�
        if($approverInfo[operate]=="record"){
            return true;
        }
        $processType = $this->findProcessType(self::TYPE);
        $projectReviewModel = new ProjectReviewModel();
        $dataModel = new DataModel();
        $selectParam = array(
            'is_used' => '1', // ���ò���
            'type' => 'product_detail_type,crm_process_sort,auto_flow_user',
        );
        $selectArr = $dataModel->getCommonParam($selectParam);
        foreach($selectArr['auto_flow_user'] as $key => $val){
            $auto_flow_user[$val['user_bu']]=$val;
        }
        foreach($selectArr['product_detail_type'] as $key => $val){
            $typeArr[$val['paras_value']]=$val['user_bu'];
        }
        $typeArr['Electric']='INHE';//���Ĭ���׵����ӵ���?
        foreach($selectArr['crm_process_sort'] as $key => $val){
            $oriUserBuArr[$val['paras_value']]=$val['paras_desc'];
        }
        
        //����������Ʒ
        $attach_types=array();
        $user_bus=array();
        $user_bus_serial_number=array();
        $productSql=" select * from  inhe_project_detail  where  form_id='$param[form_id]'";//sub_process=1 and �ֶ����Զ�
        $res=exequery(TD::conn(), $productSql); 
        
        while ($item = mysql_fetch_assoc($res)) {
            //��Ʒ��������������˾��Ʒ��ֻ�ǵ�ǰ��˾��Ʒ�������� //�������������ͽ��������׵�,������˾�׵�������
            //$item['type']=strtolower($item['type']);
            //�ɶ��ߵ������⴦��
            if($param['user_bu']=="INHE"&&$param['create_user_bu']=="CDINHE"){
                $product_non=$item;
                $product_non['form_id']=strtolower($param['create_user_bu']). "_" .$item['form_id'];
                //�Ѿ�������������д��
                $querySql=" select * from  inhe_project_detail  where form_id='$product_non[form_id]' and type='$product_non[type]' and serial_number='$product_non[serial_number]' ";
                if(!mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                    $projectReviewModel->insert_project_detail($product_non);//�Զ����ɶ�Ӧ������˾����Ҫ�����Ĳ�Ʒ��Ϣ
                }
                
            }
            if($typeArr[$item['type']]==$param['user_bu']||empty($typeArr[$item['type']])||($item['type']=="Electric"&&in_array($param['user_bu'],array("JXINHE","INHEJX","INHE","HKINHE","INHEIAC","INHEIMC","IMCINHE")))){
                    continue;
            }
            //�ɶ��ߵ��������ø��ݲ�Ʒ����һ��������Ϊ�Ѿ�����һ�������ĵ�
            if(($param['user_bu']=="INHE"&&$param['create_user_bu']=="CDINHE"&&$item['type']!="Cdinhe")||$param['user_bu']!="INHE"||$param['create_user_bu']!="CDINHE"){
                
                $product_non=$item;
                $product_non['form_id']=strtolower($typeArr[$item['type']]). "_" .$item['form_id'];
                $user_bus_serial_number[$item['type']]=$user_bus_serial_number[$item['type']]+1;
                $product_non['serial_number']=$user_bus_serial_number[$item['type']];
                //�Ѿ�������������д��
                $querySql=" select * from  inhe_project_detail  where form_id='$product_non[form_id]' and type='$product_non[type]' and serial_number='".$user_bus_serial_number[$item['type']]."' ";
                if(!mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                    $projectReviewModel->insert_project_detail($product_non);//�Զ����ɶ�Ӧ������˾����Ҫ�����Ĳ�Ʒ��Ϣ
                    //��������
                    $attachSql=" select * from  inhe_attachment  where relatedId='$mainData[form_id]' and type = 'project_detail_attach".$item[serial_number]."' ";
                    $res2=exequery(TD::conn(), $attachSql);
                    while ($item2 = mysql_fetch_assoc($res2)) {
                        $attach=$item2;
                        $attach['filename']=strtolower($typeArr[$item['type']]) .'-'.$item2['filename'];
                        
                        $attach['path']=MYOA_ROOT_PATH . "attachment/reportshop/attachment/".$attach['filename'];
                        $attach['relatedId']=strtolower($typeArr[$item['type']]) .'_'.$item2['relatedId'];
                        $attach['type']="project_detail_attach".$user_bus_serial_number[$item['type']];
                        //�Ѿ�������������д��
                        $querySql=" select * from  inhe_attachment  where relatedId='$attach[relatedId]' and filename='$attach[filename]'";
                        if(mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                            continue;
                        }
                        copy($item2['path'],$attach['path']);
                        $projectReviewModel->insert_attach($attach);//�Զ����ɶ�Ӧ��˾��Ʒ�ĸ���
                    }
                }
            }
            
            //�ɶ��ߵ������⴦��
            if($param['user_bu']=="INHE"&&$param['create_user_bu']=="CDINHE"&&$item['type']=="Cdinhe"){
                continue;
            }
            if(!in_array($typeArr[$item['type']],$user_bus)){
                array_push($user_bus,$typeArr[$item['type']]);
            }
            
        }
       //�ɶ��ߵ������⴦��
       if($param['user_bu']=="INHE"&&$param['create_user_bu']=="CDINHE"){
            //�Ѿ�������������д��
            $main=$mainData;
            $main['status']='Y';
            $main['user_bu']=$param['create_user_bu'];
            $main['form_id']=strtolower($param['create_user_bu'])."_".$main['form_id'];
            $main['from_id']=$mainData['form_id'];
            $querySql=" select * from  inhe_project_main  where form_id='".$main['form_id']."'";
            
            if(!mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                $main['related_id']=empty($main['related_id'])?'':strtolower($param['create_user_bu'])."_".$main['related_id'];
                $main['title']=$main['form_id'].'/'.$main['project_code'].'/'.$processType[$main['type']]['paras_desc'];
                //�ͻ���Ϣ��Ϊ��ת���������幫˾
                $main['customer_name']=$oriUserBuArr[$mainData['user_bu']];
                $main['contract_party']=$oriUserBuArr[$mainData['user_bu']];
                $addClientInfo=$main;
                $addClientInfo[type]='3';
                $addClientInfo[customer_name]=$oriUserBuArr[$mainData['user_bu']];
                $addClientInfo[name]='2555';
                $projectReviewModel->addClientInfo($addClientInfo);
                $projectReviewModel->insert_main($main);//�Զ����ɶ�Ӧ������˾�Ĵ���˲�������?
                $mainDetail=$mainDetailData;
                $mainDetail[form_id]=$main['form_id'];
                $projectReviewModel->insert_main_detail($mainDetail);
                $biddingStrategy=array();
                $biddingStrategy=$biddingStrategyData;
                $biddingStrategy[form_id]=$main['form_id'];
                $projectReviewModel->insert_bidding_strategy($biddingStrategy);
                 //��������
                 $attachSql=" select * from  inhe_attachment  where relatedId='$mainData[form_id]' ";
                 $res2=exequery(TD::conn(), $attachSql);
                 while ($item2 = mysql_fetch_assoc($res2)) {
                     $attach=$item2;
                     $attach['filename']=strtolower($param['create_user_bu']) .'-'.$item2['filename'];
                     $attach['path']=MYOA_ROOT_PATH . "attachment/reportshop/attachment/". $attach['filename'];
                     $attach['relatedId']=strtolower($param['create_user_bu']) .'_'.$item2['relatedId'];
                     //�Ѿ�������������д��
                     $querySql=" select * from  inhe_attachment  where relatedId='$attach[relatedId]' and filename='$attach[filename]'";
                     if(mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                         continue;
                     }
                     copy($item2['path'],$attach['path']);
                     $projectReviewModel->insert_attach($attach);//�Զ����ɶ�Ӧ��˾��Ʒ�ĸ���
                 }
            }
            //��������
            $prev_writer="";
            $prev_time="";
            $opinionSql="select a.*,b.role,b.user_bu new_user_bu,b.step new_step from inhe_flow_manage b left join inhe_flow_opinion a 
            on a.type=b.type and b.user_bu='$param[create_user_bu]' and (find_in_set(a.approve_user,b.approver) or (b.step=1 and a.step=1))  where a.form_id='$mainData[form_id]' 
            and b.type='$mainData[type]' and b.user_bu='$param[create_user_bu]' and a.type='$mainData[type]' and a.status='H' 
            and a.id =(select max(id) from inhe_flow_opinion where form_id='$mainData[form_id]' and type='$mainData[type]' and a.status='H' and step=a.step)
            order by b.step";
            $res4=exequery(TD::conn(), $opinionSql); 
            $stepCount = mysql_num_rows($res4);
            $start_time = date('Y-m-d H:i:s');
            while ($item = mysql_fetch_assoc($res4)) {
                    $start_time = date("Y-m-d H:i:s",strtotime($start_time)+60*10);
                    $opinion=$item;
                    $opinion['step']=$opinion['new_step'];
                    $opinion['user_bu']=$opinion['new_user_bu'];
                    $opinion['work_flow']=$opinion['role'];
                    $opinion['form_id']=strtolower($opinion['new_user_bu'])."_".$opinion['form_id'];
                    $opinion['title']=str_replace($mainData['form_id'],$opinion['form_id'],$opinion['title']);
                    $opinion['prev_writer']=$prev_writer;
                    $opinion['prev_time']=$prev_time;
                    $opinion['approve_time']=$start_time;
                    $prev_writer=$opinion['approve_user'];
                    $prev_time=$opinion['approve_time'];
                    //�Ѿ�������������д��
                    $querySql=" select * from  inhe_flow_opinion  where form_id='$opinion[form_id]' and step='$opinion[step]'";
                    if(mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                        continue;
                    }
                    $projectReviewModel->insert_opinion($opinion);//�Զ����ɶ�Ӧ������˾������˲�������?
            }
            
        }
        if(empty($user_bus)){
            return true;
        }
        //��������
        //����������˾������Ʒ�����ִ��?
        $user_bus=array_unique($user_bus);
        $processType = $this->findProcessType(self::TYPE);
        //�Զ��׵�����Ӧ��˾��Ҫ���յĽڵ�
        $approverInfo=array();
        foreach($user_bus as $user_bu){
            $approverInfo[$user_bu]['operate']='add';
            $approverInfo[$user_bu]['step']='1';
            $approverInfo[$user_bu]['approver']= $auto_flow_user[$user_bu][paras_value];
            $approverInfo[$user_bu]['type']=$mainData[type];
            $approverInfo[$user_bu]['user_bu']=$user_bu;

            $main=$mainData;
            $main['status']=$approverInfo[$user_bu]['operate']=='approve'?'P':($approverInfo[$user_bu]['operate']=='add'?'T':'R');
            $main['step']=$approverInfo[$user_bu]['step'];
            $main['user_bu']=$user_bu;
            $main['form_id']=strtolower($user_bu)."_".$main['form_id'];
            $main['from_id']=$mainData['form_id'];
            $main['related_id']=empty($main['related_id'])?'':strtolower($user_bu)."_".$main['related_id'];
            $main['title']=$main['form_id'].'/'.$main['project_code'].'/'.$processType[$main['type']]['paras_desc'];
            //�ͻ���Ϣ��Ϊ��ת���������幫˾
            $main['customer_name']=$oriUserBuArr[$mainData['user_bu']];
            $main['contract_party']=$oriUserBuArr[$mainData['user_bu']];
            //�Ѿ�������������д��
            $querySql=" select * from  inhe_project_main  where form_id='".$main['form_id']."'";
            if(mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                continue;
            }
            //$main[user_id]=$approverInfo[$user_bu]['approver'];
            $projectReviewModel->insert_main($main);//�Զ����ɶ�Ӧ������˾�Ĵ���˲�������?
            $mainDetail=$mainDetailData;
            $mainDetail[form_id]=$main['form_id'];
            $mainDetail[potential]="";
            $mainDetail[background]="";
            $mainDetail[explain_user]="";
            $mainDetail[explain_project]="";
            $mainDetail[cooperate_history]="";
            $projectReviewModel->insert_main_detail($mainDetail);
            $biddingStrategy=array();
            $biddingStrategy[project_level]=$biddingStrategyData[project_level];
            $biddingStrategy[form_id]=$main['form_id'];
            $projectReviewModel->insert_bidding_strategy($biddingStrategy);
        }
         //��������
         $mainData[title_number]=$mainData[project_code];
         $this->autoInsertOpinion($user_bus,$approverInfo,$mainData,$processType);
    }

      //�Զ��׵����ۺ�ͬ����
      public function autoCreateSalesContractReview250729($param){
        
        //����ͼ������麣�ͼ��ж�RETAILNERGY
        $param['user_bu']=$param['user_bu']=="HKNERGY"||$param['user_bu']=="RETAILNERGY"?"INHENERGY":$param['user_bu'];
        //�жϵ����Ƿ�Ϊ�ջ��ߵ���Ϊ�Զ����ɵĵ���
        if(empty($param['form_id'])||strpos($param['form_id'],strtolower($param['user_bu']."_"))===0){
            return true;
        }
        $mailSql=" select * from  inhe_contract_review  where form_id='$param[form_id]'";
        $res2=exequery(TD::conn(), $mailSql);
        $mainData=array();
        while ($item2 = mysql_fetch_assoc($res2)) {
            $mainData=$item2;
        }
        if(!in_array($mainData['status'],array('R','Y'))){
            return true;
        }
        $mailSql=" select * from  inhe_contract_detail  where form_id='$param[form_id]'";
        $res2=exequery(TD::conn(), $mailSql);
        $mainDetailData=array();
        if ($item2 = mysql_fetch_assoc($res2)) {
            $mainDetailData=$item2;
        }
         // ��ȡ������?
         $appParam = array(
            'step' => $param['prev_step'],
            'type' => $mainData['type'],
            'user_bu' => $mainData['user_bu']
        );
        if($mainData['user_bu']=="INHE"&&$mainData['create_user_bu']=="CDINHE"){
            $appParam[user_bu]=$mainData['user_bu']."_".$mainData['create_user_bu'];
        }
        $approverArr = $this->getApprover($appParam);
        $approverInfo = $approverArr[0];
        
        //��һ���Ǳ������������׵�
        if($approverInfo[operate]=="record"){
            return true;
        }
        $processType = $this->findProcessType(self::TYPE);
        $salesContractReviewModel = new SalesContractReviewModel();
        $dataModel = new DataModel();
        $selectParam = array(
            'is_used' => '1', // ���ò���
            'type' => 'product_detail_type,crm_process_sort,auto_flow_user',
        );
        $selectArr = $dataModel->getCommonParam($selectParam);
        foreach($selectArr['auto_flow_user'] as $key => $val){
            $auto_flow_user[$val['user_bu']]=$val;
        }
        $selectArr = $dataModel->getCommonParam($selectParam);
        foreach($selectArr['product_detail_type'] as $key => $val){
            $typeArr[strtolower($val['paras_value'])]=$val['user_bu'];
        }
        $typeArr['electric']='INHE';//���Ĭ���׵����ӵ���?
        foreach($selectArr['crm_process_sort'] as $key => $val){
            $oriUserBuArr[$val['paras_value']]=$val['paras_desc'];
        }
        //����������Ʒ
        $attach_types=array();
        $user_bus=array();

        $productSql=" select * from  inhe_product_detail  where  form_id='$param[form_id]'";//sub_process=1 and �ֶ����Զ�
       
        $res=exequery(TD::conn(), $productSql); 
        while ($item = mysql_fetch_assoc($res)) {
            //�ɶ��ߵ������⴦��1
            if($param['user_bu']=="INHE"&&$param['create_user_bu']=="CDINHE"){
                $product_non=$item;
                $product_non['form_id']=strtolower($param['create_user_bu']). "_" .$item['form_id'];
                //�Ѿ�������������д��
                $querySql=" select * from  inhe_product_detail  where form_id='$product_non[form_id]' and type='$product_non[type]' and serial_number='$product_non[serial_number]' ";
                if(!mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                    $salesContractReviewModel->insert_product_detail($product_non);//�Զ����ɶ�Ӧ������˾����Ҫ�����Ĳ�Ʒ��Ϣ
                }
            }
            //��Ʒ��������������˾��Ʒ��ֻ�ǵ�ǰ��˾��Ʒ�������� //�������������ͽ��������׵�,������˾�׵�������
            if($typeArr[$item['type']]==$param['user_bu']||empty($typeArr[$item['type']])||($item['type']=="electric"&&in_array($param['user_bu'],array("JXINHE","INHEJX","INHE","HKINHE","INHEIAC","INHEIMC","IMCINHE")))){
                continue;
            }
            //�ɶ��ߵ������⴦��2,�ɶ��ߵ��������ø��ݲ�Ʒ����һ��������Ϊ�Ѿ�����һ�������ĵ�
            if(($param['user_bu']=="INHE"&&$param['create_user_bu']=="CDINHE"&&$item['type']!="cdinhe")||$param['user_bu']!="INHE"||$param['create_user_bu']!="CDINHE"){
                $product_non=$item;
                $product_non['form_id']=strtolower($typeArr[$item['type']]). "_" .$item['form_id'];
                $product_non['price']="";
                $product_non['total_amount']="";
                //�Ѿ�������������д��
                $querySql=" select * from  inhe_product_detail  where form_id='$product_non[form_id]' and type='$product_non[type]' and serial_number='$product_non[serial_number]' ";
                if(!mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                    $salesContractReviewModel->insert_product_detail($product_non);//�Զ����ɶ�Ӧ������˾����Ҫ�����Ĳ�Ʒ��Ϣ
                }
            }
            //�ɶ��ߵ������⴦��3
            if($param['user_bu']=="INHE"&&$param['create_user_bu']=="CDINHE"&&$item['type']=="cdinhe"){
                continue;
            }
            if(!in_array($typeArr[$item['type']],$user_bus)){
                array_push($user_bus,$typeArr[$item['type']]);
            }
            
        }
        //�ɶ��ߵ������⴦��4
       if($param['user_bu']=="INHE"&&$param['create_user_bu']=="CDINHE"){
            //�Ѿ�������������д��
            $main=$mainData;
            $main['status']='Y';
            $main['user_bu']=$param['create_user_bu'];
            $main['form_id']=strtolower($param['create_user_bu'])."_".$main['form_id'];
            $main['from_id']=$mainData['form_id'];
            $main['related_id']=empty($main['related_id'])?'':strtolower($param['create_user_bu'])."_".$main['related_id'];
            $main['title']=$main['form_id'].'/'.$main['project_number'].'/'.$processType[$main['type']]['paras_desc'];
            //�ͻ���Ϣ��Ϊ��ת���������幫˾
            $main['customer_name']=$oriUserBuArr[$mainData['user_bu']];
            $main['contract_party']=$oriUserBuArr[$mainData['user_bu']];
            //�Ѿ�������������д��
            $querySql=" select * from  inhe_contract_review  where form_id='".$main['form_id']."'";
            if(!mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                $salesContractReviewModel->insert_main($main);//�Զ����ɶ�Ӧ������˾����������
                $mainDetail=$mainDetailData;
                $mainDetail[form_id]=$main['form_id'];
                $salesContractReviewModel->insert_main_detail($mainDetail);//�Զ����ɶ�Ӧ������˾����ϸ������
                //����������Ϣ
                $attachSql=" select * from  inhe_contract_settle  where form_id='$mainData[form_id]'";
                $res4=exequery(TD::conn(), $attachSql); 
                while ($item = mysql_fetch_assoc($res4)) {
                    $contract_settle=$item;
                    $contract_settle[form_id]=$main['form_id'];
                    $salesContractReviewModel->insert_contract_settle($contract_settle);//���ɽ�����Ϣ
                }
                //��������
                $attachSql=" select * from  inhe_attachment  where relatedId='$mainData[form_id]' ";
                $res2=exequery(TD::conn(), $attachSql);
                while ($item2 = mysql_fetch_assoc($res2)) {
                    $attach=$item2;
                    $attach['filename']=strtolower($param['create_user_bu']) .'-'.$item2['filename'];
                    $attach['path']=MYOA_ROOT_PATH . "attachment/reportshop/attachment/". $attach['filename'];
                    $attach['relatedId']=strtolower($param['create_user_bu']) .'_'.$item2['relatedId'];
                    //�Ѿ�������������д��
                    $querySql=" select * from  inhe_attachment  where relatedId='$attach[relatedId]' and filename='$attach[filename]'";
                    if(mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                        continue;
                    }
                    copy($item2['path'],$attach['path']);
                    $salesContractReviewModel->insert_attach($attach);//�Զ����ɶ�Ӧ��˾��Ʒ�ĸ���
                }
            }
            //��������
            $prev_writer="";
            $prev_time="";
            $flow_manageSql="select * from inhe_flow_manage b where b.type ='$mainData[type]' and b.user_bu ='$param[create_user_bu]' and ".$param[create_user_bu]."_approver=1 order by step";
            $res4=exequery(TD::conn(), $flow_manageSql); 
            //$stepCount = mysql_num_rows($res4);
            $start_time = date('Y-m-d H:i:s');
            while ($item = mysql_fetch_assoc($res4)) {
                    $item['approver']=$item['step']==1?$mainData['user_id']:$item['approver'];
                    $approve_userArr=explode(",",$item['approver']);
                    $opinion=$item;
                    //�Ѿ�������������д��
                    $querySql=" select * from  inhe_flow_opinion  where  form_id='$mainData[form_id]' and status='H' and approve_user='$approve_userArr[0]' order by id desc";
                    if($item2 =mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                        $opinion=$item2;
                    }
                    $start_time = date("Y-m-d H:i:s",strtotime($start_time)+60*10);
                    
                    $opinion['step']=$item['step'];
                    $opinion['status']='H';
                    $opinion['if_agree']='01';
                    $opinion['user_bu']=$item['user_bu'];
                    $opinion['work_flow']=$item['role'];
                    $opinion['form_id']=strtolower($item['user_bu'])."_".$mainData['form_id'];
                    $opinion['title']=str_replace($mainData['form_id'],$opinion['form_id'],$mainData['title']);
                    $opinion['prev_writer']=$prev_writer;
                    $opinion['prev_time']=$prev_time;
                    $opinion['approve_time']=$start_time;
                    
                    $opinion['approve_user']=$approve_userArr[0];
                    $prev_writer=$opinion['approve_user'];
                    $prev_time=$opinion['approve_time'];
                    if(empty($opinion['signature'])){
                        //�Ѿ�������������д��
                        $querySql=" select * from  user  where  user_id='$opinion[approve_user]'";
                        if($item3 =mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                            $opinion[signature]=$item3[USER_NAME]." ".$opinion['approve_time'];
                        }
                    }
                    //�Ѿ�������������д��
                    $querySql=" select * from  inhe_flow_opinion  where form_id='$opinion[form_id]' and step='$opinion[step]'";
                    if(mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                        continue;
                    }
                    $salesContractReviewModel->insert_opinion($opinion);//�Զ����ɶ�Ӧ������˾������˲�������?
            }
            
        }
        if(empty($user_bus)){
            return true;
        }
        //��������
        //����������˾������Ʒ�����ִ��?
        $user_bus=array_unique($user_bus);
        $processType = $this->findProcessType(self::TYPE);
        //�Զ��׵�����Ӧ��˾��Ҫ���յĽڵ�
        $approverInfo=array();
        foreach($user_bus as $user_bu){
            $approverInfo[$user_bu]['operate']='add';
            $approverInfo[$user_bu]['step']='1';
            $approverInfo[$user_bu]['approver']= $auto_flow_user[$user_bu][paras_value];
            $approverInfo[$user_bu]['type']=$mainData[type];
            $approverInfo[$user_bu]['user_bu']=$user_bu;

            $main=$mainData;
            $main['status']=$approverInfo[$user_bu]['operate']=='approve'?'P':($approverInfo[$user_bu]['operate']=='add'?'T':'R');
            $main['step']=$approverInfo[$user_bu]['step'];
            $main['user_bu']=$user_bu;
            $main['form_id']=strtolower($user_bu)."_".$main['form_id'];
            $main['from_id']=$mainData['form_id'];
            $main['related_id']=empty($main['related_id'])?'':strtolower($user_bu)."_".$main['related_id'];
            $main['title']=$main['form_id'].'/'.$main['project_number'].'/'.$processType[$main['type']]['paras_desc'];
            //�ͻ���Ϣ��Ϊ��ת���������幫˾
            $main['customer_name']=$oriUserBuArr[$mainData['user_bu']];
            $main['contract_party']=$oriUserBuArr[$mainData['user_bu']];
            //�Ѿ�������������д��
            $querySql=" select * from  inhe_contract_review  where form_id='".$main['form_id']."'";
            if(mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                continue;
            }
            //$main[user_id]=$approverInfo[$user_bu]['approver'];
            $salesContractReviewModel->insert_main($main);//�Զ����ɶ�Ӧ������˾����������
            $mainDetail=$mainDetailData;
            $mainDetail[quote_source]="";//��ͬ������Դ
            $mainDetail[history_project_code]="";//�������ʷ�������ۣ�����д��Ӧ��Ŀ����?
            $mainDetail[funds_come]="";//��Ŀ�ʽ���Դ
            $mainDetail[financing_bank]="";//�����������ʽ�����д����������
            $mainDetail[if_eligible]="";//�Ƿ���ϣ���˾��ʽ���?ȫ�����?�п��?������׼�?�˱����㹻��
            $mainDetail[product_project_type]="";//��Ŀ����
            $mainDetail[total_amount]="";//��Ŀ����
            $mainDetail[business_attribution]="";//ҵ�����?
            $mainDetail[form_date]="";//����
            $mainDetail[form_id]=$main['form_id'];
            $salesContractReviewModel->insert_main_detail($mainDetail);//�Զ����ɶ�Ӧ������˾����ϸ������
             //����������Ϣ
            $attachSql=" select * from  inhe_contract_settle  where form_id='$mainData[form_id]'";
            $res4=exequery(TD::conn(), $attachSql); 
            while ($item = mysql_fetch_assoc($res4)) {
                if(!in_array($item[item_id],array('4','5','8'))){
                    $item[content]="";
                }
                $contract_settle=$item;
                
                $contract_settle[form_id]=$main['form_id'];
                $salesContractReviewModel->insert_contract_settle($contract_settle);//���ɽ�����Ϣ
            }
            //��������
            $attachSql=" select * from  inhe_attachment  where relatedId='$mainData[form_id]' 
            and (type like 'soft_license_contract%' or type like 'tech_meter_contract%' or type like 'tech_soft_contract%')";
            $res3=exequery(TD::conn(), $attachSql); 
            while ($item = mysql_fetch_assoc($res3)) {
                $attach=$item;
                $attach['filename']=strtolower($main['user_bu']) .'-'.$item['filename'];
                $attach['path']="D:/MYOA/webroot/attachment/reportshop/attachment/". $attach['filename'];
                $attach['relatedId']=strtolower($main['user_bu']) .'_'.$item['relatedId'];
                //�Ѿ�������������д��
                // $querySql=" select * from  inhe_attachment  where relatedId='$attach[relatedId]' and filename='$attach[filename]'";
                // if(mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                //     continue;
                // }
                copy($item['path'],$attach['path']);
                $salesContractReviewModel->insert_attach($attach);//�Զ����ɶ�Ӧ��˾��Ʒ�ĸ���
            }
        }
        //����ÿ����˾����ڵ�?
         $mainData[title_number]=$mainData[project_number];
         $this->autoInsertOpinion($user_bus,$approverInfo,$mainData,$processType);
    }

    public function autoCreateProductOrder20250801($param){
        
        //����ͼ������麣�ͼ��ж�?
        $param['user_bu']=$param['user_bu']=="HKNERGY"?"INHENERGY":$param['user_bu'];
        //�жϵ����Ƿ�Ϊ�ջ��ߵ���Ϊ�Զ����ɵĵ���
        if(empty($param['form_id'])||strpos($param['form_id'],strtolower($param['user_bu']."_"))===0){
            return true;
        }
        $mailSql=" select * from  inhe_production_order  where form_id='$param[form_id]'";
        $res2=exequery(TD::conn(), $mailSql);
        $mainData=array();
        while ($item2 = mysql_fetch_assoc($res2)) {
            $mainData=$item2;
        }
        if(!in_array($mainData['status'],array('R','Y'))){
            return true;
        }
        //������������֪ͨ����Ч��ת
        if(in_array($mainData['user_bu'],array('GRIDINHE'))){
            return true;
        }

         // ��ȡ������?
         $appParam = array(
            'step' => $param['prev_step'],
            'type' => $mainData['type'],
            'user_bu' => $mainData['user_bu']
        );
        if($mainData['user_bu']=="INHE"&&$mainData['create_user_bu']=="CDINHE"){
            $appParam[user_bu]=$mainData['user_bu']."_".$mainData['create_user_bu'];
        }
        $approverArr = $this->getApprover($appParam);
        $approverInfo = $approverArr[0];
        //��һ���Ǳ������������׵�
        if($approverInfo[operate]=="record"){
            return true;
        }

        $productionOrderModel = new ProductionOrderModel();
        $dataModel = new DataModel();
        $processType = $this->findProcessType(self::TYPE);
        $selectParam = array(
            'is_used' => '1', // ���ò���
            'type' => 'product_detail_type,crm_process_sort,auto_flow_user',
        );
        $selectArr = $dataModel->getCommonParam($selectParam);
        foreach($selectArr['auto_flow_user'] as $key => $val){
            $auto_flow_user[$val['user_bu']]=$val;
        }
        $selectArr = $dataModel->getCommonParam($selectParam);
        foreach($selectArr['product_detail_type'] as $key => $val){
            $typeArr[$val['paras_value']]=$val['user_bu'];
        }
        $typeArr['Electric']='INHE';//���Ĭ���׵����ӵ���?
        foreach($selectArr['crm_process_sort'] as $key => $val){
            $oriUserBuArr[$val['paras_value']]=$val['paras_desc'];
        }
        //����������Ʒ
        $attach_types=array();
        $user_bus=array();
        $productSql=" select * from  inhe_production_detail_non  where  form_id='$param[form_id]'";//sub_process=1 and �ֶ����Զ�
        $res=exequery(TD::conn(), $productSql); 
        while ($item = mysql_fetch_assoc($res)) {
            //�ɶ��ߵ������⴦��1
            if($param['user_bu']=="INHE"&&$param['create_user_bu']=="CDINHE"){
                $product_non=$item;
                $product_non['form_id']=strtolower($param['create_user_bu']). "_" .$item['form_id'];
                //�Ѿ�������������д��
                $querySql=" select * from  inhe_production_detail_non  where form_id='$product_non[form_id]' and non_type='$product_non[non_type]' and non_serial_number='$product_non[non_serial_number]' ";
                if(!mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                    $productionOrderModel->insert_production_non($product_non);//�Զ����ɶ�Ӧ������˾����Ҫ�����Ĳ�Ʒ��Ϣ
                }
            }
            //��Ʒ��������������˾��Ʒ��'INHENERGY','INHEGRID','WITLINK'����ֻ�ǵ�ǰ��˾��Ʒ��������
            if($typeArr[$item['non_type']]==$param['user_bu']||empty($typeArr[$item['non_type']])){
                continue;
            }
             //�ɶ��ߵ������⴦��2,�ɶ��ߵ��������ø��ݲ�Ʒ����һ��������Ϊ�Ѿ�����һ�������ĵ�
            if(($param['user_bu']=="INHE"&&$param['create_user_bu']=="CDINHE"&&strtolower($item['non_type'])!="cdinhe")||$param['user_bu']!="INHE"||$param['create_user_bu']!="CDINHE"){
                $product_non=$item;
                $product_non['form_id']=strtolower($typeArr[$item['non_type']]). "_" .$item['form_id'];
                $product_non['sub_process']='';
                //�Ѿ�������������д��
                $querySql=" select * from  inhe_production_detail_non  where form_id='$product_non[form_id]' and non_type='$product_non[non_type]' and non_serial_number='$product_non[non_serial_number]' ";
                if(!mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                    $productionOrderModel->insert_production_non($product_non);//�Զ����ɶ�Ӧ������˾����Ҫ�����Ĳ�Ʒ��Ϣ
                }
            }
            //�ɶ��ߵ������⴦��3
            if($param['user_bu']=="INHE"&&$param['create_user_bu']=="CDINHE"&&strtolower($item['non_type'])=="cdinhe"){
                continue;
            }
            if(!in_array($typeArr[$item['non_type']],$user_bus)){
                array_push($user_bus,$typeArr[$item['non_type']]);
            }
            $attach_types["non_attachment_".$item['non_type'].$item['non_serial_number']]=$typeArr[$item['non_type']];
            
        }
        
        $INHEORJX='INHE';//$mainData[sub_user_bu]=='INHE'?'INHE':'JXINHE';
        //�ж�������˾�Ƿ���ڵ����Ʒ�����������׵��������������׽�����||($INHEORJX=='JXINHE'&&in_array($mainData['user_bu'],array('INHE','INHEIAC')))||($INHEORJX=='INHE'&&in_array($mainData['user_bu'],array('JXINHE','INHEJX')))
        if(in_array($mainData['user_bu'],array('INHENERGY','HKNERGY','INHEGRID','WITLINK'))||($param['user_bu']=="INHE"&&$param['create_user_bu']=="CDINHE")){
            
            $querySql=" select * from  inhe_production_detail_one  where form_id='$mainData[form_id]' ";
            if(mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
            //�жϻ����ͼ��������Ƿ���ڵ����Ʒ������������Ƿ���Ҫ�ܲ�����֧���׽������ߵ���?
                if($param['user_bu']=="INHE"&&$param['create_user_bu']=="CDINHE"){
                    $INHEORJX=$param['create_user_bu'];
                }else{
                    array_push($user_bus,$INHEORJX);
                }
                //TODO �Զ����ӵ�����Ʒ $productionOrderModel->addMeter($product_non);
                $productSql=" select * from  inhe_production_detail_one  where  form_id='$mainData[form_id]'";
                $res=exequery(TD::conn(), $productSql); 
                while ($item = mysql_fetch_assoc($res)) {
                    $product_noe=$item;
                    $product_noe['form_id']=strtolower($INHEORJX. "_" .$item['form_id']);
                    $productionOrderModel->addMeterOne($product_noe);
                }
                $productSql=" select * from  inhe_production_detail_two  where  form_id='$mainData[form_id]'";
                $res=exequery(TD::conn(), $productSql); 
                while ($item = mysql_fetch_assoc($res)) {
                    $product_two=$item;
                    $product_two['form_id']=strtolower($INHEORJX. "_" .$item['form_id']);
                    $productionOrderModel->addMeterTwo($product_two);
                }
                $productSql=" select * from  inhe_production_detail_three  where  form_id='$mainData[form_id]'";
                $res=exequery(TD::conn(), $productSql); 
                while ($item = mysql_fetch_assoc($res)) {
                    $product_three=$item;
                    $product_three['form_id']=strtolower($INHEORJX. "_" .$item['form_id']);
                    $productionOrderModel->addMeterThree($product_three);
                    if($param['user_bu']=="INHE"&&$param['create_user_bu']=="CDINHE"){
                        
                    }else{
                        $attach_types["attachment".$item['serial_number']]=$INHEORJX;
                        $attach_types["related_attachment".$item['serial_number']]=$INHEORJX;
                    }
                }
            }
        }

        //�ɶ��ߵ������⴦��4
       if($param['user_bu']=="INHE"&&$param['create_user_bu']=="CDINHE"){
            //�Ѿ�������������д��
            $main=$mainData;
            $main['status']='Y';
            $main['user_bu']=$param['create_user_bu'];
            $main['form_id']=strtolower($param['create_user_bu'])."_".$main['form_id'];
            $main['from_id']=$mainData['form_id'];
            $main['related_id']=empty($main['related_id'])?'':strtolower($param['create_user_bu'])."_".$main['related_id'];
            $main['title']=$main['form_id'].'/'.$main['project_number'].'/'.$processType[$main['type']]['paras_desc'];
            //�ͻ���Ϣ��Ϊ��ת���������幫˾
            $main['customer_name']=$oriUserBuArr[$mainData['user_bu']];
            $main['contract_party']=$oriUserBuArr[$mainData['user_bu']];
            //�Ѿ�������������д��
            $querySql=" select * from  inhe_production_order  where form_id='".$main['form_id']."'";
            if(!mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                $productionOrderModel->insert_main($main);
                //��������
                $attachSql=" select * from  inhe_attachment  where relatedId='$mainData[form_id]' ";
                $res2=exequery(TD::conn(), $attachSql);
                while ($item2 = mysql_fetch_assoc($res2)) {
                    $attach=$item2;
                    $attach['filename']=strtolower($param['create_user_bu']) .'-'.$item2['filename'];
                    $attach['path']=MYOA_ROOT_PATH . "attachment/reportshop/attachment/". $attach['filename'];
                    $attach['relatedId']=strtolower($param['create_user_bu']) .'_'.$item2['relatedId'];
                    //�Ѿ�������������д��
                    $querySql=" select * from  inhe_attachment  where relatedId='$attach[relatedId]' and filename='$attach[filename]'";
                    if(mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                        continue;
                    }
                    copy($item2['path'],$attach['path']);
                    $productionOrderModel->insert_attach($attach);//�Զ����ɶ�Ӧ��˾��Ʒ�ĸ���
                }
            }
            //��������
            $prev_writer="";
            $prev_time="";
            $flow_manageSql="select * from inhe_flow_manage b where b.type ='$mainData[type]' and b.user_bu ='$param[create_user_bu]' and ".$param[create_user_bu]."_approver=1 order by step";
            $res4=exequery(TD::conn(), $flow_manageSql); 
            $start_time = date('Y-m-d H:i:s');
            while ($item = mysql_fetch_assoc($res4)) {
                    $item['approver']=$item['step']==1?$mainData['user_id']:$item['approver'];
                    $approve_userArr=explode(",",$item['approver']);
                    $opinion=$item;
                    //�Ѿ�������������д��
                    $querySql=" select * from  inhe_flow_opinion  where  form_id='$mainData[form_id]' and status='H' and approve_user='$approve_userArr[0]' order by id desc";
                    if($item2 =mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                        $opinion=$item2;
                    }
                    $start_time = date("Y-m-d H:i:s",strtotime($start_time)+60*10);
                    
                    $opinion['step']=$item['step'];
                    $opinion['status']='H';
                    $opinion['if_agree']='01';
                    $opinion['user_bu']=$item['user_bu'];
                    $opinion['work_flow']=$item['role'];
                    $opinion['form_id']=strtolower($item['user_bu'])."_".$mainData['form_id'];
                    $opinion['title']=str_replace($mainData['form_id'],$opinion['form_id'],$mainData['title']);
                    $opinion['prev_writer']=$prev_writer;
                    $opinion['prev_time']=$prev_time;
                    $opinion['approve_time']=$start_time;
                    
                    $opinion['approve_user']=$approve_userArr[0];
                    $prev_writer=$opinion['approve_user'];
                    $prev_time=$opinion['approve_time'];
                    if(empty($opinion['signature'])){
                        //�Ѿ�������������д��
                        $querySql=" select * from  user  where  user_id='$opinion[approve_user]'";
                        if($item3 =mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                            $opinion[signature]=$item3[USER_NAME]." ".$opinion['approve_time'];
                        }
                    }
                    //�Ѿ�������������д��
                    $querySql=" select * from  inhe_flow_opinion  where form_id='$opinion[form_id]' and step='$opinion[step]'";
                    if(mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                        continue;
                    }
                    $productionOrderModel->insert_opinion($opinion);//�Զ����ɶ�Ӧ������˾������˲�������?
            }
        }
        
        if(empty($user_bus)){
            return true;
        }
        //��������
        //����������˾������Ʒ�����ִ��?
        $user_bus=array_unique($user_bus);
        
        
        //$sql = " select * from inhe_flow_manage where type='$mainData[type]' and auto_approver='1' group by user_bu";
        //$res = exequery(TD::conn(), $sql);
        $approverInfo=array();
        // while ($item = mysql_fetch_assoc($res)) {
        //     $approverInfo[$item['user_bu']] = $item;
        // }
        foreach($user_bus as $user_bu){
            $approverInfo[$user_bu]['operate']='add';
            $approverInfo[$user_bu]['step']='1';
            $approverInfo[$user_bu]['approver']= $auto_flow_user[$user_bu][paras_value];
            $approverInfo[$user_bu]['type']=$mainData[type];
            $approverInfo[$user_bu]['user_bu']=$user_bu;

            $main=$mainData;
            $main['status']=$approverInfo[$user_bu]['operate']=='approve'?'P':($approverInfo[$user_bu]['operate']=='add'?'T':'R');
            $main['step']=$approverInfo[$user_bu]['step'];
            $main['user_bu']=$user_bu;
            $main['form_id']=strtolower($user_bu)."_".$main['form_id'];
            $main['from_id']=$mainData['form_id'];
            $main['related_id']=empty($main['related_id'])?'':strtolower($user_bu)."_".$main['related_id'];
            $main['title']=$main['form_id'].'/'.$main['project_num'].'/'.$processType[$main['type']]['paras_desc'];
            $main['meter_number']=in_array($user_bu,array("INHE","JXINHE"))?$mainData['meter_number']:0;
            //�ͻ���Ϣ��Ϊ��ת���������幫˾
            $main['customer_name']=$oriUserBuArr[$mainData['user_bu']];
            $main['contract_party']=$oriUserBuArr[$mainData['user_bu']];
            //�Ѿ�������������д��
            $querySql=" select * from  inhe_production_order  where form_id='".$main['form_id']."'";
            if(mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                continue;
            }
            //$main[user_id]=$approverInfo[$user_bu]['approver'];
            $productionOrderModel->insert_main($main);//�Զ����ɶ�Ӧ������˾�Ĵ���˲�������?
            
        }
        //��������
        $attachSql=" select * from  inhe_attachment  where relatedId='$mainData[form_id]'";
        $res3=exequery(TD::conn(), $attachSql); 
        while ($item = mysql_fetch_assoc($res3)) {
            if(empty($attach_types[$item['type']])){
                continue;    
            }
            $attach=$item;
            $attach['filename']=strtolower($attach_types[$item['type']]) .'-'.$item['filename'];
            $attach['path']="D:/MYOA/webroot/attachment/reportshop/attachment/". $attach['filename'];
            $attach['relatedId']=strtolower($attach_types[$item['type']]) .'_'.$item['relatedId'];
             //�Ѿ�������������д��
             $querySql=" select * from  inhe_attachment  where relatedId='$attach[relatedId]' and filename='$attach[filename]'";
             if(mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                 continue;
             }
            copy($item['path'],$attach['path']);
            $productionOrderModel->insert_attach($attach);//�Զ����ɶ�Ӧ��˾��Ʒ�ĸ���
        }
        //��������
        $mainData[title_number]=$mainData[project_num];
        $this->autoInsertOpinion($user_bus,$approverInfo,$mainData,$processType);
       
    }

    public function autoCreateShippingNotice20250926($param){
        if($param['user_bu']=="INHE"&&$param['create_user_bu']=="CDINHE"){}else{
            return true;
        }
        //����ͼ������麣�ͼ��ж�RETAILNERGY
        $param['user_bu']=$param['user_bu']=="HKNERGY"||$param['user_bu']=="RETAILNERGY"?"INHENERGY":$param['user_bu'];
        //�жϵ����Ƿ�Ϊ�ջ��ߵ���Ϊ�Զ����ɵĵ���
        if(empty($param['form_id'])||strpos($param['form_id'],strtolower($param['user_bu']."_"))===0){
            return true;
        }
        $mailSql=" select * from  inhe_shipping_notice  where form_id='$param[form_id]'";
        $res2=exequery(TD::conn(), $mailSql);
        $mainData=array();
        while ($item2 = mysql_fetch_assoc($res2)) {
            $mainData=$item2;
        }
        if(!in_array($mainData['status'],array('R','Y'))){
            return true;
        }

         // ��ȡ������?
         $appParam = array(
            'step' => $param['prev_step'],
            'type' => $mainData['type'],
            'user_bu' => $mainData['user_bu']
        );
        if($mainData['user_bu']=="INHE"&&$mainData['create_user_bu']=="CDINHE"){
            $appParam[user_bu]=$mainData['user_bu']."_".$mainData['create_user_bu'];
        }
        $approverArr = $this->getApprover($appParam);
        $approverInfo = $approverArr[0];
        //��һ���Ǳ������������׵�
        if($approverInfo[operate]=="record"){
            return true;
        }

        $shippingNoticeModel = new ShippingNoticeModel();
        $dataModel = new DataModel();
        $selectParam = array(
            'is_used' => '1', // ���ò���
            'type' => 'product_detail_type,crm_process_sort',
        );
        $selectArr = $dataModel->getCommonParam($selectParam);
        foreach($selectArr['product_detail_type'] as $key => $val){
            $typeArr[strtolower($val['paras_value'])]=$val['user_bu'];
        }
        foreach($selectArr['crm_process_sort'] as $key => $val){
            $oriUserBuArr[$val['paras_value']]=$val['paras_desc'];
        }
        //����������Ʒ
        $attach_types=array();
        $user_bus=array();

        $productSql=" select * from  inhe_shipping_detail  where  form_id='$param[form_id]'";//sub_process=1 and �ֶ����Զ�
        $res=exequery(TD::conn(), $productSql); 
        while ($item = mysql_fetch_assoc($res)) {
             //�ɶ��ߵ������⴦��1
             if($param['user_bu']=="INHE"&&$param['create_user_bu']=="CDINHE"){
                $product_non=$item;
                $product_non['form_id']=strtolower($param['create_user_bu']). "_" .$item['form_id'];
                //�Ѿ�������������д��
                $querySql=" select * from  inhe_shipping_detail  where form_id='$product_non[form_id]' and type='$product_non[type]' and serial_number='$product_non[serial_number]' ";
                if(!mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                    $shippingNoticeModel->insert_shipping_detail($product_non);//�Զ����ɶ�Ӧ������˾����Ҫ�����Ĳ�Ʒ��Ϣ
                }
            }  
        }
        
        //�ɶ��ߵ������⴦��4
        if($param['user_bu']=="INHE"&&$param['create_user_bu']=="CDINHE"){
            //�Ѿ�������������д��
            $main=$mainData;
            $main['status']='Y';
            $main['user_bu']=$param['create_user_bu'];
            $main['form_id']=strtolower($param['create_user_bu'])."_".$main['form_id'];
            $main['from_id']=$mainData['form_id'];
            $main['related_id']=empty($main['related_id'])?'':strtolower($param['create_user_bu'])."_".$main['related_id'];
            $main['title']=$main['form_id'].'/'.$main['project_number'].'/'.$processType[$main['type']]['paras_desc'];
            //�ͻ���Ϣ��Ϊ��ת���������幫˾
            $main['customer_name']=$oriUserBuArr[$mainData['user_bu']];
            $main['contract_party']=$oriUserBuArr[$mainData['user_bu']];
            //�Ѿ�������������д��
            $querySql=" select * from  inhe_shipping_notice  where form_id='".$main['form_id']."'";
            if(!mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                $shippingNoticeModel->insert_main($main);//�Զ����ɶ�Ӧ������˾�Ĵ���˲�������?
            }
            
            //��������
            $prev_writer="";
            $prev_time="";
            $flow_manageSql="select * from inhe_flow_manage b where b.type ='$mainData[type]' and b.user_bu ='$param[create_user_bu]' and ".$param[create_user_bu]."_approver=1 order by step";
            $res4=exequery(TD::conn(), $flow_manageSql); 
            $start_time = date('Y-m-d H:i:s');
            while ($item = mysql_fetch_assoc($res4)) {
                $item['approver']=$item['step']==1?$mainData['user_id']:$item['approver'];
                $approve_userArr=explode(",",$item['approver']);
                $opinion=$item;
                //�Ѿ�������������д��
                $querySql=" select * from  inhe_flow_opinion  where  form_id='$mainData[form_id]' and status='H' and approve_user='$approve_userArr[0]' order by id desc";
                if($item2 =mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                    $opinion=$item2;
                }
                $start_time = date("Y-m-d H:i:s",strtotime($start_time)+60*10);
                
                $opinion['step']=$item['step'];
                $opinion['status']='H';
                $opinion['if_agree']='01';
                $opinion['user_bu']=$item['user_bu'];
                $opinion['work_flow']=$item['role'];
                $opinion['form_id']=strtolower($item['user_bu'])."_".$mainData['form_id'];
                $opinion['title']=str_replace($mainData['form_id'],$opinion['form_id'],$mainData['title']);
                $opinion['prev_writer']=$prev_writer;
                $opinion['prev_time']=$prev_time;
                $opinion['approve_time']=$start_time;
                
                $opinion['approve_user']=$approve_userArr[0];
                $prev_writer=$opinion['approve_user'];
                $prev_time=$opinion['approve_time'];
                if(empty($opinion['signature'])){
                    //�Ѿ�������������д��
                    $querySql=" select * from  user  where  user_id='$opinion[approve_user]'";
                    if($item3 =mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                        $opinion[signature]=$item3[USER_NAME]." ".$opinion['approve_time'];
                    }
                }
                //�Ѿ�������������д��
                $querySql=" select * from  inhe_flow_opinion  where form_id='$opinion[form_id]' and approve_time='$opinion[approve_time]'";
                if(mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                    continue;
                }
                $shippingNoticeModel->insert_opinion($opinion);//�Զ����ɶ�Ӧ������˾������˲������̣�����Ϊ�գ����ⲽ���ͻ
            }
        }

    }
    //�����Զ���ת���̴���
    /**
     * 
     */
    public function autoInsertOpinion($user_bus,$approverInfo,$mainData,$processType){

        foreach($user_bus as $user_bu){
            if(empty($approverInfo[$user_bu])){
                continue;
            }
            $form_id=strtolower($user_bu)."_".$mainData['form_id'];
            $title=$form_id.'/'.$mainData['title_number'].'/'.$processType[$mainData['type']]['paras_desc'];
            
            if($approverInfo[$user_bu]['operate']=='approve'){
                //��������
                $approve_user=explode(",",$approverInfo[$user_bu]['approver']);
                $approve_user=$approve_user[0];
            }else if($approverInfo[$user_bu]['operate']=='add'){
                //��������
                $approve_user=explode(",",$approverInfo[$user_bu]['approver']);
                $approve_user=$approve_user[0];
                $approverInfo[$user_bu]['step']=1;
                $approverInfo[$user_bu]['role']='������';
            }else{
                //��������
                $approve_user='';
                $opinionSql=" select * from  user u where 
                exists(select * from inhe_flow_manage where type='$mainData[type]' and user_bu='$user_bu'  
                and notice_user is not null
                and ((find_in_set(u.user_id,substring_index(approver,',',1)) and if_show='1' and notice_user='1' ) or  (find_in_set(u.user_id,approver) and operate='record' and next_step=''))) 
                ";
                //"and u.user_id!='meterw' and not exists(select * from inhe_flow_opinion where form_id='$form_id' and approve_user=u.user_id) ";
                $res4=exequery(TD::conn(), $opinionSql);
                while ($item = mysql_fetch_assoc($res4)) {
                    $approve_user.=$item[USER_ID].",";
                }
            }
            $flowParam = array(
                'form_id' => $form_id,
                'title' => $title,
                'type' => $approverInfo[$user_bu]['type'],
                'step' => $approverInfo[$user_bu]['step'],
                'work_flow' => $approverInfo[$user_bu]['role'],
                'common_opinion' => $approverInfo[$user_bu]['common_opinion'],
                'approve_user' => $approve_user,
                'status' => 'U',
                'prev_writer' => $approverInfo[$user_bu]['prev_writer'],
                'prev_time' => $approverInfo[$user_bu]['prev_time'],
                'user_bu' => $user_bu
            );
            $this->updateFlowData($flowParam);
            //�Ѿ�������������д��
            $querySql=" select * from  inhe_flow_opinion  where form_id='$flowParam[form_id]' and approve_time='$flowParam[approve_time]'";
            if(mysql_fetch_assoc(exequery(TD::conn(), $querySql))){
                continue;
            }
            //$salesContractReviewModel->insert_opinion($opinion);//�Զ����ɶ�Ӧ������˾����������
            $mainUrl = str_replace('_change', '', $mainData['type']);
            $mainUrl = str_replace('trial_', '', $mainUrl);
            $mainUrl = str_replace('rd_', '', $mainUrl);
            $msgDesc=$approverInfo[$user_bu]['operate']=='approve'?'�뼰ʱ���?:($approverInfo[$user_bu]['operate']=='approve'?'�뼰ʱ�ύ':'�뼰ʱ��������');
            $smsParam = array(
                'url' => "/customer_relationship/" . $mainUrl . "/detail.php?id=" . $flowParam['form_id'] . "&operate=approve&step=" . $flowParam['step'],
                'content' => '���ó���Զ����? . $processType[$mainData['type']]['paras_desc'] . '���̣�'.$msgDesc,
                'approver' => $flowParam['approve_user'],
                'smsFromUserId'=>$mainData['user_id'],
            );
            $this->sendSms($smsParam);
        }
    }

    //�����������첽�裬������ת��
    public function existOtherStep($param)
    {
        $where = " where status = 'U' "; // ��ʱ��ȡ�������ݣ�������Ҫ���޸Ĳ���
        $result = array();
        if (!array_key_exists('step', $param) || $param['step'] == '' || !array_key_exists('form_id', $param) || $param['form_id'] == '') {
            return $result;
        }
        $where .= " and m.form_id = '" . $param['form_id'] . "' and step != '" . $param['step'] . "' ";
        if (array_key_exists('approve_user', $param) && $param['approve_user'] != '') {
            $where .= " and m.approve_user = '" . $param['approve_user'] . "' ";
        }
        $sql = " select * from inhe_flow_opinion m " . $where;
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result = $item;
        }
        return $result;
    }

     /**
     * ��ȡ���ӵ��������ܼ���Ա
     */
    public function getDirectorList()
    {
         //��ȡ���������ܼ�
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
    //�ݹ��ȡ�����ܼ�?
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

    //ί�а���
    public function updateFlowOpinionApprover($param)
    {
        $result = array();
        if (!array_key_exists('id', $param) || $param['id'] == '') {
            return $result;
        }
        if (!array_key_exists('user_id', $param) || $param['user_id'] == '') {
            return $result;
        }
        if (!array_key_exists('form_id', $param) || $param['form_id'] == '') {
            return $result;
        }
        // if (!array_key_exists('step', $param) || $param['step'] == '') {
        //     return $result;
        // }
        $sql = "insert into inhe_flow_opinion(type,form_id,title,step,work_flow,approve_user,status,user_bu) 
        select type,form_id,title,step,work_flow,approve_user,status,user_bu from inhe_flow_opinion where id='$param[id]' 
        and not exists(select * from inhe_flow_opinion where approve_user='$param[user_id]' and form_id='$param[form_id]' and status='U')";

        $res = exequery(TD::conn(), $sql);
        $new_id = mysql_insert_id();
        if($new_id){
            $sql = "update inhe_flow_opinion set status='H' where id='$param[id]' ";
            $res = exequery(TD::conn(), $sql);
            $sql = "update inhe_flow_opinion set approve_user='$param[user_id]' where id='$new_id' ";
            $res = exequery(TD::conn(), $sql);

            $processType = $this->findProcessType(self::TYPE);
            $mainUrl = str_replace('_change', '', $param['type']);
            $mainUrl = str_replace('trial_', '', $mainUrl);
            $mainUrl = str_replace('rd_', '', $mainUrl);
            $msgDesc='�뼰ʱ����';
            $smsParam = array(
                'url' => "/customer_relationship/" . $mainUrl . "/detail.php?id=" . $param['form_id'] . "&operate=approve&step=" . $param['step'],
                'content' => $_SESSION[LOGIN_USER_NAME].'�ƽ����ó���Զ����? . $processType[$param['type']]['paras_desc'] . '���̣�'.$msgDesc,
                'approver' => $param['user_id'],
                'smsFromUserId'=>$_SESSION[LOGIN_USER_ID],
            );
            $this->sendSms($smsParam);
        }
        
        return true;
    }

    //�߰�
    public function urgentNotify($param)
    {
        $result = array();
        // if (!array_key_exists('step', $param) || $param['step'] == '') {
        //     return $result;
        // }
        // if (!array_key_exists('type', $param) || $param['type'] == '') {
        //     return $result;
        // }
        if (!array_key_exists('form_id', $param) || $param['form_id'] == '') {
            return $result;
        }
        // if (!array_key_exists('step', $param) || $param['step'] == '') {
        //     return $result;
        // }
        $sql = "select type,form_id,title,step,work_flow,approve_user,status,user_bu from inhe_flow_opinion where form_id='$param[form_id]' and status='U'";

        $res = exequery(TD::conn(), $sql);
        if($item = mysql_fetch_assoc($res)){
            // $sql = "update inhe_flow_opinion set status='H' where id='$param[id]' ";
            // $res = exequery(TD::conn(), $sql);
            // $sql = "update inhe_flow_opinion set approve_user='$param[user_id]' where id='$new_id' ";
            // $res = exequery(TD::conn(), $sql);
            $processType = $this->findProcessType(self::TYPE);
            $mainUrl = str_replace('_change', '', $item['type']);
            $mainUrl = str_replace('trial_', '', $mainUrl);
            $mainUrl = str_replace('rd_', '', $mainUrl);
            $msgDesc='�뾡�촦��';
            $smsParam = array(
                'url' => "/customer_relationship/" . $mainUrl . "/detail.php?id=" . $param['form_id'] . "&operate=approve&step=" . $item['step'],
                'content' => $_SESSION[LOGIN_USER_NAME].'������ٰ���? . $processType[$item['type']]['paras_desc'] . '���̡�'.$item['title'].'����'.$msgDesc,
                'approver' => $item['approve_user'],
                'smsFromUserId'=>$_SESSION[LOGIN_USER_ID],
            );
            $this->sendSms($smsParam);
        }
        
        return true;
    }
}
