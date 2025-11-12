<?php
include_once("../common/DataModel.php");  // 公共类
require_once("../common/UserModel.php");
$model = new DataModel();
$userId = $_SESSION['LOGIN_USER_ID'];
$deptId = $_SESSION['LOGIN_DEPT_ID'];
$userModel = new UserModel();
$company = $userModel->getUserBu($deptId);
$systemId = "shipping_notice";
$data = $userModel->getAdminDataQuery($userId, $deptId, $systemId);
$isAdmin = $data['isAdmin'];

$selectParam = array(
    'is_used' => '1', // 可用参数
    'type' => 'currency,yes_or_no,quote_source,sale_model',
);
$selectArr = $model->getCommonParam($selectParam);
$currency = array();
foreach ($selectArr['currency'] as $v) {
    $currency[$v['paras_value']] = $v['paras_desc'];
}
$yes_or_no = array();
foreach ($selectArr['yes_or_no'] as $v) {
    $yes_or_no[$v['paras_value']] = $v['paras_desc'];
}
$quote_source = array();
foreach ($selectArr['quote_source'] as $v) {
    $quote_source[$v['paras_value']] = $v['paras_desc'];
}
$sale_model = array();
foreach ($selectArr['sale_model'] as $v) {
    $sale_model[$v['paras_value']] = $v['paras_desc'];
}

// 数据权限范围
if ($isAdmin) {
    $whereStr .= " and ((m.user_id = '" . $userId . "' ";
    $whereStr .= " or (m.user_bu = '$company') ";
    if (!empty($data['userBu'])) {
        $whereStr .= " or m.user_bu in (" . $data['userBu'] . ") ";
    }
    if (!empty($data['deptIds'])) {
        $whereStr .= " or m.organ_id in(" . $data['deptIds'] . ") ";
    }
    if (!empty($data['userIds'])) {
        $whereStr .= " or m.user_id in(" . $data['userIds'] . ") ";
    }
    $whereStr .= ") ";
} else {
    //增加权限显示，办理人（申请人和办理人）只可见自己内容
    $whereStr .= " and ((m.user_bu = '$company' or m.user_id = '$userId') ";
}

// $limit
// $page
$param = $model->getParamToArray();
// and m.create_user = '$userId'
$where = " where 1=1 and m.type = 'sales_contract_review' and m.status in('R','Y') ";
$where .= $whereStr;
$project_numbers=$model->findNumberParticipant($userId);
if(!empty($project_numbers)){
    $project_numbers_str='';
    foreach ($project_numbers as $v) {
        $project_numbers_str .= "'" . $v . "',";
    }
    $project_numbers_str = rtrim($project_numbers_str, ',');
    $where .= " or m.project_number in ($project_numbers_str)";
}
$where .=" ) ";
$pageLimit = "";

if (array_key_exists("customer_name", $param) && $param['customer_name'] != "") {
    $where .= " and m.customer_name like '%" . trim($param['customer_name']) . "%' ";
}
if (array_key_exists("project_number", $param) && $param['project_number'] != "") {
    $where .= " and m.project_number like '%" . trim($param['project_number']) . "%' ";
}
if (array_key_exists("form_id", $param) && $param['form_id'] != "") {
    $where .= " and m.form_id = '" . trim($param['form_id']) . "' ";
}
if (array_key_exists("page", $param) && $param['page'] != "" && array_key_exists("limit", $param) && $param['limit'] != "") {
    $pageLimit .= " limit  " . ($param['page'] - 1) * $param['limit'] . "," . $param['limit'];
}
$orderBy = " order by m.id desc ";

$sql = " select distinct m.*, p.*, d.dept_name business_dept from inhe_contract_review m left join inhe_contract_detail p on m.form_id = p.form_id "
    . " left join department d on d.dept_id = p.dept_id "
    . $where . $orderBy . $pageLimit;
$countSql = " select count(*) total_count from inhe_contract_review m " . $where . $orderBy;
$countRes = exequery(TD::conn(), $countSql);
$countArr = mysql_fetch_assoc($countRes);
$totalCount = $countArr['total_count'];

$res = exequery(TD::conn(), $sql);
$data = array();
while ($item = mysql_fetch_assoc($res)) {
    $item['orig_form_id']=$item['form_id'];
    if($item['version']>0){
        $changeSql = " select distinct m.*, p.*, d.dept_name business_dept from inhe_contract_review m left join inhe_contract_detail p on m.form_id = p.form_id  "
        . " left join department d on d.dept_id = p.dept_id "
        ."where 1=1 and m.type ='sales_contract_review_change' and m.status in('R','Y') and version=".$item['version']." and related_id='".$item['form_id']."'";
        $changeData = exequery(TD::conn(), $changeSql);
        $item= mysql_fetch_assoc($changeData);
        $item['orig_form_id']=$item['form_id'];
        $item['form_id']=$item['related_id'];
    }
    $item['currency_desc'] = $currency[$item['currency']];
    $item['have_entrepot_trade_desc'] = $yes_or_no[$item['have_entrepot_trade']];
    $data[] = $item;
}
mysql_free_result($res);

$result = array(
    "code" => 0,
    "msg" => "",
    "count" => $totalCount,
    "data" => $data
);

echo json_encode($model->array_iconv($result));
exit;
