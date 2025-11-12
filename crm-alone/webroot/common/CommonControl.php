<?php
include_once("../inc/auth.php");
include_once("../inc/utility_all.php");
include_once("../inc/utility_file.php");
include_once("../inc/utility_field.php");
include_once("../inc/utility_sms1.php");
include_once("../inc/utility_sms2.php");

/**
 * 公共操作类
 * @author ysr
 * 已有功能：权限查询；附件操作；参数获取；编码转换；消息发送；返回结果封装；
 * 邮件发送；下属部门获取；获取部门人员；获取公司归属；获取admin权限；翻页控件
 * 待补充：
 */
class CommonControlModel
{

    /**
     * 更新附件关联ID
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
     */
    public function returnData($data)
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

    /**
     * 邮件发送功能
     * @param array $param 
     * 参数说明：subject  fromId  sendTo  ccTo  content
     */
    public function newMailData($param)
    {
        $SEND_TIME = time();
        $SUBJECT = $param['subject'];  // 邮件主题
        $fromId = $param['fromId'];  // 银河电力集团HR02（常用）
        $SEND_TO = $param['sendTo'];  // 发送员工
        $CC_TO = $param['ccTo'];  // 抄送人员
        $CONTENT = $param['content'];  //邮件内容
        $result = array();

        $sql = "insert into MAIL (SUBJECT, FROM_ID, SEND_TO, CC_TO, SEND_TIME, REPLY_TIME) values"
            . "('$SUBJECT', '$fromId', '" . $SEND_TO . "', '" . $CC_TO . "', '$SEND_TIME','$SEND_TIME');";
        $cursor = exequery(TD::conn(), $sql);
        $MAIL_ID = mysql_insert_id();

        if ($MAIL_ID == 0) {
            $result = array(
                'msg' => '错误，写入数据库失败(MAIL)',
                'status' => 'failed'
            );
            return $result;
        }

        $sql = "insert into MAIL_BODY (MAIL_ID, FIRST, AUTHOR, REPLY_TIME, SUBJECT, CONTENT) values"
            . "('$MAIL_ID', '1', '$fromId', '$SEND_TIME', '$SUBJECT', '" . $CONTENT . "');";
        $cursor = exequery(TD::conn(), $sql);
        $BODY_ID = mysql_insert_id();

        if ($BODY_ID == 0) {
            $result = array(
                'msg' => '错误，写入数据库失败(MAIL_BODY)',
                'status' => 'failed'
            );
            return $result;
        }

        $TO_ID_STR = $SEND_TO . $CC_TO;
        $TO_ID_STR .= find_id($TO_ID_STR, $fromId) ? "" : $fromId . ",";

        $sql = "";
        $TO_ARRAY = explode(",", $TO_ID_STR);
        // 发送人员数组数据去重
        $TO_ARRAY = array_flip($TO_ARRAY);
        $TO_ARRAY = array_flip($TO_ARRAY);

        foreach ($TO_ARRAY as $TO_ID) {
            if ($TO_ID == "") {
                continue;
            }
            $sql .= "('$MAIL_ID', '$TO_ID', '0', '0', '0', '0'),";
        }

        $sql = "insert into MAIL_TO (MAIL_ID, TO_ID, READ_FLAG, READ_COUNT, FIRST_READ, LAST_READ) values " . td_trim($sql);
        $cursor = exequery(TD::conn(), $sql);
        if (mysql_affected_rows() < 0) {
            $result = array(
                'msg' => '邮件发送失败',
                'status' => 'failed'
            );
            return $result;
        }

        $SMS_CONTENT = "请查收我的邮件！\n主题：$SUBJECT";
        $TO_ID_STR = check_id($fromId, $SEND_TO . $CC_TO, false);
        send_sms("", $fromId, $TO_ID_STR, 2, $SMS_CONTENT, "1:mail/my/read.php?MAIL_ID=$MAIL_ID");

        $result = array(
            'msg' => '邮件发送成功',
            'status' => 'success'
        );

        return $result;
    }

