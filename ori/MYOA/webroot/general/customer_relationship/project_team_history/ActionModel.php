<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_field.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");
include_once("../common/CommonControl.php");  // 公共类
require_once("../common/ResponseCode.php");
require_once("../common/FormModel.php");

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
    public $systemId = "project_code";
    public $nameRule = array(
        'D' => 'A',
        'F' => 'B',
    );

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

    public function queryMain($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";

        if (array_key_exists("id", $param) && $param['id'] != "") {
            $strWhere .= " and m.id = '" . $param['id'] . "' ";
        } else {
            return $result;
        }

        $sql = " select m.*,d.dept_name realDeptName from inhe_project_team m " .
            " left join department d on d.dept_id = m.dept_id " . $strWhere;
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
            $flag = $this->$result['action']($result['param']);
            //$result = $form->makeProcessData($result['param'], $flag);
        }
        $response = new ResponseCode();
        $result = $response->success($flag);
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
            //$result = $form->makeProcessData($result['param'], $flag);
        }
        $response = new ResponseCode();
        $result = $response->success($flag);
        return $result;
    }


    /**
     * 新增一个客户
     */
    public function add($param)
    {
        //$form_id = $this->makeTempCode($param);
        $createTime = date('Y-m-d H:i:s');
        $deptId = $_SESSION['LOGIN_DEPT_ID'];
        $userId = $_SESSION['LOGIN_USER_ID'];
        $sql = "INSERT INTO `inhe_project_team` (`user_bu`, `dept_id`, `team_name`, `form_id`, `business_owner`, `commerce_owner`, `technical_support_owner`, `business`, `commerce`,
         `technical_support`, `region`, `update_time`, `update_user_id`, `business_name`, `commerce_name`, `technical_support_name`, `business_owner_name`, `commerce_owner_name`,
          `technical_support_owner_name`) VALUES
         ('$param[user_bu]', '$deptId',  '$param[team_name]', '$param[form_id]', '$param[business_owner]', '$param[commerce_owner]',
          '$param[technical_support_owner]', '$param[business]', '$param[commerce]', '$param[technical_support]', '$param[region]', '$createTime', '$userId', '$param[business_name]',
           '$param[commerce_name]','$param[technical_support_name]', '$param[business_owner_name]', '$param[commerce_owner_name]','$param[technical_support_owner_name]');
        ";
        exequery(TD::conn(), $sql);
        $sql = "INSERT INTO `inhe_project_team_history` (`user_bu`, `dept_id`, `team_name`, `form_id`, `business_owner`, `commerce_owner`, `technical_support_owner`, `business`, `commerce`,
         `technical_support`, `region`, `update_time`, `update_user_id`, `business_name`, `commerce_name`, `technical_support_name`, `business_owner_name`, `commerce_owner_name`,
          `technical_support_owner_name`,`type`) VALUES
         ('$param[user_bu]', '$deptId',  '$param[team_name]', '$param[form_id]', '$param[business_owner]', '$param[commerce_owner]',
          '$param[technical_support_owner]', '$param[business]', '$param[commerce]', '$param[technical_support]', '$param[region]', '$createTime', '$userId', '$param[business_name]',
           '$param[commerce_name]','$param[technical_support_name]', '$param[business_owner_name]', '$param[commerce_owner_name]','$param[technical_support_owner_name]','add');
        ";
        exequery(TD::conn(), $sql);
        $id = mysql_insert_id();
        $flag = false;
        if ($id > 0) {
            $flag = true;
        }
        return $flag;
    }

    /**
     * 构造新临时项目代号代码
     */
    public function makeTempCode($param)
    {
        $form = new FormModel();
        $param = array(
            'fieldName' => 'form_id',
            'tableName' => 'inhe_project_team',
            'flowType'  => 'PT',
            'digit'     => '3'
        );
        $newCode = $form->createFlowId($param, true);
        return $newCode;
    }

    /**
     * 更新词条数据
     */
    public function update($param)
    {
        $flag = false;

        $flag = $this->updateData($param);

        if (!$flag) {
            return false;
        }

        return true;
    }

    public function updateData($param)
    {
        $createTime = date('Y-m-d H:i:s');
        $deptId = $_SESSION['LOGIN_DEPT_ID'];
        $userId = $_SESSION['LOGIN_USER_ID'];
        
       
        $mainSql = "UPDATE `TD_OA`.`inhe_project_team` SET `user_bu` = '$param[user_bu]', `dept_id` = '$deptId', `team_name` = '$param[team_name]', 
         `business_owner` = '$param[business_owner]', `commerce_owner` = '$param[commerce_owner]',`technical_support_owner` = '$param[technical_support_owner]',
          `business` = '$param[business]', `commerce` = '$param[commerce]', `technical_support` = '$param[technical_support]', `region` = '$param[region]', 
          `update_time` = '$createTime', `update_user_id` = '$userId',`business_name` = '$param[business_name]', `commerce_name` = '$param[commerce_name]',
           `technical_support_name` = '$param[technical_support_name]', `business_owner_name` = '$param[business_owner_name]',
           `commerce_owner_name` = '$param[commerce_owner_name]', `technical_support_owner_name` = '$param[technical_support_owner_name]' WHERE `id` = $param[id];
        ";
        $res = exequery(TD::conn(), $mainSql);
        $sql = "INSERT INTO `inhe_project_team_history` (`user_bu`, `dept_id`, `team_name`, `form_id`, `business_owner`, `commerce_owner`, `technical_support_owner`, `business`, `commerce`,
        `technical_support`, `region`, `update_time`, `update_user_id`, `business_name`, `commerce_name`, `technical_support_name`, `business_owner_name`, `commerce_owner_name`,
         `technical_support_owner_name`,`type`) VALUES
        ('$param[user_bu]', '$deptId',  '$param[team_name]', '$param[form_id]', '$param[business_owner]', '$param[commerce_owner]',
         '$param[technical_support_owner]', '$param[business]', '$param[commerce]', '$param[technical_support]', '$param[region]', '$createTime', '$userId', '$param[business_name]',
          '$param[commerce_name]','$param[technical_support_name]', '$param[business_owner_name]', '$param[commerce_owner_name]','$param[technical_support_owner_name]','update');
       ";
       exequery(TD::conn(), $sql);
        
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
