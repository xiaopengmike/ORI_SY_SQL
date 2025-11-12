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
    const SYSTEM_ID = "customer_share";

    public function queryById($param)
    {
        $result = array();
        $main = $this->queryMain($param);
       
        $result = array(
            'main' => $main,
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
        $sql = " select k.*, u2.user_name from_username,u3.user_name to_username from inhe_customer_transfer k " .
            " left join user u on u.user_id = k.user_id " .
            " left join user u2 on u2.user_id = k.from_userid " .
            " left join user u3 on u3.user_id = k.to_userid " .
            " left join department d on d.dept_id = k.organ_id " .
            $strWhere;
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result = $item;
        }
        mysql_free_result($res);
        return $result;
    }

   

    // 提交
    public function submit()
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

    public function add($param)
    {
        $customerId = $this->addMain($param);
        if ($customerId <= 0) {
            return false;
        }
        $this->addShareUser($param);
        return true;
    }

    public function addMain($param)
    {
        $userId = $_SESSION['LOGIN_USER_ID'];
        $userName = $_SESSION['LOGIN_USER_NAME'];
        $deptId = $_SESSION['LOGIN_DEPT_ID'];
        $time = date('Y-m-d H:i:s');
        $mainSql = "insert into inhe_customer_transfer (
            organ_id,
            user_id,
            write_time,
            title,
            form_id,
            user_name,
            dept_name,
            remark,
            from_userid,
            to_userid,
            customer_ids,
            status,
            type, 
            user_bu) values" .
            "('" . $deptId . "', '" . $userId . "', '" . $time . "',  '" . $param['title'] . "', '" .
            $param['form_id'] . "', '" . $userName . "', '" . $param['dept_belong'] . "', '" . $param['remark'] . "', '" . 
            $param['from_userid'] . "', '" . $param['to_userid']. "', '" . $param['customer_ids'] . "', '" . $param['status'] . "', '" . $param['type'] . "', '" .$param['user_bu'] . "')";
        exequery(TD::conn(), $mainSql);
        $id = mysql_insert_id();
        return $id;
    }

    public function addShareUser($param)
    {
        $userSql = " select * from inhe_customer_data where find_in_set(id,'$param[customer_ids]') ";
        $res = exequery(TD::conn(), $userSql);
        $sql = "insert into inhe_customer_transfer_detail (customer_id,form_id,customer_name,transfer_type) values";
        $valSql="";
        while ($item = mysql_fetch_assoc($res)) {
            if (empty($item[id])) continue;
            $transfer_type = $param['from_userid']==$item[user_id]?"create":"share";
            $valSql .= "('" . $item['id'] ."','". $param['form_id'] . "','" . $item['customer_name'] . "','" . $transfer_type .  "'),";
        };
        if(!empty($valSql)){
            $sql .= rtrim($valSql, ',');
            $res = exequery(TD::conn(), $sql);
        }
        return true;
    }


    public function update($param)
    {
        $this->updateMainData($param);
        $this->updateShareUser($param);
        return true;
    }

    public function updateMainData($param)
    {
        $form = new FormModel();
        $paramInsert = array(
            'update_time' => date('Y-m-d H:i:s'),
            'title' => $param['title'],
            'customer_ids' => $param['customer_ids'],
            'from_userid' => $param['from_userid'],
            'to_userid' => $param['to_userid'],
            'remark' => $param['remark'],
            'status' => $param['status']
        );
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_customer_transfer';
        $result = $form->updateMainData($paramInsert, $condition, $tableName, array(), '');
        return $result;
    }
    public function updateShareUser($param)
    {
        $sql = "delete from inhe_customer_share_user where form_id = '" . $param['form_id'] . "' ";
        exequery(TD::conn(), $sql);
        $userSql = " select u.user_name,u.user_id,d.dept_name from user u left join department d on u.dept_id=d.dept_id where find_in_set(user_id,'$param[user_id]') ";
        $res = exequery(TD::conn(), $userSql);
        $sql = "insert into inhe_customer_share_user (user_id,customer_id,form_id,user_name,dept_name) values";
        $valSql="";
        while ($item = mysql_fetch_assoc($res)) {
            if (empty($item[user_id])) continue;
            $valSql .= "('" . $item['user_id'] . "','". $param['customer_id'] ."','". $param['form_id'] . "','" . $item['user_name']. "','" . $item['dept_name'] . "'),";
        };
        if(!empty($valSql)){
            $sql .= rtrim($valSql, ',');
            $res = exequery(TD::conn(), $sql);
        }
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
          //  'project_star' => $param['project_star'],
            'user_bu' => $param['user_bu']
        );
        $condition = array('form_id' => $param['related_id']);
        $tableName = 'inhe_customer_transfer';
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
        $sql = " delete from inhe_customer_transfer " . $where;
        exequery(TD::conn(), $sql);
        $sql = " delete from inhe_customer_transfer_detail " . $where;
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

}
