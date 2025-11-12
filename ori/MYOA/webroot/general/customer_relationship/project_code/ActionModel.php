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

        $sql = " select m.*,d.dept_name realDeptName from inhe_project_code m " .
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
        $param = $this->getParamToArray();
        $result = $this->add($param);
        return $result;
    }

    /**
     * 新增一个客户
     */
    public function add($param)
    {
        $newCode = $this->makeTempCode($param);
        $createTime = date('Y-m-d H:i:s');
        $deptId = $_SESSION['LOGIN_DEPT_ID'];
        $sql = "insert into inhe_temp_code(temp_code,project_name,location,scope_code,remark,"
            . "dept_id,user_id,user_name,create_time,company) values "
            . "('$newCode','$param[project_name]','$param[location]','$param[scope_code]',"
            . "'$param[remark]','$deptId','$param[user_id]','$param[user_name]',"
            . "'$createTime','$param[company]')";
        exequery(TD::conn(), $sql);
        $id = mysql_insert_id();
        $flag = false;
        if ($id > 0) {
            $flag = true;
        }
        $response = new ResponseCode();
        $result = $response->success($flag);
        return $result;
    }

    /**
     * 构造新临时项目代号代码
     */
    public function makeTempCode($param)
    {
        $form = new FormModel();
        $param = array(
            'fieldName' => 'temp_code',
            'tableName' => 'inhe_temp_code',
            'flowType'  => $this->nameRule[$param['scope_code']],
            'digit'     => '4'
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
        $time = date('Y-m-d H:i:s');

        $mainSql = "update inhe_project_code set update_time = '" . $time . "', continent='" . $param['continent'] . "', country='" . $param['country'] . "', "
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
