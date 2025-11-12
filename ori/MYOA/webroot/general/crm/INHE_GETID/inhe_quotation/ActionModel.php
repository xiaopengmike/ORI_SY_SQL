<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_field.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

/**
 * 模板库数据库操作类
 */

class ActionModel
{

    // 根据项目代号获取项目比例数据
    public function query($pmId)
    {
        $mainSql = " select k.*, u.user_name approver, u.dept_id, d.dept_name from inhe_pm_proportion k " .
            " left join user u on u.user_id = k.Reviewed_ID " .
            " left join department d on d.dept_id = u.dept_id " .
            " where k.PM_ID = '" . $pmId . "' ";
        $res = exequery(TD::conn(), $mainSql);

        $subSql = " select p.* from inhe_pm_proportion_item p where p.PM_ID = '" . $pmId . "' ORDER BY p.user_item asc ";
        $res1 = exequery(TD::conn(), $subSql);

        $mainResult = mysql_fetch_assoc($res);
        while ($item = mysql_fetch_assoc($res1)) {
            $subResult[] = $item;
        }

        return array('mainResult' => $mainResult, 'subResult' => $subResult);
    }

    public function add()
    {
        
        $sql3="select max(SC_NO)SC_NO from inhe_crm_quotation where SC_NO LIKE  'INHEQP".date('Ymd')."%'";
        $res3=exequery(TD::conn(),$sql3);
         if($ROW3=mysql_fetch_array($res3)){
	
            $SC_NO='INHEQP'.date('Ymd').sprintf("%02d",(substr($ROW3["SC_NO"],-2)+1));
	       }
           else
           {            
             $SC_NO='INHEQP'.date('Ymd').'01';
           }        
 
         
        
        $paras = $this->getParamToArray();
        // array (
        //     'pm_id' => 'GA',
        //     'dept_name' => '董事长(Chairman)',
        //     'menu_parent' => '2',
        //     'user_name' => 'INHE-0909',
        //     'data_line' => '',
        //     'user_item1' => '1',
        //     'user_id1' => '沈怡青',
        //     'proportion1' => '100',
        //     'action' => 'Add',
        //     'sub' => '提交',
        // );
        // var_export($paras);exit;
        $userId = $_SESSION['LOGIN_USER_ID'];
        $userName = $_SESSION['LOGIN_USER_NAME'];
        $time = date('Y-m-d H:i:s');
        $paras['Reviewed_ID'] = $paras['user_name'];
        // select * from inhe_pm_proportion;  // 主表带审核记录
        // select * from inhe_pm_proportion_item;  // 项目人员比例详细数据表
        // select * from inhe_pm_proportion_history;  // 项目人员比例历史记录表
   //     $mainSql = "insert into inhe_pm_proportion (PM_ID, user_id, user_name, Reviewed_ID, Create_date) values" .
//            "('" . $paras['pm_id'] . "', '" . $userId . "', '" . $userName . "', '" . $paras['Reviewed_ID'] . "', '" . $time . "')";
         
         $mainSql = "insert into inhe_crm_quotation(SC_NO, PM_ID, USER_NAME, USER_ID, Create_date) values" .
           "('" . $SC_NO . "', '" . $paras['PM_ID'] . "', '" . $paras['USER_NAME'] . "', '" . $userId . "', '" . $time . "')";

        $res1 = exequery(TD::conn(), $mainSql);
        // var_export($res1);exit;

        //for ($i = 1; $i <= $paras['data_count']; $i++) {
//            // user_id未获取 待补充
//            $itemSql = "insert into inhe_pm_proportion_item (PM_ID, user_item, user_id, user_name, proportion) values" .
//                "('" . $paras['pm_id'] . "', '" . $paras['user_item' . $i] . "', '" . $paras['user_id' . $i] . "', '" . $paras['user_name' . $i] . "', '" . $paras['proportion' . $i] . "')";
//
//            $historySql = "insert into inhe_pm_proportion_history (PM_ID, user_item, user_id, user_name, proportion, Create_date) values" .
//                "('" . $paras['pm_id'] . "', '" . $paras['user_item' . $i] . "', '" . $paras['user_id' . $i] . "', '" . $paras['user_name' . $i] . "', '" . $paras['proportion' . $i] . "', '" . $time . "')";
//
//            // var_export($itemSql);exit;
//            $res2 = exequery(TD::conn(), $itemSql);
//            $res3 = exequery(TD::conn(), $historySql);
//
//            if ($paras['sub'] == '提交') {
//                // 提交后发送消息提醒审核人
//                $smsParas = array(
//                    'url' => 'hr/pm_manage/index.php',
//                    'content' => $userName . '提交项目比例记录，请及时审核',
//                    'approver' => $paras['Reviewed_ID']
//                );
//                $this->sendSms($smsParas);
//            }
//        }


        return true;
    }

//    /**
//     * 更新项目比例数据
//     */
//    public function update()
//    {
//        // 获取传递的参数组成数组
//        $paras = $this->getParamToArray();
//        // var_export($paras);exit;
//
//        // $userId = $_SESSION['LOGIN_USER_ID'];
//        // $userName = $_SESSION['LOGIN_USER_NAME'];
//        $time = date('Y-m-d H:i:s');
//        $paras['Reviewed_ID'] = $paras['user_name'];
//
//        $mainSql = "update inhe_pm_proportion set Reviewed_ID = '" . $paras['Reviewed_ID'] . "', update_date = '" . $time . "' where PM_ID = '" . $paras['pm_id'] . "' ";
//        $res1 = exequery(TD::conn(), $mainSql);
//
//        $deleteSql = " delete from inhe_pm_proportion_item where PM_ID = '" . $paras['pm_id'] . "' ";
//        $res4 = exequery(TD::conn(), $deleteSql);
//
//        for ($i = 1; $i <= $paras['data_count']; $i++) {
//            // user_id未获取 待补充
//            $itemSql = "insert into inhe_pm_proportion_item (PM_ID, user_item, user_id, user_name, proportion) values" .
//                "('" . $paras['pm_id'] . "', '" . $paras['user_item' . $i] . "', '" . $paras['user_id' . $i] . "', '" . $paras['user_name' . $i] . "', '" . $paras['proportion' . $i] . "')";
//
//            $historySql = "insert into inhe_pm_proportion_history (PM_ID, user_item, user_id, user_name, proportion, Create_date) values" .
//                "('" . $paras['pm_id'] . "', '" . $paras['user_item' . $i] . "', '" . $paras['user_id' . $i] . "', '" . $paras['user_name' . $i] . "', '" . $paras['proportion' . $i] . "', '" . $time . "')";
//
//            // var_export($itemSql);exit;
//            $res2 = exequery(TD::conn(), $itemSql);
//            $res3 = exequery(TD::conn(), $historySql);
//        }
//
//
//        return true;
//    }

