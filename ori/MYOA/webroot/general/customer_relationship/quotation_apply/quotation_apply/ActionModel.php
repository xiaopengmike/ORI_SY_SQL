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
 * inhe_quotation_apply  数据主表 insert or update
 * inhe_control_library_history 历史记录表 insert
 */

class ActionModel
{
    public $systemId = "quotation_apply";

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
        $product = $this->queryProduct($param);
        $param['user_bu'] = $main['user_bu'];


        $result = array(
            'main' => $main,
            'product' => $product,
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

        $sql = " select distinct k.*,m.project_star realStar from inhe_quotation_apply k left join inhe_project_main m on m.project_code = k.project_code " . $strWhere;
        $res = exequery(TD::conn(), $sql);

        while ($item = mysql_fetch_assoc($res)) {
            if ($item['project_star'] != $item['realStar'] && $item['realStar'] != '') {
                $item['project_star'] = $item['realStar'];
            }
            $result = $item;
        }
        mysql_free_result($res);

        return $result;
    }

    public function queryProduct($param)
    {
        $result = array();
        $strWhere = " where 1=1 ";

        if (array_key_exists("id", $param) && $param['id'] != "") {
            $strWhere .= " and k.form_id = '" . $param['id'] . "' ";
        } else {
            return $result;
        }

        $sql = " select * from inhe_quotation_product k " . $strWhere . " order by serial_number ";
        $res = exequery(TD::conn(), $sql);

        while ($item = mysql_fetch_assoc($res)) {
            $result['quotation'][] = $item;
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
            if($this->verifyDataExist($param['form_id']) && $param['pageName']){
                $result = array(
                    'code' => '0',
                    'status' => 'failed',
                    'msg' => '单号已经被使用！',
                    'data' => '单号已经被使用！'
                );
                return $result;
            }
            if (!$this->verifyDataExist($param['form_id'])) {
                $result['action'] = 'add';
                if ($param['action'] == 'ChangeSave') $result['action'] = 'changeAdd';
                $result['param']['title'] = $form->makeFormTitle($param);
            }
            $flag = $this->$result['action']($result['param']);
            //增加如果数据表存在form_id则重新调用
            if($flag == false && $result['action'] == 'add'){
                return $this->submit();
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
            if($this->verifyDataExist($param['form_id']) && $param['pageName']){
                $result = array(
                    'code' => '0',
                    'status' => 'failed',
                    'msg' => '单号已经被使用！',
                    'data' => '单号已经被使用！'
                );
                return $result;
            }
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

    public function verifyDataExist($formId)
    {
        $flag = false;
        if (isset($formId)) {
            $sql = " select * from inhe_quotation_apply where form_id = '$formId' ";
            $res = exequery(TD::conn(), $sql);
            $count = mysql_num_rows($res);
            if ($count > 0) {
                $flag = true;
            }
        }
        return $flag;
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
        $this->addProduct($param);

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
        $time = date('Y-m-d H:i:s');
        $status = $param['status'];
        //更新数据时增加form_id是否存在对应值
        $where = " where not exists (select * from inhe_quotation_apply where form_id='".$param['form_id']."')";
        $limit = " limit 1";
        // $sql = "insert into inhe_quotation_apply (title, 
        //     form_id,
        //     related_id,
        //     project_code,
        //     price_terms,
        //     exchange_rate,
        //     business_belong,
        //     currency,
        //     quotation_time,
        //     valid_days,
        //     project_survey,
        //     change_explain,
        //     organ_id,
        //     user_id,
        //     write_time,
        //     status,
        //     type,
        //     user_bu)" .
        //     " select '" . $param['title'] . "', '" . $param['form_id'] . "', '" . $param['related_id'] . "', '" . $param['project_code'] . "', '" . 
        //     $param['price_terms'] . "', '" .$param['exchange_rate'] . "', '" . $param['business_belong'] . "', '" . $param['currency'] . "', '" .
        //     $param['quotation_time'] . "', '" . $param['valid_days'] . "','" . $param['project_survey'] . "', '". $param['change_explain'] . "', '" . 
        //     $deptId . "', '" . $userId . "','" . $time . "', '" . $status .
        //     "', '" . $param['type'] . "','" . $param['user_bu'] . "' from inhe_quotation_apply ".$where.$limit;
            $sql = "insert into inhe_quotation_apply (title, 
            form_id,
            related_id,
            project_code,
            price_terms,
            exchange_rate,
            business_belong,
            currency,
            quotation_time,
            valid_days,
            project_survey,
            change_explain,
            organ_id,
            user_id,
            write_time,
            status,
            type,
            user_bu)" .
            " value( '" . $param['title'] . "', '" . $param['form_id'] . "', '" . $param['related_id'] . "', '" . $param['project_code'] . "', '" . 
            $param['price_terms'] . "', '" .$param['exchange_rate'] . "', '" . $param['business_belong'] . "', '" . $param['currency'] . "', '" .
            $param['quotation_time'] . "', '" . $param['valid_days'] . "','" . $param['project_survey'] . "', '". $param['change_explain'] . "', '" . 
            $deptId . "', '" . $userId . "','" . $time . "', '" . $status .
            "', '" . $param['type'] . "','" . $param['user_bu'] . "') ";
        exequery(TD::conn(), $sql);
        //更新数据时增加form_id是否存在对应值,若存在则重新调用add方法
        if(mysql_insert_id()==0){
            return false;
        };

        return true;
    }

    public function addProduct($param)
    {
        $typeArr['quotation']="addQuotationDetailCount";
        
        //$typeArr = array('electric' => 'addEleDetailCount', 'other' => 'addOtherDetailCount', 'soft' => 'addSoftDetailCount', 'electricParts' => 'addElectricPartsDetailCount', 'distribution' => 'addDistributionDetailCount');
        foreach ($typeArr as $type => $addCount) {
            for ($i = 1; $i <= $param[$addCount]; $i++) {
                $addItem = "";
                $addItem = $type . $i;
                $sql = "insert into inhe_quotation_product (form_id,
                        serial_number,
                        product_type,
                        product_name,
                        remarks,
                        cost,
                        quotation_scheme,
                        quotation_price,
                        number,
                        count_price,
                        proposal_price) values" .
                    "('" . $param['form_id'] . "', '" . $i . "', '" . $param['product_type_' . $addItem] . "', '".$param['product_name_' . $addItem] . "', '" . $param['remarks_' . $addItem] .
                    "', '" . $param['cost_' . $addItem] . "','" . $param['quotation_scheme_' . $addItem] . "', '" . $param['quotation_price_' . $addItem] .
                    "', '" . $param['number_' . $addItem] . "', '" . $param['count_price_' . $addItem] . "', '" . $param['proposal_price_' . $addItem] . "')";
                if (!empty($param['product_type_' . $addItem])) {
                    exequery(TD::conn(), $sql);
                }
            }
        }

        return true;
    }

    /**
     * 更新数据
     */
    public function update($param)
    {
        $this->updateMainData($param);
        $this->updateProductData($param);

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
            'project_code' => $param['project_code'],
            'price_terms' => $param['price_terms'],
            'exchange_rate' => $param['exchange_rate'],
            'business_belong' => $param['business_belong'],
            'currency' => $param['currency'],
            'quotation_time' => $param['quotation_time'],
            'valid_days' => $param['valid_days'],
            'project_survey' => $param['project_survey'],
            'change_explain' => $param['change_explain'],
            'status' => $param['status'],
            'type' => $param['type'],
            'user_bu' => $param['user_bu']
        );
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_quotation_apply';
        $result = $form->updateMainData($paramInsert, $condition, $tableName, array(), '');
        return $result;
    }