    /**
     * 根据deptId获取所有归属部门集合（包括本部门）
     */
    public function getSubDept($deptId)
    {
        if ($deptId == '' || $deptId == null) {
            $deptId = $_SESSION['LOGIN_DEPT_ID'];
        }

        $subDeptSql = " call getChild($deptId); ";
        $res = exequery(TD::conn(), $subDeptSql);
        $result = '';
        while ($item = mysql_fetch_assoc($res)) {
            $result = $item;
        }
        mysql_free_result($res);

        return $result;
    }

    /**
     * 根据部门ID获取所有对应的用户信息
     */
    public function getUsersByDept($deptId, $whereStr = '')
    {
        if ($deptId == '' || $deptId == null) {
            $deptId = $_SESSION['LOGIN_DEPT_ID'];
        }

        $userSql = " select u.uid, u.user_id, u.user_name, u.byname, u.user_byid, u.user_bu, u.dept_id, d.dept_name 
                    from user u 
                    left join department d on d.dept_id = u.dept_id 
                    where u.dept_id in($deptId) ";

        if ($whereStr != '' && $whereStr != null) {
            $userSql .= $whereStr;
        }

        $res = exequery(TD::conn(), $userSql);
        $result = array();
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
        }
        mysql_free_result($res);

        return $result;
    }

    /**
     * 针对user表公司部分人员未设置公司归属userBu，采用department表的userBu获取公司归属信息
     * 根据deptId获取userBu
     */
    function getUserBuNew($deptId)
    {
        // 公司公司归属
        $comSql = " select user_bu from department where dept_id = '" . $deptId . "' ";
        $comRes = exequery(TD::conn(), $comSql);
        $com = mysql_fetch_assoc($comRes);
        $company = "";
        $company = $com['user_bu'];
        return $company;
    }

    /**
     * 获取指定用户信息
     */
    public function getUserInfo($userId)
    {
        $result = array();
        $where = " where 1=1 ";
        if (empty($userId)) {
            return $result;
        } else {
            $where .= " and u.user_id = '" . $userId . "' ";
        }

        $sql = " select u.*, d.dept_name deptName, c.id companyId, c.company companyName from user u " .
            " left join department d on d.dept_id = u.dept_id " .
            " left join company c on c.user_bu = d.user_bu " . $where;
        $res = exequery(TD::conn(), $sql);
        $result = mysql_fetch_assoc($res);

        return $result;
    }

    /**
     * 获取用户对应模块的查询权限
     * 流程类型从inhe_hr_flow_type获取
     */
    public function getAdminDataQuery($userId, $deptId, $type)
    {
        $isAdmin = $systemAdmin = false;
        $where = " where 1=1 ";
        if ($userId != "") {
            $where .= " and user_id = '" . $userId . "' ";
        } else {
            if ($deptId != "") {
                $where .= " and dept_id = '" . $deptId . "' ";
            }
        }
        if ($type != "") {
            $where .= " and type = '" . $type . "' ";
        }
        $sql = " select user_bu, dept_ids, user_ids, is_admin, system_admin from inhe_system_admin " . $where . " order by id desc ";
        $res = exequery(TD::conn(), $sql);
        $comArr = $userIds = $deptIds = $result = array();
        while ($item = mysql_fetch_assoc($res)) {
            if ($item['user_bu'] != '') {
                $comArr = explode(',', $item['user_bu']);
            }
            if ($item['user_ids'] != '') {
                $userIds = explode(',', $item['user_ids']);
            }
            if ($item['dept_ids'] != '') {
                $deptIds = explode(',', $item['dept_ids']);
            }
            $isAdmin = $item['is_admin'];
            $systemAdmin = $item['system_admin'];
        }
        if (count($comArr) > 0) {
            $comStr = "";
            foreach ($comArr as $v) {
                $comStr .= "'" . $v . "',";
            }
        }
        $comStr = rtrim($comStr, ',');

        if (count($userIds) > 0) {
            $userIdStr = "";
            foreach ($userIds as $kv) {
                $userIdStr .= "'" . $kv . "',";
            }
        }
        $userIdStr = rtrim($userIdStr, ',');

        if (count($deptIds) > 0) {
            $deptIdStr = "";
            foreach ($deptIds as $kv) {
                $deptIdStr .= "'" . $kv . "',";
            }
        }
        $deptIdStr = rtrim($deptIdStr, ',');

        // 释放结果集，减少内存占用
        mysql_free_result($res);
        $result = array(
            'systemAdmin' => $systemAdmin,  // 系统管理员权限开放，包括数据和功能权限
            'isAdmin' => $isAdmin,  // 数据权限开放
            'userBu' => $comStr,  // 公司范围权限
            'deptIds' => $deptIdStr,  // 部门范围权限
            'userIds' => $userIdStr  // 指定用户范围权限
        );

        return $result;
    }

    /**
     * 翻页控件构造
     * @param $current_page 当前页；$total_items 总数；$page_size 每页显示条数，默认10条；$script_href 查询函数，默认为loadList()；$show_total 是否显示总数
     * 参考范例： <?= $model->page_bar($param['page'], $count, $param['page_size'], 'loadList(\'%s\')', '1') ?>
     */
    public function page_bar($current_page, $total_items, $page_size = 10, $script_href = NULL, $show_total = 0)
    {
        $current_start_item =  ($current_page - 1) * $page_size;
        $page_total = ceil($total_items / $page_size);
        if ($current_start_item < 0 || $total_items < $current_start_item) {
            $current_start_item = 0;
        }
        $num_pages = ceil($total_items / $page_size);
        $cur_page = ceil($current_start_item / $page_size) + 1;
        $result_str .= "<script>function goto_page(){var page=parseInt(document.getElementById('page').value);" .
            "if(isNaN(page)||page<1||page>" . $num_pages . "){alert(\"" . sprintf(_("页数必须为1-%s"), $num_pages) . "\");return false;}" .
            sprintf(str_replace("'", "", $script_href), "page") . ";} " .
            "function input_page_no(){if(event.keyCode==13) goto_page();if(event.keyCode<47||event.keyCode>57) event.returnValue=false;}</script>";
        if ($show_total == 1) {
            $result_str .= "<div id=\"pageArea\" class=\"pageArea\" style=\"width:350px\">\n";
            $result_str .= sprintf(_("共%s条") . "&nbsp;", "<span id=\"pageNumber\" class=\"pageNumber\">" . $total_items . "</span>");
        } else {
            $result_str .= "<div id=\"pageArea\" class=\"pageArea\">\n";
        }
        $result_str .= sprintf(_("第%s页"), "<span id=\"pageNumber\" class=\"pageNumber\">" . $cur_page . "/" . $num_pages . "</span>");
        if ($cur_page <= 1) {
            $result_str .= "<a href=\"javascript:;\" id=\"pageFirst\" class=\"pageFirstDisable\" title=\"" . _("首页") . "\"></a>\r\n  " .
                "<a href=\"javascript:;\" id=\"pagePrevious\" class=\"pagePreviousDisable\" title=\"" . _("上一页") . "\"></a>";
        } else {
            $result_str .= "<a href=\"javascript:void(0);\" onclick=\"" . sprintf($script_href, '1') . ";return false;\" id=\"pageFirst\" class=\"pageFirst\" title=\"" . _("首页") . "\"></a>\r\n  " .
                "<a href=\"javascript:void(0);\" onclick=\"" . sprintf($script_href, $cur_page - 1) . ";return false;\" id=\"pagePrevious\" class=\"pagePrevious\" title=\"" . _("上一页") . "\"></a>";
        }
        if ($num_pages <= $cur_page) {
            $result_str .= "<a href=\"javascript:;\" id=\"pageNext\" class=\"pageNextDisable\" title=\"" . _("下一页") . "\"></a>\r\n  " .
                "<a href=\"javascript:;\" id=\"pageLast\" class=\"pageLastDisable\" title=\"" . _("末页") . "\"></a>";
        } else {
            $result_str .= "<a href=\"javascript:void(0);\" onclick=\"" . sprintf($script_href, $cur_page + 1) . ";return false;\" id=\"pageNext\" class=\"pageNext\" title=\"" . _("下一页") . "\"></a>\r\n  " .
                "<a href=\"javascript:void(0);\" id=\"pageLast\" onclick=\"" . sprintf($script_href, $page_total) . ";return false;\" class=\"pageLast\" title=\"" . _("末页") . "\"></a>";
        }
        $result_str .= sprintf(_("转到 第 %s 页 "), "<input type=\"text\" size=\"3\" class=\"SmallInput\" name=\"page\" id=\"page\" " .
            "onkeypress=\"input_page_no()\" style='text-align:center;'>") .
            "<a href=\"javascript:goto_page();\" id=\"pageGoto\" class=\"pageGoto\" title=\"" . _("转到") . "\"></a>";
        $result_str .= "</div>";


        return $result_str;
    }

    public function flowManage()
    {
        $result = array();

        $sql = " select * from inhe_flow_manage ";
        $res = exequery(TD::conn(), $sql);

        while ($item = mysql_fetch_assoc($sql)) {
            $result[] = $item;
        }

        mysql_free_result($res);
    }

    /**
     * 流程操作
     * 
     */
    public function flowOperate($param)
    {
        $action = $param['operate'];

        include_once("Operation.php");
        //调用
        $obj = new Operation();
        $obj->operateData(new $action());
    }

    public function approveFlow()
    {
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

        $sql = "select k.* from country_code k " . $where;
        $res = exequery(TD::conn(), $sql);
        $result = array();
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
        }

        mysql_free_result($res);

        return $result;
    }

    /**
     * 获取对应数据
     */
    public function queryData()
    {
        $param = $this->getParamToArray();

        $dataType = "";
        if (array_key_exists("dataType", $param) && $param['dataType'] != "") {
            $dataType = $param['dataType'];
        } else {
            $result = array(
                'code' => 500,
                'msg' => '请求数据错误'
            );
        }

        $data = $this->$dataType($param);
        $result = array(
            'code' => 200,
            'data' => $data,
            'msg' => '获取数据成功'
        );

        return $result;
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
    public function createFlowId($paras)
    {
        $flag = false;
        $curDate = date("Ymd", time());
        $month = date("Ym");
        $year = date("Y");
        $century = date("yy");
        $where = " where 1=1 ";

        if (array_key_exists('fieldName', $paras) && $paras['fieldName'] != "") {
            $fieldName = $paras['fieldName'];
        } else {
            $flag = true;
        }
        if (array_key_exists('tableName', $paras) && $paras['tableName'] != "") {
            $tableName = $paras['tableName'];
        } else {
            $flag = true;
        }
        if (array_key_exists('flowType', $paras) && $paras['flowType'] != "") {
            $flowType = $paras['flowType'];
        } else {
            $flag = true;
        }
        // 计数位数
        if (array_key_exists('digit', $paras) && $paras['digit'] != "") {
            $digit = $paras['digit'];
        } else {
            $digit = 3;  // 默认采用3位计数位，如001
        }
        // 按日期重置计数规则: 按日、月、年或不重置
        if (array_key_exists('reset', $paras) && $paras['reset'] != "") {
            if ($paras['reset'] == "day") {
                $where .=  " and " . $fieldName . " like '%" . $flowType . $curDate . "%' ";
            } else if ($paras['reset'] == "month") {
                $where .=  " and " . $fieldName . " like '%" . $flowType . $month . "%' ";
            } else if ($paras['reset'] == "year") {
                $where .=  " and " . $fieldName . " like '%" . $flowType . $year . "%' ";
            }
        } else {
            // 默认1个世纪重置一次计数起始
            $where .=  " and " . $fieldName . " like '%" . $flowType . $century . "%' ";
        }

        if ($flag) {
            return $curDate . sprintf("%0" . $digit . "d", 1); // 默认编号规则：20200804001
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
     * 获取审核人员
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

        $sql = " select k.* from inhe_flow_manage k " . $where;
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
        }
        mysql_free_result($res);

        return $result;
    }

    public function nextApprover($param)
    {
        $flag = false;
        $where = " where 1=1 ";
        if (array_key_exists('type', $param) && $param['type']) {
            $where .= " and k.type = '" . $param['type'] . "' ";
        } else {
            $flag = true;
        }
        if (array_key_exists('user_bu', $param) && $param['user_bu']) {
            $where .= " and k.user_bu = '" . $param['user_bu'] . "' ";
        } else {
            $flag = true;
        }
        if ($flag) {
            return;
        }
        $approverArr = $this->getApprover($param);
        $nextStep = $approverArr[0]['next_step'];
        if (!empty($nextStep)) {
            $where .= " and k.step in($nextStep) ";
        } else {
            return $result;
        }

        $sql = " select step, role, approver, operate from inhe_flow_manage k " . $where . " order by step  ";
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
        }
        mysql_free_result($res);

        return $result;
    }

    public function getBackStep($param)
    {
        $flag = false;
        $where = " where status = 'H' and step < $param[step] ";
        if (array_key_exists('type', $param) && $param['type']) {
            $where .= " and k.type = '" . $param['type'] . "' ";
        } else {
            $flag = true;
        }
        if (array_key_exists('form_id', $param) && $param['form_id']) {
            $where .= " and k.form_id = '" . $param['form_id'] . "' ";
        } else {
            $flag = true;
        }
        if ($flag) {
            return;
        }

        $sql = " select step, work_flow role, approve_user approver, 'approve' operate from inhe_flow_opinion k " . $where . " order by step  ";
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
        }
        mysql_free_result($res);

        return $result;
    }

    public function updateFlowData($param)
    {
        $handleStatus = "H";

        $where = " where form_id = '" . $param['form_id'] . "' and type ='" . $param['type'] . "' and approve_user = '" . $param['prev_writer'] . "' and status = 'U' ";
        $updateSql = " update inhe_flow_opinion set approve_time = '" . $param['prev_time'] . "', status = '" . $handleStatus . "' " . $where;
        exequery(TD::conn(), $updateSql);

        if (isset($param['step']) && !empty($param['step'])) {
            $userArr = explode(',', $param['approve_user']);
            foreach ($userArr as $approveUser) {
                if (!empty($approveUser)) {
                    $sql = " insert into inhe_flow_opinion (type, form_id, title, step, work_flow, approve_user, status, prev_task, prev_writer, prev_time, " .
                        " opinion, project_star, have_manager, extra, if_agree, signature, user_bu) values ('" .
                        $param['type'] . "','" . $param['form_id'] . "','" . $param['title'] . "','" .
                        $param['step'] . "','" . $param['work_flow'] . "','" . $approveUser . "','" . $param['status'] . "','" .
                        $param['prev_task'] . "','" . $param['prev_writer'] . "','" . $param['prev_time'] . "','" . $param['opinion'] . "','" .
                        $param['project_star'] . "','" . $param['have_manager'] . "','" . $param['extra'] . "','" . $param['if_agree'] . "','" .
                        $param['signature'] . "','" . $param['user_bu'] . "'" .
                        ")";
                    $res = exequery(TD::conn(), $sql);
                }
            }
        }

        return true;
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

        $sql = " select f.id, f.paras_group, f.type, f.type_desc, f.bz, f.paras_desc, f.paras_value, f.sub_group, extra, is_used, user_bu 
                from inhe_oa_paras f " . $strWhere . " order by sort_code ";

        $result = array();
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result[$item['type']][] = $item;
        }
        mysql_free_result($res);

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
        $sql = " select m.field_name, m.colum_name, m.field_type, m.verify, m.css, m.other_info from inhe_report_column m  " . $where . $orderBy;
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

    public function createReportTitle($column)
    {
        $title = '<tr>';
        foreach ($column as $cl) {
            $title .= '<td></td>';
        }
        $title .= '</tr>';

        return $title;
    }

    public function createReportLine($field)
    {
        foreach ($field as $fd) {
        }
    }

    public function addLineHtml()
    {
    }

    public function verifyProjectCode()
    {
        $param = $this->getParamToArray();
        $where = " where 1=1 ";
        $flag = false;
        if (array_key_exists("projectCode", $param) && $param['projectCode'] != "") {
            $where .= " and k.project_code = '" . $param['projectCode'] . "' ";
        } else {
            return $flag;
        }

        $sql = "select count(1) count from inhe_project_main k " . $where;
        $res = exequery(TD::conn(), $sql);
        $item = mysql_fetch_assoc($res);
        mysql_free_result($res);
        if ($item['count'] > 0) {
            $flag = true;
        }

        return $flag;
    }

    /**
     * 获取所属客户信息数据列表
     * 数据权限范围：登记人，
     * 使用模块：立项代理商、招标方、买方；合同评审合同相对方
     */
    public function getCustomerList()
    {
        $userId = $_SESSION['LOGIN_USER_ID'];
        $param = $this->getParamToArray();
        $where = " where 1=1 and k.user_id = '" . $userId . "' ";
        $orderBy = ' order by k.id desc ';

        $sql = "select k.id, k.customer_name from inhe_customer_data k " . $where . $orderBy;
        $res = exequery(TD::conn(), $sql);
        $result = array();
        while ($item = mysql_fetch_assoc($res)) {
            $result[$item['id']] = $item['customer_name'];
        }
        mysql_free_result($res);

        $sql2 = "select customer_id id, customer_name from inhe_customer_share where user_ids = '" . $userId . "' ";
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

        $sql = "select k.customer_name, k.short_name, cc.zh_name country_desc, c.name contact_name, c.phone_one contact_phone, c.email, k.summarize "
            . " from inhe_customer_data k "
            . " left join inhe_customer_contact c on c.customer_id = k.id "
            . " left join country_code cc on cc.iso_three = k.country "
            . $where  . " order by k.id, c.id desc ";
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
        $where = " where 1=1 and p.if_project != '01' "; // 已立项项目代号除外
        if (array_key_exists("project_code", $param) && $param['project_code'] != "") {
            $where .= " and m.project_code = '" . $param['project_code'] . "' ";
        }
        if (array_key_exists("user_id", $param) && $param['user_id'] != "") {
            $where .= " and m.user_id = '" . $param['user_id'] . "' ";
        }

        $sql = "select m.*,d.dept_name realDeptName, c.company company_desc from inhe_project_code m "
            . " left join inhe_project_main p on p.project_code = m.project_code "
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

        $sql = "select m.*,d.dept_name realDeptName, c.company company_desc from inhe_temp_code m "
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
            $where .= " and k.id = '" . $param['id'] . "' ";
        }

        $sql = "select k.customer_name, cc.contract_party, u.user_name create_user "
            . " from inhe_customer_data k "
            . " left join inhe_customer_company cc on cc.customer_id = k.id "
            . " left join user u on u.user_id = k.user_id "
            . $where;
        $res = exequery(TD::conn(), $sql);
        $result = array();
        while ($item = mysql_fetch_assoc($res)) {
            $result['customer_name'] = $item['customer_name'];
            $result['create_user'] = $item['create_user'];
            $result['contract_party'][] = $item['contract_party'];
        }

        mysql_free_result($res);

        return $result;
    }



    public function queryShareList($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";

        if (array_key_exists("id", $param) && $param['id'] != "") {
            $strWhere .= " and k.customer_id = '" . $param['id'] . "' ";
        } else {
            return $result;
        }

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
        $sql = " select * from inhe_project_code where project_code = '$newCode' ";
        $res = exequery(TD::conn(), $sql);
        if (mysql_fetch_assoc($res)) {
            $this->getFormalProjectCode($param);
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
     * 获取审核流程数据
     */
    public function getFlowData($param)
    {
        $where = " where 1=1 ";
        if (array_key_exists("form_id", $param) && $param['form_id'] != "") {
            $where .= " and m.form_id = '" . $param['form_id'] . "' ";
        }
        if (array_key_exists("user_id", $param) && $param['user_id'] != "") {
            $where .= " and m.user_id = '" . $param['user_id'] . "' ";
        }

        $sql = "select m.*,d.dept_name realDeptName, c.company company_desc from inhe_temp_code m "
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

    /**
     * 获取项目参与人数据
     */
    public function findParticipantData($param)
    {
        $where = " where 1=1 ";
        if (array_key_exists("project_code", $param) && $param['project_code'] != "") {
            $where .= " and m.project_code = '" . $param['project_code'] . "' ";
        }
        $sql = " select m.project_code, m.leader_id, m.leader, m.proportion leader_proportion, " .
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
        }
        $sql = " select p.contract_party from inhe_customer_data m " .
            " left join inhe_customer_company p on p.customer_id = m.id " . $where;
        $res = exequery(TD::conn(), $sql);
        $result = array();
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item;
        }
        mysql_free_result($res);

        array_push($result, $param['customer_name']);
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
        $sql = " select m.* from inhe_product_detail m " . $where . $orderBy;
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
        $sql = " select m.* from inhe_customer_data m " . $where;
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
}