    public function delete()
    {
        $paras = $this->getParamToArray();

        $result = array();
        $where = " where 1=1 ";
        if (array_key_exists('id', $paras) && $paras['id']) {
            $where .= " and PM_ID = '" . $paras['id'] . "' ";
        } else {
            $result = array(
                'data' => '',
                'status' => '',
                'msg' => ''
            );
        }

        // 删除主表数据
        $sql = " delete from inhe_pm_proportion k " . $where;
        $re = exequery(TD::conn(), $sql);

        // 删除项目比例表数据
        $sql = " delete from inhe_pm_proportion_item  p " . $where;
        $re = exequery(TD::conn(), $sql);

        return true;
    }

    public function approve()
    {
        $paras = $this->getParamToArray();
        $time = date('Y-m-d H:i:s');
        $sql = "update inhe_pm_proportion set Reviewed_content = '" . $paras['reviewed_content'] . "',  Reviewed_date = '" . $time . "', " .
            "Reviewed = '" . $paras['reviewed'] . "' where PM_ID = '" . $paras['pm_id'] . "' ";
        $re = exequery(TD::conn(), $sql);

        return true;
    }

    /**
     * 获取前台传递的get和post参数值
     */
    public function getParamToArray()
    {
        $postParam = eval(" return " . @iconv("utf-8//IGNORE", "gbk", var_export($_POST, true)) . ";");
        $getParam = eval(" return " . @iconv("utf-8//IGNORE", "gbk", var_export($_GET, true)) . ";");
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

    public function returnData()
    {
    }

    /**
     * sms短信发送
     * @param $paras 参数数组
     */
    public function sendSms($paras)
    {
        $remindUrl = $paras['url'];
        $smsContent = $paras['content'];
        $approver = $paras['approver'];
        // 发送消息提醒
        send_sms("", $_SESSION["LOGIN_USER_ID"], $approver, 0, $smsContent, $remindUrl);
    }
}