    public function updateProductData($param)
    {
        $form = new FormModel();
        $normalField = array(
            'form_id' => 'form_id'
        );
        $batchField = array(
            'serial_number' => 'serial_number',
            'product_type' => 'product_type',
            'product_name' => 'product_name',
            'remarks' => 'remarks',
            'cost' => 'cost',
            'quotation_scheme' => 'quotation_scheme',
            'quotation_price' => 'quotation_price',
            'number' => 'number',
            'count_price' => 'count_price',
            'proposal_price' => 'proposal_price',
        );
       
        $typeArr = array('quotation');
        $countArr = array(
            'quotation' => $param['addQuotationDetailCount'],
        );
        $condition = array('form_id' => $param['form_id']);
        $tableName = 'inhe_quotation_product';
        $result = $form->updateBatchDetailByType($normalField, $batchField, $param, $condition, $tableName, $typeArr, $countArr);
        $sql = " delete from $tableName where form_id = '" . $param['form_id'] . "' and (product_type = '' or product_type is null) ";
        exequery(TD::conn(), $sql);
        return $result;
    }

    public function change()
    {

        $flag = false;
        $param = $this->getParamToArray();
         //存在待审核变更单，请先处理
         $sql="select * from inhe_quotation_apply where status='p' and related_id = '".$param['related_id']."'";
         $res = exequery(TD::conn(), $sql);
         if(mysql_num_rows($res)>1){
             return array("code"=>0,"msg"=>"该编号（".$param['related_id']."）存在审核中变更单！请先处理");
         }
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
        $this->changeProductData($param);
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

    public function changeUpdate($param)
    {
        $this->updateMainData($param);
        $this->updateProductData($param);
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
            'price_terms' => $param['price_terms'],
            'exchange_rate' => $param['exchange_rate'],
            'business_belong' => $param['business_belong'],
            'currency' => $param['currency'],
            'quotation_time' => $param['quotation_time'],
            'valid_days' => $param['valid_days'],
            'project_survey' => $param['project_survey'],
            'change_explain' => $param['change_explain'],
            'status' => $param['status'],
            'type' => $param['type'],
            'user_bu' => $param['user_bu']
        );
        $condition = array('form_id' => $param['related_id']);
        $tableName = 'inhe_quotation_apply';
        $result = $form->changeMainData($paramInsert, $condition, $tableName, array(), '');
        return $result;
    }

