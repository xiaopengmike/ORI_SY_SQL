<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_field.php");
include_once("../common/CommonControl.php");  // 公共类
require_once '../common/ProcessModel.php';
require_once '../common/FormModel.php';

/**
 * 后台操作类
 * @author ysr
 */
class ActionModel
{
    const SYSTEM_ID = "project_participant";

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

    public function updateApproveOpinion()
    {
        $param = $this->getParamToArray();
        $process = new ProcessModel();
        $result = $process->updateProcessOpinion($param);
        return $result;
    }

    public function add($param)
    {
        $this->addMain($param);
        $this->addDetail($param);
        $response = new ResponseCode();
        $result = $response->success(true);
        return $result;
    }

    public function addMain($param)
    {
        $userId = $_SESSION['LOGIN_USER_ID'];
        $deptId = $_SESSION['LOGIN_DEPT_ID'];
        $time = date('Y-m-d H:i:s');
        $mainSql = "insert into inhe_project_participant (
            form_id,
            title,
            dept_belong,
            project_code,
            customer_name,
            leader_id,
            leader,
            proportion,
            status,
            organ_id,
            user_id,
            write_time,
            remark,
            type,
            user_bu
        ) values" .
            "('" . $param['form_id'] . "', '" . $param['title'] . "', '" . $param['dept_name'] . "', '" . $param['project_code'] .
            "', '" . $param['customer_name'] . "','" . $param['leader_id'] . "', '" . $param['leader'] . "', '" . $param['leader_proportion'] .
            "', '" . $param['status'] . "','" . $deptId . "', '" . $userId .
            "', '" . $time . "','" . $param['remark'] . "','" . $param['type'] . "','" . $param['user_bu'] . "')";
        exequery(TD::conn(), $mainSql);
        $id = mysql_insert_id();
        if ($id > 0) {
            return true;
        }
        return false;
    }

    public function addDetail($param)
    {
        if ($param['data_count'] <= 0) {
            return true;
        }
        for ($i = 1; $i <= $param['data_count']; $i++) {
            $str = $i == 0 ? '' : $i;
            $sql = "insert into inhe_project_participant_item (
                form_id,
                serial_number,
                user_id,
                user_name,
                proportion
                ) values" .
                "('" . $param['form_id'] . "', '" . $param['user_item' . $str] . "', '" . $param['user_id' . $str] . "', '" .
                $param['user_name' . $str] . "','" . $param['proportion' . $str] . "')";
            exequery(TD::conn(), $sql);
        }
        return true;
    }

    public function queryById($param)
    {
        $result = array();
        $form = new FormModel();
        $main = $form->queryMain($param);
        $detail = $form->queryDetail($param);
        $result = array(
            'main' => $main,
            'detail' => $detail
        );
        return $result;
    }

    public function update($param)
    {
        // var_export($param);exit;
        $this->updateMainData($param);
        $this->updateDetailData($param);
        return true;
    }

    public function updateMainData($param)
    {
        $form = new FormModel();
        $paramInsert = array(
            'update_time' => date('Y-m-d H:i:s'),
            'title' => $param['title'],
            'form_id' => $param['form_id'],
            'status' => $param['status'],
            'type' => $param['type'],
            'user_bu' => $param['user_bu'],
            'dept_belong' => $param['dept_name'],
            'project_code' => $param['project_code'],
            'customer_name' => $param['customer_name'],
            'leader_id' => $param['leader_id'],
            'leader' => $param['leader'],
            'remark' => $param['remark'],
            'proportion' => $param['leader_proportion']
        );
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_project_participant';
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
            'serial_number' => 'user_item',
            'user_id' => 'user_id',
            'user_name' => 'user_name',
            'proportion' => 'proportion'
        );
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_project_participant_item';
        $count = $param['data_count'];
        $result = $form->updateDetailData($normalField, $batchField, $param, $condition, $tableName, $count);
        $sql = "delete from inhe_project_participant_item where user_name = '' and form_id = '" . $param['form_id'] . "' ";
        exequery(TD::conn(), $sql);
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
        $this->changeDetailData($param);
        return true;
    }

    public function changeUpdate($param)
    {
        $this->updateMainData($param);
        $this->updateDetailData($param);
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
            'status' => $param['status'],
            'type' => $param['type'],
            'user_bu' => $param['user_bu'],
            'dept_belong' => $param['dept_name'],
            'project_code' => $param['project_name'],
            'customer_name' => $param['customer_name'],
            'leader_id' => $param['leader_id'],
            'leader' => $param['leader'],
            'proportion' => $param['leader_proportion']
        );
        $condition = array('form_id' => $param['related_id']);
        $tableName = 'inhe_project_participant';
        $result = $form->changeMainData($paramInsert, $condition, $tableName, array(), '');
        return $result;
    }

    public function changeDetailData($param)
    {
        $form = new FormModel();
        $normalField = array(
            'form_id' => 'form_id'
        );
        $batchField = array(
            'serial_number' => 'user_item',
            'user_id' => 'user_id',
            'user_name' => 'user_name',
            'proportion' => 'proportion'
        );
        $condition = array('form_id' => $param['related_id']);
        $tableName = 'inhe_project_participant_item';
        $count = $param['data_count'];
        $result = $form->changeDetailData($normalField, $batchField, $param, $condition, $tableName, $count);
        return $result;
    }

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
        
        $sql = " delete from inhe_project_participant " . $where;
        $re = exequery(TD::conn(), $sql);

        $sql = " delete from inhe_project_participant_item where form_id = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $sql);

        $sql = " delete from inhe_flow_opinion  where  form_id = '" . $param['id'] . "' ";
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
