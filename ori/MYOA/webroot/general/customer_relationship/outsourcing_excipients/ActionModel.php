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
require_once("../common/DataModel.php");

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
    public $systemId = "outsourcing_excipients";
    public static $NON_METER_TYPE = array('non_excipients');

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
        $orderDetail = $this->queryOrderDetail($param);
        $detailItem = $this->getOrderDetailItem();

        $result = array(
            'main' => $main,
            'orderDetail' => $orderDetail,
            'detailItem' => $detailItem,
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

        $sql = " select k.*,u.user_name createUserName,d.dept_name deptName from inhe_outsourcing_excipients k " .
            " left join user u on u.user_id = k.user_id " .
            " left join department d on d.dept_id = k.organ_id " . $strWhere;
        $res = exequery(TD::conn(), $sql);

        while ($item = mysql_fetch_assoc($res)) {
            $result = $item;
        }
        mysql_free_result($res);

        return $result;
    }

    public function queryOrderDetail($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";

        if (array_key_exists("id", $param) && $param['id'] != "") {
            $strWhere .= " and k.form_id = '" . $param['id'] . "' ";
        } else {
            return $result;
        }

        $sql = " select * from inhe_outexcipients_detail k " . $strWhere . " order by id ";
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result['non_excipients'][] = $item;
        }
       
        mysql_free_result($res);

        return $result;
    }

    public function getOrderDetailItem()
    {
        $sql = " select * from inhe_detail_item where module_id = '" . $this->systemId . "' order by table_id, sort_code";
        $res = exequery(TD::conn(), $sql);
        $result = array();
        while ($item = mysql_fetch_assoc($res)) {
            $result[$item['table_id']][] = $item;
        }
        mysql_free_result($res);

        return $result;
    }

    /**
     * 提交申请
     * 保存表单数据 -> 获取下一步审核人员 -> 新增申请人记录，更新审核人数据，发送消息提醒
     * */
    public function submit()
    {
        $flag = false;
        $param = $this->getParamToArray();
        $form = new FormModel();
        $result = $form->submit($param); // 拆分add和update部分独立，其他公共使用
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
            $flag = $this->$result['action']($result['param']); // action分为add和update
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

    /**
     * 更新审核意见，包括同意提交和退回操作
     */
    public function updateApproveOpinion()
    {
        $param = $this->getParamToArray();
        $attachArr = $param['attach'];
        if (is_array($attachArr)) {
            $attachIds = implode(',', $attachArr);
            $attachIds = rtrim($attachIds, ',');
            $attach = new AttachModel();
            $attach->updateAttach($attachIds, $param['form_id']);
        }
        $process = new ProcessModel();
        $result = $process->updateProcessOpinion($param);
        return $result;
    }

    /**
     * 新增一个客户
     */
    public function add($param)
    {
        $userId = $_SESSION['LOGIN_USER_ID'];
        $deptId = $_SESSION['LOGIN_DEPT_ID'];
        $time = date('Y-m-d H:i:s');
        $param['user_id']=$userId;
        $param['organ_id']=$deptId;
        $param['write_time']=$time;
        $param['others']=$param['other_explain'];
        $this->addMain($param);
        $this->addDetail($param);
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
        $mainSql = "insert into inhe_outsourcing_excipients (title, 
        form_id, 
        other_explain, 
        organ_id, 
        user_id, 
        write_time, 
        status, 
        type, 
        user_bu
        ) values" .
            "('" . $param['title'] . "', '" . $param['form_id'] . "', '" . $param['other_explain'] . 
            "', '" . $param['organ_id'] . "','" . $param['user_id'] . "', '" . $param['write_time'] .
            "', '" . $param['status'] . "', '" . $param['type'] .  "', '" .
            $param['user_bu'] . "')";
        exequery(TD::conn(), $mainSql);
        $id = mysql_insert_id();
        if ($id > 0) {
            return true;
        }

        return false;
    }

    public function addDetail($param)
    {
        $this->addNonMeter($param);
        return true;
    }

    public function addNonMeter($param)
    {
       
        for ($i = 1; $i <= $param['addNonExcipientsCount']; $i++) {
            $str = "_NonExcipients".$i;
            if (!empty($param['non_excipients_name' . $str])) {
                $sql = "insert into inhe_outexcipients_detail (form_id, 
                non_excipients_serial_number, 
                non_excipients_name, 
                non_excipients_specification, 
                non_excipients_model, 
                non_excipients_number, 
                non_excipients_attachment
                    ) values" .
                    "('" . $param['form_id'] . "', '" . $param['non_excipients_serial_number' . $str] . "', '" . $param['non_excipients_name' . $str] . "', '" .
                    $param['non_excipients_specification' . $str] . "','" . $param['non_excipients_model' . $str] . "', '" . $param['non_excipients_number' . $str] . "','" .
                    $param['non_excipients_attachment' . $str] . "')";
                    
                exequery(TD::conn(), $sql);
            }
        }
        return true;
    }

    public function update($param)
    {
        $this->updateMainData($param);
        $this->updateNoneMeter($param);
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
            'update_time' => date('Y-m-d H:i:s'),
            'title' => $param['title'],
            'form_id' => $param['form_id'],
            'form_type' => $param['form_type'],
            'related_id' => $param['related_id'],
            'other_explain' => $param['other_explain'],
            'status' => $param['status'],
            'type' => $param['type'],
            'user_bu' => $param['user_bu']
        );
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_outsourcing_excipients';
        $result = $form->updateMainData($paramInsert, $condition, $tableName, array(), '');
        return $result;
    }
    public function updateNoneMeter($param)
    {
        $form = new FormModel();
        $normalField = array(
            'form_id' => 'form_id'
        );
        $batchField = array(
            'non_excipients_serial_number' => 'non_excipients_serial_number',
            'non_excipients_name' => 'non_excipients_name',
            'non_excipients_specification' => 'non_excipients_specification',
            'non_excipients_model' => 'non_excipients_model',
            'non_excipients_number' => 'non_excipients_number',
            'non_excipients_attachment' => 'non_excipients_attachment',
        );
        
        $typeArr[]="NonExcipients";
        $countArr["NonExcipients"]=$param["addNonExcipientsCount"];
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_outexcipients_detail';
        $result = $form->updateBatchDetailByType($normalField, $batchField, $param, $condition, $tableName, $typeArr, $countArr);
        $sql = " delete from $tableName where form_id = '" . $param['form_id'] . "' and (non_excipients_name = '' or non_excipients_name is null) ";
        exequery(TD::conn(), $sql);
        return $result;
    }

    public function change()
    {
        $flag = false;
        $param = $this->getParamToArray();
        $form = new FormModel();
        $result = $form->submit($param); // 拆分add和update部分独立，其他公共使用
        if (array_key_exists('action', $result)) {
            $flag = $this->$result['action']($result['param']); // action分为add和update
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
        $this->changeNoneMeter($param);
        // 附件数据保存 
        $attachArr = $param['attach'];
        if (is_array($attachArr)) {
            $attachIds = implode(',', $attachArr);
            $attachIds = rtrim($attachIds, ',');
            $attach = new AttachModel();
            //$attach->updateAttach($attachIds, $param['form_id']);
            $attach->changeAddAttach($attachIds, $param['form_id']);
            
        }
        return true;
    }

    public function changeUpdate($param)
    {
        $this->updateMainData($param);
        $this->updateNoneMeter($param);
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
            'status' => $param['status'],
            'type' => $param['type'],
            'user_bu' => $param['user_bu']
        );
        $condition = array('form_id' => $param['related_id']);
        $tableName = 'inhe_outsourcing_excipients';
        $result = $form->changeMainData($paramInsert, $condition, $tableName, array(), '');
        return $result;
    }

    public function changeNoneMeter($param)
    {
        $form = new FormModel();
        $normalField = array(
            'form_id' => 'form_id'
        );
        $batchField = array(
            'non_excipients_serial_number' => 'non_excipients_serial_number',
            'non_excipients_name' => 'non_excipients_name',
            'non_excipients_specification' => 'non_excipients_specification',
            'non_excipients_model' => 'non_excipients_model',
            'non_excipients_number' => 'non_excipients_number',
            'non_excipients_attachment' => 'non_excipients_attachment',
        );
    
        $typeArr[]="NonExcipients";
        $countArr["NonExcipients"]=$param["addNonExcipientsCount"];
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_outexcipients_detail';
        $result = $form->changeDetailByType($normalField, $batchField, $param, $condition, $tableName, $typeArr, $countArr);
        $deleteSql = "delete from inhe_outexcipients_detail where non_excipients_name = '' and form_id = '" . $param['form_id'] . "' ";
        exequery(TD::conn(), $deleteSql);
        return $result;
    }

    /**
     * 删除指定词条
     */
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
        $sql = " delete from inhe_outsourcing_excipients " . $where;
        $re = exequery(TD::conn(), $sql);

        $sql = " delete from inhe_outexcipients_detail  where form_id = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $sql);

        $sql = " delete from inhe_flow_opinion  where  form_id = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $sql);

        $result = $response->success(true);
        return $result;
    }

    public function makeLineHtml($param, $number,$add_type='')
    {
        $model = new DataModel();
        $selectParam = array(
            'is_used' => '1', // 可用参数
            'type' => 'yes_or_no,inspection_type,display_mode,production_mode,test_report,product_brand'
        );
        $selectArr = $model->getCommonParam($selectParam);

        if ($number <= 0) $number = 1;
        $htmlArr = array();
        foreach ($param as $meter_type => $lineArr) {
            $html = '';
            $html = '<tr align="center">';
            foreach ($lineArr as $val) {
                $fieldId = '';
                $fieldId = $val['item_id'] .$add_type. $number;
                $required=$val['is_required']==1?'lay-verify="required"':'';
                $html .= '<td class="TableData" colspan="' . $val['colspan'] . '">';
                if ($val['have_attach']) {
                    $html .= '<input id="' .  $fieldId . '" type="button" class="layui-btn layui-bg-gray btn-upload" style="display: inline!important;" value="上传" />';
                    // $htmlArr['htmlScript'] .= 'attach.init({elem: "#' . $fieldId . '",uploadUrl: "/general/attachment/attach.php?type=' . $fieldId . '&url=common"});';
                } else if (!empty($val['if_select'])) {
                    $html .= '<div class="layui-input-inline">' .
                        '<select lay-filter="'.$val['item_id'].'" id="' .  $fieldId . '" name="' .  $fieldId . '" lay-search '.$required.'>' .
                        '<option value="">请选择</option>';
                    foreach ($selectArr[$val['if_select']] as $v) {
                        $html .= '<option value="' . $v['paras_value'] . '">' . $v['paras_desc'] . '</option>';
                    }
                    $html .= '</select></div>';
                } else {
                    if (strpos($fieldId, 'serial_number') !== false) {
                        $html .= '<input type="text" id="' .  $val['item_id'].$add_type . '" name="' .  $fieldId . '" class="layui-input" readonly value="' . $number . '" />';
                    } else {
                        $html .= '<input type="text" '.$required.' id="' .  $val['item_id'].$add_type . '" name="' .  $fieldId . '" class="layui-input" />';
                    }
                }
                $html .= '</td>';
            }
            $html = mb_substr($html, 0, -5);
            $html .= '<a href="javascript:;" onclick="removeLine(this)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;删除</a></td>';
            $html .= '</tr>';
            $htmlArr[$meter_type] = $html;
        }
        return $htmlArr;
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