    public function changeProductData($param)
    {
        $form = new FormModel();
        $normalField = array(
            'form_id' => 'form_id'
        );
        $batchField = array(
            'serial_number' => 'serial_number',
            'product_type' => 'product_type',
            'product_name' => 'product_name',
            'remarks' => 'remarks',
            'cost' => 'cost',
            'quotation_scheme' => 'quotation_scheme',
            'quotation_price' => 'quotation_price',
            'number' => 'number',
            'count_price' => 'count_price',
            'proposal_price' => 'proposal_price',
        );
       
        $typeArr = array('quotation');
        $countArr = array(
            'quotation' => $param['addQuotationDetailCount'],
        );
        $condition = array('form_id' => $param['related_id']);
        $tableName = 'inhe_quotation_product';
        $result = $form->changeDetailByType($normalField, $batchField, $param, $condition, $tableName, $typeArr, $countArr);
        $sql = " delete from $tableName where form_id = '" . $param['form_id'] . "' and (product_type = '' or product_type is null) ";
        exequery(TD::conn(), $sql);
        return $result;
    }

    //获取最新版本变更单
    public function getNewChange($relatedId){
        $result=array();
        $sql = "select a.* from inhe_quotation_apply a  where a.form_id='".$relatedId."'";
        $res = exequery(TD::conn(), $sql);
        $row=mysql_fetch_assoc($res);
        $relatedId=empty($row['related_id'])?$relatedId:$row['related_id'];
        if(empty($row['related_id'])){
            if($row['version']<=0){
                return $result;
            }
            $result[] = $row;
        }else{
            $sql = "select a.* from inhe_quotation_apply a where a.status in ('Y','R') and a.form_id='".$row['related_id']."'";
            $res = exequery(TD::conn(), $sql);
            $result[] = mysql_fetch_assoc($res);
        }
        if($result[0]['version']<=0){
            return $result;
        }
        //备案或者审核通过('Y','R')
        $sql = "select a.* from inhe_quotation_apply a where a.status in ('Y','R') and a.related_id='".$relatedId."'  order by a.version,a.id";
        $res = exequery(TD::conn(), $sql);
        
        while($row=mysql_fetch_assoc($res)){
            $result[] = $row;
        }
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

        $sql = " delete from inhe_quotation_apply " . $where;
        $re = exequery(TD::conn(), $sql);
        $sql = " delete from inhe_quotation_product  where form_id = '" . $param['id'] . "' ";
        $res = exequery(TD::conn(), $sql);

        $result = $response->success(true);
        return $result;
    }

    public function updateApproveOpinion()
    {
        $param = $this->getParamToArray();
        $process = new ProcessModel();
        $result = $process->updateProcessOpinion($param);
        return $result;
    }

    public function addOtherCondition()
    {
        $sql = " select * from inhe_oa_paras where type = 'other_condition' ";
        $res = exequery(TD::conn(), $sql);
        while ($item = mysql_fetch_assoc($res)) {
            $result[] = $item['paras_value'];
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
