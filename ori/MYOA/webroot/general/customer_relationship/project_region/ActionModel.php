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

        if (array_key_exists("id", $param) && $param['id'] != "") {
            $strWhere .= " and m.id = '" . $param['id'] . "' ";
        } else {
            return $result;
        }

        $sql = " select m.*,d.dept_name realDeptName from inhe_project_region m " .
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
            
            if (!$this->verifyDataExist($param['form_id'])) {
                $result['action'] = 'add';
                if ($param['action'] == 'ChangeSave') $result['action'] = 'changeAdd';
                $result['param']['title'] = $form->makeFormTitle($param);
            }
            $result['param'][status]="Y";
            $flag = $this->$result['action']($result['param']);
            //增加如果数据表存在form_id则重新调用
            if($flag == false && $result['action'] == 'add'){
                return $this->add();
            }
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
            if (!$this->verifyDataExist($param['form_id'])) {
                $result['action'] = 'add';
                if ($param['action'] == 'ChangeSave') $result['action'] = 'changeAdd';
                $result['param']['title'] = $form->makeFormTitle($param);
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
        $addMainResult=$this->addMain($param);
        if(!$addMainResult){
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
        $userName = $_SESSION['LOGIN_USER_NAME'];
        $deptId = $_SESSION['LOGIN_DEPT_ID'];
        $time = date('Y-m-d H:i:s');
        $status = $param['status'];
        //更新数据时增加form_id是否存在对应值
        //$where = " where not exists (select * from inhe_project_region where form_id='".$param['form_id']."')";
        //$limit = " limit 1";
        $sql = "insert into inhe_project_region (title, 
            form_id,
            name,
            business_dept_id,
            business_dept_name,
            business_user,
            business_user_name,
            delivery_user,
            delivery_user_name,
            user_name,
            dept_id,
            user_id,
            create_time,
            status,
            type,
            user_bu)" .
            " values( '" . $param['title'] . "', '" . $param['form_id'] . "', '" . $param['name'] . "', '" .
            $param['business_dept'] . "', '" . $param['business_dept_name'] . "', '" .
             $param['business_user'] . "', '" . $param['business_user_name'] . "', '" .
            $param['delivery_user']. "','" .$param['delivery_user_name']. "','" . $userName. "','" .
            $deptId . "', '" . $userId . "','" . $time . "', '" . $status .
            "', '" . $param['type'] . "', '" .  $param['user_bu'] . "' ) ".$where.$limit;
        exequery(TD::conn(), $sql);
        
        //更新数据时增加form_id是否存在对应值,若存在则重新调用add方法
        if(mysql_insert_id()==0){
            return false;
        };

        return true;
    }

    public function verifyDataExist($formId)
    {
        $flag = false;
        if (isset($formId)) {
            $sql = " select * from inhe_project_region where form_id = '$formId' ";
            $res = exequery(TD::conn(), $sql);
            $count = mysql_num_rows($res);
            if ($count > 0) {
                $flag = true;
            }
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
            'fieldName' => 'temp_code',
            'tableName' => 'inhe_temp_code',
            'flowType'  => $this->nameRule[$param['scope_code']],
            'digit'     => '3'
        );
        $newCode = $form->createFlowId($param, true);
        return $newCode;
    }

      /**
     * 更新数据
     */
    public function update($param)
    {
        $this->updateMainData($param);
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
        $form = new FormModel();
        $paramInsert = array(
            'title' => $param['title'],
            'name' => $param['name'],
            'business_dept_id' => $param['business_dept'],
            'business_dept_name' => $param['business_dept_name'],
            'business_user' => $param['business_user'],
            'business_user_name' => $param['business_user_name'],
            'delivery_user' => $param['delivery_user'],
            'delivery_user_name' => $param['delivery_user_name'],
            'status' => $param['status'],
            'type' => $param['type'],
            'dept_id' => $param['dept_id'],
            'user_bu' => $param['user_bu']
        );
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_project_region';
        $result = $form->updateMainData($paramInsert, $condition, $tableName, array(), '');
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
        // if (!array_key_exists('tableName', $param) || $param['tableName'] == '') {
        //     $result = array(
        //         'data' => '',
        //         'status' => 'failed',
        //         'msg' => '删除失败'
        //     );
        //     return $result;
        // }
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

        $sql = " delete from inhe_project_region " . $where;
        $re = exequery(TD::conn(), $sql);

        $result = array(
            'data' => '',
            'status' => 'success',
            'msg' => '删除成功'
        );

        return $result;
    }
/**
     * 删除指定词条
     */
    public function Forbidden()
    {
        $param = $this->getParamToArray();

        $result = array();
        $where = " where 1=1 ";
        if (array_key_exists('id', $param) && $param['id'] != '') {
            $where .= " and id = '" . $param['id'] . "' ";
        } else {
            $result = array(
                'data' => '',
                'status' => 'failed',
                'msg' => '操作失败'
            );
            return $result;
        }
        $main=$this->queryMain($param);
        $status=$main["status"]=="Y"?"N":"Y";
        $sql = " update  inhe_project_region set status='$status' " . $where;
        $re = exequery(TD::conn(), $sql);

        $result = array(
            'data' => '',
            'status' => 'success',
            'msg' => '操作成功'
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
